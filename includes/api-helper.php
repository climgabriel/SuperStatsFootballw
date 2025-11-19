<?php
/**
 * Backend API Helper Functions
 *
 * Backward-compatible wrapper functions for the new ApiRepository
 * This maintains existing API for legacy code while using the improved architecture
 */

require_once __DIR__ . '/api-config.php';
require_once __DIR__ . '/ApiRepository.php';
require_once __DIR__ . '/UserManager.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize the API Repository singleton
function getApiRepository() {
    static $repository = null;
    if ($repository === null) {
        $repository = new ApiRepository();
    }
    return $repository;
}

/**
 * Legacy API request function - now uses ApiRepository
 * Kept for backward compatibility
 *
 * @deprecated Use ApiRepository directly instead
 */
function apiRequest($endpoint, $method = 'GET', $data = null, $headers = []) {
    // This function is kept for backward compatibility
    // but internally uses the new ApiRepository
    $repo = getApiRepository();
    // Note: Direct access to request() is private, so this returns a basic response
    // For new code, use ApiRepository methods directly
    return [
        'success' => false,
        'data' => null,
        'error' => 'Use ApiRepository methods directly',
        'http_code' => 500
    ];
}

/**
 * Get goals statistics - Now uses ApiRepository
 */
function getGoalsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    return getApiRepository()->getGoalsStatistics($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Get corners statistics - Now uses ApiRepository
 */
function getCornersStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    return getApiRepository()->getCornersStatistics($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Get cards statistics - Now uses ApiRepository
 */
function getCardsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    return getApiRepository()->getCardsStatistics($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Get shots statistics - Now uses ApiRepository
 */
function getShotsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    return getApiRepository()->getShotsStatistics($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Get fouls statistics - Now uses ApiRepository
 */
function getFoulsStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    return getApiRepository()->getFoulsStatistics($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Get offsides statistics - Now uses ApiRepository
 */
function getOffsidesStatistics($daysAhead = 7, $leagueId = null, $limit = 50, $offset = 0) {
    return getApiRepository()->getOffsidesStatistics($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Get upcoming odds - Now uses ApiRepository
 */
function getUpcomingOdds($daysAhead = 7, $leagueId = null, $limit = 100, $offset = 0) {
    return getApiRepository()->getUpcomingOdds($daysAhead, $leagueId, $limit, $offset);
}

/**
 * Login user - Now uses ApiRepository
 */
function loginUser($email, $password) {
    return getApiRepository()->loginUser($email, $password);
}

/**
 * Register new user - Now uses ApiRepository
 */
function registerUser($email, $password, $fullName, $plan = 1) {
    return getApiRepository()->registerUser($email, $password, $fullName, $plan);
}

/**
 * Get current user info from backend
 */
function getCurrentUserInfo() {
    return getApiRepository()->getCurrentUserInfo();
}

/**
 * Get available leagues from backend
 */
function getLeagues($useCache = true) {
    return getApiRepository()->getLeagues($useCache);
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
 * Checks both api_auth_token and access_token for backwards compatibility
 *
 * @return bool
 */
function isAuthenticated() {
    // Check new token format (ApiRepository)
    if (isset($_SESSION[SESSION_TOKEN_KEY]) && !empty($_SESSION[SESSION_TOKEN_KEY])) {
        return true;
    }

    // Check legacy token format (APIClient) for backwards compatibility
    if (isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])) {
        return true;
    }

    return false;
}

/**
 * Get current user data from session
 *
 * @return array|null
 */
function getCurrentUser() {
    return $_SESSION[SESSION_USER_KEY] ?? null;
}

/**
 * Get user's pricing tier
 *
 * @return string User tier (free, starter, pro, premium, ultimate)
 */
function getUserTier() {
    $user = getCurrentUser();
    return $user['tier'] ?? 'free';
}

/**
 * Check if user has access to premium statistics (non-1X2 pages)
 * Admin users have full access
 * Free tier only has access to 1X2 page
 *
 * @return bool True if user can access premium statistics
 */
function hasPremiumStatsAccess() {
    // Admin users have full access to all statistics
    $userRole = UserManager::getUserRole();
    if ($userRole === UserManager::ROLE_ADMIN) {
        return true;
    }

    // For regular users, check tier
    $tier = getUserTier();
    // Free tier users cannot access premium statistics
    return $tier !== 'free';
}
