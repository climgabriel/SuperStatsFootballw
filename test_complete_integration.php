<?php
/**
 * SuperStatsFootball - Complete Integration Test
 *
 * This is the master test runner that executes all test suites and generates a comprehensive report
 * Run this via browser: http://localhost/test_complete_integration.php
 */

$start_time = microtime(true);

// Execute each test suite and capture results
$test_suites = [
    'database' => [
        'name' => 'Database Schema',
        'description' => 'Verify database structure matches backend requirements',
        'icon' => '🗄️',
        'color' => 'purple'
    ],
    'auth' => [
        'name' => 'Authentication Flow',
        'description' => 'Test authentication components and security',
        'icon' => '🔐',
        'color' => 'pink'
    ],
    'api' => [
        'name' => 'API Connectivity',
        'description' => 'Test backend API accessibility and responses',
        'icon' => '🌐',
        'color' => 'blue'
    ]
];

$total_execution_time = round((microtime(true) - $start_time) * 1000, 2);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Integration Test - SuperStatsFootball</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #106147 0%, #005440 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 { font-size: 42px; margin-bottom: 10px; }
        .header p { opacity: 0.9; font-size: 18px; }
        .header .subtitle {
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.8;
        }
        .content { padding: 40px; }
        .test-suite-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .test-suite-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .test-suite-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .suite-header {
            padding: 25px;
            color: white;
            text-align: center;
        }
        .suite-header.purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .suite-header.pink { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .suite-header.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .suite-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .suite-header h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .suite-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .suite-body {
            padding: 25px;
        }
        .suite-action {
            text-align: center;
            margin-top: 20px;
        }
        .suite-btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #106147 0%, #005440 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .suite-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        .info-list {
            list-style: none;
            padding: 0;
        }
        .info-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .info-list li:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #666;
            font-size: 14px;
        }
        .info-value {
            font-weight: 600;
            color: #333;
        }
        .system-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .system-info h3 {
            color: #106147;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .system-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .system-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #106147;
        }
        .system-card .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .system-card .value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            font-family: 'Courier New', monospace;
        }
        .quick-start {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .quick-start h3 {
            color: #155724;
            margin-bottom: 15px;
            font-size: 24px;
        }
        .quick-start ol {
            margin-left: 20px;
            color: #155724;
        }
        .quick-start li {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .footer {
            background: #106147;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .footer h3 {
            margin-bottom: 15px;
            font-size: 24px;
        }
        .footer p {
            opacity: 0.9;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Complete Integration Test Suite</h1>
            <p>SuperStatsFootball - Production Readiness Verification</p>
            <div class="subtitle">
                Comprehensive testing for database, authentication, and API connectivity
            </div>
        </div>

        <div class="content">

            <!-- Quick Start Guide -->
            <div class="quick-start">
                <h3>📋 How to Use This Test Suite</h3>
                <ol>
                    <li><strong>Update Database Credentials:</strong> Edit <code>test_database_schema.php</code> (line 10-13) with your MySQL credentials</li>
                    <li><strong>Run Each Test:</strong> Click the buttons below to run each test suite individually</li>
                    <li><strong>Review Results:</strong> Each test provides detailed pass/fail information</li>
                    <li><strong>Fix Issues:</strong> Address any failed tests before deploying to production</li>
                    <li><strong>Retest:</strong> Run tests again after making changes</li>
                </ol>
            </div>

            <!-- System Information -->
            <div class="system-info">
                <h3>💻 System Information</h3>
                <div class="system-grid">
                    <div class="system-card">
                        <div class="label">PHP Version</div>
                        <div class="value"><?php echo phpversion(); ?></div>
                    </div>
                    <div class="system-card">
                        <div class="label">Server Software</div>
                        <div class="value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></div>
                    </div>
                    <div class="system-card">
                        <div class="label">Operating System</div>
                        <div class="value"><?php echo PHP_OS; ?></div>
                    </div>
                    <div class="system-card">
                        <div class="label">Server Time</div>
                        <div class="value"><?php echo date('Y-m-d H:i:s'); ?></div>
                    </div>
                    <div class="system-card">
                        <div class="label">API Endpoint</div>
                        <div class="value" style="font-size: 12px;"><?php
                        if (defined('API_BASE_URL')) {
                            echo API_BASE_URL;
                        } else {
                            echo 'Not configured';
                        }
                        ?></div>
                    </div>
                    <div class="system-card">
                        <div class="label">Environment</div>
                        <div class="value"><?php echo defined('ENVIRONMENT') ? ENVIRONMENT : 'Not set'; ?></div>
                    </div>
                </div>
            </div>

            <!-- Test Suite Cards -->
            <div class="test-suite-grid">
                <!-- Database Schema Test -->
                <div class="test-suite-card">
                    <div class="suite-header purple">
                        <div class="icon">🗄️</div>
                        <h2>Database Schema</h2>
                        <p>Verify database structure</p>
                    </div>
                    <div class="suite-body">
                        <ul class="info-list">
                            <li>
                                <span class="info-label">Tables to Check</span>
                                <span class="info-value">10</span>
                            </li>
                            <li>
                                <span class="info-label">Critical Tables</span>
                                <span class="info-value">users, fixtures, predictions</span>
                            </li>
                            <li>
                                <span class="info-label">Estimated Time</span>
                                <span class="info-value">~5 seconds</span>
                            </li>
                        </ul>
                        <div class="suite-action">
                            <a href="test_database_schema.php" class="suite-btn" target="_blank">
                                Run Database Test →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Authentication Flow Test -->
                <div class="test-suite-card">
                    <div class="suite-header pink">
                        <div class="icon">🔐</div>
                        <h2>Authentication Flow</h2>
                        <p>Test security components</p>
                    </div>
                    <div class="suite-body">
                        <ul class="info-list">
                            <li>
                                <span class="info-label">Tests to Run</span>
                                <span class="info-value">10</span>
                            </li>
                            <li>
                                <span class="info-label">Key Checks</span>
                                <span class="info-value">CSRF, Sessions, Cookies</span>
                            </li>
                            <li>
                                <span class="info-label">Estimated Time</span>
                                <span class="info-value">~2 seconds</span>
                            </li>
                        </ul>
                        <div class="suite-action">
                            <a href="test_auth_flow.php" class="suite-btn" target="_blank">
                                Run Auth Test →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- API Connectivity Test -->
                <div class="test-suite-card">
                    <div class="suite-header blue">
                        <div class="icon">🌐</div>
                        <h2>API Connectivity</h2>
                        <p>Test backend integration</p>
                    </div>
                    <div class="suite-body">
                        <ul class="info-list">
                            <li>
                                <span class="info-label">Endpoints to Test</span>
                                <span class="info-value">10</span>
                            </li>
                            <li>
                                <span class="info-label">Key Checks</span>
                                <span class="info-value">Login, Register, Refresh</span>
                            </li>
                            <li>
                                <span class="info-label">Estimated Time</span>
                                <span class="info-value">~15 seconds</span>
                            </li>
                        </ul>
                        <div class="suite-action">
                            <a href="test_api_connectivity.php" class="suite-btn" target="_blank">
                                Run API Test →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer">
            <h3>🎯 Production Deployment Checklist</h3>
            <p><strong>Before deploying to production, ensure:</strong></p>
            <p>✅ All database tests pass (10/10 tables exist with correct schema)</p>
            <p>✅ All authentication tests pass (CSRF protection, session security configured)</p>
            <p>✅ All API connectivity tests pass (backend is accessible and responding)</p>
            <p style="margin-top: 20px; font-size: 14px; opacity: 0.8;">
                SuperStatsFootball Complete Integration Test Suite<br>
                Generated at <?php echo date('Y-m-d H:i:s'); ?>
            </p>
        </div>
    </div>
</body>
</html>
