# SuperStatsFootball Backend Architecture Analysis

## Executive Summary
The SuperStatsFootball backend is a **PHP-based application** that serves as a frontend client to a **FastAPI backend** hosted on Railway. The PHP application acts as a middleware layer providing authentication, caching, and data transformation.

---

## 1. PROJECT STRUCTURE

### Directory Layout
```
/home/user/SuperStatsFootballw/
├── includes/                      # Core application logic
│   ├── api-config.php            # API endpoint definitions
│   ├── api-helper.php            # Legacy API wrapper functions
│   ├── ApiRepository.php         # Main API communication class (Repository Pattern)
│   ├── UserManager.php           # User roles, plans, permissions
│   ├── CacheManager.php          # File-based caching system
│   ├── Logger.php                # Logging utility
│   ├── auth-middleware.php       # Authentication middleware
│   ├── app-header.php            # Header template
│   ├── app-footer.php            # Footer template
│   ├── auth-header.php           # Auth page header
│   ├── auth-footer.php           # Auth page footer
│   ├── statistics-filter-modal.php # Shared filter component
│   └── ...
├── assets/                        # Static files
│   ├── js/
│   │   ├── config.js             # JS configuration
│   │   ├── main.js               # Main JS
│   │   ├── statistics-filter.js  # Filter logic
│   │   └── 1x2-filter.js         # 1x2 specific filters
│   ├── css/
│   │   └── demo.css
│   ├── vendor/                   # Third-party libraries
│   └── img/                      # Images and icons
├── [statistics-pages]            # Main endpoint files
│   ├── goals.php                 # Goals statistics
│   ├── corners.php               # Corners statistics
│   ├── cards.php                 # Cards statistics
│   ├── shots.php                 # Shots statistics
│   ├── faults.php                # Fouls statistics
│   ├── offsides.php              # Offsides statistics
│   └── 1x2.php                   # 1x2 predictions
├── [auth-pages]                  # Authentication pages
│   ├── login.php                 # Login form
│   ├── register.php              # Registration form
│   └── forgot-password.php       # Password recovery
├── [account-pages]               # User management
│   ├── account-settings.php      # Account settings
│   ├── plans.php                 # Plans page
│   └── payment-offers.php        # Payment offers
├── index.php                     # Dashboard homepage
└── [policy-pages]
    ├── terms-and-conditions.php
    └── privacy-policy.php
```

---

## 2. KEY TECHNOLOGIES & FRAMEWORKS

### Backend (PHP)
- **Language**: PHP 7.4+
- **Pattern**: Repository Pattern with Singleton
- **HTTP Client**: cURL
- **Caching**: File-based caching system
- **Logging**: Custom file-based logger

### Frontend Libraries
- **Bootstrap 5**: UI Framework
- **jQuery**: DOM manipulation
- **Popper.js**: Tooltip/popover library
- **Perfect Scrollbar**: Custom scrollbar
- **Boxicons**: Icon library
- **No Framework**: Vanilla JavaScript for most interactions

### External API
- **FastAPI Backend**: https://superstatsfootball-production.up.railway.app
- **API Version**: v1
- **Protocol**: REST JSON

---

## 3. API CONFIGURATION & ENDPOINTS

### Base Configuration (api-config.php)

```php
API_BASE_URL = https://superstatsfootball-production.up.railway.app
API_VERSION = v1
API_PREFIX = {BASE_URL}/api/v1
API_TIMEOUT = 10 seconds
API_SSL_VERIFY = true
```

### Defined Endpoints

#### Authentication Endpoints
```
POST   /api/v1/auth/login      - User login
POST   /api/v1/auth/register   - User registration
GET    /api/v1/auth/me         - Get current user info
```

#### Statistics Endpoints
```
GET    /api/v1/statistics/goals     - Goals statistics
GET    /api/v1/statistics/corners   - Corners statistics
GET    /api/v1/statistics/cards     - Cards (yellow/red) statistics
GET    /api/v1/statistics/shots     - Shots on target statistics
GET    /api/v1/statistics/fouls     - Fouls statistics
GET    /api/v1/statistics/offs      - Offsides statistics
```

