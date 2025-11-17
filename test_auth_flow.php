<?php
/**
 * SuperStatsFootball - Authentication Flow Test
 *
 * This script tests all authentication components without making actual API calls
 * Run this via browser: http://localhost/test_auth_flow.php
 */

require_once 'config.php';
require_once 'includes/APIClient.php';

// Test results storage
$tests = [];
$total_tests = 0;
$passed_tests = 0;
$failed_tests = 0;

function runTest($name, $description, $testFunction) {
    global $tests, $total_tests, $passed_tests, $failed_tests;
    $total_tests++;

    $start_time = microtime(true);
    try {
        $result = $testFunction();
        $passed = $result['passed'];
        $message = $result['message'];
        $details = $result['details'] ?? '';
    } catch (Exception $e) {
        $passed = false;
        $message = 'Exception: ' . $e->getMessage();
        $details = $e->getTraceAsString();
    }
    $execution_time = round((microtime(true) - $start_time) * 1000, 2);

    if ($passed) {
        $passed_tests++;
    } else {
        $failed_tests++;
    }

    $tests[] = [
        'number' => $total_tests,
        'name' => $name,
        'description' => $description,
        'passed' => $passed,
        'message' => $message,
        'details' => $details,
        'time' => $execution_time
    ];
}

// TEST 1: CSRF Token Generation
runTest(
    'CSRF Token Generation',
    'Verify that CSRF tokens are generated correctly',
    function() {
        $token1 = generateCSRFToken();
        $token2 = getCSRFToken();

        if (empty($token1)) {
            return ['passed' => false, 'message' => 'CSRF token is empty'];
        }

        if (strlen($token1) != 64) { // 32 bytes = 64 hex characters
            return ['passed' => false, 'message' => 'CSRF token length incorrect (expected 64, got ' . strlen($token1) . ')'];
        }

        if ($token1 !== $token2) {
            return ['passed' => false, 'message' => 'Multiple calls to getCSRFToken() return different values'];
        }

        return [
            'passed' => true,
            'message' => 'CSRF token generated successfully',
            'details' => 'Token: ' . substr($token1, 0, 16) . '... (length: ' . strlen($token1) . ')'
        ];
    }
);

// TEST 2: CSRF Token Validation
runTest(
    'CSRF Token Validation',
    'Verify that CSRF token validation works correctly',
    function() {
        $token = getCSRFToken();

        // Test valid token
        if (!validateCSRFToken($token)) {
            return ['passed' => false, 'message' => 'Valid token rejected'];
        }

        // Test invalid token
        if (validateCSRFToken('invalid_token_12345')) {
            return ['passed' => false, 'message' => 'Invalid token accepted'];
        }

        // Test empty token
        if (validateCSRFToken('')) {
            return ['passed' => false, 'message' => 'Empty token accepted'];
        }

        return [
            'passed' => true,
            'message' => 'CSRF validation works correctly',
            'details' => 'Valid tokens accepted, invalid tokens rejected'
        ];
    }
);

// TEST 3: Session Security Configuration
runTest(
    'Session Security Settings',
    'Check if session security settings are properly configured',
    function() {
        $issues = [];
        $config_ok = true;

        // Check session.cookie_httponly
        $httponly = ini_get('session.cookie_httponly');
        if ($httponly != '1') {
            $issues[] = 'session.cookie_httponly should be 1 (currently: ' . $httponly . ')';
            $config_ok = false;
        }

        // Check session.cookie_samesite
        $samesite = ini_get('session.cookie_samesite');
        if ($samesite !== 'Lax' && $samesite !== 'Strict') {
            $issues[] = 'session.cookie_samesite should be Lax or Strict (currently: ' . $samesite . ')';
            $config_ok = false;
        }

        // Check session.use_strict_mode
        $strict_mode = ini_get('session.use_strict_mode');
        if ($strict_mode != '1') {
            $issues[] = 'session.use_strict_mode should be 1 (currently: ' . $strict_mode . ')';
            $config_ok = false;
        }

        if ($config_ok) {
            return [
                'passed' => true,
                'message' => 'All session security settings configured correctly',
                'details' => 'HttpOnly: ' . $httponly . ', SameSite: ' . $samesite . ', Strict Mode: ' . $strict_mode
            ];
        } else {
            return [
                'passed' => false,
                'message' => 'Session security issues found',
                'details' => implode("\n", $issues)
            ];
        }
    }
);

