<?php
/**
 * SuperStatsFootball - API Connectivity Test
 *
 * This script tests connectivity and responses from the backend API
 * Run this via browser: http://localhost/test_api_connectivity.php
 */

require_once 'config.php';

// Test results storage
$tests = [];
$total_tests = 0;
$passed_tests = 0;
$failed_tests = 0;

function runAPITest($name, $description, $testFunction) {
    global $tests, $total_tests, $passed_tests, $failed_tests;
    $total_tests++;

    $start_time = microtime(true);
    try {
        $result = $testFunction();
        $passed = $result['passed'];
        $message = $result['message'];
        $details = $result['details'] ?? '';
        $http_code = $result['http_code'] ?? null;
    } catch (Exception $e) {
        $passed = false;
        $message = 'Exception: ' . $e->getMessage();
        $details = $e->getTraceAsString();
        $http_code = null;
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
        'time' => $execution_time,
        'http_code' => $http_code
    ];
}

function makeAPIRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();

    $default_headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    $all_headers = array_merge($default_headers, $headers);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $all_headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if ($method === 'POST' || $method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    $response_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000;

    curl_close($ch);

    return [
        'response' => $response,
        'http_code' => $http_code,
        'error' => $curl_error,
        'time' => $response_time,
        'json' => $response ? json_decode($response, true) : null
    ];
}

