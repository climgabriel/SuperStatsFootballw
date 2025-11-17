<?php
/**
 * SuperStatsFootball - Logout
 */

require_once 'config.php';

// Try to logout from API first (while session is still active)
try {
    $api = new APIClient();
    $result = $api->makeRequest('POST', '/auth/logout', null, true);
} catch (Exception $e) {
    // Ignore API errors during logout
}

// Clear session data
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Delete authentication cookies
setcookie(TOKEN_COOKIE_NAME, '', time() - 3600, '/');
setcookie(REFRESH_TOKEN_COOKIE_NAME, '', time() - 3600, '/');

// Destroy session
session_destroy();

// Redirect to login
header('Location: login.php?logged_out=1');
exit;
