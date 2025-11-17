<?php
$pageTitle = "Faults Statistics - Super Stats Football";
$pageDescription = "Faults Statistics";
$activePage = "faults";
include 'includes/app-header.php';

// Faults data
$faultsData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'faults_ht' => ['u15' => '33.4%', 'u25' => '57.2%', 'o15' => '51.0%', 'o25' => '86.5%'],
        'faults_ft' => ['u25' => '51.0%', 'u35' => '75.9%', 'u45' => '79.7%', 'o25' => '86.0%', 'o35' => '86.0%', 'o45' => '43.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'faults_ht' => ['u15' => '75.9%', 'u25' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'faults_ft' => ['u25' => '70.6%', 'u35' => '56.0%', 'u45' => '63.3%', 'o25' => '75.9%', 'o35' => '86.0%', 'o45' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'St Truiden',
        'team2' => 'Mouscron',
        'faults_ht' => ['u15' => '75.9%', 'u25' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'faults_ft' => ['u25' => '70.6%', 'u35' => '56.0%', 'u45' => '63.3%', 'o25' => '43.9%', 'o35' => '50.0%', 'o45' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waasland-Beveren',
        'team2' => 'Club Brugge',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waregem',
        'team2' => 'Mechelen',
        'faults_ht' => ['u15' => '70.6%', 'u25' => '69.5%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'faults_ft' => ['u25' => '79.7%', 'u35' => '43.9%', 'u45' => '75.9%', 'o25' => '43.9%', 'o35' => '75.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Anderlecht',
        'team2' => 'Oostende',
        'faults_ht' => ['u15' => '70.2%', 'u25' => '37.5%', 'o15' => '63.3%', 'o25' => '74.1%'],
        'faults_ft' => ['u25' => '63.3%', 'u35' => '70.9%', 'u45' => '48.6%', 'o25' => '70.9%', 'o35' => '48.6%', 'o45' => '50.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Charleroi',
        'team2' => 'Gent',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Eupen',
        'team2' => 'Antwerp',
        'faults_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '33.4%', 'o25' => '57.2%'],
        'faults_ft' => ['u25' => '69.4%', 'u35' => '57.2%', 'u45' => '48.6%', 'o25' => '57.2%', 'o35' => '48.6%', 'o45' => '50.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '26-07-2019',
        'team1' => 'Stuttgart',
        'team2' => 'Hannover',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Dresden',
        'team2' => 'Nurnberg',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'faults_ht' => ['u15' => '74.1%', 'u25' => '48.6%', 'o15' => '74.1%', 'o25' => '48.6%'],
        'faults_ft' => ['u25' => '74.1%', 'u35' => '48.6%', 'u45' => '74.1%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '48.6%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'faults_ht' => ['u15' => '63.3%', 'u25' => '63.3%', 'o15' => '63.3%', 'o25' => '70.9%'],
        'faults_ft' => ['u25' => '78.6%', 'u35' => '86.3%', 'u45' => '94.0%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '94.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'faults_ht' => ['u15' => '51.0%', 'u25' => '86.5%', 'o15' => '32.0%', 'o25' => '86.0%'],
        'faults_ft' => ['u25' => '86.0%', 'u35' => '86.0%', 'u45' => '86.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'faults_ht' => ['u15' => '70.6%', 'u25' => '69.5%', 'o15' => '73.3%', 'o25' => '50.0%'],
        'faults_ft' => ['u25' => '86.0%', 'u35' => '75.9%', 'u45' => '86.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'faults_ht' => ['u15' => '70.2%', 'u25' => '37.5%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'faults_ft' => ['u25' => '79.7%', 'u35' => '43.9%', 'u45' => '50.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'faults_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '43.9%', 'o25' => '35.8%'],
        'faults_ft' => ['u25' => '27.6%', 'u35' => '19.5%', 'u45' => '86.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '75.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Nancy',
        'team2' => 'Orleans',
        'faults_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'faults_ft' => ['u25' => '32.0%', 'u35' => '86.0%', 'u45' => '37.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Niort',
        'team2' => 'Troyes',
        'faults_ht' => ['u15' => '79.7%', 'u25' => '53.8%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'faults_ft' => ['u25' => '32.0%', 'u35' => '86.0%', 'u45' => '37.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Rodez',
        'team2' => 'Auxerre',
        'faults_ht' => ['u15' => '-', 'u25' => '-', 'o15' => '-', 'o25' => '-'],
        'faults_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Sochaux',
        'team2' => 'Caen',
        'faults_ht' => ['u15' => '33.4%', 'u25' => '57.2%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'faults_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '27-07-2019',
        'team1' => 'Le Mans',
        'team2' => 'Lens',
        'faults_ht' => ['u15' => '75.9%', 'u25' => '86.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'faults_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'o25' => '70.2%', 'o35' => '54.1%', 'o45' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '29-07-2019',
        'team1' => 'Lorient',
        'team2' => 'Paris FC',
        'faults_ht' => ['u15' => '48.6%', 'u25' => '50.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'faults_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'o25' => '70.2%', 'o35' => '54.1%', 'o45' => '70.9%']
    ]
];
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <h4 class="mb-0">Faults Statistics</h4>
            </div>

            <!-- Faults Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* Table styling matching other statistics pages */
                  .faults-table {
                    border-collapse: collapse !important;
                    font-size: 0.75rem;
                  }

                  .faults-table th, .faults-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.4rem 0.3rem;
                    border: 1px solid #555555 !important;
                  }

                  /* Sticky header row for vertical scrolling - only 3rd row */
                  .faults-table thead tr:nth-child(3) {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                  }

                  /* Sticky columns for horizontal scrolling - only team columns (3 & 4) */
                  .faults-table th:nth-child(3),
                  .faults-table td:nth-child(3) {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    background-color: inherit;
                    border-right: 1px solid #555555 !important;
                  }

                  .faults-table th:nth-child(4),
                  .faults-table td:nth-child(4) {
                    position: sticky;
                    left: 120px;
                    z-index: 5;
                    background-color: inherit;
                  }

                  /* Higher z-index for sticky header cells that are also in sticky columns */
                  .faults-table thead th:nth-child(3),
                  .faults-table thead th:nth-child(4) {
                    z-index: 15;
                  }

                  /* Exterior borders 2px */
                  .faults-table thead tr:first-child th {
                    border-top-width: 2px !important;
                  }
                  .faults-table tbody tr:last-child td {
                    border-bottom-width: 2px !important;
                  }
                  .faults-table th:first-child,
                  .faults-table td:first-child {
                    border-left-width: 2px !important;
                  }
                  .faults-table th:last-child,
                  .faults-table td:last-child {
                    border-right-width: 2px !important;
                  }

                  /* Team columns - Column 3 and 4 */
                  .faults-table th:nth-child(3),
                  .faults-table td:nth-child(3) {
                    border-left-width: 2px !important;
                  }
                  .faults-table th:nth-child(4),
                  .faults-table td:nth-child(4) {
                    border-right-width: 2px !important;
                  }

                  /* Major section dividers */
                  .faults-table td:nth-child(5),
                  .faults-table thead tr th:nth-child(5) {
                    border-left-width: 2px !important;
                  }

                  /* Treat columns 7-8 (O 1.5 to O 2.5) as one big section with 2px borders */
                  .faults-table td:nth-child(7),
                  .faults-table thead tr th:nth-child(7) {
                    border-left-width: 2px !important;
                  }

                  .faults-table td:nth-child(8),
                  .faults-table thead tr th:nth-child(8) {
                    border-right-width: 2px !important;
                  }

                  /* Treat columns 12-14 (O 2.5 to O 4.5) as one big section with 2px borders */
                  .faults-table td:nth-child(12),
                  .faults-table thead tr th:nth-child(12) {
                    border-left-width: 2px !important;
                  }

                  .faults-table td:nth-child(14),
                  .faults-table thead tr th:nth-child(14) {
                    border-right-width: 2px !important;
                  }

                  .league-col {
                    text-align: left !important;
                    min-width: 160px;
                  }

                  .date-col {
                    min-width: 90px;
                  }

                  .team-col {
                    min-width: 100px;
                  }

                  .data-col-sm {
                    min-width: 45px;
                    font-size: 0.7rem;
                  }

                  /* Alternating row colors - Green theme */
                  .faults-table tbody tr:nth-child(odd) {
                    background-color: #E8F5E9;
                  }
                  .faults-table tbody tr:nth-child(even) {
                    background-color: #F1F8F4;
                  }
                  .faults-table tbody tr:hover {
                    background-color: #C8E6D0;
                    transition: background-color 0.2s ease;
                  }

                  /* Background colors for sticky cells to match row colors */
                  .faults-table tbody tr:nth-child(odd) td:nth-child(3),
                  .faults-table tbody tr:nth-child(odd) td:nth-child(4) {
                    background-color: #E8F5E9;
                  }

                  .faults-table tbody tr:nth-child(even) td:nth-child(3),
                  .faults-table tbody tr:nth-child(even) td:nth-child(4) {
                    background-color: #F1F8F4;
                  }

                  .faults-table tbody tr:hover td:nth-child(3),
                  .faults-table tbody tr:hover td:nth-child(4) {
                    background-color: #C8E6D0;
                  }

                  /* Ensure sticky header cells maintain their background colors */
                  .faults-table thead tr:nth-child(1) th:nth-child(3),
                  .faults-table thead tr:nth-child(1) th:nth-child(4) {
                    background-color: #106147;
                  }

                  .faults-table thead tr:nth-child(2) th:nth-child(3),
                  .faults-table thead tr:nth-child(2) th:nth-child(4) {
                    background-color: #106147;
                  }

                  .faults-table thead tr:nth-child(3) th:nth-child(3),
                  .faults-table thead tr:nth-child(3) th:nth-child(4) {
                    background-color: #1a8a6b;
                  }

                  /* Force white text for all header rows */
                  .faults-table thead th {
                    color: #FFFFFF !important;
                  }
                </style>

                <div class="table-responsive">
                  <table class="table table-sm faults-table">
                    <thead>
                      <tr style="background-color: #106147; color: white; font-weight: 700;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="10" class="text-center">FAULTS</th>
                      </tr>
                      <tr style="background-color: #106147; color: white; font-weight: 600;">
                        <th colspan="4" class="text-center">Half Time</th>
                        <th colspan="6" class="text-center">Full Time</th>
                      </tr>
                      <tr style="background-color: #1a8a6b; color: white; font-weight: 500;">
                        <th class="data-col-sm">U 1.5</th>
                        <th class="data-col-sm">U 2.5</th>
                        <th class="data-col-sm">O 1.5</th>
                        <th class="data-col-sm">O 2.5</th>
                        <th class="data-col-sm">U 2.5</th>
                        <th class="data-col-sm">U 3.5</th>
                        <th class="data-col-sm">U 4.5</th>
                        <th class="data-col-sm">O 2.5</th>
                        <th class="data-col-sm">O 3.5</th>
                        <th class="data-col-sm">O 4.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($faultsData as $match): ?>
                        <tr>
                          <td class="league-col"><?php echo htmlspecialchars($match['league']); ?></td>
                          <td class="date-col"><?php echo htmlspecialchars($match['date']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team1']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team2']); ?></td>

                          <!-- Faults Half Time -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ht']['u15']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ht']['u25']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ht']['o15']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ht']['o25']); ?></td>

                          <!-- Faults Full Time -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ft']['u25']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ft']['u35']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ft']['u45']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ft']['o25']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ft']['o35']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['faults_ft']['o45']); ?></td>
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
