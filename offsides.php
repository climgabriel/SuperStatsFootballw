<?php
$pageTitle = "Offsides Statistics - Super Stats Football";
$pageDescription = "Offside Statistics and Analysis";
$activePage = "offsides";
include 'includes/app-header.php';

// Offsides statistics data
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
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <h4 class="mb-0">
                <span class="text-muted fw-light">Statistics /</span> Offsides Analysis
              </h4>
              <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#filterModal" style="background-color: #106147; border-color: #106147;">
                <i class="bx bx-filter me-1"></i> Filter
              </button>
            </div>

            <!-- Filter Modal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <div class="modal-header" style="background-color: #106147; color: white;">
                    <h5 class="modal-title" id="filterModalLabel">
                      <i class="bx bx-filter me-2"></i>Filter Options
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <!-- Leagues Filter -->
                    <div class="mb-4">
                      <label class="form-label fw-bold d-flex align-items-center">
                        <i class="bx bx-trophy me-2" style="color: #106147;"></i>Leagues
                      </label>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-league" type="checkbox" value="Belgium - Jupiler League" id="league1">
                            <label class="form-check-label" for="league1">Belgium - Jupiler League</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-league" type="checkbox" value="Germany - Bundesliga 2" id="league2">
                            <label class="form-check-label" for="league2">Germany - Bundesliga 2</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-league" type="checkbox" value="French - Ligue 2" id="league3">
                            <label class="form-check-label" for="league3">French - Ligue 2</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <!-- Date Range Filter -->
                    <div class="mb-3">
                      <label class="form-label fw-bold d-flex align-items-center">
                        <i class="bx bx-calendar me-2" style="color: #106147;"></i>Date Range
                      </label>
                      <div class="row">
                        <div class="col-md-6">
                          <label class="form-label">From</label>
                          <input type="date" class="form-control" id="dateFrom">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">To</label>
                          <input type="date" class="form-control" id="dateTo">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="clearFilters">
                      <i class="bx bx-x me-1"></i>Clear All
                    </button>
                    <button type="button" class="btn btn-primary" id="applyFilters" style="background-color: #106147; border-color: #106147;">
                      <i class="bx bx-check me-1"></i>Apply Filters
                    </button>
                  </div>
                </div>
              </div>
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
