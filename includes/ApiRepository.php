<?php
/**
 * API Repository - Clean interface for backend API communication
 *
 * Features:
 * - Repository pattern for clean architecture
 * - Built-in caching with CacheManager
 * - Error logging with Logger
 * - Request validation and user limits enforcement
 * - Performance monitoring
 */

require_once 'api-config.php';
require_once 'CacheManager.php';
require_once 'Logger.php';
require_once 'UserManager.php';

class ApiRepository {

    private $cache;
    private $logger;
    private $cacheTTL = 300; // 5 minutes default cache

    /**
     * Constructor
     *
     * @param CacheManager|null $cache Cache manager instance
     * @param Logger|null $logger Logger instance
     */
    public function __construct($cache = null, $logger = null) {
        $this->cache = $cache ?? new CacheManager();
        $this->logger = $logger ?? new Logger();
    }

    /**
     * Make an authenticated API request with caching and logging
     *
     * @param string $endpoint API endpoint URL
     * @param string $method HTTP method
     * @param array|null $data Request data
     * @param array $headers Additional headers
     * @param bool $useCache Whether to use caching
     * @return array Response data
     */
    private function request($endpoint, $method = 'GET', $data = null, $headers = [], $useCache = true) {
        $startTime = microtime(true);

        // Generate cache key for GET requests
        if ($method === 'GET' && $useCache) {
            $cacheKey = 'api_' . md5($endpoint);
            $cached = $this->cache->get($cacheKey);

            if ($cached !== null) {
                $this->logger->debug("Cache hit for: {$endpoint}");
                return $cached;
            }
        }

        // Get auth token from session
        $token = $_SESSION[SESSION_TOKEN_KEY] ?? null;

        // Prepare headers
        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if ($token) {
            $defaultHeaders[] = 'Authorization: Bearer ' . $token;
        }

        $allHeaders = array_merge($defaultHeaders, $headers);

        // Initialize cURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, API_TIMEOUT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, API_SSL_VERIFY);

        // Set method-specific options
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $duration = microtime(true) - $startTime;

        // Log API request
        $this->logger->logApiRequest($endpoint, $method, $httpCode, $duration);

        // Handle cURL errors
        if ($error) {
            $this->logger->error("API request failed", [
                'endpoint' => $endpoint,
                'error' => $error,
                'http_code' => $httpCode
            ]);

            return [
                'success' => false,
                'data' => null,
                'error' => 'Connection error: ' . $error,
                'http_code' => $httpCode
            ];
        }

        // Parse response
        $responseData = json_decode($response, true);

        // Build result
        if ($httpCode >= 200 && $httpCode < 300) {
            $result = [
                'success' => true,
                'data' => $responseData,
                'error' => null,
                'http_code' => $httpCode
            ];

            // Cache successful GET requests
            if ($method === 'GET' && $useCache) {
                $this->cache->set($cacheKey, $result, $this->cacheTTL);
            }

            return $result;
        } else {
            $errorMessage = $responseData['detail'] ?? 'Request failed';

            $this->logger->error("API error response", [
                'endpoint' => $endpoint,
                'http_code' => $httpCode,
                'error' => $errorMessage
            ]);

            return [
                'success' => false,
                'data' => null,
                'error' => $errorMessage,
                'http_code' => $httpCode
            ];
        }
    }

    /**
     * Build endpoint with query parameters
     *
     * @param string $baseEndpoint Base endpoint URL
     * @param array $params Query parameters
     * @return string Full endpoint with query string
     */
    private function buildEndpoint($baseEndpoint, $params = []) {
        // Add base URL if not already present
        if (strpos($baseEndpoint, 'http') !== 0) {
            $baseEndpoint = API_BASE_URL . $baseEndpoint;
        }

        $params = array_filter($params, function($value) {
            return $value !== null;
        });

        if (empty($params)) {
            return $baseEndpoint;
        }

        return $baseEndpoint . '?' . http_build_query($params);
    }

    /**
     * Validate league selection based on user limits
     *
     * @param mixed $leagueIds League ID(s)
     * @return array ['valid' => bool, 'leagues' => array, 'error' => string]
     */
    private function validateLeagues($leagueIds) {
        if ($leagueIds === null) {
            return ['valid' => true, 'leagues' => [], 'error' => null];
        }

        // Convert to array
        $leagues = is_array($leagueIds) ? $leagueIds : [$leagueIds];

        // Get user role and limits
        $userRole = UserManager::getUserRole();
        $validation = UserManager::validateLeagueSelection($leagues, $userRole);

        if (!$validation['valid']) {
            $this->logger->warning("League selection limit exceeded", [
                'user_role' => $userRole,
                'selected_count' => count($leagues),
                'max_allowed' => UserManager::getMaxLeagues($userRole)
            ]);

            return [
                'valid' => false,
                'leagues' => [],
                'error' => $validation['message']
            ];
        }

        return ['valid' => true, 'leagues' => $leagues, 'error' => null];
    }

