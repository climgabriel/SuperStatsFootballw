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

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-3" id="statsTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="full-time-tab" data-bs-toggle="tab" data-bs-target="#full-time" type="button" role="tab">
                  Full Time Statistics
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="half-time-tab" data-bs-toggle="tab" data-bs-target="#half-time" type="button" role="tab">
                  Half Time Statistics
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="draw-no-bet-tab" data-bs-toggle="tab" data-bs-target="#draw-no-bet" type="button" role="tab">
                  Draw No Bet
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="double-chance-tab" data-bs-toggle="tab" data-bs-target="#double-chance" type="button" role="tab">
                  Double Chance
                </button>
              </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="statsTabContent">

              <!-- Full Time Tab -->
              <div class="tab-pane fade show active" id="full-time" role="tabpanel">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Full Time 1X2 Statistics</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead style="background-color: #005440; color: white;">
                          <tr>
                            <th>League</th>
                            <th>Date</th>
                            <th>Match</th>
                            <th colspan="3" class="text-center">Bookmaker Odds</th>
                            <th colspan="3" class="text-center">Probability %</th>
                            <th colspan="3" class="text-center">True Odds</th>
                          </tr>
                          <tr style="background-color: #106147; color: white;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>1</th>
                            <th>X</th>
                            <th>2</th>
                            <th>1</th>
                            <th>X</th>
                            <th>2</th>
                            <th>1</th>
                            <th>X</th>
                            <th>2</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($matches as $match): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($match['league']); ?></td>
                              <td><?php echo htmlspecialchars($match['date']); ?></td>
                              <td><strong><?php echo htmlspecialchars($match['team1']) . ' vs ' . htmlspecialchars($match['team2']); ?></strong></td>
                              <td><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['1'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['bookmaker_odds']['2'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['probability']['1'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['probability']['X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['probability']['2'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['true_odds']['1'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['true_odds']['X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['full_time']['true_odds']['2'] ?? '-'); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Half Time Tab -->
              <div class="tab-pane fade" id="half-time" role="tabpanel">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Half Time 1X2 Statistics</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead style="background-color: #005440; color: white;">
                          <tr>
                            <th>League</th>
                            <th>Date</th>
                            <th>Match</th>
                            <th colspan="3" class="text-center">Bookmaker Odds</th>
                            <th colspan="3" class="text-center">Probability %</th>
                            <th colspan="3" class="text-center">True Odds</th>
                          </tr>
                          <tr style="background-color: #106147; color: white;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>1</th>
                            <th>X</th>
                            <th>2</th>
                            <th>1</th>
                            <th>X</th>
                            <th>2</th>
                            <th>1</th>
                            <th>X</th>
                            <th>2</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($matches as $match): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($match['league']); ?></td>
                              <td><?php echo htmlspecialchars($match['date']); ?></td>
                              <td><strong><?php echo htmlspecialchars($match['team1']) . ' vs ' . htmlspecialchars($match['team2']); ?></strong></td>
                              <td><?php echo htmlspecialchars($match['half_time']['bookmaker_odds']['1'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['bookmaker_odds']['X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['bookmaker_odds']['2'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['probability']['1'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['probability']['X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['probability']['2'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['true_odds']['1'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['true_odds']['X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['half_time']['true_odds']['2'] ?? '-'); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Draw No Bet Tab -->
              <div class="tab-pane fade" id="draw-no-bet" role="tabpanel">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Draw No Bet Statistics</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead style="background-color: #005440; color: white;">
                          <tr>
                            <th>League</th>
                            <th>Date</th>
                            <th>Match</th>
                            <th colspan="2" class="text-center">Half Time</th>
                            <th colspan="2" class="text-center">Full Time</th>
                          </tr>
                          <tr style="background-color: #106147; color: white;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>1 DNB</th>
                            <th>2 DNB</th>
                            <th>1 DNB</th>
                            <th>2 DNB</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($matches as $match): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($match['league']); ?></td>
                              <td><?php echo htmlspecialchars($match['date']); ?></td>
                              <td><strong><?php echo htmlspecialchars($match['team1']) . ' vs ' . htmlspecialchars($match['team2']); ?></strong></td>
                              <td><?php echo htmlspecialchars($match['draw_no_bet']['half_time']['1_dnb'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['draw_no_bet']['half_time']['2_dnb'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['draw_no_bet']['full_time']['1_dnb'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['draw_no_bet']['full_time']['2_dnb'] ?? '-'); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Double Chance Tab -->
              <div class="tab-pane fade" id="double-chance" role="tabpanel">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Double Chance Statistics</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <thead style="background-color: #005440; color: white;">
                          <tr>
                            <th>League</th>
                            <th>Date</th>
                            <th>Match</th>
                            <th colspan="3" class="text-center">Half Time</th>
                            <th colspan="3" class="text-center">Full Time</th>
                          </tr>
                          <tr style="background-color: #106147; color: white;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>1X</th>
                            <th>X2</th>
                            <th>12</th>
                            <th>1X</th>
                            <th>X2</th>
                            <th>12</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($matches as $match): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($match['league']); ?></td>
                              <td><?php echo htmlspecialchars($match['date']); ?></td>
                              <td><strong><?php echo htmlspecialchars($match['team1']) . ' vs ' . htmlspecialchars($match['team2']); ?></strong></td>
                              <td><?php echo htmlspecialchars($match['double_chance']['half_time']['1X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['double_chance']['half_time']['X2'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['double_chance']['half_time']['12'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['double_chance']['full_time']['1X'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['double_chance']['full_time']['X2'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($match['double_chance']['full_time']['12'] ?? '-'); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </div>
          <!-- / Content -->

<?php include 'includes/app-footer.php'; ?>