// TEST 4: Session Created Timestamp
runTest(
    'Session Timestamp Tracking',
    'Verify session creation time is tracked for regeneration',
    function() {
        if (!isset($_SESSION['created'])) {
            return ['passed' => false, 'message' => 'Session creation timestamp not set'];
        }

        $created = $_SESSION['created'];
        if (!is_numeric($created)) {
            return ['passed' => false, 'message' => 'Session created timestamp is not numeric'];
        }

        $age = time() - $created;
        if ($age < 0 || $age > 3600) {
            return ['passed' => false, 'message' => 'Session timestamp appears invalid (age: ' . $age . ' seconds)'];
        }

        return [
            'passed' => true,
            'message' => 'Session timestamp tracking works',
            'details' => 'Session age: ' . $age . ' seconds'
        ];
    }
);

// TEST 5: Config Constants
runTest(
    'Configuration Constants',
    'Verify all required configuration constants are defined',
    function() {
        $required_constants = [
            'API_BASE_URL', 'API_VERSION', 'API_PREFIX',
            'API_AUTH_LOGIN', 'API_AUTH_REGISTER', 'API_AUTH_REFRESH', 'API_AUTH_LOGOUT',
            'SESSION_NAME', 'TOKEN_COOKIE_NAME', 'REFRESH_TOKEN_COOKIE_NAME',
            'TOKEN_EXPIRY_MINUTES', 'REFRESH_TOKEN_EXPIRY_DAYS',
            'APP_NAME', 'APP_VERSION', 'ENVIRONMENT'
        ];

        $missing = [];
        foreach ($required_constants as $const) {
            if (!defined($const)) {
                $missing[] = $const;
            }
        }

        if (count($missing) > 0) {
            return [
                'passed' => false,
                'message' => count($missing) . ' constants missing',
                'details' => 'Missing: ' . implode(', ', $missing)
            ];
        }

        return [
            'passed' => true,
            'message' => 'All ' . count($required_constants) . ' configuration constants defined',
            'details' => 'API: ' . API_BASE_URL . ', Environment: ' . ENVIRONMENT
        ];
    }
);

// TEST 6: Helper Functions
runTest(
    'Helper Functions Exist',
    'Verify all authentication helper functions are defined',
    function() {
        $required_functions = [
            'isLoggedIn', 'getUserTier', 'getAccessToken', 'getRefreshToken',
            'redirectToLogin', 'requireAuth',
            'generateCSRFToken', 'getCSRFToken', 'validateCSRFToken'
        ];

        $missing = [];
        foreach ($required_functions as $func) {
            if (!function_exists($func)) {
                $missing[] = $func;
            }
        }

        if (count($missing) > 0) {
            return [
                'passed' => false,
                'message' => count($missing) . ' functions missing',
                'details' => 'Missing: ' . implode(', ', $missing)
            ];
        }

        return [
            'passed' => true,
            'message' => 'All ' . count($required_functions) . ' helper functions defined',
            'details' => 'Authentication helpers ready'
        ];
    }
);

// TEST 7: Authentication State Check
runTest(
    'Authentication State Check',
    'Test isLoggedIn() function behavior',
    function() {
        // Should be logged out initially (no test user)
        $logged_in = isLoggedIn();

        if ($logged_in) {
            // Check if there's actually session data
            if (!isset($_SESSION['user']) || !isset($_SESSION['access_token'])) {
                return [
                    'passed' => false,
                    'message' => 'isLoggedIn() returns true but session data is incomplete'
                ];
            }
            return [
                'passed' => true,
                'message' => 'User is currently logged in',
                'details' => 'User: ' . ($_SESSION['user']['email'] ?? 'unknown')
            ];
        } else {
            return [
                'passed' => true,
                'message' => 'User is not logged in (expected for test)',
                'details' => 'No active session detected'
            ];
        }
    }
);