    /**
     * Get statistics with league validation
     *
     * @param string $endpoint Endpoint key from API_ENDPOINTS
     * @param int $daysAhead Number of days ahead
     * @param mixed $leagueIds League ID(s) to filter
     * @param int $limit Result limit
     * @param int $offset Pagination offset
     * @return array API response
     */
    private function getStatistics($endpoint, $daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        // Validate league selection
        $leagueValidation = $this->validateLeagues($leagueIds);

        if (!$leagueValidation['valid']) {
            return [
                'success' => false,
                'data' => null,
                'error' => $leagueValidation['error'],
                'http_code' => 400
            ];
        }

        // Build parameters
        $params = [
            'days_ahead' => $daysAhead,
            'limit' => $limit,
            'offset' => $offset
        ];

        // Add league filter if provided
        if (!empty($leagueValidation['leagues'])) {
            $params['league_id'] = implode(',', $leagueValidation['leagues']);
        }

        $fullEndpoint = $this->buildEndpoint(API_ENDPOINTS[$endpoint], $params);
        return $this->request($fullEndpoint, 'GET');
    }

    /**
     * Get goals statistics
     */
    public function getGoalsStatistics($daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        return $this->getStatistics('stats_goals', $daysAhead, $leagueIds, $limit, $offset);
    }

    /**
     * Get corners statistics
     */
    public function getCornersStatistics($daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        return $this->getStatistics('stats_corners', $daysAhead, $leagueIds, $limit, $offset);
    }

    /**
     * Get cards statistics
     */
    public function getCardsStatistics($daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        return $this->getStatistics('stats_cards', $daysAhead, $leagueIds, $limit, $offset);
    }

    /**
     * Get shots statistics
     */
    public function getShotsStatistics($daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        return $this->getStatistics('stats_shots', $daysAhead, $leagueIds, $limit, $offset);
    }

    /**
     * Get fouls statistics
     */
    public function getFoulsStatistics($daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        return $this->getStatistics('stats_fouls', $daysAhead, $leagueIds, $limit, $offset);
    }

    /**
     * Get offsides statistics
     */
    public function getOffsidesStatistics($daysAhead = 7, $leagueIds = null, $limit = 50, $offset = 0) {
        return $this->getStatistics('stats_offsides', $daysAhead, $leagueIds, $limit, $offset);
    }

    /**
     * Get upcoming odds
     */
    public function getUpcomingOdds($daysAhead = 7, $leagueIds = null, $limit = 100, $offset = 0) {
        $leagueValidation = $this->validateLeagues($leagueIds);

        if (!$leagueValidation['valid']) {
            return [
                'success' => false,
                'data' => null,
                'error' => $leagueValidation['error'],
                'http_code' => 400
            ];
        }

        $params = [
            'days_ahead' => $daysAhead,
            'limit' => $limit,
            'offset' => $offset
        ];

        if (!empty($leagueValidation['leagues'])) {
            $params['league_id'] = implode(',', $leagueValidation['leagues']);
        }

        $endpoint = $this->buildEndpoint(API_ENDPOINTS['odds_upcoming'], $params);
        return $this->request($endpoint, 'GET');
    }

    /**
     * Login user
     */
    public function loginUser($email, $password) {
        $data = [
            'username' => $email,
            'password' => $password
        ];

        $response = $this->request(API_BASE_URL . API_ENDPOINTS['auth_login'], 'POST', $data, [], false);

        // Store token and user data in session
        if ($response['success'] && isset($response['data']['access_token'])) {
            $_SESSION[SESSION_TOKEN_KEY] = $response['data']['access_token'];
            $_SESSION[SESSION_USER_KEY] = $response['data']['user'] ?? null;

            $this->logger->info("User logged in", ['email' => $email]);
        }

        return $response;
    }

    /**
     * Get available leagues from backend
     *
     * @param bool $useCache Whether to use caching
     * @return array API response with leagues list
     */
    public function getLeagues($useCache = true) {
        return $this->request(API_BASE_URL . API_ENDPOINTS['leagues'], 'GET', null, [], $useCache);
    }

    /**
     * Get current user information
     *
     * @return array API response with user data
     */
    public function getCurrentUserInfo() {
        return $this->request(API_BASE_URL . API_ENDPOINTS['auth_me'], 'GET', null, [], false);
    }

    /**
     * Register a new user
     *
     * @param string $email User email
     * @param string $password User password
     * @param string $fullName User's full name
     * @param int $plan Subscription plan (1-5)
     * @return array Registration response
     */
    public function registerUser($email, $password, $fullName, $plan = 1) {
        // Backend only accepts email, password, and full_name
        // Plan is set to 'free' by default on the backend
        $data = [
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName
        ];

        $response = $this->request(API_BASE_URL . API_ENDPOINTS['auth_register'], 'POST', $data, [], false);

        // Store token and user data if registration successful
        if ($response['success'] && isset($response['data']['access_token'])) {
            $_SESSION[SESSION_TOKEN_KEY] = $response['data']['access_token'];
            $_SESSION[SESSION_USER_KEY] = $response['data']['user'] ?? null;

            $this->logger->info("User registered successfully", ['email' => $email]);
        }

        return $response;
    }

    /**
     * Clear cache for specific endpoint or all
     */
    public function clearCache($endpoint = null) {
        if ($endpoint) {
            $cacheKey = 'api_' . md5($endpoint);
            return $this->cache->delete($cacheKey);
        }

        return $this->cache->clear();
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats() {
        return $this->cache->getStats();
    }

    /**
     * Get logger statistics
     */
    public function getLoggerStats() {
        return $this->logger->getStats();
    }
}
