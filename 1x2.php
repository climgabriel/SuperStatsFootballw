<?php
require_once 'config.php';
require_once 'includes/auth-middleware.php';
require_once 'includes/APIClient.php';

// Require authentication for this page
requireAuth();

$pageTitle = "1X2 Statistics - Super Stats Football";
$pageDescription = "1X2 Match Predictions and Statistics with ML Models";
$activePage = "1x2";

// Initialize API client
$api = new APIClient();
$matches = [];
$error = null;
$userTier = getUserTier();

// Get filter parameters
$daysAhead = isset($_GET['days']) ? (int)$_GET['days'] : 7;
$leagueId = isset($_GET['league_id']) ? (int)$_GET['league_id'] : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

try {
    // Fetch predictions with odds from unified endpoint
    $result = $api->getPredictionsWithOdds($daysAhead, $leagueId, $limit);

    if ($result['success']) {
        $responseData = $result['data'];
        $matches = $responseData['fixtures'] ?? [];
    } else {
        $error = $result['error'] ?? 'Failed to load predictions';
        error_log('API Error: ' . $error);
    }

} catch (Exception $e) {
    $error = 'System error: ' . $e->getMessage();
    error_log('Exception in 1x2.php: ' . $e->getMessage());
}

// Fallback sample data so the UI never renders empty tables
if (empty($matches)) {
    $matches = [
        [
            'league' => 'England - Premier League',
            'date' => date('d-m-Y', strtotime('+1 day')),
            'team1' => 'Manchester United',
            'team2' => 'Manchester City',
            'half_time' => [
                'bookmaker_odds' => ['1' => '2.35', 'X' => '2.05', '2' => '3.60'],
                'probability' => ['1' => '41.2%', 'X' => '33.5%', '2' => '25.3%'],
                'true_odds' => ['1' => '2.28', 'X' => '2.98', '2' => '3.95']
            ],
            'full_time' => [
                'bookmaker_odds' => ['1' => '2.15', 'X' => '3.40', '2' => '3.10'],
                'probability' => ['1' => '46.4%', 'X' => '25.1%', '2' => '28.5%'],
                'true_odds' => ['1' => '2.05', 'X' => '3.15', '2' => '3.50']
            ],
            'draw_no_bet' => [
                'half_time' => ['1_dnb' => '1.60', '2_dnb' => '2.30'],
                'full_time' => ['1_dnb' => '1.48', '2_dnb' => '2.60']
            ],
            'double_chance' => [
                'half_time' => ['1X' => '1.28', 'X2' => '1.60', '12' => '1.55'],
                'full_time' => ['1X' => '1.25', 'X2' => '1.52', '12' => '1.48']
            ]
        ],
        [
            'league' => 'Spain - La Liga',
            'date' => date('d-m-Y', strtotime('+2 days')),
            'team1' => 'Barcelona',
            'team2' => 'Real Madrid',
            'half_time' => [
                'bookmaker_odds' => ['1' => '2.10', 'X' => '2.15', '2' => '3.80'],
                'probability' => ['1' => '44.0%', 'X' => '35.5%', '2' => '20.5%'],
                'true_odds' => ['1' => '2.05', 'X' => '2.95', '2' => '4.30']
            ],
            'full_time' => [
                'bookmaker_odds' => ['1' => '1.95', 'X' => '3.60', '2' => '3.70'],
                'probability' => ['1' => '48.9%', 'X' => '24.3%', '2' => '26.8%'],
                'true_odds' => ['1' => '1.90', 'X' => '3.25', '2' => '3.60']
            ],
            'draw_no_bet' => [
                'half_time' => ['1_dnb' => '1.55', '2_dnb' => '2.45'],
                'full_time' => ['1_dnb' => '1.42', '2_dnb' => '2.75']
            ],
            'double_chance' => [
                'half_time' => ['1X' => '1.24', 'X2' => '1.65', '12' => '1.47'],
                'full_time' => ['1X' => '1.21', 'X2' => '1.58', '12' => '1.39']
            ]
        ],
        [
            'league' => 'Germany - Bundesliga',
            'date' => date('d-m-Y', strtotime('+3 days')),
            'team1' => 'Bayern Munich',
            'team2' => 'Borussia Dortmund',
            'half_time' => [
                'bookmaker_odds' => ['1' => '2.45', 'X' => '2.00', '2' => '3.30'],
                'probability' => ['1' => '39.5%', 'X' => '36.0%', '2' => '24.5%'],
                'true_odds' => ['1' => '2.30', 'X' => '2.90', '2' => '4.05']
            ],
            'full_time' => [
                'bookmaker_odds' => ['1' => '2.25', 'X' => '3.55', '2' => '3.30'],
                'probability' => ['1' => '44.2%', 'X' => '25.0%', '2' => '30.8%'],
                'true_odds' => ['1' => '2.10', 'X' => '3.20', '2' => '3.60']
            ],
            'draw_no_bet' => [
                'half_time' => ['1_dnb' => '1.65', '2_dnb' => '2.25'],
                'full_time' => ['1_dnb' => '1.52', '2_dnb' => '2.55']
            ],
            'double_chance' => [
                'half_time' => ['1X' => '1.30', 'X2' => '1.55', '12' => '1.62'],
                'full_time' => ['1X' => '1.26', 'X2' => '1.48', '12' => '1.52']
            ]
        ]
    ];

    if (!$error) {
        $error = 'Live API data unavailable – displaying demo data.';
    }
}