// TEST 8: APIClient Class
runTest(
    'APIClient Class Available',
    'Verify APIClient class exists and can be instantiated',
    function() {
        if (!class_exists('APIClient')) {
            return ['passed' => false, 'message' => 'APIClient class not found'];
        }

        try {
            $api = new APIClient();
            $reflection = new ReflectionClass('APIClient');
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            $method_names = array_map(function($m) { return $m->name; }, $methods);

            $required_methods = ['login', 'register', 'logout', 'refreshAccessToken', 'getUserProfile'];
            $missing_methods = array_diff($required_methods, $method_names);

            if (count($missing_methods) > 0) {
                return [
                    'passed' => false,
                    'message' => 'APIClient missing methods',
                    'details' => 'Missing: ' . implode(', ', $missing_methods)
                ];
            }

            return [
                'passed' => true,
                'message' => 'APIClient class ready with all required methods',
                'details' => 'Methods: ' . implode(', ', $required_methods)
            ];
        } catch (Exception $e) {
            return ['passed' => false, 'message' => 'Failed to instantiate APIClient: ' . $e->getMessage()];
        }
    }
);

// TEST 9: File Structure
runTest(
    'Required Files Exist',
    'Check that all critical authentication files are present',
    function() {
        $required_files = [
            'config.php',
            'index.php',
            'login.php',
            'register.php',
            'logout.php',
            '1x2.php',
            'includes/APIClient.php',
            'includes/app-header.php',
            'includes/app-footer.php',
            'includes/auth-header.php',
            'includes/auth-footer.php',
            'includes/auth-logo.php',
            'error_401.php',
            'error_403.php',
            'error_404.php',
            'error_500.php',
            '.htaccess'
        ];

        $missing = [];
        foreach ($required_files as $file) {
            if (!file_exists(__DIR__ . '/' . $file)) {
                $missing[] = $file;
            }
        }

        if (count($missing) > 0) {
            return [
                'passed' => false,
                'message' => count($missing) . ' files missing',
                'details' => 'Missing: ' . implode(', ', $missing)
            ];
        }

        return [
            'passed' => true,
            'message' => 'All ' . count($required_files) . ' required files present',
            'details' => 'File structure is complete'
        ];
    }
);

