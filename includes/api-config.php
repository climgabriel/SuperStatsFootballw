<?php
/**
 * Backend API Configuration
 *
 * Configuration for connecting to the SuperStatsFootball FastAPI backend
 */

// Backend API base URL
// PRODUCTION: Update this to your actual backend URL when deploying
// Only define if not already defined in main config.php
if (!defined('API_BASE_URL')) {
    // Check for API_BASE_URL first (GreenGeeks), then BACKEND_API_URL (local dev)
    $envApiUrl = getenv('API_BASE_URL') ?: getenv('BACKEND_API_URL') ?: '';
    define('API_BASE_URL', $envApiUrl ?: 'https://superstatsfootball-production.up.railway.app');
}
if (!defined('API_VERSION')) {
    define('API_VERSION', 'v1');
}
if (!defined('API_PREFIX')) {
    // API_PREFIX should be just the path, not the full URL
    define('API_PREFIX', '/api/' . API_VERSION);
}

// API Endpoints
define('API_ENDPOINTS', [
    // Authentication
    'auth_login' => API_PREFIX . '/auth/login',
    'auth_register' => API_PREFIX . '/auth/register',
    'auth_me' => API_PREFIX . '/auth/me',

    // Statistics
    'stats_goals' => API_PREFIX . '/statistics/goals',
    'stats_corners' => API_PREFIX . '/statistics/corners',
    'stats_cards' => API_PREFIX . '/statistics/cards',
    'stats_shots' => API_PREFIX . '/statistics/shots',
    'stats_fouls' => API_PREFIX . '/statistics/fouls',
    'stats_offsides' => API_PREFIX . '/statistics/offs',

    // Odds
    'odds_upcoming' => API_PREFIX . '/odds/upcoming',
    'odds_fixture' => API_PREFIX . '/odds/fixture',

    // Predictions
    'predictions' => API_PREFIX . '/predictions',
    'combined_predictions' => API_PREFIX . '/combined',

    // Leagues
    'leagues' => API_PREFIX . '/leagues',
    'fixtures' => API_PREFIX . '/fixtures',
]);

// Default request timeout (seconds)
define('API_TIMEOUT', 10);

// Enable/disable SSL verification (disable for local development only)
define('API_SSL_VERIFY', true);

// Session key for storing auth token
if (!defined('SESSION_TOKEN_KEY')) {
    define('SESSION_TOKEN_KEY', 'api_auth_token');
}
if (!defined('SESSION_USER_KEY')) {
    define('SESSION_USER_KEY', 'api_user_data');
}