include 'includes/app-header.php';
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <div>
                <h4 class="mb-0">1X2 Statistics</h4>
                <small class="text-muted">
                  Your tier: <strong><?php echo strtoupper($userTier); ?></strong> |
                  Showing: <strong><?php echo count($matches); ?> matches</strong>
                </small>
              </div>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
              <i class="bx bx-error me-2"></i><?php echo htmlspecialchars($error); ?>
              <p class="mb-0 mt-2"><small>Showing cached data or placeholder values.</small></p>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- Include Shared Filter Modal Component -->
            <?php include 'includes/statistics-filter-modal.php'; ?>

            <!-- Single Comprehensive Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* [KEEPING ALL EXISTING STYLES FROM ORIGINAL FILE] */
                  .stats-table {
                    border-collapse: collapse !important;
                  }

                  .stats-table th, .stats-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.5rem;
                    border: 1px solid var(--table-border) !important;
                  }

                  .stats-table thead tr:nth-child(3) {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                  }

                  .stats-table th:nth-child(3),
                  .stats-table td:nth-child(3) {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    background-color: inherit;
                    border-right: 1px solid var(--table-border) !important;
                  }

                  .stats-table th:nth-child(4),
                  .stats-table td:nth-child(4) {
                    position: sticky;
                    left: 120px;
                    z-index: 5;
                    background-color: inherit;
                  }

                  .stats-table thead th:nth-child(3),
                  .stats-table thead th:nth-child(4) {
                    z-index: 15;
                  }

                  /* Header row colors */
                  .stats-table thead tr {
                    color: #FFFFFF !important;
                  }

                  /* Alternating row colors */
                  .stats-table tbody tr:nth-child(odd) {
                    background-color: var(--table-row-odd);
                  }
                  .stats-table tbody tr:nth-child(even) {
                    background-color: var(--table-row-even);
                  }
                  .stats-table tbody tr:hover {
                    background-color: var(--table-row-hover);
                    transition: background-color 0.2s ease;
                  }

                  /* Highlight ML predictions */
                  .ml-prediction {
                    font-weight: 600;
                    color: var(--brand-primary);
                  }
                </style>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover stats-table" style="font-size: 0.85rem;">
                    <thead>
                      <tr style="background-color: var(--brand-primary-darker); color: white; font-weight: 600;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="9" class="text-center">HALF TIME</th>
                        <th colspan="9" class="text-center">FULL TIME</th>
                        <th colspan="4" class="text-center">DRAW NO BET</th>
                        <th colspan="6" class="text-center">DOUBLE CHANCE</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary); color: white; font-weight: 600;">
                        <th colspan="3" class="text-center">BOOKMAKER ODDS</th>
                        <th colspan="3" class="text-center">PROBABILITY %</th>
                        <th colspan="3" class="text-center">TRUE ODDS</th>
                        <th colspan="3" class="text-center">BOOKMAKER ODDS</th>
                        <th colspan="3" class="text-center">PROBABILITY %</th>
                        <th colspan="3" class="text-center">TRUE ODDS</th>
                        <th colspan="2" class="text-center">Half Time</th>
                        <th colspan="2" class="text-center">Full Time</th>
                        <th colspan="3" class="text-center">Half Time</th>
                        <th colspan="3" class="text-center">Full Time</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary-light); color: white; font-weight: 500;">
                        <th class="data-col">1</th>
                        <th class="data-col">X</th>
                        <th class="data-col">2</th>
                        <th class="data-col">1</th>
                        <th class="data-col">X</th>
                        <th class="data-col">2</th>
                        <th class="data-col">1</th>
                        <th class="data-col">X</th>
                        <th class="data-col">2</th>
                        <th class="data-col">1</th>
                        <th class="data-col">X</th>
                        <th class="data-col">2</th>
                        <th class="data-col">1</th>
                        <th class="data-col">X</th>
                        <th class="data-col">2</th>
                        <th class="data-col">1</th>
                        <th class="data-col">X</th>
                        <th class="data-col">2</th>
                        <th class="data-col">1 DNB</th>
                        <th class="data-col">2 DNB</th>
                        <th class="data-col">1 DNB</th>
                        <th class="data-col">2 DNB</th>
                        <th class="data-col">1X</th>
                        <th class="data-col">X2</th>
                        <th class="data-col">12</th>
                        <th class="data-col">1X</th>
                        <th class="data-col">X2</th>
                        <th class="data-col">12</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($matches)): ?>
                        <tr>
                          <td colspan="32" class="text-center py-5">
                            <i class="bx bx-info-circle" style="font-size: 3rem; color: #999;"></i>
                            <p class="mt-3 mb-0">No matches found for the selected filters.</p>
                            <small class="text-muted">Try adjusting your filter settings.</small>
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($matches as $match): ?>
                          <tr>
                            <td class="league-col"><?php echo htmlspecialchars($match['league']); ?></td>
                            <td class="date-col"><?php echo htmlspecialchars($match['date']); ?></td>
                            <td class="team-col"><?php echo htmlspecialchars($match['team1']); ?></td>
                            <td class="team-col"><?php echo htmlspecialchars($match['team2']); ?></td>

                            <!-- Half Time Bookmaker Odds -->
                            <td class="data-col"><?php echo htmlspecialchars($match['half_time']['bookmaker_odds']['1'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['half_time']['bookmaker_odds']['X'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['half_time']['bookmaker_odds']['2'] ?? '-'); ?></td>

                            <!-- Half Time Probability (ML PREDICTIONS!) -->
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['half_time']['probability']['1'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['half_time']['probability']['X'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['half_time']['probability']['2'] ?? '-'); ?></td>

                            <!-- Half Time True Odds (CALCULATED FROM ML!) -->
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['half_time']['true_odds']['1'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['half_time']['true_odds']['X'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['half_time']['true_odds']['2'] ?? '-'); ?></td>

                            <!-- Full Time Bookmaker Odds -->
                            <td class="data-col"><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['1'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['X'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['2'] ?? '-'); ?></td>

                            <!-- Full Time Probability (ML PREDICTIONS!) -->
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['full_time']['probability']['1'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['full_time']['probability']['X'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['full_time']['probability']['2'] ?? '-'); ?></td>

                            <!-- Full Time True Odds (CALCULATED FROM ML!) -->
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['full_time']['true_odds']['1'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['full_time']['true_odds']['X'] ?? '-'); ?></td>
                            <td class="data-col ml-prediction"><?php echo htmlspecialchars($match['full_time']['true_odds']['2'] ?? '-'); ?></td>

                            <!-- Draw No Bet (CALCULATED!) -->
                            <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['half_time']['1_dnb'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['half_time']['2_dnb'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['full_time']['1_dnb'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['full_time']['2_dnb'] ?? '-'); ?></td>

                            <!-- Double Chance (CALCULATED!) -->
                            <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['half_time']['1X'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['half_time']['X2'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['half_time']['12'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['full_time']['1X'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['full_time']['X2'] ?? '-'); ?></td>
                            <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['full_time']['12'] ?? '-'); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <?php if (!empty($matches)): ?>
                <div class="mt-3">
                  <small class="text-muted">
                    <i class="bx bx-info-circle"></i>
                    <strong class="ml-prediction">Green values</strong> are ML predictions from
                    <?php
                    if (isset($matches[0]['models_used'])) {
                        echo implode(', ', array_map('ucfirst', $matches[0]['models_used']));
                    } else {
                        echo 'AI models';
                    }
                    ?>
                  </small>
                </div>
                <?php endif; ?>
              </div>
            </div>

          </div>
          <!-- / Content -->

<?php include 'includes/app-footer.php'; ?>
