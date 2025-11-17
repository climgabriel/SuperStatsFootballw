<?php
/**
 * User Manager - Handles user roles, plans, and permissions
 *
 * User Roles: 'user', 'admin'
 * Subscription Plans: 1 (free), 2-5 (paid)
 *
 * Features:
 * - League selection limits (5 for users, 10 for admins)
 * - Prediction model access based on plan
 * - User permissions management
 */

class UserManager {

    // User roles
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    // Subscription plans
    const PLAN_FREE = 1;
    const PLAN_BASIC = 2;
    const PLAN_STANDARD = 3;
    const PLAN_PREMIUM = 4;
    const PLAN_ULTIMATE = 5;

    // League limits
    const MAX_LEAGUES_USER = 5;
    const MAX_LEAGUES_ADMIN = 10;

    // Prediction models
    const MODEL_POISSON = 'poisson';
    const MODEL_DIXON_COLES = 'dixon_coles';
    const MODEL_BIVARIATE = 'bivariate_poisson';
    const MODEL_ELO = 'elo_rating';
    const MODEL_GLICKO = 'glicko_trueskill';

    /**
     * Get available prediction models based on user plan
     *
     * @param int $plan Subscription plan number (1-5)
     * @return array Available model identifiers
     */
    public static function getAvailableModels($plan) {
        $models = [];

        // Plan 1 (Free): Poisson goal model
        if ($plan >= self::PLAN_FREE) {
            $models[] = self::MODEL_POISSON;
        }

        // Plan 2: + Dixon-Coles Poisson
        if ($plan >= self::PLAN_BASIC) {
            $models[] = self::MODEL_DIXON_COLES;
        }

        // Plan 3: + Bivariate Poisson
        if ($plan >= self::PLAN_STANDARD) {
            $models[] = self::MODEL_BIVARIATE;
        }

        // Plan 4: + Elo rating system
        if ($plan >= self::PLAN_PREMIUM) {
            $models[] = self::MODEL_ELO;
        }

        // Plan 5: + Glicko/TrueSkill ratings
        if ($plan >= self::PLAN_ULTIMATE) {
            $models[] = self::MODEL_GLICKO;
        }

        return $models;
    }

    /**
     * Get model display names
     *
     * @return array Model names
     */
    public static function getModelNames() {
        return [
            self::MODEL_POISSON => 'Poisson Goal Model',
            self::MODEL_DIXON_COLES => 'Dixon-Coles Poisson',
            self::MODEL_BIVARIATE => 'Bivariate Poisson',
            self::MODEL_ELO => 'Elo Rating System',
            self::MODEL_GLICKO => 'Glicko/TrueSkill Ratings'
        ];
    }

    /**
     * Check if user has access to specific model
     *
     * @param string $model Model identifier
     * @param int $plan User's subscription plan
     * @return bool
     */
    public static function hasModelAccess($model, $plan) {
        $availableModels = self::getAvailableModels($plan);
        return in_array($model, $availableModels);
    }

    /**
     * Get maximum leagues user can select
     *
     * @param string $role User role ('user' or 'admin')
     * @return int Maximum number of leagues
     */
    public static function getMaxLeagues($role) {
        return ($role === self::ROLE_ADMIN)
            ? self::MAX_LEAGUES_ADMIN
            : self::MAX_LEAGUES_USER;
    }

    /**
     * Validate league selection
     *
     * @param array $selectedLeagues Array of selected league IDs
     * @param string $role User role
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateLeagueSelection($selectedLeagues, $role) {
        $maxLeagues = self::getMaxLeagues($role);
        $count = count($selectedLeagues);

        if ($count > $maxLeagues) {
            return [
                'valid' => false,
                'message' => "You can select a maximum of {$maxLeagues} leagues. Currently selected: {$count}."
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Get user role from session
     *
     * @return string User role (defaults to 'user')
     */
    public static function getUserRole() {
        $user = $_SESSION[SESSION_USER_KEY] ?? null;

        if (!$user) {
            return self::ROLE_USER;
        }

        return $user['role'] ?? self::ROLE_USER;
    }

    /**
     * Get user subscription plan from session
     *
     * @return int Subscription plan (defaults to free plan)
     */
    public static function getUserPlan() {
        $user = $_SESSION[SESSION_USER_KEY] ?? null;

        if (!$user) {
            return self::PLAN_FREE;
        }

        return $user['plan'] ?? self::PLAN_FREE;
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public static function isAdmin() {
        return self::getUserRole() === self::ROLE_ADMIN;
    }

    /**
     * Get plan features description
     *
     * @param int $plan Plan number
     * @return array Plan details
     */
    public static function getPlanFeatures($plan) {
        $features = [
            self::PLAN_FREE => [
                'name' => 'Free Plan',
                'price' => 0,
                'models' => 1,
                'leagues' => 5,
                'description' => 'Basic Poisson model predictions'
            ],
            self::PLAN_BASIC => [
                'name' => 'Basic Plan',
                'price' => 9.99,
                'models' => 2,
                'leagues' => 5,
                'description' => 'Poisson + Dixon-Coles models'
            ],
            self::PLAN_STANDARD => [
                'name' => 'Standard Plan',
                'price' => 19.99,
                'models' => 3,
                'leagues' => 5,
                'description' => 'Add Bivariate Poisson model'
            ],
            self::PLAN_PREMIUM => [
                'name' => 'Premium Plan',
                'price' => 29.99,
                'models' => 4,
                'leagues' => 5,
                'description' => 'Add Elo Rating system'
            ],
            self::PLAN_ULTIMATE => [
                'name' => 'Ultimate Plan',
                'price' => 39.99,
                'models' => 5,
                'leagues' => 5,
                'description' => 'All models including Glicko/TrueSkill'
            ]
        ];

        return $features[$plan] ?? $features[self::PLAN_FREE];
    }

    /**
     * Get user statistics limits
     *
     * @return array Limits for current user
     */
    public static function getUserLimits() {
        $role = self::getUserRole();
        $plan = self::getUserPlan();

        return [
            'max_leagues' => self::getMaxLeagues($role),
            'available_models' => self::getAvailableModels($plan),
            'is_admin' => self::isAdmin(),
            'plan' => $plan,
            'plan_name' => self::getPlanFeatures($plan)['name']
        ];
    }
}
