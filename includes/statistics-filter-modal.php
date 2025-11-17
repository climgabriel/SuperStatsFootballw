<?php
/**
 * Shared Statistics Filter Modal Component
 *
 * This modal is used across all statistics pages for filtering by league and date range
 * Includes user-based league selection limits
 */

require_once 'UserManager.php';

// Get user limits
$maxLeagues = UserManager::getMaxLeagues(UserManager::getUserRole());
$userRole = UserManager::getUserRole();
?>

<!-- Filter Button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal" style="background-color: #106147; border-color: #106147;">
  <i class="bx bx-filter me-1"></i> Filter
</button>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true"
     data-max-leagues="<?php echo $maxLeagues; ?>"
     data-user-role="<?php echo $userRole; ?>">
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
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="French - Ligue 2" id="league3">
                <label class="form-check-label" for="league3">French - Ligue 2</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="England - Premier League" id="league4">
                <label class="form-check-label" for="league4">England - Premier League</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Spain - La Liga" id="league5">
                <label class="form-check-label" for="league5">Spain - La Liga</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Italy - Serie A" id="league6">
                <label class="form-check-label" for="league6">Italy - Serie A</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Netherlands - Eredivisie" id="league7">
                <label class="form-check-label" for="league7">Netherlands - Eredivisie</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Portugal - Primeira Liga" id="league8">
                <label class="form-check-label" for="league8">Portugal - Primeira Liga</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Turkey - Super Lig" id="league9">
                <label class="form-check-label" for="league9">Turkey - Super Lig</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Russia - Premier League" id="league10">
                <label class="form-check-label" for="league10">Russia - Premier League</label>
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

        <!-- User Plan Info -->
        <?php if ($userRole === 'user'): ?>
        <div class="alert alert-info mt-3 mb-0" style="background-color: #e8f5f2; border-color: #106147;">
          <i class="bx bx-info-circle me-2"></i>
          <small>You can select up to <strong><?php echo $maxLeagues; ?> leagues</strong>.
          <?php
          $plan = UserManager::getUserPlan();
          $planFeatures = UserManager::getPlanFeatures($plan);
          ?>
          Your plan includes <strong><?php echo $planFeatures['models']; ?> prediction model(s)</strong>.</small>
        </div>
        <?php elseif ($userRole === 'admin'): ?>
        <div class="alert alert-success mt-3 mb-0" style="background-color: #e8f5f2; border-color: #106147;">
          <i class="bx bx-crown me-2"></i>
          <small><strong>Admin Access:</strong> You can select up to <strong><?php echo $maxLeagues; ?> leagues</strong>
          with access to all prediction models.</small>
        </div>
        <?php endif; ?>
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

<!-- Include Filter JavaScript -->
<script src="assets/js/statistics-filter.js"></script>