#### Odds & Predictions
```
GET    /api/v1/odds/upcoming        - Upcoming match odds
GET    /api/v1/odds/fixture         - Fixture odds
GET    /api/v1/predictions          - Predictions data
GET    /api/v1/combined             - Combined predictions
```

#### Data Reference
```
GET    /api/v1/leagues              - League list
GET    /api/v1/fixtures             - Fixture list
```

---

## 4. API REQUEST/RESPONSE FORMATS

### Authentication Endpoint

**Request (Login)**
```json
POST /api/v1/auth/login
{
  "username": "user@example.com",
  "password": "password123"
}
```

**Response (Success)**
```json
{
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 123,
    "email": "user@example.com",
    "role": "user",  // "user" or "admin"
    "plan": 1  // 1-5 for free/paid plans
  }
}
```

### Statistics Endpoints (Example: Goals)

**Request**
```
GET /api/v1/statistics/goals?days_ahead=7&limit=50&offset=0&league_id=1

Query Parameters:
- days_ahead: integer (number of days to look ahead)
- limit: integer (max results per page, default 50)
- offset: integer (pagination offset, default 0)
- league_id: integer or comma-separated IDs (optional filter)
```

**Response Format**
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "England - Premier League",
      "league_id": 1,
      "fixture_date": "2025-11-20T15:00:00Z",
      "home_team": "Manchester United",
      "away_team": "Liverpool",
      
      // Both Teams to Score (BTS) probabilities
      "bts_ht_yes": 0.45,     // 45% both score in first half
      "bts_ht_no": 0.55,
      "bts_ft_yes": 0.62,     // 62% both score full time
      "bts_ft_no": 0.38,
      
      // Goals Under/Over probabilities (First Half)
      "goals_ht_u05": 0.15,   // 15% under 0.5 goals
      "goals_ht_u15": 0.35,   // 35% under 1.5 goals
      "goals_ht_u25": 0.55,   // 55% under 2.5 goals
      "goals_ht_o05": 0.85,   // 85% over 0.5 goals
      "goals_ht_o15": 0.65,
      "goals_ht_o25": 0.45,
      
      // Goals Under/Over probabilities (Full Time)
      "goals_ft_u15": 0.25,
      "goals_ft_u25": 0.48,
      "goals_ft_u35": 0.68,
      "goals_ft_o15": 0.75,
      "goals_ft_o25": 0.52,
      "goals_ft_o35": 0.32,
      
      // Bookmaker odds
      "bookmaker_u25": "1.85",  // Bookmaker odds under 2.5
      "bookmaker_o25": "1.95",  // Bookmaker odds over 2.5
      
      // True/Fair odds
      "true_odds_u25": "1.92",
      "true_odds_o25": "1.88"
    }
  ],
  "pagination": {
    "total": 250,
    "limit": 50,
    "offset": 0
  }
}
```

### Corners Endpoint Response
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "...",
      "fixture_date": "...",
      "home_team": "...",
      "away_team": "...",
      
      // Corners Under/Over (First Half)
      "corners_ht_u35": 0.30,  // Under 3.5 corners
      "corners_ht_u45": 0.50,  // Under 4.5 corners
      "corners_ht_u55": 0.70,  // Under 5.5 corners
      "corners_ht_o35": 0.70,
      "corners_ht_o45": 0.50,
      "corners_ht_o55": 0.30,
      
      // Corners Under/Over (Full Time)
      "corners_ft_u65": 0.25,
      "corners_ft_u75": 0.45,
      "corners_ft_u85": 0.65,
      "corners_ft_o65": 0.75,
      "corners_ft_o75": 0.55,
      "corners_ft_o85": 0.35,
      
      // Bookmaker odds
      "bookmaker_u65": "1.80",
      "bookmaker_o65": "2.00",
      
      "true_odds_u65": "1.88",
      "true_odds_o65": "1.92"
    }
  ]
}
```

