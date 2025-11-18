<?php
/**
 * Shared Statistics Filter Modal Component - Enhanced
 *
 * Features:
 * - Dynamic league loading from backend
 * - User-based league selection limits
 * - Prediction model selection based on subscription plan
 * - Real-time validation
 */

require_once 'UserManager.php';
require_once 'api-helper.php';

// Get user limits
$maxLeagues = UserManager::getMaxLeagues(UserManager::getUserRole());
$userRole = UserManager::getUserRole();
$userPlan = UserManager::getUserPlan();
$availableModels = UserManager::getAvailableModels($userPlan);
$modelNames = UserManager::getModelNames();

// Fetch available leagues from backend
$leaguesResponse = getLeagues(true); // Use cache
$leagues = [];
if ($leaguesResponse['success'] && isset($leaguesResponse['data']['leagues'])) {
    $leagues = $leaguesResponse['data']['leagues'];
} else {
    // Fallback to default leagues if API fails
    $leagues = [
        ['id' => 1, 'name' => 'England - Premier League', 'country' => 'England'],
        ['id' => 2, 'name' => 'Spain - La Liga', 'country' => 'Spain'],
        ['id' => 3, 'name' => 'Germany - Bundesliga', 'country' => 'Germany'],
        ['id' => 4, 'name' => 'Italy - Serie A', 'country' => 'Italy'],
        ['id' => 5, 'name' => 'France - Ligue 1', 'country' => 'France'],
        ['id' => 6, 'name' => 'Netherlands - Eredivisie', 'country' => 'Netherlands'],
        ['id' => 7, 'name' => 'Portugal - Primeira Liga', 'country' => 'Portugal'],
        ['id' => 8, 'name' => 'Belgium - Jupiler League', 'country' => 'Belgium'],
        ['id' => 9, 'name' => 'Turkey - Super Lig', 'country' => 'Turkey'],
        ['id' => 10, 'name' => 'Russia - Premier League', 'country' => 'Russia']
    ];
}
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
          <i class="bx bx-filter me-2"></i>Filter & Prediction Options
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- Prediction Models Section -->
        <?php if (!empty($availableModels)): ?>
        <div class="mb-4">
          <label class="form-label fw-bold d-flex align-items-center">
            <i class="bx bx-brain me-2" style="color: #106147;"></i>Prediction Models
            <span class="badge bg-success ms-2" style="font-size: 0.7rem; font-weight: 400;">
              <?php echo count($availableModels); ?> Available
            </span>
          </label>
          <p class="text-muted small mb-3">Select prediction models to apply to your statistics analysis</p>

          <div class="row">
            <?php foreach ($availableModels as $modelKey): ?>
            <div class="col-md-6">
              <div class="form-check mb-2">
                <input class="form-check-input prediction-model" type="checkbox"
                       value="<?php echo $modelKey; ?>"
                       id="model_<?php echo $modelKey; ?>">
                <label class="form-check-label d-flex align-items-center" for="model_<?php echo $modelKey; ?>">
                  <span><?php echo $modelNames[$modelKey]; ?></span>
                  <?php if ($modelKey === UserManager::MODEL_POISSON): ?>
                  <span class="badge bg-info ms-2" style="font-size: 0.65rem;">Default</span>
                  <?php endif; ?>
                </label>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <?php if ($userPlan < UserManager::PLAN_ULTIMATE): ?>
          <div class="alert alert-light border mt-3 mb-0" style="font-size: 0.85rem;">
            <i class="bx bx-info-circle me-1"></i>
            <small>Upgrade to unlock more prediction models.
            <a href="pricing.php" class="alert-link">View plans</a></small>
          </div>
          <?php endif; ?>
        </div>

        <hr>
        <?php endif; ?>

        <!-- Leagues Filter -->
        <div class="mb-4">
          <label class="form-label fw-bold d-flex align-items-center">
            <i class="bx bx-trophy me-2" style="color: #106147;"></i>Leagues
          </label>

          <!-- Search box for leagues -->
          <div class="mb-3">
            <input type="text" class="form-control form-control-sm" id="leagueSearch"
                   placeholder="Search leagues...">
          </div>

          <div class="row" id="leaguesList">
            <?php
            $halfCount = ceil(count($leagues) / 2);
            $leftLeagues = array_slice($leagues, 0, $halfCount);
            $rightLeagues = array_slice($leagues, $halfCount);
            ?>
            <div class="col-md-6">
              <?php foreach ($leftLeagues as $league): ?>
              <div class="form-check mb-2 league-item" data-league-name="<?php echo strtolower($league['name']); ?>">
                <input class="form-check-input filter-league" type="checkbox"
                       value="<?php echo htmlspecialchars($league['name']); ?>"
                       id="league<?php echo $league['id']; ?>">
                <label class="form-check-label" for="league<?php echo $league['id']; ?>">
                  <?php echo htmlspecialchars($league['name']); ?>
                </label>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="col-md-6">
              <?php foreach ($rightLeagues as $league): ?>
              <div class="form-check mb-2 league-item" data-league-name="<?php echo strtolower($league['name']); ?>">
                <input class="form-check-input filter-league" type="checkbox"
                       value="<?php echo htmlspecialchars($league['name']); ?>"
                       id="league<?php echo $league['id']; ?>">
                <label class="form-check-label" for="league<?php echo $league['id']; ?>">
                  <?php echo htmlspecialchars($league['name']); ?>
                </label>
              </div>
              <?php endforeach; ?>
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
          $planFeatures = UserManager::getPlanFeatures($userPlan);
          ?>
          Your <strong><?php echo $planFeatures['name']; ?></strong> includes <strong><?php echo $planFeatures['models']; ?> prediction model(s)</strong>.</small>
        </div>
        <?php elseif ($userRole === 'admin'): ?>
        <div class="alert alert-success mt-3 mb-0" style="background-color: #e8f5f2; border-color: #106147;">
          <i class="bx bx-crown me-2"></i>
          <small><strong>Admin Access:</strong> You can select up to <strong><?php echo $maxLeagues; ?> leagues</strong>
          with access to all <?php echo count($modelNames); ?> prediction models.</small>
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

<!-- Enhanced Filter JavaScript -->
<script src="assets/js/statistics-filter.js"></script>
<script>
// Add league search functionality
document.addEventListener('DOMContentLoaded', function() {
    const leagueSearch = document.getElementById('leagueSearch');
    if (leagueSearch) {
        leagueSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const leagueItems = document.querySelectorAll('.league-item');

            leagueItems.forEach(item => {
                const leagueName = item.dataset.leagueName;
                if (leagueName.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Auto-select Poisson model (default for all plans)
    const poissonModel = document.getElementById('model_<?php echo UserManager::MODEL_POISSON; ?>');
    if (poissonModel && !poissonModel.checked) {
        const urlParams = new URLSearchParams(window.location.search);
        const models = urlParams.get('models');
        if (!models) {
            // Only auto-select if no models in URL
            poissonModel.checked = true;
        }
    }
});
</script>
