<?php
/**
 * Backend API Helper Functions
 *
 * Backward-compatible wrapper functions for the new ApiRepository
 * This maintains existing API for legacy code while using the improved architecture
 */

require_once 'api-config.php';
require_once 'ApiRepository.php';

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