### Cards (Yellow/Red) Endpoint Response
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "...",
      "fixture_date": "...",
      "home_team": "...",
      "away_team": "...",
      
      // Cards Under/Over (First Half)
      "cards_ht_u05": 0.40,   // Under 0.5 cards
      "cards_ht_u15": 0.65,   // Under 1.5 cards
      "cards_ht_u25": 0.85,   // Under 2.5 cards
      "cards_ht_o05": 0.60,
      "cards_ht_o15": 0.35,
      "cards_ht_o25": 0.15,
      
      // Cards Under/Over (Full Time)
      "cards_ft_u25": 0.50,
      "cards_ft_u35": 0.70,
      "cards_ft_u45": 0.85,
      "cards_ft_o25": 0.50,
      "cards_ft_o35": 0.30,
      "cards_ft_o45": 0.15,
      
      "bookmaker_u25": "1.75",
      "bookmaker_o25": "2.10",
      "true_odds_u25": "1.82",
      "true_odds_o25": "2.05"
    }
  ]
}
```

### Shots Endpoint Response
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "...",
      "fixture_date": "...",
      "home_team": "...",
      "away_team": "...",
      
      // Total shots on target (Full Time - 1x2 format)
      "total_shots_ft_1": 0.35,   // 35% home team wins
      "total_shots_ft_x": 0.30,   // 30% draw
      "total_shots_ft_2": 0.35,   // 35% away team wins
      
      // Alternative: Specific probabilities
      "shots_on_target_ht_u15": 0.40,
      "shots_on_target_ht_u25": 0.60,
      "shots_on_target_ft_u35": 0.35,
      "shots_on_target_ft_u45": 0.55,
      
      "bookmaker_u25": "1.70",
      "bookmaker_o25": "2.15",
      "true_odds_u25": "1.78",
      "true_odds_o25": "2.10"
    }
  ]
}
```

### Offsides Endpoint Response
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "...",
      "fixture_date": "...",
      "home_team": "...",
      "away_team": "...",
      
      // Offsides Under/Over (First Half)
      "offsides_ht_u15": 0.50,
      "offsides_ht_u25": 0.70,
      "offsides_ht_o15": 0.50,
      "offsides_ht_o25": 0.30,
      
      // Offsides Under/Over (Full Time)
      "offsides_ft_u25": 0.35,
      "offsides_ft_u35": 0.55,
      "offsides_ft_o25": 0.65,
      "offsides_ft_o35": 0.45,
      
      "bookmaker_u25": "1.90",
      "bookmaker_o25": "1.85",
      "true_odds_u25": "1.95",
      "true_odds_o25": "1.88"
    }
  ]
}
```

### Fouls Endpoint Response
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "...",
      "fixture_date": "...",
      "home_team": "...",
      "away_team": "...",
      
      // Fouls Under/Over (First Half)
      "faults_ht_u85": 0.30,   // Under 8.5 fouls
      "faults_ht_u95": 0.50,   // Under 9.5 fouls
      "faults_ht_u105": 0.70,  // Under 10.5 fouls
      "faults_ht_o85": 0.70,
      "faults_ht_o95": 0.50,
      "faults_ht_o105": 0.30,
      
      // Fouls Under/Over (Full Time)
      "faults_ft_u155": 0.25,
      "faults_ft_u175": 0.45,
      "faults_ft_u195": 0.65,
      "faults_ft_o155": 0.75,
      "faults_ft_o175": 0.55,
      "faults_ft_o195": 0.35,
      
      "bookmaker_u175": "1.80",
      "bookmaker_o175": "2.00",
      "true_odds_u175": "1.88",
      "true_odds_o175": "1.95"
    }
  ]
}
```

