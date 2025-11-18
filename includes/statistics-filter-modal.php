<?php
/**
 * Shared Statistics Filter Modal Component
 *
 * This modal is used across all statistics pages for filtering by league and date range
 * Includes user-based league selection limits
 */

require_once 'UserManager.php';
require_once 'LeagueList.php';

// Get user limits
$maxLeagues = UserManager::getMaxLeagues(UserManager::getUserRole());
$userRole = UserManager::getUserRole();

$leagueFilePath = __DIR__ . '/../data/leagues.txt';
$leagueGroups = LeagueList::loadFromFile($leagueFilePath);

if (empty($leagueGroups)) {
    $fallbackLeagues = [
        ['display' => 'England - Premier League', 'value' => '152', 'id' => '152'],
        ['display' => 'Spain - La Liga', 'value' => '140', 'id' => '140'],
        ['display' => 'Italy - Serie A', 'value' => '207', 'id' => '207'],
        ['display' => 'Germany - Bundesliga', 'value' => '78', 'id' => '78'],
        ['display' => 'France - Ligue 1', 'value' => '61', 'id' => '61'],
        ['display' => 'Netherlands - Eredivisie', 'value' => '244', 'id' => '244'],
        ['display' => 'Portugal - Primeira Liga', 'value' => '264', 'id' => '264'],
        ['display' => 'Belgium - Jupiler League', 'value' => '63', 'id' => '63'],
        ['display' => 'Turkey - Super Lig', 'value' => '322', 'id' => '322'],
        ['display' => 'Champions League', 'value' => '3', 'id' => '3'],
    ];

    $leagueGroups = [
        [
            'region' => 'Featured Leagues',
            'leagues' => array_map(function ($league) {
                return [
                    'label' => $league['display'],
                    'display' => $league['display'],
                    'value' => $league['value'],
                    'id' => $league['id'],
                ];
            }, $fallbackLeagues),
        ],
    ];
}
?>

<!-- Filter Button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal" style="background-color: #106147; border-color: #106147;">
  <i class="bx bx-filter me-1"></i> Filter
</button>

<style>
  .league-scroll {
    max-height: 320px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    padding: 1rem;
    background-color: #f9fbfa;
  }

  .league-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    column-gap: 1.5rem;
  }

  .league-group-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #106147;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
  }

  .league-scroll .form-check-label small {
    font-size: 0.7rem;
    color: #6c757d;
  }
</style>

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
          <div class="league-scroll">
            <?php foreach ($leagueGroups as $groupIndex => $group): ?>
              <div class="league-group mb-3">
                <div class="league-group-title"><?php echo htmlspecialchars($group['region']); ?></div>
                <div class="league-grid">
                  <?php foreach ($group['leagues'] as $leagueIndex => $league): ?>
                    <div class="form-check mb-2">
                      <input
                        class="form-check-input filter-league"
                        type="checkbox"
                        value="<?php echo htmlspecialchars($league['value']); ?>"
                        id="league<?php echo $groupIndex; ?>_<?php echo $leagueIndex; ?>"
                        data-league-id="<?php echo htmlspecialchars($league['id'] ?? ''); ?>"
                        data-league-name="<?php echo htmlspecialchars($league['display']); ?>"
                      >
                      <label class="form-check-label" for="league<?php echo $groupIndex; ?>_<?php echo $leagueIndex; ?>">
                        <?php echo htmlspecialchars($league['display']); ?>
                        <?php if (!empty($league['id'])): ?>
                          <small>#<?php echo htmlspecialchars($league['id']); ?></small>
                        <?php endif; ?>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
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
