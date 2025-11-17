<?php
/**
 * Backend API Helper Functions
 *
 * Helper functions for making authenticated requests to the FastAPI backend
 */

require_once 'api-config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Make an authenticated API request
 *
 * @param string $endpoint API endpoint URL
 * @param string $method HTTP method (GET, POST, PUT, DELETE)
 * @param array|null $data Request data (for POST/PUT)
 * @param array $headers Additional headers
 * @return array Response data with 'success', 'data', and 'error' keys
 */
function apiRequest($endpoint, $method = 'GET', $data = null, $headers = []) {
    // Get auth token from session
    $token = $_SESSION[SESSION_TOKEN_KEY] ?? null;

    // Prepare headers
    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    // Add authorization header if token exists
    if ($token) {
        $defaultHeaders[] = 'Authorization: Bearer ' . $token;
    }

    // Merge with custom headers
    $allHeaders = array_merge($defaultHeaders, $headers);

    // Initialize cURL
    $ch = curl_init();

    // Set common options
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
        case 'GET':
        default:
            // GET is default, no additional options needed
            break;
    }

    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    // Handle errors
    if ($error) {
        return [
            'success' => false,
            'data' => null,
            'error' => 'Connection error: ' . $error,
            'http_code' => $httpCode
        ];
    }

    // Parse response
    $responseData = json_decode($response, true);

    // Check HTTP status
    if ($httpCode >= 200 && $httpCode < 300) {
        return [
            'success' => true,
            'data' => $responseData,
            'error' => null,
            'http_code' => $httpCode
        ];
    } else {
        return [
            'success' => false,
            'data' => null,
            'error' => $responseData['detail'] ?? 'Request failed',
            'http_code' => $httpCode
        ];
    }
}

/**
 * Get goals statistics from backend API
 *
 * @param int $daysAhead Number of days to look ahead
 * @param int|null $leagueId Optional league filter
 * @param int $limit Maximum results
 * @param int $offset Pagination offset
 * @return array API response
 */
function getGoalsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['stats_goals'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Get corners statistics from backend API
 */
function getCornersStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['stats_corners'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Get cards statistics from backend API
 */
function getCardsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['stats_cards'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Get shots statistics from backend API
 */
function getShotsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['stats_shots'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Get fouls/faults statistics from backend API
 */
function getFoulsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['stats_fouls'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Get offsides statistics from backend API
 */
function getOffsidesStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['stats_offsides'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Get odds for upcoming fixtures
 */
function getUpcomingOdds($daysAhead = 7, $leagueId = null, $limit = 100, $offset = 0) {
    $params = [
        'days_ahead' => $daysAhead,
        'limit' => $limit,
        'offset' => $offset
    ];

    if ($leagueId) {
        $params['league_id'] = $leagueId;
    }

    $endpoint = API_ENDPOINTS['odds_upcoming'] . '?' . http_build_query($params);
    return apiRequest($endpoint, 'GET');
}

/**
 * Login user and store auth token
 *
 * @param string $email User email
 * @param string $password User password
 * @return array Login response
 */
function loginUser($email, $password) {
    $data = [
        'username' => $email, // FastAPI OAuth2 uses 'username' field
        'password' => $password
    ];

    $response = apiRequest(API_ENDPOINTS['auth_login'], 'POST', $data);

    // Store token in session if login successful
    if ($response['success'] && isset($response['data']['access_token'])) {
        $_SESSION[SESSION_TOKEN_KEY] = $response['data']['access_token'];
        $_SESSION[SESSION_USER_KEY] = $response['data']['user'] ?? null;
    }

    return $response;
}

/**
 * Logout user and clear session
 */
function logoutUser() {
    unset($_SESSION[SESSION_TOKEN_KEY]);
    unset($_SESSION[SESSION_USER_KEY]);
    session_destroy();
}

/**
 * Check if user is authenticated
 *
 * @return bool
 */
function isAuthenticated() {
    return isset($_SESSION[SESSION_TOKEN_KEY]) && !empty($_SESSION[SESSION_TOKEN_KEY]);
}

/**
 * Get current user data from session
 *
 * @return array|null
 */
function getCurrentUser() {
    return $_SESSION[SESSION_USER_KEY] ?? null;
}