### Odds/1x2 Endpoint Response
```json
{
  "fixtures": [
    {
      "fixture_id": "12345",
      "league_name": "England - Premier League",
      "fixture_date": "2025-11-20T15:00:00Z",
      "home_team": "Manchester United",
      "away_team": "Liverpool",
      
      // Match outcome probabilities
      "home_win": 0.45,        // 45% home team wins
      "draw": 0.28,            // 28% draw
      "away_win": 0.27,        // 27% away team wins
      
      // Over/Under 2.5 goals
      "over_25": 0.65,
      "under_25": 0.35,
      
      // Both Teams to Score
      "both_teams_score": 0.58,
      "both_teams_no_score": 0.42,
      
      // Bookmaker odds
      "bookmaker_1": "2.10",
      "bookmaker_x": "3.50",
      "bookmaker_2": "3.40",
      
      // True/Fair odds
      "true_odds_1": "2.22",
      "true_odds_x": "3.57",
      "true_odds_2": "3.70"
    }
  ]
}
```

---

## 5. REQUEST/RESPONSE HANDLING

### Request Handling (ApiRepository.php)

**HTTP Methods Supported**
- GET: For fetching data (with caching)
- POST: For login/registration (no caching)
- PUT: For updates (if available)
- DELETE: For deletion (if available)

**Default Headers**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {TOKEN} (if authenticated)
```

**Request Building**
```php
// Query parameters are automatically serialized
// League IDs are comma-separated for multiple selections
$endpoint = buildEndpoint(
    '/api/v1/statistics/goals',
    [
        'days_ahead' => 7,
        'league_id' => '1,2,3',  // Multiple leagues
        'limit' => 50,
        'offset' => 0
    ]
);
// Result: /api/v1/statistics/goals?days_ahead=7&league_id=1,2,3&limit=50&offset=0
```

**Response Format (Standardized)**
```php
[
    'success' => true/false,
    'data' => $apiResponseData,     // Actual API response
    'error' => 'Error message' or null,
    'http_code' => 200  // HTTP status code
]
```

---

## 6. CACHING SYSTEM

### Cache Manager (CacheManager.php)

**Configuration**
- **Storage**: File-based in `/cache` directory
- **Default TTL**: 300 seconds (5 minutes)
- **Max Log Size**: 10MB (auto-rotation)

**Cache Key Generation**
```php
// Generated as MD5 hash of endpoint URL
// Example: api_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

**Caching Behavior**
- **GET requests**: Automatically cached
- **POST/PUT/DELETE**: No caching
- **Cache Invalidation**: Time-based expiration
- **Cache Cleanup**: Manual cleanup of expired files

**Usage in API Repository**
```php
// Cache is transparent - happens automatically for GET requests
// Check cache first
$cached = $this->cache->get($cacheKey);
if ($cached !== null) {
    return $cached;  // Return cached result
}

// Execute API request
$response = curl_exec($ch);

// Cache successful response
if ($httpCode >= 200 && $httpCode < 300) {
    $this->cache->set($cacheKey, $result, $this->cacheTTL);
}
```

---

## 7. USER MANAGEMENT & ROLES

### User Roles (UserManager.php)

**Role Types**
1. **user**: Regular user
   - Max leagues: 5
   - League limit enforced on API calls

2. **admin**: Administrator
   - Max leagues: 10
   - All prediction models available

### Subscription Plans

**Plan Structure** (1-5)

```php
1. FREE PLAN ($0/month)
   - 1 prediction model (Poisson Goal Model)
   - 5 league selection limit
   - Basic statistics access
   - Included features: Basic stats

2. BASIC PLAN ($9.99/month)
   - 2 models: Poisson + Dixon-Coles
   - 5 league selection limit
   - Enhanced statistics

3. STANDARD PLAN ($19.99/month)
   - 3 models: + Bivariate Poisson
   - 5 league selection limit
   - Full statistics access

4. PREMIUM PLAN ($29.99/month)
   - 4 models: + Elo Rating System
   - 5 league selection limit
   - Advanced analytics

5. ULTIMATE PLAN ($39.99/month)
   - 5 models: + Glicko/TrueSkill Ratings
   - 5 league selection limit (can be increased)
   - All features included
```

### Prediction Models Available

