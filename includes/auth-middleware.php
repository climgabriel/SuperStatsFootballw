<?php
/**
 * Authentication Middleware
 *
 * Protects pages that require authentication
 */

require_once __DIR__ . '/api-helper.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is authenticated, redirect to login if not
 *
 * @param string $redirectTo URL to redirect after login
 */
function requireAuth($redirectTo = null) {
    if (!isAuthenticated()) {
        // Store the intended destination
        if ($redirectTo) {
            $_SESSION['redirect_after_login'] = $redirectTo;
        } else {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        }

        // Redirect to login
        header('Location: login.php');
        exit;
    }
}

/**
 * Try to authenticate with demo account for seamless UX
 *
 * @return bool Success status
 */
function tryDemoAuth() {
    // Check if already authenticated
    if (isAuthenticated()) {
        return true;
    }

    // Try to login with demo credentials (if available)
    // This provides seamless access to statistics
    $demoEmail = getenv('DEMO_USER_EMAIL') ?: 'demo@superstatsfootball.com';
    $demoPassword = getenv('DEMO_USER_PASSWORD') ?: 'demo123';

    $response = loginUser($demoEmail, $demoPassword);

    return $response['success'];
}
