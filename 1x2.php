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
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <h4 class="mb-0">1X2 Statistics</h4>
              <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#filterModal" style="background-color: #106147; border-color: #106147;">
                <i class="bx bx-filter me-1"></i> Filter
              </button>
            </div>

            <?php
            // Load JSON data
            $jsonData = file_get_contents('1x2_data.json');
            $matches = json_decode($jsonData, true);
            ?>

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
                            <input class="form-check-input filter-league" type="checkbox" value="league1" id="league1">
                            <label class="form-check-label" for="league1">League 1</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-league" type="checkbox" value="league2" id="league2">
                            <label class="form-check-label" for="league2">League 2</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-league" type="checkbox" value="league3" id="league3">
                            <label class="form-check-label" for="league3">League 3</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-league" type="checkbox" value="league4" id="league4">
                            <label class="form-check-label" for="league4">League 4</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <!-- Season Filter -->
                    <div class="mb-4">
                      <label class="form-label fw-bold d-flex align-items-center">
                        <i class="bx bx-calendar me-2" style="color: #106147;"></i>Season
                      </label>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-season" type="checkbox" value="current" id="seasonCurrent">
                            <label class="form-check-label" for="seasonCurrent">Current Season</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-season" type="checkbox" value="last1" id="seasonLast1">
                            <label class="form-check-label" for="seasonLast1">Last Season 1</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-season" type="checkbox" value="last2" id="seasonLast2">
                            <label class="form-check-label" for="seasonLast2">Last Season 2</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-season" type="checkbox" value="last3" id="seasonLast3">
                            <label class="form-check-label" for="seasonLast3">Last Season 3</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-season" type="checkbox" value="last4" id="seasonLast4">
                            <label class="form-check-label" for="seasonLast4">Last Season 4</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <!-- Analytics Model Filter -->
                    <div class="mb-3">
                      <label class="form-label fw-bold d-flex align-items-center">
                        <i class="bx bx-bar-chart-alt-2 me-2" style="color: #106147;"></i>Analytics Model
                      </label>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-model" type="checkbox" value="model1" id="model1">
                            <label class="form-check-label" for="model1">Model 1</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-model" type="checkbox" value="model2" id="model2">
                            <label class="form-check-label" for="model2">Model 2</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-model" type="checkbox" value="model3" id="model3">
                            <label class="form-check-label" for="model3">Model 3</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-model" type="checkbox" value="model4" id="model4">
                            <label class="form-check-label" for="model4">Model 4</label>
                          </div>
                          <div class="form-check mb-2">
                            <input class="form-check-input filter-model" type="checkbox" value="model5" id="model5">
                            <label class="form-check-label" for="model5">Model 5</label>
                          </div>
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

            <!-- Single Comprehensive Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* Updated: 2025-01-13 - Simplified borders */
                  .stats-table {
                    border-collapse: collapse !important;
                  }

                  .stats-table th, .stats-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.5rem;
                    border: 1px solid #555555 !important;
                  }

                  /* Exterior borders 2px */
                  .stats-table thead tr:first-child th {
                    border-top-width: 2px !important;
                  }
                  .stats-table tbody tr:last-child td {
                    border-bottom-width: 2px !important;
                  }
                  .stats-table th:first-child,
                  .stats-table td:first-child {
                    border-left-width: 2px !important;
                  }
                  .stats-table th:last-child,
                  .stats-table td:last-child {
                    border-right-width: 2px !important;
                  }

                  /* Team columns borders - Column 3 (1) left side and Column 4 (2) right side */
                  .stats-table th:nth-child(3),
                  .stats-table td:nth-child(3) {
                    border-left-width: 2px !important;
                  }
                  .stats-table th:nth-child(4),
                  .stats-table td:nth-child(4) {
                    border-right-width: 2px !important;
                  }

                  /* Half Time Probability section - 2px left/right borders */
                  .stats-table td:nth-child(8) {
                    border-left-width: 2px !important;
                  }
                  .stats-table td:nth-child(10) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(8),
                  .stats-table thead tr:nth-child(2) th:nth-child(8) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(10),
                  .stats-table thead tr:nth-child(2) th:nth-child(10) {
                    border-right-width: 2px !important;
                  }
                  /* For header row 3 (no rowspan, different child numbering) */
                  .stats-table thead tr:nth-child(3) th:nth-child(4) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(6) {
                    border-right-width: 2px !important;
                  }

                  /* Half Time True Odds section - 2px left/right borders */
                  .stats-table td:nth-child(11) {
                    border-left-width: 2px !important;
                  }
                  .stats-table td:nth-child(13) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(11),
                  .stats-table thead tr:nth-child(2) th:nth-child(11) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(13),
                  .stats-table thead tr:nth-child(2) th:nth-child(13) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(7) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(9) {
                    border-right-width: 2px !important;
                  }

                  /* Full Time Bookmaker Odds section - 2px left/right borders */
                  .stats-table td:nth-child(14) {
                    border-left-width: 2px !important;
                  }
                  .stats-table td:nth-child(16) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(14),
                  .stats-table thead tr:nth-child(2) th:nth-child(14) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(16),
                  .stats-table thead tr:nth-child(2) th:nth-child(16) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(10) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(12) {
                    border-right-width: 2px !important;
                  }

                  /* Full Time Probability section - 2px left/right borders */
                  .stats-table td:nth-child(17) {
                    border-left-width: 2px !important;
                  }
                  .stats-table td:nth-child(19) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(17),
                  .stats-table thead tr:nth-child(2) th:nth-child(17) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(19),
                  .stats-table thead tr:nth-child(2) th:nth-child(19) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(13) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(15) {
                    border-right-width: 2px !important;
                  }

                  /* Draw No Bet Half Time section - 2px left/right borders */
                  .stats-table td:nth-child(23) {
                    border-left-width: 2px !important;
                  }
                  .stats-table td:nth-child(24) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(23),
                  .stats-table thead tr:nth-child(2) th:nth-child(23) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(24),
                  .stats-table thead tr:nth-child(2) th:nth-child(24) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(19) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(20) {
                    border-right-width: 2px !important;
                  }

                  /* Double Chance Half Time section - 2px left/right borders */
                  .stats-table td:nth-child(27) {
                    border-left-width: 2px !important;
                  }
                  .stats-table td:nth-child(29) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(27),
                  .stats-table thead tr:nth-child(2) th:nth-child(27) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(1) th:nth-child(29),
                  .stats-table thead tr:nth-child(2) th:nth-child(29) {
                    border-right-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(23) {
                    border-left-width: 2px !important;
                  }
                  .stats-table thead tr:nth-child(3) th:nth-child(25) {
                    border-right-width: 2px !important;
                  }

                  .stats-table th.league-col { min-width: 180px; }
                  .stats-table td.league-col { text-align: center; }
                  .stats-table th.date-col { min-width: 90px; }
                  .stats-table th.team-col { min-width: 120px; }
                  .stats-table td.team-col { text-align: center; font-weight: 600; }
                  .stats-table th.data-col { min-width: 60px; }
                  .stats-table td.data-col { text-align: center; }

                  /* Header row colors */
                  .stats-table thead tr {
                    color: #FFFFFF !important;
                  }
                  .stats-table thead th {
                    color: #FFFFFF !important;
                  }

                  /* Alternating row colors - Green theme */
                  .stats-table tbody tr:nth-child(odd) {
                    background-color: #E8F5E9;
                  }
                  .stats-table tbody tr:nth-child(even) {
                    background-color: #F1F8F4;
                  }
                  .stats-table tbody tr:hover {
                    background-color: #C8E6D0;
                    transition: background-color 0.2s ease;
                  }
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

<script src="assets/js/1x2-filter.js"></script>
<?php include 'includes/app-footer.php'; ?>