```php
const MODEL_POISSON = 'poisson';              // Plan 1+
const MODEL_DIXON_COLES = 'dixon_coles';      // Plan 2+
const MODEL_BIVARIATE = 'bivariate_poisson';  // Plan 3+
const MODEL_ELO = 'elo_rating';               // Plan 4+
const MODEL_GLICKO = 'glicko_trueskill';      // Plan 5+
```

### User Data Storage

**Session Variables**
```php
$_SESSION['api_auth_token']    // JWT access token
$_SESSION['api_user_data']     // User object:
    [
        'id' => 123,
        'email' => 'user@example.com',
        'role' => 'user|admin',
        'plan' => 1-5,
        'created_at' => '2025-01-01T00:00:00Z'
    ]
```

### Permission Validation

**League Selection Validation**
```php
// Validates user selection before API call
$validation = UserManager::validateLeagueSelection($leagues, $userRole);

// Returns:
[
    'valid' => true|false,
    'message' => 'Error message if invalid'
]
```

**Model Access Check**
```php
// Check if user can access specific model
$hasAccess = UserManager::hasModelAccess('glicko_trueskill', $userPlan);
```

---

## 8. RATE LIMITING & ACCESS CONTROL

### Server-Side Limits (PHP)

**Request Timeouts**
- Global API timeout: 10 seconds
- Socket timeout for API calls

**User League Selection Limits** (Frontend Validation)
- Regular users: Maximum 5 leagues per request
- Admins: Maximum 10 leagues per request
- Enforced in:
  - ApiRepository::validateLeagues()
  - JavaScript (statistics-filter.js)

**Pagination Controls**
- Default limit: 50 results per page
- Maximum offset: Determined by backend
- Parameters: `limit` and `offset`

### Frontend Rate Limiting (JavaScript)

**Filter Modal Validation**
- Real-time league selection limit checking
- Disable submit button if limit exceeded
- Error messages for user guidance

```javascript
if (count > maxLeagues) {
    showErrorMessage(
        `You can select max ${maxLeagues} leagues. Currently: ${count}`
    );
    applyFiltersBtn.disabled = true;
}
```

### Logging & Monitoring

**API Request Logging** (Logger.php)
```php
// Logs all API requests with:
- Endpoint URL
- HTTP method
- Response code
- Duration (milliseconds)
- Timestamp
```

**Log Location**
- Directory: `/logs`
- File naming: `app_YYYY-MM-DD.log`
- Rotation: Auto-rotates at 10MB
- Retention: Configurable (default 30 days)

**Log Entry Example**
```
[2025-01-15 10:30:45] [INFO] API Request | endpoint: https://...statistics/goals, method: GET, http_code: 200, duration_ms: 245.32
```

---

## 9. AUTHENTICATION FLOW

### Login Process (login.php)

**Step 1: User Submits Login Form**
```php
POST login.php
{
    'email-username' => 'user@example.com',
    'password' => 'password123'
}
```

**Step 2: PHP Calls Backend API**
```php
$response = loginUser($email, $password);
// Internally calls ApiRepository::loginUser()
```

**Step 3: Backend Returns Token**
```json
{
    "access_token": "eyJ...",
    "user": {
        "id": 123,
        "email": "user@example.com",
        "role": "user",
        "plan": 2
    }
}
```

**Step 4: Token Stored in Session**
```php
$_SESSION['api_auth_token'] = $response['data']['access_token'];
$_SESSION['api_user_data'] = $response['data']['user'];
```

**Step 5: Redirect to Requested Page**
```php
$redirectTo = $_SESSION['redirect_after_login'] ?? 'index.php';
header('Location: ' . $redirectTo);
```

### Authentication Middleware (auth-middleware.php)

**Protect Page**
```php
require_once 'auth-middleware.php';
requireAuth();  // Redirects to login if not authenticated
```

**Demo Authentication (Auto-Login)**
```php
tryDemoAuth();  // Attempts login with demo credentials
// Email: demo@superstatsfootball.com
// Password: demo123
// Allows seamless access without explicit login
```

### Session Management

**Authentication Check**
```php
function isAuthenticated() {
    return isset($_SESSION[SESSION_TOKEN_KEY]) 
        && !empty($_SESSION[SESSION_TOKEN_KEY]);
}
```

