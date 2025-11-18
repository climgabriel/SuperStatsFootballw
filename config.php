<?php
/**
 * SuperStatsFootball Frontend Configuration
 *
 * Central configuration file for API endpoints and application settings
 */

// Prevent direct access
if (!defined('SSF_CONFIG')) {
    define('SSF_CONFIG', true);
}

// Include API configuration to ensure constants are available
require_once __DIR__ . '/includes/api-config.php';

// API Configuration (allow environment variable override)
// Check for API_BASE_URL first (GreenGeeks hosting), then BACKEND_API_URL (local dev)
$backendApiUrl = getenv('API_BASE_URL') ?: getenv('BACKEND_API_URL') ?: '';
if (empty($backendApiUrl) && isset($_ENV['API_BASE_URL'])) {
    $backendApiUrl = $_ENV['API_BASE_URL'];
} elseif (empty($backendApiUrl) && isset($_ENV['BACKEND_API_URL'])) {
    $backendApiUrl = $_ENV['BACKEND_API_URL'];
}
// Default to production Railway backend
$backendApiUrl = $backendApiUrl ? rtrim($backendApiUrl, '/') : 'https://superstatsfootball-production.up.railway.app';

// Only define if api-config.php hasn't already defined them
if (!defined('API_BASE_URL')) {
    define('API_BASE_URL', $backendApiUrl);
}
if (!defined('API_VERSION')) {
    define('API_VERSION', 'v1');
}
if (!defined('API_PREFIX')) {
    define('API_PREFIX', '/api/' . API_VERSION);
}

// API Endpoints (only if not already defined)
if (!defined('API_AUTH_LOGIN')) {
    define('API_AUTH_LOGIN', API_BASE_URL . API_PREFIX . '/auth/login');
    define('API_AUTH_REGISTER', API_BASE_URL . API_PREFIX . '/auth/register');
    define('API_AUTH_REFRESH', API_BASE_URL . API_PREFIX . '/auth/refresh');
    define('API_AUTH_LOGOUT', API_BASE_URL . API_PREFIX . '/auth/logout');
    define('API_PREDICTIONS_ODDS', API_BASE_URL . API_PREFIX . '/combined/fixtures/predictions-with-odds');
    define('API_FIXTURES', API_BASE_URL . API_PREFIX . '/fixtures');
    define('API_LEAGUES', API_BASE_URL . API_PREFIX . '/leagues');
    define('API_USER_PROFILE', API_BASE_URL . API_PREFIX . '/users/me');
}

// Session Configuration
if (!defined('SESSION_NAME')) {
    define('SESSION_NAME', 'ssf_session');
}
if (!defined('TOKEN_COOKIE_NAME')) {
    define('TOKEN_COOKIE_NAME', 'ssf_access_token');
}
if (!defined('REFRESH_TOKEN_COOKIE_NAME')) {
    define('REFRESH_TOKEN_COOKIE_NAME', 'ssf_refresh_token');
}
if (!defined('TOKEN_EXPIRY_MINUTES')) {
    define('TOKEN_EXPIRY_MINUTES', 30);
}
if (!defined('REFRESH_TOKEN_EXPIRY_DAYS')) {
    define('REFRESH_TOKEN_EXPIRY_DAYS', 7);
}

// Application Settings
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Super Stats Football');
}
if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'production'); // 'development' or 'production'
}

// Error Reporting (disable in production)
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('UTC');

// Helper Functions
function isLoggedIn() {
    return isset($_SESSION['user']) && isset($_SESSION['access_token']);
}

function getUserTier() {
    return $_SESSION['user']['tier'] ?? 'free';
}

function getAccessToken() {
    return $_SESSION['access_token'] ?? $_COOKIE[TOKEN_COOKIE_NAME] ?? null;
}

function getRefreshToken() {
    return $_SESSION['refresh_token'] ?? $_COOKIE[REFRESH_TOKEN_COOKIE_NAME] ?? null;
}

function redirectToLogin() {
    header('Location: login.php');
    exit;
}

if (!function_exists('requireAuth')) {
    function requireAuth() {
        if (!isLoggedIn()) {
            redirectToLogin();
        }
    }
}

// CSRF Protection Functions
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getCSRFToken() {
    return $_SESSION['csrf_token'] ?? generateCSRFToken();
}

function validateCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Session Security Configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', ENVIRONMENT === 'production' ? '1' : '0');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
ini_set('session.gc_maxlifetime', TOKEN_EXPIRY_MINUTES * 60);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();

    // Regenerate session ID periodically for security
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}