// TEST 10: Cookie Parameters
runTest(
    'Cookie Configuration',
    'Verify cookie security parameters for tokens',
    function() {
        // Check token expiry settings
        if (TOKEN_EXPIRY_MINUTES < 15) {
            return [
                'passed' => false,
                'message' => 'Access token expiry too short (should be >= 15 minutes)',
                'details' => 'Currently: ' . TOKEN_EXPIRY_MINUTES . ' minutes'
            ];
        }

        if (REFRESH_TOKEN_EXPIRY_DAYS < 1) {
            return [
                'passed' => false,
                'message' => 'Refresh token expiry too short (should be >= 1 day)',
                'details' => 'Currently: ' . REFRESH_TOKEN_EXPIRY_DAYS . ' days'
            ];
        }

        return [
            'passed' => true,
            'message' => 'Cookie expiry settings are appropriate',
            'details' => 'Access: ' . TOKEN_EXPIRY_MINUTES . 'min, Refresh: ' . REFRESH_TOKEN_EXPIRY_DAYS . ' days'
        ];
    }
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Flow Test - SuperStatsFootball</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .header p { opacity: 0.9; font-size: 16px; }
        .content { padding: 30px; }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .summary-card.success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .summary-card.danger { background: linear-gradient(135deg, #dc3545 0%, #f56565 100%); }
        .summary-card.info { background: linear-gradient(135deg, #17a2b8 0%, #00d4ff 100%); }
        .summary-card .number {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary-card .label {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .test-result {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .test-result:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .test-result.passed { border-left: 5px solid #28a745; }
        .test-result.failed { border-left: 5px solid #dc3545; }
        .test-header {
            padding: 20px;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .test-header .test-info h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }
        .test-header .test-info p {
            font-size: 14px;
            color: #666;
        }
        .test-header .test-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-badge.passed {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }
        .time-badge {
            background: #d1ecf1;
            color: #0c5460;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }
        .test-body {
            padding: 20px;
            background: white;
        }
        .test-message {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #333;
        }
        .test-details {
            padding: 15px;
            background: #e9ecef;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #666;
            white-space: pre-wrap;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
        .final-verdict {
            background: #fff;
            border: 3px solid;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        .final-verdict.pass {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }
        .final-verdict.fail {
            border-color: #dc3545;
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        }
        .final-verdict h2 {
            font-size: 36px;
            margin-bottom: 15px;
        }
        .final-verdict.pass h2 { color: #155724; }
        .final-verdict.fail h2 { color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Authentication Flow Test</h1>
            <p>SuperStatsFootball - Security & Configuration Verification</p>
        </div>
        <div class="content">

            <!-- Summary Stats -->
            <div class="summary">
                <div class="summary-card info">
                    <div class="number"><?php echo $total_tests; ?></div>
                    <div class="label">Total Tests</div>
                </div>
                <div class="summary-card success">
                    <div class="number"><?php echo $passed_tests; ?></div>
                    <div class="label">Passed</div>
                </div>
                <div class="summary-card danger">
                    <div class="number"><?php echo $failed_tests; ?></div>
                    <div class="label">Failed</div>
                </div>
                <div class="summary-card <?php echo $failed_tests == 0 ? 'success' : 'danger'; ?>">
                    <div class="number"><?php echo round(($passed_tests / $total_tests) * 100); ?>%</div>
                    <div class="label">Pass Rate</div>
                </div>
            </div>

            <!-- Final Verdict -->
            <div class="final-verdict <?php echo $failed_tests == 0 ? 'pass' : 'fail'; ?>">
                <h2><?php echo $failed_tests == 0 ? '✅ ALL TESTS PASSED!' : '⚠️ SOME TESTS FAILED'; ?></h2>
                <p style="font-size: 18px; margin-bottom: 10px;">
                    <?php
                    if ($failed_tests == 0) {
                        echo 'Your authentication system is properly configured and ready for production!';
                    } else {
                        echo 'Please review the failed tests below and fix the issues before deploying to production.';
                    }
                    ?>
                </p>
                <p style="font-size: 14px; opacity: 0.8;">
                    <?php echo $passed_tests . ' out of ' . $total_tests . ' tests passed'; ?>
                </p>
            </div>

            <!-- Individual Test Results -->
            <?php foreach ($tests as $test): ?>
            <div class="test-result <?php echo $test['passed'] ? 'passed' : 'failed'; ?>">
                <div class="test-header">
                    <div class="test-info">
                        <h3><?php echo $test['number']; ?>. <?php echo htmlspecialchars($test['name']); ?></h3>
                        <p><?php echo htmlspecialchars($test['description']); ?></p>
                    </div>
                    <div class="test-status">
                        <span class="time-badge"><?php echo $test['time']; ?>ms</span>
                        <span class="status-badge <?php echo $test['passed'] ? 'passed' : 'failed'; ?>">
                            <?php echo $test['passed'] ? '✓ PASSED' : '✗ FAILED'; ?>
                        </span>
                    </div>
                </div>
                <div class="test-body">
                    <div class="test-message">
                        <?php echo $test['passed'] ? '✅' : '❌'; ?>
                        <?php echo htmlspecialchars($test['message']); ?>
                    </div>
                    <?php if (!empty($test['details'])): ?>
                    <div class="test-details"><?php echo htmlspecialchars($test['details']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        <div class="footer">
            <p>SuperStatsFootball Authentication Flow Test</p>
            <p style="font-size: 12px; margin-top: 5px; opacity: 0.7;">Generated at <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
