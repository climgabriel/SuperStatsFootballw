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
        <!-- Days Ahead Filter -->
        <div class="mb-4">
          <label class="form-label fw-bold d-flex align-items-center">
            <i class="bx bx-calendar me-2" style="color: #106147;"></i>Days Ahead
          </label>
          <div class="row">
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input filter-days" type="radio" name="daysAhead" value="7" id="days7" checked>
                <label class="form-check-label" for="days7">Next 7 days</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input filter-days" type="radio" name="daysAhead" value="14" id="days14">
                <label class="form-check-label" for="days14">Next 14 days</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input filter-days" type="radio" name="daysAhead" value="30" id="days30">
                <label class="form-check-label" for="days30">Next 30 days</label>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <!-- Leagues Filter -->
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0 d-flex align-items-center">
              <i class="bx bx-trophy me-2" style="color: #106147;"></i>Leagues
            </label>
            <div>
              <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="selectAllLeagues">Select All</button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllLeagues">Deselect All</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="England - Premier League" id="league1">
                <label class="form-check-label" for="league1">England - Premier League</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Spain - La Liga" id="league2">
                <label class="form-check-label" for="league2">Spain - La Liga</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Italy - Serie A" id="league3">
                <label class="form-check-label" for="league3">Italy - Serie A</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Germany - Bundesliga" id="league4">
                <label class="form-check-label" for="league4">Germany - Bundesliga</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="France - Ligue 1" id="league5">
                <label class="form-check-label" for="league5">France - Ligue 1</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Netherlands - Eredivisie" id="league6">
                <label class="form-check-label" for="league6">Netherlands - Eredivisie</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Portugal - Primeira Liga" id="league7">
                <label class="form-check-label" for="league7">Portugal - Primeira Liga</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Belgium - Jupiler League" id="league8">
                <label class="form-check-label" for="league8">Belgium - Jupiler League</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Turkey - Super Lig" id="league9">
                <label class="form-check-label" for="league9">Turkey - Super Lig</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-league" type="checkbox" value="Champions League" id="league10">
                <label class="form-check-label" for="league10">Champions League</label>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <!-- Season Filter -->
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0 d-flex align-items-center">
              <i class="bx bx-time me-2" style="color: #106147;"></i>Season
            </label>
            <div>
              <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="selectAllSeasons">Select All</button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllSeasons">Deselect All</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-check mb-2">
                <input class="form-check-input filter-season" type="checkbox" value="2024" id="season1" checked>
                <label class="form-check-label" for="season1">2024/2025</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check mb-2">
                <input class="form-check-input filter-season" type="checkbox" value="2023" id="season2">
                <label class="form-check-label" for="season2">2023/2024</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check mb-2">
                <input class="form-check-input filter-season" type="checkbox" value="2022" id="season3">
                <label class="form-check-label" for="season3">2022/2023</label>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <!-- Analytics Model Filter -->
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0 d-flex align-items-center">
              <i class="bx bx-brain me-2" style="color: #106147;"></i>Analytics Models
            </label>
            <div>
              <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="selectAllModels">Select All</button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllModels">Deselect All</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <!-- Statistical Models (Always available) -->
              <small class="text-muted fw-bold">Statistical Models</small>
              <div class="form-check mb-2 mt-1">
                <input class="form-check-input filter-model" type="checkbox" value="poisson" id="model1" checked>
                <label class="form-check-label" for="model1">Poisson</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input filter-model" type="checkbox" value="dixon-coles" id="model2" checked>
                <label class="form-check-label" for="model2">Dixon-Coles</label>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input filter-model" type="checkbox" value="elo" id="model3" checked>
                <label class="form-check-label" for="model3">Elo Rating</label>
              </div>

              <?php
              // Get user tier to determine which ML models to show
              $userTier = getUserTier();
              $tierModels = [
                'free' => [],
                'starter' => ['random-forest', 'gradient-boost'],
                'pro' => ['random-forest', 'gradient-boost', 'xgboost', 'lightgbm'],
                'premium' => ['random-forest', 'gradient-boost', 'xgboost', 'lightgbm', 'catboost', 'neural-network'],
                'ultimate' => ['random-forest', 'gradient-boost', 'xgboost', 'lightgbm', 'catboost', 'neural-network', 'ensemble'],
                'admin' => ['random-forest', 'gradient-boost', 'xgboost', 'lightgbm', 'catboost', 'neural-network', 'ensemble']
              ];

              $availableModels = $tierModels[$userTier] ?? [];
              $allMlModels = [
                'random-forest' => 'Random Forest',
                'gradient-boost' => 'Gradient Boosting',
                'xgboost' => 'XGBoost',
                'lightgbm' => 'LightGBM',
                'catboost' => 'CatBoost',
                'neural-network' => 'Neural Network',
                'ensemble' => 'Ensemble Model'
              ];
              ?>

              <?php if (!empty($availableModels)): ?>
              <small class="text-muted fw-bold">Machine Learning Models</small>
              <?php foreach ($availableModels as $index => $modelKey): ?>
                <?php if ($index < 3): // First 3 ML models in left column ?>
              <div class="form-check mb-2 mt-1">
                <input class="form-check-input filter-model" type="checkbox" value="<?php echo $modelKey; ?>" id="ml<?php echo $index; ?>">
                <label class="form-check-label" for="ml<?php echo $index; ?>"><?php echo $allMlModels[$modelKey]; ?></label>
              </div>
                <?php endif; ?>
              <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <div class="col-md-6">
              <?php if (count($availableModels) > 3): ?>
              <small class="text-muted fw-bold">Additional ML Models</small>
              <?php foreach ($availableModels as $index => $modelKey): ?>
                <?php if ($index >= 3): // Remaining ML models in right column ?>
              <div class="form-check mb-2 mt-1">
                <input class="form-check-input filter-model" type="checkbox" value="<?php echo $modelKey; ?>" id="ml<?php echo $index; ?>">
                <label class="form-check-label" for="ml<?php echo $index; ?>"><?php echo $allMlModels[$modelKey]; ?></label>
              </div>
                <?php endif; ?>
              <?php endforeach; ?>
              <?php elseif (empty($availableModels)): ?>
              <div class="alert alert-info mb-0">
                <small><i class="bx bx-info-circle me-1"></i>Upgrade your plan to access ML prediction models!</small>
              </div>
              <?php endif; ?>
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