**Get Current User**
```php
function getCurrentUser() {
    return $_SESSION[SESSION_USER_KEY] ?? null;
}
```

**Logout**
```php
function logoutUser() {
    unset($_SESSION[SESSION_TOKEN_KEY]);
    unset($_SESSION[SESSION_USER_KEY]);
    session_destroy();
}
```

---

## 10. FILTERING & PAGINATION

### Filter Parameters

**Supported Filters (All Statistics Endpoints)**
```
- days_ahead: integer
  Default: 7
  Determines how many days ahead to look for fixtures

- league_id: integer or comma-separated string
  Example: league_id=1,2,3
  Subject to user role limits (5 for users, 10 for admins)

- limit: integer
  Default: 50
  Maximum results per page

- offset: integer
  Default: 0
  Pagination offset for result set
```

### Filter Application (JavaScript)

**Filter Modal Component** (statistics-filter-modal.php)
- League selection with checkboxes
- Date range picker
- User limit badges
- Plan information display

**Frontend Validation** (statistics-filter.js)
- Real-time league limit checking
- URL parameter management
- Filter persistence across page reloads
- Error messaging

**URL Parameter Format**
```
?leagues=1,2,3&date_from=2025-01-15&date_to=2025-01-20

Parsed in PHP:
$leagueFilter = explode(',', $_GET['leagues']);  // [1, 2, 3]
$dateFrom = $_GET['date_from'];
$dateTo = $_GET['date_to'];
```

---

## 11. ERROR HANDLING

### API Error Response

**Failed Request**
```json
{
    "detail": "Unauthorized",
    "status_code": 401
}
```

**PHP Response for Error**
```php
[
    'success' => false,
    'data' => null,
    'error' => 'Unauthorized',
    'http_code' => 401
]
```

### HTTP Status Codes

```
200-299: Success
- Data returned as 'data' in response

400: Bad Request
- Invalid parameters
- League limit exceeded

401: Unauthorized
- Invalid/expired token
- Login required

403: Forbidden
- Permission denied
- Invalid access

404: Not Found
- Endpoint not found

500+: Server Error
- Backend error
- Database error
```

### Connection Error Handling

```php
// cURL error
if ($error) {
    return [
        'success' => false,
        'data' => null,
        'error' => 'Connection error: ' . $error,
        'http_code' => $httpCode
    ];
}

// JSON parsing error
$responseData = json_decode($response, true);
// if decode fails, returns null
```

---

## 12. DATA TRANSFORMATION

### API Response to Frontend Format

**Example: Goals Statistics**

**Backend API Response**
```json
{
    "fixtures": [{
        "league_name": "Belgium - Jupiler League",
        "fixture_date": "2019-07-26T00:00:00Z",
        "home_team": "Genk",
        "away_team": "Kortrijk",
        "bts_ht_yes": 0.766,
        "goals_ht_u25": 0.694,
        ...
    }]
}
```

**Transformed to Frontend Format** (goals.php)
```php
[
    'league' => 'Belgium - Jupiler League',
    'date' => '26-07-2019',
    'team1' => 'Genk',
    'team2' => 'Kortrijk',
    'bts' => [
        'ht_yes' => '76.6%',      // Converted to percentage
        'ht_no' => '23.4%',
        'ft_yes' => '76.6%',
        'ft_no' => '23.4%'
    ],
    'goals_ht' => [
        'u05' => '33.4%',
        'u15' => '57.2%',
        ...
    ]
]
```

---

## 13. ENVIRONMENT CONFIGURATION

### Configuration Variables

**Backend API URL** (api-config.php)
```php
define('BACKEND_API_URL', getenv('BACKEND_API_URL') 
    ?: 'https://superstatsfootball-production.up.railway.app');
```

**Demo Credentials** (auth-middleware.php)
```php
define('DEMO_USER_EMAIL', getenv('DEMO_USER_EMAIL') 
    ?: 'demo@superstatsfootball.com');
define('DEMO_USER_PASSWORD', getenv('DEMO_USER_PASSWORD') 
    ?: 'demo123');
```

