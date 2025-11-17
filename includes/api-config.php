<?php
/**
 * Backend API Configuration
 *
 * Configuration for connecting to the SuperStatsFootball FastAPI backend
 */

// Backend API base URL
// PRODUCTION: Update this to your actual backend URL when deploying
define('API_BASE_URL', getenv('BACKEND_API_URL') ?: 'https://superstatsfootball-production.up.railway.app');
define('API_VERSION', 'v1');
define('API_PREFIX', API_BASE_URL . '/api/' . API_VERSION);

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
define('SESSION_TOKEN_KEY', 'api_auth_token');
define('SESSION_USER_KEY', 'api_user_data');
