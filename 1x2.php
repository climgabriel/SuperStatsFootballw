<?php
$pageTitle = "1X2 Statistics - Super Stats Football";
$pageDescription = "1X2 Match Predictions and Statistics";
$activePage = "1x2";
include 'includes/app-header.php';
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4">1X2 Statistics</h4>

            <?php
            // Load JSON data
            $jsonData = file_get_contents('1x2_data.json');
            $matches = json_decode($jsonData, true);
            ?>

            <!-- Single Comprehensive Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  .stats-table th, .stats-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.5rem;
                  }
                  .stats-table th.league-col { min-width: 180px; }
                  .stats-table td.league-col { text-align: left; }
                  .stats-table th.date-col { min-width: 90px; }
                  .stats-table th.team-col { min-width: 120px; }
                  .stats-table td.team-col { text-align: left; font-weight: 600; }
                  .stats-table th.data-col { min-width: 60px; }
                  .stats-table td.data-col { text-align: center; }
                </style>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover stats-table" style="font-size: 0.85rem;">
                    <thead>
                      <tr style="background-color: #005440; color: white; font-weight: 600;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="9" class="text-center">HALF TIME</th>
                        <th colspan="9" class="text-center">FULL TIME</th>
                        <th colspan="4" class="text-center">DRAW NO BET</th>
                        <th colspan="6" class="text-center">DOUBLE CHANCE</th>
                      </tr>
                      <tr style="background-color: #106147; color: white; font-weight: 600;">
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
                      <tr style="background-color: #1a8a6b; color: white; font-weight: 500;">
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

                          <!-- Half Time Probability -->
                          <td class="data-col"><?php echo htmlspecialchars($match['half_time']['probability']['1'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['half_time']['probability']['X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['half_time']['probability']['2'] ?? '-'); ?></td>

                          <!-- Half Time True Odds -->
                          <td class="data-col"><?php echo htmlspecialchars($match['half_time']['true_odds']['1'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['half_time']['true_odds']['X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['half_time']['true_odds']['2'] ?? '-'); ?></td>

                          <!-- Full Time Bookmaker Odds -->
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['1'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['2'] ?? '-'); ?></td>

                          <!-- Full Time Probability -->
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['probability']['1'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['probability']['X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['probability']['2'] ?? '-'); ?></td>

                          <!-- Full Time True Odds -->
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['true_odds']['1'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['true_odds']['X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['full_time']['true_odds']['2'] ?? '-'); ?></td>

                          <!-- Draw No Bet -->
                          <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['half_time']['1_dnb'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['half_time']['2_dnb'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['full_time']['1_dnb'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['draw_no_bet']['full_time']['2_dnb'] ?? '-'); ?></td>

                          <!-- Double Chance -->
                          <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['half_time']['1X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['half_time']['X2'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['half_time']['12'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['full_time']['1X'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['full_time']['X2'] ?? '-'); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['double_chance']['full_time']['12'] ?? '-'); ?></td>
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