**Session Configuration**
```php
define('SESSION_TOKEN_KEY', 'api_auth_token');
define('SESSION_USER_KEY', 'api_user_data');
```

---

## 14. FRONTEND INTEGRATION POINTS

### Key JavaScript Files

**config.js**: Theme color configuration
```javascript
window.config = {
    colors: {
        primary: '#106147',
        secondary: '#...',
        success: '#...',
        ...
    }
}
```

**statistics-filter.js**: Filter modal logic
- Event listeners for checkboxes
- Validation logic
- URL parameter management
- Real-time league limit checking

**main.js**: General application logic
**1x2-filter.js**: 1x2 specific filtering

### Template Components

**app-header.php**: Main navigation
**app-footer.php**: Footer with social links
**statistics-filter-modal.php**: Reusable filter component

---

## 15. COMPATIBILITY NOTES

### Frontend Framework Expectations

The frontend JavaScript expects:
1. **Bootstrap 5 Modal Support** for filter modal
2. **URL Search Parameters API** for filter persistence
3. **CSS class `.filter-league`** for league checkboxes
4. **Modal data attributes** for user limits

### Backend Expectations

The backend API is expected to:
1. Return standardized JSON response with `fixtures` array
2. Include fixture data with specific field names
3. Support pagination with `limit` and `offset`
4. Return probabilities as decimals (0.0-1.0)
5. Support Bearer token authentication

### Required Response Fields

Each fixture in statistics response must include:
- `league_name`
- `fixture_date`
- `home_team`
- `away_team`
- Various statistic fields (e.g., `goals_ht_u25`)

---

## 16. PERFORMANCE OPTIMIZATION

### Caching Strategy

**GET Requests**: Cached for 5 minutes
```php
// Automatic caching in ApiRepository::request()
if ($method === 'GET' && $useCache) {
    // Check cache first
    // Return cached if available
    // Cache response if successful
}
```

**Cache Invalidation**: Time-based TTL

### Request Optimization

**Single League Selection** (Current Limitation)
```php
// Currently only first league is used
$leagueFilter = isset($_GET['leagues']) 
    ? explode(',', $_GET['leagues']) 
    : null;
$apiResponse = getGoalsStatistics(
    $daysAhead, 
    $leagueFilter ? $leagueFilter[0] : null  // Only [0]
);
```

**To Fix**: Modify to pass full league array
```php
$apiResponse = getGoalsStatistics(
    $daysAhead,
    $leagueFilter,  // Pass entire array
    50,
    0
);
```

---

## 17. KNOWN ISSUES & IMPROVEMENTS NEEDED

1. **Single League in API Call**: Currently only uses first selected league
2. **Filter Modal Hard-coded Leagues**: League list should come from backend
3. **No Search Functionality**: Cannot search for specific leagues
4. **No Sorting**: Results cannot be sorted by date/team
5. **Missing Sports Data**: Odds, predictions not fully integrated
6. **No Real-time Updates**: Data refreshes only on page load

---

## 18. DEPLOYMENT CONSIDERATIONS

### Required Environment Variables
```
BACKEND_API_URL=https://superstatsfootball-production.up.railway.app
DEMO_USER_EMAIL=demo@superstatsfootball.com
DEMO_USER_PASSWORD=demo123
```

### Directory Permissions
```
/cache/      - Must be writable (0755)
/logs/       - Must be writable (0755)
```

### PHP Configuration
- Session support enabled
- cURL extension required
- JSON extension required
- File I/O permissions for caching/logging

---

## Summary Table

| Component | Technology | Purpose |
|-----------|-----------|---------|
| Backend API | FastAPI (Python) | Data source |
| Frontend | PHP + Bootstrap 5 | Web server & UI |
| Authentication | JWT tokens | User authentication |
| Caching | File-based | Performance optimization |
| Logging | File-based | Application monitoring |
| User Limits | Role-based | Access control |
| Statistics | JSON API | Data retrieval |

