<?php
require_once 'config.php';

$pageTitle = "Offsides Statistics - Super Stats Football";
$pageDescription = "Offside Statistics and Analysis";
$activePage = "offsides";

// Include API helper and authentication
require_once 'includes/api-helper.php';
require_once 'includes/auth-middleware.php';

// Try demo authentication for seamless UX
tryDemoAuth();

// Get filter parameters from URL
$leagueFilter = isset($_GET['leagues']) ? explode(',', $_GET['leagues']) : null;
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : null;
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : null;

// Calculate days ahead from date range
$daysAhead = 7; // Default
if ($dateTo) {
    $daysAhead = max(1, ceil((strtotime($dateTo) - time()) / 86400));
}

// Fetch data from backend API
$apiResponse = getOffsidesStatistics($daysAhead, $leagueFilter ? $leagueFilter[0] : null, 50, 0);

// Process API response
$offsidesData = [];
if ($apiResponse['success'] && isset($apiResponse['data']['fixtures'])) {
    // Transform backend data to match our table format
    foreach ($apiResponse['data']['fixtures'] as $fixture) {
        $offsidesData[] = [
            'league' => $fixture['league_name'] ?? '-',
            'date' => isset($fixture['fixture_date']) ? date('d-m-Y', strtotime($fixture['fixture_date'])) : '-',
            'team1' => $fixture['home_team'] ?? '-',
            'team2' => $fixture['away_team'] ?? '-',
            'offsides_ht' => [
                'u15' => isset($fixture['offsides_ht_u15']) ? round($fixture['offsides_ht_u15'] * 100, 1) . '%' : '-',
                'u25' => isset($fixture['offsides_ht_u25']) ? round($fixture['offsides_ht_u25'] * 100, 1) . '%' : '-',
                'o15' => isset($fixture['offsides_ht_o15']) ? round($fixture['offsides_ht_o15'] * 100, 1) . '%' : '-',
                'o25' => isset($fixture['offsides_ht_o25']) ? round($fixture['offsides_ht_o25'] * 100, 1) . '%' : '-'
            ],
            'offsides_ft' => [
                'u25' => isset($fixture['offsides_ft_u25']) ? round($fixture['offsides_ft_u25'] * 100, 1) . '%' : '-',
                'u35' => isset($fixture['offsides_ft_u35']) ? round($fixture['offsides_ft_u35'] * 100, 1) . '%' : '-',
                'u45' => isset($fixture['offsides_ft_u45']) ? round($fixture['offsides_ft_u45'] * 100, 1) . '%' : '-',
                'o25' => isset($fixture['offsides_ft_o25']) ? round($fixture['offsides_ft_o25'] * 100, 1) . '%' : '-',
                'o35' => isset($fixture['offsides_ft_o35']) ? round($fixture['offsides_ft_o35'] * 100, 1) . '%' : '-',
                'o45' => isset($fixture['offsides_ft_o45']) ? round($fixture['offsides_ft_o45'] * 100, 1) . '%' : '-'
            ]
        ];
    }
}