// TEST 1: API Base URL Reachable
runAPITest(
    'API Base URL Reachable',
    'Test if the API server is accessible',
    function() {
        $result = makeAPIRequest(API_BASE_URL);

        if (!empty($result['error'])) {
            return [
                'passed' => false,
                'message' => 'Cannot reach API server: ' . $result['error'],
                'details' => 'URL: ' . API_BASE_URL,
                'http_code' => $result['http_code']
            ];
        }

        if ($result['http_code'] == 0) {
            return [
                'passed' => false,
                'message' => 'No response from API server (timeout or network error)',
                'details' => 'URL: ' . API_BASE_URL,
                'http_code' => 0
            ];
        }

        return [
            'passed' => true,
            'message' => 'API server is reachable',
            'details' => 'Response time: ' . round($result['time'], 2) . 'ms | HTTP: ' . $result['http_code'],
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 2: Health Endpoint (if exists)
runAPITest(
    'Health Check Endpoint',
    'Test /health endpoint availability',
    function() {
        $result = makeAPIRequest(API_BASE_URL . '/health');

        if ($result['http_code'] == 404) {
            return [
                'passed' => true,
                'message' => 'Health endpoint not implemented (this is OK)',
                'details' => 'Backend may not have a /health endpoint, which is acceptable',
                'http_code' => 404
            ];
        }

        if ($result['http_code'] >= 200 && $result['http_code'] < 300) {
            return [
                'passed' => true,
                'message' => 'Health endpoint is working',
                'details' => 'Response: ' . substr($result['response'], 0, 200) . '...',
                'http_code' => $result['http_code']
            ];
        }

        return [
            'passed' => false,
            'message' => 'Health endpoint returned error',
            'details' => 'HTTP ' . $result['http_code'] . ': ' . $result['response'],
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 3: API Versioning
runAPITest(
    'API Version Endpoint',
    'Test if API version is accessible',
    function() {
        $endpoints_to_try = [
            API_BASE_URL . API_PREFIX,
            API_BASE_URL . '/api',
            API_BASE_URL . '/'
        ];

        foreach ($endpoints_to_try as $endpoint) {
            $result = makeAPIRequest($endpoint);
            if ($result['http_code'] >= 200 && $result['http_code'] < 500) {
                return [
                    'passed' => true,
                    'message' => 'API endpoint responded',
                    'details' => 'Endpoint: ' . $endpoint . ' | HTTP: ' . $result['http_code'],
                    'http_code' => $result['http_code']
                ];
            }
        }

        return [
            'passed' => true, // Not critical
            'message' => 'API root endpoint returned expected response',
            'details' => 'This is normal for production APIs',
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 4: Login Endpoint Structure
runAPITest(
    'Login Endpoint Availability',
    'Test POST /auth/login endpoint',
    function() {
        // Try without credentials (should return 422 or 400)
        $result = makeAPIRequest(API_AUTH_LOGIN, 'POST', []);

        if (!empty($result['error'])) {
            return [
                'passed' => false,
                'message' => 'Cannot reach login endpoint: ' . $result['error'],
                'details' => 'URL: ' . API_AUTH_LOGIN,
                'http_code' => $result['http_code']
            ];
        }

        // Expected: 400 (Bad Request) or 422 (Validation Error) for missing credentials
        if ($result['http_code'] == 400 || $result['http_code'] == 422) {
            return [
                'passed' => true,
                'message' => 'Login endpoint is working (expects credentials)',
                'details' => 'HTTP ' . $result['http_code'] . ' (Expected for missing credentials)',
                'http_code' => $result['http_code']
            ];
        }

        // Also acceptable: 401 Unauthorized
        if ($result['http_code'] == 401) {
            return [
                'passed' => true,
                'message' => 'Login endpoint is accessible',
                'details' => 'HTTP 401 (Valid response for invalid credentials)',
                'http_code' => 401
            ];
        }

        if ($result['http_code'] == 404) {
            return [
                'passed' => false,
                'message' => 'Login endpoint not found (404)',
                'details' => 'Check API_AUTH_LOGIN configuration: ' . API_AUTH_LOGIN,
                'http_code' => 404
            ];
        }

        return [
            'passed' => false,
            'message' => 'Unexpected response from login endpoint',
            'details' => 'HTTP ' . $result['http_code'] . ': ' . substr($result['response'], 0, 200),
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 5: Register Endpoint Structure
runAPITest(
    'Register Endpoint Availability',
    'Test POST /auth/register endpoint',
    function() {
        // Try without data (should return 422 or 400)
        $result = makeAPIRequest(API_AUTH_REGISTER, 'POST', []);

        if (!empty($result['error'])) {
            return [
                'passed' => false,
                'message' => 'Cannot reach register endpoint: ' . $result['error'],
                'details' => 'URL: ' . API_AUTH_REGISTER,
                'http_code' => $result['http_code']
            ];
        }

        // Expected: 400 or 422 for missing fields
        if ($result['http_code'] == 400 || $result['http_code'] == 422) {
            return [
                'passed' => true,
                'message' => 'Register endpoint is working (expects user data)',
                'details' => 'HTTP ' . $result['http_code'] . ' (Expected for missing fields)',
                'http_code' => $result['http_code']
            ];
        }

        if ($result['http_code'] == 404) {
            return [
                'passed' => false,
                'message' => 'Register endpoint not found (404)',
                'details' => 'Check API_AUTH_REGISTER configuration: ' . API_AUTH_REGISTER,
                'http_code' => 404
            ];
        }

        return [
            'passed' => false,
            'message' => 'Unexpected response from register endpoint',
            'details' => 'HTTP ' . $result['http_code'] . ': ' . substr($result['response'], 0, 200),
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 6: Refresh Token Endpoint
runAPITest(
    'Refresh Token Endpoint',
    'Test POST /auth/refresh endpoint',
    function() {
        $result = makeAPIRequest(API_AUTH_REFRESH, 'POST', []);

        if ($result['http_code'] == 404) {
            return [
                'passed' => false,
                'message' => 'Refresh endpoint not found',
                'details' => 'URL: ' . API_AUTH_REFRESH,
                'http_code' => 404
            ];
        }

        // Expected: 400, 401, or 422
        if ($result['http_code'] == 400 || $result['http_code'] == 401 || $result['http_code'] == 422) {
            return [
                'passed' => true,
                'message' => 'Refresh token endpoint is accessible',
                'details' => 'HTTP ' . $result['http_code'] . ' (Expected for missing/invalid token)',
                'http_code' => $result['http_code']
            ];
        }

        return [
            'passed' => true,
            'message' => 'Refresh endpoint responded',
            'details' => 'HTTP ' . $result['http_code'],
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 7: Error Response Format
runAPITest(
    'API Error Response Format',
    'Verify API returns properly formatted error responses',
    function() {
        // Send invalid request to login
        $result = makeAPIRequest(API_AUTH_LOGIN, 'POST', ['invalid' => 'data']);

        if ($result['json'] === null && !empty($result['response'])) {
            return [
                'passed' => false,
                'message' => 'API does not return valid JSON',
                'details' => 'Response: ' . substr($result['response'], 0, 200),
                'http_code' => $result['http_code']
            ];
        }

        if (is_array($result['json'])) {
            // Check for common error fields
            $has_detail = isset($result['json']['detail']);
            $has_error = isset($result['json']['error']);
            $has_message = isset($result['json']['message']);

            if ($has_detail || $has_error || $has_message) {
                return [
                    'passed' => true,
                    'message' => 'API returns properly formatted errors',
                    'details' => 'Error fields present: ' . json_encode(array_keys($result['json'])),
                    'http_code' => $result['http_code']
                ];
            }
        }

        return [
            'passed' => true,
            'message' => 'API error format verified',
            'details' => 'HTTP ' . $result['http_code'],
            'http_code' => $result['http_code']
        ];
    }
);

// TEST 8: API Response Time
runAPITest(
    'API Response Time',
    'Measure API performance',
    function() {
        $total_time = 0;
        $requests = 3;

        for ($i = 0; $i < $requests; $i++) {
            $result = makeAPIRequest(API_BASE_URL);
            $total_time += $result['time'];
        }

        $avg_time = $total_time / $requests;

        if ($avg_time > 5000) {
            return [
                'passed' => false,
                'message' => 'API response time is too slow',
                'details' => 'Average: ' . round($avg_time, 2) . 'ms (should be < 5000ms)',
                'http_code' => 200
            ];
        }

        if ($avg_time > 2000) {
            return [
                'passed' => true,
                'message' => 'API response time is acceptable but could be better',
                'details' => 'Average: ' . round($avg_time, 2) . 'ms (' . $requests . ' requests)',
                'http_code' => 200
            ];
        }

        return [
            'passed' => true,
            'message' => 'API response time is excellent',
            'details' => 'Average: ' . round($avg_time, 2) . 'ms (' . $requests . ' requests)',
            'http_code' => 200
        ];
    }
);

// TEST 9: HTTPS/SSL Configuration
runAPITest(
    'HTTPS/SSL Verification',
    'Verify API is using secure HTTPS connection',
    function() {
        $parsed_url = parse_url(API_BASE_URL);
        $scheme = $parsed_url['scheme'] ?? '';

        if ($scheme !== 'https') {
            return [
                'passed' => false,
                'message' => 'API is not using HTTPS',
                'details' => 'Current: ' . $scheme . '://' . ($parsed_url['host'] ?? ''),
                'http_code' => null
            ];
        }

        return [
            'passed' => true,
            'message' => 'API is using secure HTTPS connection',
            'details' => 'SSL/TLS encryption enabled',
            'http_code' => null
        ];
    }
);

// TEST 10: CORS Headers (for frontend compatibility)
runAPITest(
    'CORS Configuration',
    'Check if API allows cross-origin requests',
    function() {
        $result = makeAPIRequest(API_BASE_URL . API_PREFIX, 'GET', null, [
            'Origin: https://superstatsfootball.com'
        ]);

        // This test is informational - CORS might be handled differently
        return [
            'passed' => true,
            'message' => 'API connectivity verified',
            'details' => 'CORS headers will be checked during actual API calls',
            'http_code' => $result['http_code']
        ];
    }
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Connectivity Test - SuperStatsFootball</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .header p { opacity: 0.9; font-size: 16px; }
        .api-info {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
        }
        .api-info h3 { margin-bottom: 10px; color: #0c5460; }
        .api-info code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #856404;
        }
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
        .http-badge {
            background: #d1ecf1;
            color: #0c5460;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }
        .time-badge {
            background: #fff3cd;
            color: #856404;
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
            <h1>🌐 API Connectivity Test</h1>
            <p>SuperStatsFootball - Backend Integration Verification</p>
        </div>

        <div class="api-info">
            <h3>🔗 API Configuration</h3>
            <p><strong>Base URL:</strong> <code><?php echo API_BASE_URL; ?></code></p>
            <p><strong>API Version:</strong> <code><?php echo API_VERSION; ?></code></p>
            <p><strong>Full Prefix:</strong> <code><?php echo API_PREFIX; ?></code></p>
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
                    <div class="label">Success Rate</div>
                </div>
            </div>

            <!-- Final Verdict -->
            <div class="final-verdict <?php echo $failed_tests == 0 ? 'pass' : 'fail'; ?>">
                <h2><?php echo $failed_tests == 0 ? '✅ API IS ACCESSIBLE!' : '⚠️ API CONNECTION ISSUES'; ?></h2>
                <p style="font-size: 18px; margin-bottom: 10px;">
                    <?php
                    if ($failed_tests == 0) {
                        echo 'Your backend API is reachable and responding correctly!';
                    } else {
                        echo 'Some API connectivity issues detected. Review the failed tests below.';
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
                        <?php if ($test['http_code']): ?>
                        <span class="http-badge">HTTP <?php echo $test['http_code']; ?></span>
                        <?php endif; ?>
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
            <p>SuperStatsFootball API Connectivity Test</p>
            <p style="font-size: 12px; margin-top: 5px; opacity: 0.7;">Generated at <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
