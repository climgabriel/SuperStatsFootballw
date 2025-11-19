<?php
/**
 * API Client for SuperStatsFootball Backend
 *
 * Handles all API requests with authentication, error handling, and token refresh
 */

require_once __DIR__ . '/../config.php';

class APIClient {
    private $baseUrl;
    private $accessToken;
    private $refreshToken;

    public function __construct() {
        $this->baseUrl = API_BASE_URL . API_PREFIX;
        $this->accessToken = getAccessToken();
        $this->refreshToken = getRefreshToken();
    }

    /**
     * Make HTTP request to API
     */
    private function makeRequest($method, $endpoint, $data = null, $requireAuth = false) {
        $url = $this->baseUrl . $endpoint;

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if ($requireAuth && $this->accessToken) {
            $headers[] = 'Authorization: Bearer ' . $this->accessToken;
        }

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Add request body for POST/PUT/PATCH
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        // Execute request
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Handle connection errors
        if ($response === false || $error) {
            return [
                'success' => false,
                'error' => 'Failed to connect to API: ' . ($error ?: 'Unknown error'),
                'data' => null
            ];
        }

        $decoded = json_decode($response, true);

        // Handle 401 Unauthorized - try to refresh token
        if ($statusCode === 401 && $requireAuth && $this->refreshToken) {
            $refreshResult = $this->refreshAccessToken();
            if ($refreshResult['success']) {
                // Retry the original request with new token
                return $this->makeRequest($method, $endpoint, $data, $requireAuth);
            }
        }

        return [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'status_code' => $statusCode,
            'data' => $decoded,
            'error' => $decoded['detail'] ?? ($statusCode >= 400 ? 'API Error' : null)
        ];
    }

    /**
     * Authentication: Login
     */
    public function login($email, $password) {
        $result = $this->makeRequest('POST', '/auth/login', [
            'email' => $email,
            'password' => $password
        ]);

        if ($result['success'] && isset($result['data']['access_token'])) {
            $this->setTokens(
                $result['data']['access_token'],
                $result['data']['refresh_token'] ?? null
            );

            // Store user info in session (new + legacy keys for compatibility)
            $_SESSION['user'] = $result['data']['user'] ?? [];
            $_SESSION[SESSION_USER_KEY] = $_SESSION['user'];
        }

        return $result;
    }

    /**
     * Authentication: Register
     */
    public function register($email, $password, $fullName) {
        return $this->makeRequest('POST', '/auth/register', [
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName
        ]);
    }

    /**
     * Authentication: Logout
     */
    public function logout() {
        $result = $this->makeRequest('POST', '/auth/logout', null, true);

        // Clear session and cookies regardless of API response
        $this->clearTokens();
        session_destroy();

        return $result;
    }

    /**
     * Refresh access token
     */
    public function refreshAccessToken() {
        if (!$this->refreshToken) {
            return ['success' => false, 'error' => 'No refresh token available'];
        }

        $result = $this->makeRequest('POST', '/auth/refresh', [
            'refresh_token' => $this->refreshToken
        ]);

        if ($result['success'] && isset($result['data']['access_token'])) {
            $this->setTokens(
                $result['data']['access_token'],
                $result['data']['refresh_token'] ?? $this->refreshToken
            );
        }

        return $result;
    }

    /**
     * Get user profile
     */
    public function getUserProfile() {
        return $this->makeRequest('GET', '/users/me', null, true);
    }

    /**
     * Get predictions with odds (UNIFIED ENDPOINT)
     */
    public function getPredictionsWithOdds($daysAhead = 7, $leagueId = null, $limit = 100, $offset = 0) {
        $params = [
            'days_ahead' => $daysAhead,
            'limit' => $limit,
            'offset' => $offset
        ];

        if ($leagueId) {
            $params['league_id'] = $leagueId;
        }

        $queryString = http_build_query($params);
        $endpoint = '/combined/fixtures/predictions-with-odds?' . $queryString;

        return $this->makeRequest('GET', $endpoint, null, true);
    }

    /**
     * Get accessible leagues for user
     */
    public function getAccessibleLeagues() {
        return $this->makeRequest('GET', '/leagues/accessible/me', null, true);
    }

    /**
     * Get league tier info
     */
    public function getLeagueTierInfo() {
        return $this->makeRequest('GET', '/leagues/tier-info', null, false);
    }

    /**
     * Set authentication tokens
     */
    private function setTokens($accessToken, $refreshToken = null) {
        $this->accessToken = $accessToken;
        $_SESSION[SESSION_TOKEN_KEY] = $accessToken;
        $_SESSION['access_token'] = $accessToken;

        // Set cookies for token persistence
        $secure = (defined('ENVIRONMENT') && ENVIRONMENT === 'production');
        setcookie(
            TOKEN_COOKIE_NAME,
            $accessToken,
            time() + (TOKEN_EXPIRY_MINUTES * 60),
            '/',
            '',
            $secure,
            true   // HTTP only (no JavaScript access)
        );

        if ($refreshToken) {
            $this->refreshToken = $refreshToken;
            $_SESSION['refresh_token'] = $refreshToken;
            setcookie(
                REFRESH_TOKEN_COOKIE_NAME,
                $refreshToken,
                time() + (REFRESH_TOKEN_EXPIRY_DAYS * 24 * 60 * 60),
                '/',
                '',
                $secure,
                true
            );
        } else {
            unset($_SESSION['refresh_token']);
        }
    }

    /**
     * Clear authentication tokens
     */
    private function clearTokens() {
        $this->accessToken = null;
        $this->refreshToken = null;

        // Clear cookies
        $secure = (defined('ENVIRONMENT') && ENVIRONMENT === 'production');
        setcookie(TOKEN_COOKIE_NAME, '', time() - 3600, '/', '', $secure, true);
        setcookie(REFRESH_TOKEN_COOKIE_NAME, '', time() - 3600, '/', '', $secure, true);

        // Clear session
        unset($_SESSION['user'], $_SESSION[SESSION_USER_KEY], $_SESSION['access_token'], $_SESSION[SESSION_TOKEN_KEY], $_SESSION['refresh_token']);
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated() {
        return !empty($this->accessToken);
    }
}