// Fallback to sample data if API fails or returns no data
if (empty($offsidesData)) {
    $offsidesData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'offsides_ht' => ['u15' => '33.4%', 'u25' => '57.2%', 'o15' => '51.0%', 'o25' => '86.5%'],
        'offsides_ft' => ['u25' => '51.0%', 'u35' => '75.9%', 'u45' => '79.7%', 'o25' => '86.0%', 'o35' => '86.0%', 'o45' => '43.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'offsides_ht' => ['u15' => '75.9%', 'u25' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'offsides_ft' => ['u25' => '70.6%', 'u35' => '56.0%', 'u45' => '63.3%', 'o25' => '75.9%', 'o35' => '86.0%', 'o45' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'St Truiden',
        'team2' => 'Mouscron',
        'offsides_ht' => ['u15' => '75.9%', 'u25' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'offsides_ft' => ['u25' => '70.6%', 'u35' => '56.0%', 'u45' => '63.3%', 'o25' => '43.9%', 'o35' => '50.0%', 'o45' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waasland-Beveren',
        'team2' => 'Club Brugge',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waregem',
        'team2' => 'Mechelen',
        'offsides_ht' => ['u15' => '70.6%', 'u25' => '69.5%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'offsides_ft' => ['u25' => '79.7%', 'u35' => '43.9%', 'u45' => '75.9%', 'o25' => '43.9%', 'o35' => '75.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Anderlecht',
        'team2' => 'Oostende',
        'offsides_ht' => ['u15' => '70.2%', 'u25' => '37.5%', 'o15' => '63.3%', 'o25' => '74.1%'],
        'offsides_ft' => ['u25' => '63.3%', 'u35' => '70.9%', 'u45' => '48.6%', 'o25' => '70.9%', 'o35' => '48.6%', 'o45' => '50.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Charleroi',
        'team2' => 'Gent',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Eupen',
        'team2' => 'Antwerp',
        'offsides_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '33.4%', 'o25' => '57.2%'],
        'offsides_ft' => ['u25' => '69.4%', 'u35' => '57.2%', 'u45' => '48.6%', 'o25' => '57.2%', 'o35' => '48.6%', 'o45' => '50.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '26-07-2019',
        'team1' => 'Stuttgart',
        'team2' => 'Hannover',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Dresden',
        'team2' => 'Nurnberg',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'offsides_ht' => ['u15' => '74.1%', 'u25' => '48.6%', 'o15' => '74.1%', 'o25' => '48.6%'],
        'offsides_ft' => ['u25' => '74.1%', 'u35' => '48.6%', 'u45' => '74.1%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '48.6%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'offsides_ht' => ['u15' => '63.3%', 'u25' => '63.3%', 'o15' => '63.3%', 'o25' => '70.9%'],
        'offsides_ft' => ['u25' => '78.6%', 'u35' => '86.3%', 'u45' => '94.0%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '94.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'offsides_ht' => ['u15' => '51.0%', 'u25' => '86.5%', 'o15' => '32.0%', 'o25' => '86.0%'],
        'offsides_ft' => ['u25' => '86.0%', 'u35' => '86.0%', 'u45' => '86.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'offsides_ht' => ['u15' => '70.6%', 'u25' => '69.5%', 'o15' => '73.3%', 'o25' => '50.0%'],
        'offsides_ft' => ['u25' => '86.0%', 'u35' => '75.9%', 'u45' => '86.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'offsides_ht' => ['u15' => '70.2%', 'u25' => '37.5%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'offsides_ft' => ['u25' => '79.7%', 'u35' => '43.9%', 'u45' => '50.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'offsides_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '43.9%', 'o25' => '35.8%'],
        'offsides_ft' => ['u25' => '27.6%', 'u35' => '19.5%', 'u45' => '86.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '75.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Nancy',
        'team2' => 'Orleans',
        'offsides_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'offsides_ft' => ['u25' => '32.0%', 'u35' => '86.0%', 'u45' => '37.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Niort',
        'team2' => 'Troyes',
        'offsides_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'offsides_ft' => ['u25' => '32.0%', 'u35' => '86.0%', 'u45' => '37.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Rodez',
        'team2' => 'Auxerre',
        'offsides_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'offsides_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Sochaux',
        'team2' => 'Caen',
        'offsides_ht' => ['u15' => '33.4%', 'u25' => '57.2%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'offsides_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '27-07-2019',
        'team1' => 'Le Mans',
        'team2' => 'Lens',
        'offsides_ht' => ['u15' => '75.9%', 'u25' => '86.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'offsides_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'o25' => '70.2%', 'o35' => '54.1%', 'o45' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '29-07-2019',
        'team1' => 'Lorient',
        'team2' => 'Paris FC',
        'offsides_ht' => ['u15' => '48.6%', 'u25' => '50.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'offsides_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'o25' => '70.2%', 'o35' => '54.1%', 'o45' => '70.9%']
    ]
];
}

include 'includes/app-header.php';
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <h4 class="mb-0">
                <span class="text-muted fw-light">Statistics /</span> Offsides Analysis
              </h4>
              <?php include 'includes/statistics-filter-modal.php'; ?>
            </div>

            <!-- Offsides Statistics Table -->
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <style>
                    .offsides-table {
                      border: 2px solid #dee2e6;
                      font-size: 0.85rem;
                    }
                    .offsides-table thead th {
                      background-color: #106147;
                      color: white;
                      font-weight: 600;
                      text-align: center;
                      vertical-align: middle;
                      border: 1px solid #dee2e6;
                      padding: 0.75rem 0.5rem;
                      white-space: nowrap;
                    }
                    .offsides-table thead tr:nth-child(2) th {
                      background-color: #1a8a6b;
                    }
                    .offsides-table tbody td {
                      text-align: center;
                      vertical-align: middle;
                      border: 1px solid #dee2e6;
                      padding: 0.5rem 0.4rem;
                    }
                    .offsides-table tbody td:first-child,
                    .offsides-table tbody td:nth-child(2),
                    .offsides-table tbody td:nth-child(3),
                    .offsides-table tbody td:nth-child(4) {
                      text-align: left;
                      font-weight: 500;
                    }
                    .offsides-table tbody tr:hover {
                      background-color: rgba(16, 97, 71, 0.05);
                    }
                    /* Section dividers */
                    .offsides-table thead th:nth-child(4),
                    .offsides-table tbody td:nth-child(4) {
                      border-right: 2px solid #dee2e6;
                    }
                    .offsides-table thead th:nth-child(8),
                    .offsides-table tbody td:nth-child(8) {
                      border-right: 2px solid #dee2e6;
                    }
                  </style>
                  <table class="table table-bordered offsides-table">
                    <thead>
                      <tr>
                        <th rowspan="2">League</th>
                        <th rowspan="2">Date</th>
                        <th rowspan="2">Team 1</th>
                        <th rowspan="2">Team 2</th>
                        <th colspan="4">Offsides Half Time</th>
                        <th colspan="6">Offsides Full Time</th>
                      </tr>
                      <tr>
                        <th>U 1.5</th>
                        <th>U 2.5</th>
                        <th>O 1.5</th>
                        <th>O 2.5</th>
                        <th>U 2.5</th>
                        <th>U 3.5</th>
                        <th>U 4.5</th>
                        <th>O 2.5</th>
                        <th>O 3.5</th>
                        <th>O 4.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($offsidesData as $match): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($match['league']); ?></td>
                        <td><?php echo htmlspecialchars($match['date']); ?></td>
                        <td><?php echo htmlspecialchars($match['team1']); ?></td>
                        <td><?php echo htmlspecialchars($match['team2']); ?></td>
                        <!-- Half Time -->
                        <td><?php echo htmlspecialchars($match['offsides_ht']['u15']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ht']['u25']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ht']['o15']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ht']['o25']); ?></td>
                        <!-- Full Time -->
                        <td><?php echo htmlspecialchars($match['offsides_ft']['u25']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ft']['u35']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ft']['u45']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ft']['o25']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ft']['o35']); ?></td>
                        <td><?php echo htmlspecialchars($match['offsides_ft']['o45']); ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          <!-- / Content -->
<?php include 'includes/app-footer.php'; ?>
