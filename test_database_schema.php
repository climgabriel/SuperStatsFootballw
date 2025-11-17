<?php
/**
 * SuperStatsFootball - Database Schema Verification Test
 *
 * This script verifies that your database schema matches the backend requirements
 * Run this via browser: http://localhost/test_database_schema.php
 */

// Database configuration - UPDATE THESE WITH YOUR LOCAL CREDENTIALS
$db_host = 'localhost';
$db_name = 'tipscom_SSF';
$db_user = 'cpses_tuhhy2wgw';
$db_pass = 'YOUR_PASSWORD_HERE'; // UPDATE THIS!
$db_port = 3306;

// Expected database schema
$expected_tables = [
    'users' => [
        'columns' => ['id', 'email', 'password_hash', 'full_name', 'tier', 'subscription_id', 'subscription_status', 'stripe_customer_id', 'created_at', 'updated_at', 'last_login'],
        'required_indexes' => ['email', 'tier'],
        'description' => 'User authentication and subscription data'
    ],
    'leagues' => [
        'columns' => ['id', 'name', 'country', 'logo', 'season', 'tier_required', 'is_active', 'priority', 'created_at', 'updated_at'],
        'required_indexes' => ['tier_required', 'is_active'],
        'description' => 'League/competition information'
    ],
    'teams' => [
        'columns' => ['id', 'name', 'code', 'country', 'logo', 'founded', 'venue_name', 'venue_capacity', 'created_at', 'updated_at'],
        'required_indexes' => ['name'],
        'description' => 'Team information'
    ],
    'fixtures' => [
        'columns' => ['id', 'league_id', 'season', 'round', 'match_date', 'timestamp', 'home_team_id', 'away_team_id', 'status', 'elapsed_time', 'venue', 'referee', 'created_at', 'updated_at'],
        'required_indexes' => ['league_id', 'round', 'match_date', 'home_team_id', 'away_team_id', 'status'],
        'description' => 'Match/fixture data'
    ],
    'fixture_stats' => [
        'columns' => ['id', 'fixture_id', 'team_id', 'shots_on_goal', 'shots_off_goal', 'total_shots', 'blocked_shots', 'shots_inside_box', 'shots_outside_box', 'fouls', 'corners', 'offsides', 'ball_possession', 'yellow_cards', 'red_cards', 'goalkeeper_saves', 'total_passes', 'passes_accurate', 'passes_percentage', 'expected_goals', 'created_at', 'updated_at'],
        'required_indexes' => ['fixture_id', 'team_id'],
        'description' => 'Detailed match statistics'
    ],
    'fixture_scores' => [
        'columns' => ['id', 'fixture_id', 'home_halftime', 'away_halftime', 'home_fulltime', 'away_fulltime', 'home_extratime', 'away_extratime', 'home_penalty', 'away_penalty', 'created_at', 'updated_at'],
        'required_indexes' => ['fixture_id'],
        'description' => 'Match scores (HT, FT, ET, Penalties)'
    ],
    'fixture_odds' => [
        'columns' => ['id', 'fixture_id', 'bookmaker_id', 'bookmaker_name', 'home_win_odds', 'draw_odds', 'away_win_odds', 'ht_home_win_odds', 'ht_draw_odds', 'ht_away_win_odds', 'ft_home_win_odds', 'ft_draw_odds', 'ft_away_win_odds', 'over_2_5_odds', 'under_2_5_odds', 'is_live', 'fetched_at', 'created_at', 'updated_at'],
        'required_indexes' => ['fixture_id', 'bookmaker_name', 'is_live'],
        'description' => 'Betting odds data'
    ],
    'predictions' => [
        'columns' => ['id', 'fixture_id', 'user_id', 'model_type', 'prediction_data', 'confidence_score', 'created_at', 'is_admin_model'],
        'required_indexes' => ['fixture_id', 'user_id', 'model_type', 'created_at'],
        'description' => 'ML prediction results'
    ],
    'team_ratings' => [
        'columns' => ['id', 'team_id', 'league_id', 'season', 'elo_rating', 'offensive_strength', 'defensive_strength', 'home_advantage', 'form_last_5', 'updated_at'],
        'required_indexes' => ['team_id', 'league_id'],
        'description' => 'Team performance ratings'
    ],
    'user_settings' => [
        'columns' => ['id', 'user_id', 'favorite_leagues', 'favorite_teams', 'default_model', 'timezone', 'notifications', 'ui_preferences', 'created_at', 'updated_at'],
        'required_indexes' => ['user_id'],
        'description' => 'User preferences and settings'
    ]
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Schema Verification - SuperStatsFootball</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .header p { opacity: 0.9; font-size: 16px; }
        .content { padding: 30px; }
        .summary {
            background: #f8f9fa;
            border-left: 4px solid #106147;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .summary h2 { color: #106147; margin-bottom: 15px; font-size: 24px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .stat-box { background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .stat-box .number { font-size: 36px; font-weight: bold; margin-bottom: 5px; }
        .stat-box .label { color: #666; font-size: 14px; }
        .success .number { color: #28a745; }
        .danger .number { color: #dc3545; }
        .warning .number { color: #ffc107; }
        .info .number { color: #17a2b8; }
        .table-section { margin-bottom: 30px; }
        .table-header {
            background: #106147;
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-header h3 { font-size: 18px; }
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge.success { background: #28a745; }
        .badge.danger { background: #dc3545; }
        .badge.warning { background: #ffc107; color: #000; }
        .table-body {
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 20px;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr 100px;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #333; }
        .detail-value { color: #666; font-family: 'Courier New', monospace; font-size: 13px; }
        .status-icon { text-align: center; font-size: 24px; }
        .column-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }
        .column-tag {
            background: #e9ecef;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-family: 'Courier New', monospace;
        }
        .column-tag.missing { background: #f8d7da; color: #721c24; }
        .error-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .error-box h3 { margin-bottom: 10px; }
        .connection-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗄️ Database Schema Verification</h1>
            <p>SuperStatsFootball - Production Readiness Test</p>
        </div>
        <div class="content">

<?php
// Test database connection
try {
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo '<div class="connection-info">';
    echo '<strong>✅ Database Connection Successful</strong><br>';
    echo "Host: {$db_host}:{$db_port} | Database: {$db_name} | User: {$db_user}";
    echo '</div>';

    // Get all tables in database
    $stmt = $pdo->query("SHOW TABLES");
    $actual_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Initialize counters
    $tables_found = 0;
    $tables_missing = 0;
    $columns_correct = 0;
    $columns_missing = 0;
    $indexes_found = 0;
    $total_expected_tables = count($expected_tables);

    // Summary stats
    echo '<div class="summary">';
    echo '<h2>📊 Verification Summary</h2>';

    // Check each expected table
    foreach ($expected_tables as $table_name => $table_info) {
        if (in_array($table_name, $actual_tables)) {
            $tables_found++;

            // Get actual columns
            $stmt = $pdo->query("DESCRIBE {$table_name}");
            $actual_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($table_info['columns'] as $expected_col) {
                if (in_array($expected_col, $actual_columns)) {
                    $columns_correct++;
                } else {
                    $columns_missing++;
                }
            }

            // Check indexes
            $stmt = $pdo->query("SHOW INDEX FROM {$table_name}");
            $indexes = $stmt->fetchAll();
            $index_names = array_unique(array_column($indexes, 'Column_name'));

            foreach ($table_info['required_indexes'] as $required_index) {
                if (in_array($required_index, $index_names)) {
                    $indexes_found++;
                }
            }
        } else {
            $tables_missing++;
        }
    }

    echo '<div class="stats">';
    echo '<div class="stat-box ' . ($tables_found == $total_expected_tables ? 'success' : 'danger') . '">';
    echo '<div class="number">' . $tables_found . '/' . $total_expected_tables . '</div>';
    echo '<div class="label">Tables Found</div>';
    echo '</div>';

    echo '<div class="stat-box ' . ($tables_missing == 0 ? 'success' : 'danger') . '">';
    echo '<div class="number">' . $tables_missing . '</div>';
    echo '<div class="label">Tables Missing</div>';
    echo '</div>';

    echo '<div class="stat-box ' . ($columns_correct > 0 ? 'success' : 'warning') . '">';
    echo '<div class="number">' . $columns_correct . '</div>';
    echo '<div class="label">Columns Correct</div>';
    echo '</div>';

    echo '<div class="stat-box ' . ($columns_missing == 0 ? 'success' : 'danger') . '">';
    echo '<div class="number">' . $columns_missing . '</div>';
    echo '<div class="label">Columns Missing</div>';
    echo '</div>';

    echo '<div class="stat-box info">';
    echo '<div class="number">' . $indexes_found . '</div>';
    echo '<div class="label">Indexes Found</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Detailed table analysis
    foreach ($expected_tables as $table_name => $table_info) {
        $table_exists = in_array($table_name, $actual_tables);

        echo '<div class="table-section">';
        echo '<div class="table-header">';
        echo '<div>';
        echo '<h3>📋 ' . strtoupper($table_name) . '</h3>';
        echo '<small style="opacity:0.8">' . $table_info['description'] . '</small>';
        echo '</div>';
        if ($table_exists) {
            echo '<span class="badge success">✓ EXISTS</span>';
        } else {
            echo '<span class="badge danger">✗ MISSING</span>';
        }
        echo '</div>';

        echo '<div class="table-body">';

        if ($table_exists) {
            // Get actual columns
            $stmt = $pdo->query("DESCRIBE {$table_name}");
            $actual_columns_data = $stmt->fetchAll();
            $actual_columns = array_column($actual_columns_data, 'Field');

            echo '<div class="detail-row">';
            echo '<div class="detail-label">Expected Columns:</div>';
            echo '<div class="detail-value">';
            echo '<div class="column-list">';
            foreach ($table_info['columns'] as $col) {
                $exists = in_array($col, $actual_columns);
                $class = $exists ? 'column-tag' : 'column-tag missing';
                $icon = $exists ? '✓' : '✗';
                echo "<span class='{$class}'>{$icon} {$col}</span>";
            }
            echo '</div>';
            echo '</div>';
            echo '<div class="status-icon">' . (count(array_diff($table_info['columns'], $actual_columns)) == 0 ? '✅' : '⚠️') . '</div>';
            echo '</div>';

            // Show indexes
            $stmt = $pdo->query("SHOW INDEX FROM {$table_name}");
            $indexes = $stmt->fetchAll();
            $index_names = array_unique(array_column($indexes, 'Column_name'));

            echo '<div class="detail-row">';
            echo '<div class="detail-label">Indexes:</div>';
            echo '<div class="detail-value">';
            echo '<div class="column-list">';
            foreach ($table_info['required_indexes'] as $idx) {
                $exists = in_array($idx, $index_names);
                $class = $exists ? 'column-tag' : 'column-tag missing';
                $icon = $exists ? '✓' : '✗';
                echo "<span class='{$class}'>{$icon} {$idx}</span>";
            }
            echo '</div>';
            echo '</div>';
            echo '<div class="status-icon">' . (count(array_diff($table_info['required_indexes'], $index_names)) == 0 ? '✅' : '⚠️') . '</div>';
            echo '</div>';

            // Show row count
            $stmt = $pdo->query("SELECT COUNT(*) FROM {$table_name}");
            $row_count = $stmt->fetchColumn();
            echo '<div class="detail-row">';
            echo '<div class="detail-label">Row Count:</div>';
            echo '<div class="detail-value">' . number_format($row_count) . ' rows</div>';
            echo '<div class="status-icon">📊</div>';
            echo '</div>';

        } else {
            echo '<div class="error-box">';
            echo '<h3>⚠️ Table Not Found</h3>';
            echo '<p>This table is required but does not exist in your database. You need to run database migrations or create this table manually.</p>';
            echo '<p><strong>Required columns:</strong> ' . implode(', ', $table_info['columns']) . '</p>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }

    // Final recommendation
    echo '<div class="summary">';
    echo '<h2>🎯 Recommendations</h2>';

    if ($tables_missing == 0 && $columns_missing == 0) {
        echo '<div style="color: #28a745; font-size: 18px; margin-bottom: 10px;">✅ <strong>Database Schema is PERFECT!</strong></div>';
        echo '<p>Your database schema matches the backend requirements exactly. You are ready for production deployment!</p>';
    } elseif ($tables_missing > 0) {
        echo '<div style="color: #dc3545; font-size: 18px; margin-bottom: 10px;">❌ <strong>Critical Issues Found</strong></div>';
        echo '<p><strong>' . $tables_missing . ' tables are missing.</strong> You need to:</p>';
        echo '<ol>';
        echo '<li>Run database migrations from the backend project</li>';
        echo '<li>Or manually create the missing tables using the schema above</li>';
        echo '<li>Ensure all foreign key relationships are properly set up</li>';
        echo '</ol>';
    } elseif ($columns_missing > 0) {
        echo '<div style="color: #ffc107; font-size: 18px; margin-bottom: 10px;">⚠️ <strong>Minor Issues Found</strong></div>';
        echo '<p><strong>' . $columns_missing . ' columns are missing.</strong> This might cause errors when the backend tries to access these fields.</p>';
        echo '<p>Review the tables above with ⚠️ icons and add the missing columns.</p>';
    }

    echo '</div>';

} catch (PDOException $e) {
    echo '<div class="error-box">';
    echo '<h3>❌ Database Connection Failed</h3>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Solution:</strong></p>';
    echo '<ol>';
    echo '<li>Update the database credentials at the top of this file (line 10-13)</li>';
    echo '<li>Ensure MySQL/MariaDB server is running</li>';
    echo '<li>Verify the database "' . htmlspecialchars($db_name) . '" exists</li>';
    echo '<li>Check that user "' . htmlspecialchars($db_user) . '" has access to the database</li>';
    echo '</ol>';
    echo '</div>';
}
?>

        </div>
        <div class="footer">
            <p>SuperStatsFootball Database Schema Verification Test</p>
            <p style="font-size: 12px; margin-top: 5px; opacity: 0.7;">Generated at <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
