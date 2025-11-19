<?php
require_once 'config.php';

$pageTitle = "Goals Statistics - Super Stats Football";
$pageDescription = "Goals Statistics and Analysis";
$activePage = "goals";

// Include API helper and authentication
require_once 'includes/api-helper.php';
require_once 'includes/auth-middleware.php';

// Require authentication for this page
requireAuth();

// Check if user has access to premium statistics
$hasAccess = hasPremiumStatsAccess();
$userTier = getUserTier();
$accessDenied = false;

// Get filter parameters from URL
$leagueFilter = null;
if (isset($_GET['leagues']) && $_GET['leagues'] !== '') {
    $selectedLeagues = array_filter(array_map('intval', explode(',', $_GET['leagues'])));
    $leagueFilter = !empty($selectedLeagues) ? $selectedLeagues : null;
}
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : null;
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : null;

// Calculate days ahead from date range
$daysAhead = 7; // Default
if ($dateTo) {
    $daysAhead = max(1, ceil((strtotime($dateTo) - time()) / 86400));
}

// Process API response
$goalsData = [];
$error = null;

// Fetch data from backend API regardless of tier (backend enforces access)
$apiResponse = getGoalsStatistics($daysAhead, $leagueFilter, 50, 0);

if ($apiResponse['success'] && isset($apiResponse['data']['fixtures'])) {
    // Transform backend data to match our table format
    foreach ($apiResponse['data']['fixtures'] as $fixture) {
        $goalsData[] = [
            'league' => $fixture['league_name'] ?? '-',
            'date' => isset($fixture['fixture_date']) ? date('d-m-Y', strtotime($fixture['fixture_date'])) : '-',
            'team1' => $fixture['home_team'] ?? '-',
            'team2' => $fixture['away_team'] ?? '-',
            'bts' => [
                'ht_yes' => isset($fixture['bts_ht_yes']) ? round($fixture['bts_ht_yes'] * 100, 1) . '%' : '-',
                'ht_no' => isset($fixture['bts_ht_no']) ? round($fixture['bts_ht_no'] * 100, 1) . '%' : '-',
                'ft_yes' => isset($fixture['bts_ft_yes']) ? round($fixture['bts_ft_yes'] * 100, 1) . '%' : '-',
                'ft_no' => isset($fixture['bts_ft_no']) ? round($fixture['bts_ft_no'] * 100, 1) . '%' : '-'
            ],
            'goals_ht' => [
                'u05' => isset($fixture['goals_ht_u05']) ? round($fixture['goals_ht_u05'] * 100, 1) . '%' : '-',
                'u15' => isset($fixture['goals_ht_u15']) ? round($fixture['goals_ht_u15'] * 100, 1) . '%' : '-',
                'u25' => isset($fixture['goals_ht_u25']) ? round($fixture['goals_ht_u25'] * 100, 1) . '%' : '-',
                'o05' => isset($fixture['goals_ht_o05']) ? round($fixture['goals_ht_o05'] * 100, 1) . '%' : '-',
                'o15' => isset($fixture['goals_ht_o15']) ? round($fixture['goals_ht_o15'] * 100, 1) . '%' : '-',
                'o25' => isset($fixture['goals_ht_o25']) ? round($fixture['goals_ht_o25'] * 100, 1) . '%' : '-'
            ],
            'goals_ft' => [
                'u15' => isset($fixture['goals_ft_u15']) ? round($fixture['goals_ft_u15'] * 100, 1) . '%' : '-',
                'u25' => isset($fixture['goals_ft_u25']) ? round($fixture['goals_ft_u25'] * 100, 1) . '%' : '-',
                'u35' => isset($fixture['goals_ft_u35']) ? round($fixture['goals_ft_u35'] * 100, 1) . '%' : '-',
                'o15' => isset($fixture['goals_ft_o15']) ? round($fixture['goals_ft_o15'] * 100, 1) . '%' : '-',
                'o25' => isset($fixture['goals_ft_o25']) ? round($fixture['goals_ft_o25'] * 100, 1) . '%' : '-',
                'o35' => isset($fixture['goals_ft_o35']) ? round($fixture['goals_ft_o35'] * 100, 1) . '%' : '-'
            ],
            'bookmaker' => [
                'u25' => $fixture['bookmaker_u25'] ?? '-',
                'o25' => $fixture['bookmaker_o25'] ?? '-'
            ],
            'true_odds' => [
                'u25' => $fixture['true_odds_u25'] ?? '-',
                'o25' => $fixture['true_odds_o25'] ?? '-'
            ]
        ];
    }
} else {
    if (($apiResponse['http_code'] ?? null) === 403) {
        $accessDenied = true;
    } elseif (!empty($apiResponse['error'])) {
        $error = $apiResponse['error'];
    }
}

// Fallback to sample data if API fails or returns no data
if (empty($goalsData)) {
$goalsData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'bts' => ['ht_yes' => '76.6%', 'ht_no' => '50.5%', 'ft_yes' => '76.6%', 'ft_no' => '50.5%'],
        'goals_ht' => ['u05' => '33.4%', 'u15' => '57.2%', 'u25' => '69.4%', 'o05' => '57.2%', 'o15' => '51.0%', 'o25' => '86.5%'],
        'goals_ft' => ['u15' => '51.0%', 'u25' => '75.9%', 'u35' => '79.7%', 'o15' => '53.8%', 'o25' => '79.7%', 'o35' => '43.9%'],
        'bookmaker' => ['u25' => '2.02', 'o25' => '4.28'],
        'true_odds' => ['u25' => '2.02', 'o25' => '4.28']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'bts' => ['ht_yes' => '82.7%', 'ht_no' => '74.1%', 'ft_yes' => '51.8%', 'ft_no' => '74.1%'],
        'goals_ht' => ['u05' => '75.9%', 'u15' => '86.0%', 'u25' => '32.0%', 'o05' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'goals_ft' => ['u15' => '70.6%', 'u25' => '56.0%', 'u35' => '63.3%', 'o15' => '74.1%', 'o25' => '63.3%', 'o35' => '70.9%'],
        'bookmaker' => ['u25' => '3.85', 'o25' => '2.07'],
        'true_odds' => ['u25' => '3.85', 'o25' => '2.07']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'St Truiden',
        'team2' => 'Mouscron',
        'bts' => ['ht_yes' => '33.4%', 'ht_no' => '57.2%', 'ft_yes' => '69.4%', 'ft_no' => '57.2%'],
        'goals_ht' => ['u05' => '75.9%', 'u15' => '86.0%', 'u25' => '32.0%', 'o05' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'goals_ft' => ['u15' => '70.6%', 'u25' => '56.0%', 'u35' => '63.3%', 'o15' => '74.1%', 'o25' => '63.3%', 'o35' => '70.9%'],
        'bookmaker' => ['u25' => '2.34', 'o25' => '3.27'],
        'true_odds' => ['u25' => '2.34', 'o25' => '3.27']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waasland-Beveren',
        'team2' => 'Club Brugge',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waregem',
        'team2' => 'Mechelen',
        'bts' => ['ht_yes' => '48.6%', 'ht_no' => '50.0%', 'ft_yes' => '73.3%', 'ft_no' => '50.0%'],
        'goals_ht' => ['u05' => '70.6%', 'u15' => '69.5%', 'u25' => '70.6%', 'o05' => '56.0%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'goals_ft' => ['u15' => '79.7%', 'u25' => '43.9%', 'u35' => '75.9%', 'o15' => '86.0%', 'o25' => '32.0%', 'o35' => '86.0%'],
        'bookmaker' => ['u25' => '2.00', 'o25' => '3.75'],
        'true_odds' => ['u25' => '2.00', 'o25' => '3.75']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Anderlecht',
        'team2' => 'Oostende',
        'bts' => ['ht_yes' => '48.6%', 'ht_no' => '50.0%', 'ft_yes' => '73.3%', 'ft_no' => '50.0%'],
        'goals_ht' => ['u05' => '70.2%', 'u15' => '37.5%', 'u25' => '70.2%', 'o05' => '54.1%', 'o15' => '63.3%', 'o25' => '74.1%'],
        'goals_ft' => ['u15' => '63.3%', 'u25' => '70.9%', 'u35' => '48.6%', 'o15' => '50.0%', 'o25' => '73.3%', 'o35' => '50.0%'],
        'bookmaker' => ['u25' => '1.56', 'o25' => '7.42'],
        'true_odds' => ['u25' => '1.56', 'o25' => '7.42']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Charleroi',
        'team2' => 'Gent',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Eupen',
        'team2' => 'Antwerp',
        'bts' => ['ht_yes' => '56.0%', 'ht_no' => '82.7%', 'ft_yes' => '37.5%', 'ft_no' => '82.7%'],
        'goals_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '33.4%', 'o25' => '57.2%'],
        'goals_ft' => ['u15' => '69.4%', 'u25' => '57.2%', 'u35' => '48.6%', 'o15' => '50.0%', 'o25' => '73.3%', 'o35' => '50.0%'],
        'bookmaker' => ['u25' => '5.80', 'o25' => '1.60'],
        'true_odds' => ['u25' => '5.80', 'o25' => '1.60']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '26-07-2019',
        'team1' => 'Stuttgart',
        'team2' => 'Hannover',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Dresden',
        'team2' => 'Nurnberg',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'bts' => ['ht_yes' => '74.1%', 'ht_no' => '48.6%', 'ft_yes' => '74.1%', 'ft_no' => '48.6%'],
        'goals_ht' => ['u05' => '74.1%', 'u15' => '48.6%', 'u25' => '74.1%', 'o05' => '48.6%', 'o15' => '74.1%', 'o25' => '48.6%'],
        'goals_ft' => ['u15' => '74.1%', 'u25' => '48.6%', 'u35' => '74.1%', 'o15' => '48.6%', 'o25' => '74.1%', 'o35' => '48.6%'],
        'bookmaker' => ['u25' => '1.95', 'o25' => '3.86'],
        'true_odds' => ['u25' => '1.95', 'o25' => '3.86']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'bts' => ['ht_yes' => '51.0%', 'ht_no' => '86.5%', 'ft_yes' => '51.0%', 'ft_no' => '75.9%'],
        'goals_ht' => ['u05' => '63.3%', 'u15' => '63.3%', 'u25' => '63.3%', 'o05' => '74.1%', 'o15' => '63.3%', 'o25' => '70.9%'],
        'goals_ft' => ['u15' => '78.6%', 'u25' => '86.3%', 'u35' => '94.0%', 'o15' => '94.0%', 'o25' => '94.0%', 'o35' => '94.0%'],
        'bookmaker' => ['u25' => '4.15', 'o25' => '2.04'],
        'true_odds' => ['u25' => '4.15', 'o25' => '2.04']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'bts' => ['ht_yes' => '79.7%', 'ht_no' => '53.8%', 'ft_yes' => '79.7%', 'ft_no' => '43.9%'],
        'goals_ht' => ['u05' => '51.0%', 'u15' => '86.5%', 'u25' => '75.9%', 'o05' => '86.0%', 'o15' => '32.0%', 'o25' => '86.0%'],
        'goals_ft' => ['u15' => '86.0%', 'u25' => '86.0%', 'u35' => '86.0%', 'o15' => '86.0%', 'o25' => '86.0%', 'o35' => '86.0%'],
        'bookmaker' => ['u25' => '2.27', 'o25' => '3.40'],
        'true_odds' => ['u25' => '2.27', 'o25' => '3.40']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'bts' => ['ht_yes' => '79.7%', 'ht_no' => '53.8%', 'ft_yes' => '79.7%', 'ft_no' => '43.9%'],
        'goals_ht' => ['u05' => '70.6%', 'u15' => '69.5%', 'u25' => '48.6%', 'o05' => '50.0%', 'o15' => '73.3%', 'o25' => '50.0%'],
        'goals_ft' => ['u15' => '86.0%', 'u25' => '75.9%', 'u35' => '86.0%', 'o15' => '32.0%', 'o25' => '86.0%', 'o35' => '86.0%'],
        'bookmaker' => ['u25' => '1.78', 'o25' => '4.93'],
        'true_odds' => ['u25' => '1.78', 'o25' => '4.93']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'bts' => ['ht_yes' => '63.3%', 'ht_no' => '74.1%', 'ft_yes' => '63.3%', 'ft_no' => '70.9%'],
        'goals_ht' => ['u05' => '70.2%', 'u15' => '37.5%', 'u25' => '70.2%', 'o05' => '54.1%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'goals_ft' => ['u15' => '79.7%', 'u25' => '43.9%', 'u35' => '50.0%', 'o15' => '73.3%', 'o25' => '50.0%', 'o35' => '86.0%'],
        'bookmaker' => ['u25' => '3.44', 'o25' => '2.72'],
        'true_odds' => ['u25' => '3.44', 'o25' => '2.72']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'bts' => ['ht_yes' => '68.1%', 'ht_no' => '51.0%', 'ft_yes' => '68.1%', 'ft_no' => '58.6%'],
        'goals_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '43.9%', 'o25' => '35.8%'],
        'goals_ft' => ['u15' => '27.6%', 'u25' => '19.5%', 'u35' => '86.5%', 'o15' => '51.0%', 'o25' => '75.9%', 'o35' => '75.9%'],
        'bookmaker' => ['u25' => '2.42', 'o25' => '3.13'],
        'true_odds' => ['u25' => '2.42', 'o25' => '3.13']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Nancy',
        'team2' => 'Orleans',
        'bts' => ['ht_yes' => '74.1%', 'ht_no' => '56.2%', 'ft_yes' => '68.8%', 'ft_no' => '56.2%'],
        'goals_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'goals_ft' => ['u15' => '32.0%', 'u25' => '86.0%', 'u35' => '37.5%', 'o15' => '70.2%', 'o25' => '54.1%', 'o35' => '54.1%'],
        'bookmaker' => ['u25' => '2.29', 'o25' => '3.21'],
        'true_odds' => ['u25' => '2.29', 'o25' => '3.21']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Niort',
        'team2' => 'Troyes',
        'bts' => ['ht_yes' => '57.2%', 'ht_no' => '89.2%', 'ft_yes' => '35.4%', 'ft_no' => '89.2%'],
        'goals_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'goals_ft' => ['u15' => '32.0%', 'u25' => '86.0%', 'u35' => '37.5%', 'o15' => '70.2%', 'o25' => '54.1%', 'o35' => '54.1%'],
        'bookmaker' => ['u25' => '9.27', 'o25' => '1.55'],
        'true_odds' => ['u25' => '9.27', 'o25' => '1.55']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Rodez',
        'team2' => 'Auxerre',
        'bts' => ['ht_yes' => '-', 'ht_no' => '-', 'ft_yes' => '-', 'ft_no' => '-'],
        'goals_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'goals_ft' => ['u15' => '-', 'u25' => '-', 'u35' => '-', 'o15' => '-', 'o25' => '-', 'o35' => '-'],
        'bookmaker' => ['u25' => '-', 'o25' => '-'],
        'true_odds' => ['u25' => '-', 'o25' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Sochaux',
        'team2' => 'Caen',
        'bts' => ['ht_yes' => '35.7%', 'ht_no' => '89.2%', 'ft_yes' => '89.2%', 'ft_no' => '89.2%'],
        'goals_ht' => ['u05' => '33.4%', 'u15' => '57.2%', 'u25' => '69.4%', 'o05' => '57.2%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'goals_ft' => ['u15' => '20.4%', 'u25' => '63.3%', 'u35' => '74.1%', 'o15' => '63.3%', 'o25' => '70.9%', 'o35' => '70.9%'],
        'bookmaker' => ['u25' => '1.78', 'o25' => '4.93'],
        'true_odds' => ['u25' => '1.78', 'o25' => '4.93']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '27-07-2019',
        'team1' => 'Le Mans',
        'team2' => 'Lens',
        'bts' => ['ht_yes' => '35.7%', 'ht_no' => '89.2%', 'ft_yes' => '89.2%', 'ft_no' => '89.2%'],
        'goals_ht' => ['u05' => '75.9%', 'u15' => '86.0%', 'u25' => '32.0%', 'o05' => '86.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'goals_ft' => ['u15' => '20.4%', 'u25' => '63.3%', 'u35' => '74.1%', 'o15' => '63.3%', 'o25' => '70.9%', 'o35' => '70.9%'],
        'bookmaker' => ['u25' => '3.44', 'o25' => '2.72'],
        'true_odds' => ['u25' => '3.44', 'o25' => '2.72']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '29-07-2019',
        'team1' => 'Lorient',
        'team2' => 'Paris FC',
        'bts' => ['ht_yes' => '65.2%', 'ht_no' => '65.8%', 'ft_yes' => '65.2%', 'ft_no' => '65.8%'],
        'goals_ht' => ['u05' => '48.6%', 'u15' => '50.0%', 'u25' => '73.3%', 'o05' => '50.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'goals_ft' => ['u15' => '20.4%', 'u25' => '63.3%', 'u35' => '74.1%', 'o15' => '63.3%', 'o25' => '70.9%', 'o35' => '70.9%'],
        'bookmaker' => ['u25' => '2.92', 'o25' => '2.88'],
        'true_odds' => ['u25' => '2.92', 'o25' => '2.88']
    ]
];
}

include 'includes/app-header.php';
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <h4 class="mb-0">Goals Statistics</h4>
              <?php include 'includes/statistics-filter-modal.php'; ?>
            </div>

            <!-- Goals Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* Table styling matching 1x2.php */
                  .goals-table {
                    border-collapse: collapse !important;
                  }

                  .goals-table th, .goals-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.5rem;
                    border: 1px solid var(--table-border) !important;
                  }

                  /* Sticky header row for vertical scrolling - only 3rd row */
                  .goals-table thead tr:nth-child(3) {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                  }

                  /* Sticky columns for horizontal scrolling - only team columns (3 & 4) */
                  .goals-table th:nth-child(3),
                  .goals-table td:nth-child(3) {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    background-color: inherit;
                    border-right: 1px solid var(--table-border) !important;
                  }

                  .goals-table th:nth-child(4),
                  .goals-table td:nth-child(4) {
                    position: sticky;
                    left: 120px;
                    z-index: 5;
                    background-color: inherit;
                  }

                  /* Higher z-index for sticky header cells that are also in sticky columns */
                  .goals-table thead th:nth-child(3),
                  .goals-table thead th:nth-child(4) {
                    z-index: 15;
                  }

                  /* Exterior borders 2px */
                  .goals-table thead tr:first-child th {
                    border-top-width: 2px !important;
                  }
                  .goals-table tbody tr:last-child td {
                    border-bottom-width: 2px !important;
                  }
                  .goals-table th:first-child,
                  .goals-table td:first-child {
                    border-left-width: 2px !important;
                  }
                  .goals-table th:last-child,
                  .goals-table td:last-child {
                    border-right-width: 2px !important;
                  }

                  /* Team columns - Column 3 and 4 */
                  .goals-table th:nth-child(3),
                  .goals-table td:nth-child(3) {
                    border-left-width: 2px !important;
                  }
                  .goals-table th:nth-child(4),
                  .goals-table td:nth-child(4) {
                    border-right-width: 2px !important;
                  }

                  /* BTS sections - thicker borders */
                  .goals-table td:nth-child(5),
                  .goals-table thead tr:nth-child(2) th:nth-child(5),
                  .goals-table thead tr:nth-child(3) th:nth-child(3) {
                    border-left-width: 2px !important;
                  }

                  .goals-table td:nth-child(8),
                  .goals-table thead tr:nth-child(2) th:nth-child(6),
                  .goals-table thead tr:nth-child(3) th:nth-child(6) {
                    border-right-width: 2px !important;
                  }

                  /* Goals Half Time section */
                  .goals-table td:nth-child(9),
                  .goals-table thead tr:nth-child(1) th:nth-child(9),
                  .goals-table thead tr:nth-child(2) th:nth-child(7) {
                    border-left-width: 2px !important;
                  }

                  .goals-table td:nth-child(14),
                  .goals-table thead tr:nth-child(1) th:nth-child(14),
                  .goals-table thead tr:nth-child(2) th:nth-child(12) {
                    border-right-width: 2px !important;
                  }

                  /* Goals Full Time section */
                  .goals-table td:nth-child(15),
                  .goals-table thead tr:nth-child(1) th:nth-child(15),
                  .goals-table thead tr:nth-child(2) th:nth-child(13) {
                    border-left-width: 2px !important;
                  }

                  .goals-table td:nth-child(20),
                  .goals-table thead tr:nth-child(1) th:nth-child(20),
                  .goals-table thead tr:nth-child(2) th:nth-child(18) {
                    border-right-width: 2px !important;
                  }

                  /* Bookmaker Odds section */
                  .goals-table td:nth-child(21),
                  .goals-table thead tr:nth-child(1) th:nth-child(21),
                  .goals-table thead tr:nth-child(2) th:nth-child(19) {
                    border-left-width: 2px !important;
                  }

                  .goals-table td:nth-child(22),
                  .goals-table thead tr:nth-child(1) th:nth-child(22),
                  .goals-table thead tr:nth-child(2) th:nth-child(20) {
                    border-right-width: 2px !important;
                  }

                  /* True Odds section */
                  .goals-table td:nth-child(23),
                  .goals-table thead tr:nth-child(1) th:nth-child(23),
                  .goals-table thead tr:nth-child(2) th:nth-child(21) {
                    border-left-width: 2px !important;
                  }

                  .goals-table td:nth-child(24),
                  .goals-table thead tr:nth-child(1) th:nth-child(24),
                  .goals-table thead tr:nth-child(2) th:nth-child(22) {
                    border-right-width: 2px !important;
                  }

                  .league-col {
                    min-width: 180px;
                  }

                  .date-col {
                    min-width: 100px;
                  }

                  .team-col {
                    min-width: 120px;
                  }

                  .data-col {
                    min-width: 60px;
                    font-size: 0.85rem;
                  }

                  /* Alternating row colors - Green theme */
                  .goals-table tbody tr:nth-child(odd) {
                    background-color: var(--table-row-odd);
                  }
                  .goals-table tbody tr:nth-child(even) {
                    background-color: var(--table-row-even);
                  }
                  .goals-table tbody tr:hover {
                    background-color: var(--table-row-hover);
                    transition: background-color 0.2s ease;
                  }

                  /* Background colors for sticky cells to match row colors */
                  .goals-table tbody tr:nth-child(odd) td:nth-child(3),
                  .goals-table tbody tr:nth-child(odd) td:nth-child(4) {
                    background-color: var(--table-row-odd);
                  }

                  .goals-table tbody tr:nth-child(even) td:nth-child(3),
                  .goals-table tbody tr:nth-child(even) td:nth-child(4) {
                    background-color: var(--table-row-even);
                  }

                  .goals-table tbody tr:hover td:nth-child(3),
                  .goals-table tbody tr:hover td:nth-child(4) {
                    background-color: var(--table-row-hover);
                  }

                  /* Ensure sticky header cells maintain their background colors */
                  .goals-table thead tr:nth-child(1) th:nth-child(3),
                  .goals-table thead tr:nth-child(1) th:nth-child(4) {
                    background-color: var(--brand-primary-darker);
                  }

                  .goals-table thead tr:nth-child(2) th:nth-child(3),
                  .goals-table thead tr:nth-child(2) th:nth-child(4) {
                    background-color: var(--brand-primary);
                  }

                  .goals-table thead tr:nth-child(3) th:nth-child(3),
                  .goals-table thead tr:nth-child(3) th:nth-child(4) {
                    background-color: var(--brand-primary-light);
                  }

                  /* Force white text for all header rows */
                  .goals-table thead th {
                    color: #FFFFFF !important;
                  }
                </style>

                <div class="table-responsive">
                  <table class="table table-sm goals-table">
                    <thead>
                      <tr style="background-color: var(--brand-primary-darker); color: white; font-weight: 700;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="4" class="text-center">BTS</th>
                        <th colspan="16" class="text-center">GOALS</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary); color: white; font-weight: 600;">
                        <th colspan="2" class="text-center">Half Time</th>
                        <th colspan="2" class="text-center">Full Time</th>
                        <th colspan="6" class="text-center">Half Time</th>
                        <th colspan="6" class="text-center">Full Time</th>
                        <th colspan="2" class="text-center">BOOKMAKER ODDS</th>
                        <th colspan="2" class="text-center">TRUE ODDS</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary-light); color: white; font-weight: 500;">
                        <th class="data-col">YES</th>
                        <th class="data-col">NO</th>
                        <th class="data-col">YES</th>
                        <th class="data-col">NO</th>
                        <th class="data-col">U 0.5</th>
                        <th class="data-col">U 1.5</th>
                        <th class="data-col">U 2.5</th>
                        <th class="data-col">O 0.5</th>
                        <th class="data-col">O 1.5</th>
                        <th class="data-col">O 2.5</th>
                        <th class="data-col">U 1.5</th>
                        <th class="data-col">U 2.5</th>
                        <th class="data-col">U 3.5</th>
                        <th class="data-col">O 1.5</th>
                        <th class="data-col">O 2.5</th>
                        <th class="data-col">O 3.5</th>
                        <th class="data-col">U 2.5</th>
                        <th class="data-col">O 2.5</th>
                        <th class="data-col">U 2.5</th>
                        <th class="data-col">O 2.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($accessDenied): ?>
                        <tr>
                          <td colspan="26" class="text-center py-5">
                            <div class="alert alert-warning" role="alert">
                              <h4 class="alert-heading"><i class="bx bx-lock-alt me-2"></i>Premium Feature</h4>
                              <p class="mb-2">Goals Statistics are available for <strong>Starter plan</strong> and above.</p>
                              <p class="mb-0">
                                <a href="plans.php" class="btn btn-warning">
                                  <i class="bx bx-rocket me-1"></i>Upgrade Now
                                </a>
                              </p>
                            </div>
                          </td>
                        </tr>
                      <?php else: ?>
                      <?php foreach ($goalsData as $match): ?>
                        <tr>
                          <td class="league-col"><?php echo htmlspecialchars($match['league']); ?></td>
                          <td class="date-col"><?php echo htmlspecialchars($match['date']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team1']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team2']); ?></td>

                          <!-- BTS -->
                          <td class="data-col"><?php echo htmlspecialchars($match['bts']['ht_yes']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['bts']['ht_no']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['bts']['ft_yes']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['bts']['ft_no']); ?></td>

                          <!-- Goals Half Time -->
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ht']['u05']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ht']['u15']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ht']['u25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ht']['o05']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ht']['o15']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ht']['o25']); ?></td>

                          <!-- Goals Full Time -->
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ft']['u15']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ft']['u25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ft']['u35']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ft']['o15']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ft']['o25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['goals_ft']['o35']); ?></td>

                          <!-- Bookmaker Odds -->
                          <td class="data-col"><?php echo htmlspecialchars($match['bookmaker']['u25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['bookmaker']['o25']); ?></td>

                          <!-- True Odds -->
                          <td class="data-col"><?php echo htmlspecialchars($match['true_odds']['u25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['true_odds']['o25']); ?></td>
                        </tr>
                      <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          <!-- / Content -->

<?php include 'includes/app-footer.php'; ?>
