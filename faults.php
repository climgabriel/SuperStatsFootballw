<?php
require_once 'config.php';

$pageTitle = "Faults Statistics - Super Stats Football";
$pageDescription = "Fouls and Faults Statistics";
$activePage = "faults";

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
$faultsData = [];
$error = null;

// Fetch data from backend API regardless of tier (backend enforces access)
$apiResponse = getFoulsStatistics($daysAhead, $leagueFilter, 50, 0);

if ($apiResponse['success'] && isset($apiResponse['data']['fixtures'])) {
    // Transform backend data to match our table format
    foreach ($apiResponse['data']['fixtures'] as $fixture) {
        $faultsData[] = [
            'league' => $fixture['league_name'] ?? '-',
            'date' => isset($fixture['fixture_date']) ? date('d-m-Y', strtotime($fixture['fixture_date'])) : '-',
            'team1' => $fixture['home_team'] ?? '-',
            'team2' => $fixture['away_team'] ?? '-',
            'faults_ht' => [
                'u85' => isset($fixture['faults_ht_u85']) ? round($fixture['faults_ht_u85'] * 100, 1) . '%' : '-',
                'u95' => isset($fixture['faults_ht_u95']) ? round($fixture['faults_ht_u95'] * 100, 1) . '%' : '-',
                'u105' => isset($fixture['faults_ht_u105']) ? round($fixture['faults_ht_u105'] * 100, 1) . '%' : '-',
                'o85' => isset($fixture['faults_ht_o85']) ? round($fixture['faults_ht_o85'] * 100, 1) . '%' : '-',
                'o95' => isset($fixture['faults_ht_o95']) ? round($fixture['faults_ht_o95'] * 100, 1) . '%' : '-',
                'o105' => isset($fixture['faults_ht_o105']) ? round($fixture['faults_ht_o105'] * 100, 1) . '%' : '-'
            ],
            'faults_ft' => [
                'u235' => isset($fixture['faults_ft_u235']) ? round($fixture['faults_ft_u235'] * 100, 1) . '%' : '-',
                'u245' => isset($fixture['faults_ft_u245']) ? round($fixture['faults_ft_u245'] * 100, 1) . '%' : '-',
                'u255' => isset($fixture['faults_ft_u255']) ? round($fixture['faults_ft_u255'] * 100, 1) . '%' : '-',
                'u265' => isset($fixture['faults_ft_u265']) ? round($fixture['faults_ft_u265'] * 100, 1) . '%' : '-',
                'u275' => isset($fixture['faults_ft_u275']) ? round($fixture['faults_ft_u275'] * 100, 1) . '%' : '-',
                'o235' => isset($fixture['faults_ft_o235']) ? round($fixture['faults_ft_o235'] * 100, 1) . '%' : '-',
                'o245' => isset($fixture['faults_ft_o245']) ? round($fixture['faults_ft_o245'] * 100, 1) . '%' : '-',
                'o255' => isset($fixture['faults_ft_o255']) ? round($fixture['faults_ft_o255'] * 100, 1) . '%' : '-',
                'o265' => isset($fixture['faults_ft_o265']) ? round($fixture['faults_ft_o265'] * 100, 1) . '%' : '-',
                'o275' => isset($fixture['faults_ft_o275']) ? round($fixture['faults_ft_o275'] * 100, 1) . '%' : '-'
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
if (empty($faultsData)) {
    $faultsData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'faults_ht' => ['u85' => '33.4%', 'u95' => '57.2%', 'u105' => '79.7%', 'o85' => '86.0%', 'o95' => '51.0%', 'o105' => '86.5%'],
        'faults_ft' => ['u235' => '51.0%', 'u245' => '75.9%', 'u255' => '79.7%', 'u265' => '75.9%', 'u275' => '79.7%', 'o235' => '86.0%', 'o245' => '86.0%', 'o255' => '86.0%', 'o265' => '86.0%', 'o275' => '43.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'faults_ht' => ['u85' => '75.9%', 'u95' => '86.0%', 'u105' => '63.3%', 'o85' => '75.9%', 'o95' => '70.6%', 'o105' => '69.5%'],
        'faults_ft' => ['u235' => '70.6%', 'u245' => '56.0%', 'u255' => '63.3%', 'u265' => '56.0%', 'u275' => '63.3%', 'o235' => '75.9%', 'o245' => '86.0%', 'o255' => '75.9%', 'o265' => '86.0%', 'o275' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'St Truiden',
        'team2' => 'Mouscron',
        'faults_ht' => ['u85' => '75.9%', 'u95' => '86.0%', 'u105' => '63.3%', 'o85' => '43.9%', 'o95' => '70.6%', 'o105' => '69.5%'],
        'faults_ft' => ['u235' => '70.6%', 'u245' => '56.0%', 'u255' => '63.3%', 'u265' => '56.0%', 'u275' => '63.3%', 'o235' => '43.9%', 'o245' => '50.0%', 'o255' => '43.9%', 'o265' => '50.0%', 'o275' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waasland-Beveren',
        'team2' => 'Club Brugge',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waregem',
        'team2' => 'Mechelen',
        'faults_ht' => ['u85' => '70.6%', 'u95' => '69.5%', 'u105' => '75.9%', 'o85' => '43.9%', 'o95' => '79.7%', 'o105' => '53.8%'],
        'faults_ft' => ['u235' => '79.7%', 'u245' => '43.9%', 'u255' => '75.9%', 'u265' => '43.9%', 'u275' => '75.9%', 'o235' => '43.9%', 'o245' => '75.9%', 'o255' => '43.9%', 'o265' => '75.9%', 'o275' => '86.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Anderlecht',
        'team2' => 'Oostende',
        'faults_ht' => ['u85' => '70.2%', 'u95' => '37.5%', 'u105' => '48.6%', 'o85' => '70.9%', 'o95' => '63.3%', 'o105' => '74.1%'],
        'faults_ft' => ['u235' => '63.3%', 'u245' => '70.9%', 'u255' => '48.6%', 'u265' => '70.9%', 'u275' => '48.6%', 'o235' => '70.9%', 'o245' => '48.6%', 'o255' => '70.9%', 'o265' => '48.6%', 'o275' => '50.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Charleroi',
        'team2' => 'Gent',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Eupen',
        'team2' => 'Antwerp',
        'faults_ht' => ['u85' => '79.7%', 'u95' => '53.8%', 'u105' => '48.6%', 'o85' => '57.2%', 'o95' => '33.4%', 'o105' => '57.2%'],
        'faults_ft' => ['u235' => '69.4%', 'u245' => '57.2%', 'u255' => '48.6%', 'u265' => '57.2%', 'u275' => '48.6%', 'o235' => '57.2%', 'o245' => '48.6%', 'o255' => '57.2%', 'o265' => '48.6%', 'o275' => '50.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '26-07-2019',
        'team1' => 'Stuttgart',
        'team2' => 'Hannover',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Dresden',
        'team2' => 'Nurnberg',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'faults_ht' => ['u85' => '74.1%', 'u95' => '48.6%', 'u105' => '74.1%', 'o85' => '48.6%', 'o95' => '74.1%', 'o105' => '48.6%'],
        'faults_ft' => ['u235' => '74.1%', 'u245' => '48.6%', 'u255' => '74.1%', 'u265' => '48.6%', 'u275' => '74.1%', 'o235' => '48.6%', 'o245' => '74.1%', 'o255' => '48.6%', 'o265' => '74.1%', 'o275' => '48.6%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'faults_ht' => ['u85' => '63.3%', 'u95' => '63.3%', 'u105' => '94.0%', 'o85' => '48.6%', 'o95' => '63.3%', 'o105' => '70.9%'],
        'faults_ft' => ['u235' => '78.6%', 'u245' => '86.3%', 'u255' => '94.0%', 'u265' => '86.3%', 'u275' => '94.0%', 'o235' => '48.6%', 'o245' => '74.1%', 'o255' => '48.6%', 'o265' => '74.1%', 'o275' => '94.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'faults_ht' => ['u85' => '51.0%', 'u95' => '86.5%', 'u105' => '86.0%', 'o85' => '63.3%', 'o95' => '32.0%', 'o105' => '86.0%'],
        'faults_ft' => ['u235' => '86.0%', 'u245' => '86.0%', 'u255' => '86.0%', 'u265' => '86.0%', 'u275' => '86.0%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '86.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'faults_ht' => ['u85' => '70.6%', 'u95' => '69.5%', 'u105' => '86.0%', 'o85' => '63.3%', 'o95' => '73.3%', 'o105' => '50.0%'],
        'faults_ft' => ['u235' => '86.0%', 'u245' => '75.9%', 'u255' => '86.0%', 'u265' => '75.9%', 'u275' => '86.0%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'faults_ht' => ['u85' => '70.2%', 'u95' => '37.5%', 'u105' => '50.0%', 'o85' => '63.3%', 'o95' => '79.7%', 'o105' => '53.8%'],
        'faults_ft' => ['u235' => '79.7%', 'u245' => '43.9%', 'u255' => '50.0%', 'u265' => '43.9%', 'u275' => '50.0%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'faults_ht' => ['u85' => '79.7%', 'u95' => '53.8%', 'u105' => '86.5%', 'o85' => '63.3%', 'o95' => '43.9%', 'o105' => '35.8%'],
        'faults_ft' => ['u235' => '27.6%', 'u245' => '19.5%', 'u255' => '86.5%', 'u265' => '19.5%', 'u275' => '86.5%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '75.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Nancy',
        'team2' => 'Orleans',
        'faults_ht' => ['u85' => '79.7%', 'u95' => '53.8%', 'u105' => '37.5%', 'o85' => '63.3%', 'o95' => '75.9%', 'o105' => '86.0%'],
        'faults_ft' => ['u235' => '32.0%', 'u245' => '86.0%', 'u255' => '37.5%', 'u265' => '86.0%', 'u275' => '37.5%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Niort',
        'team2' => 'Troyes',
        'faults_ht' => ['u85' => '79.7%', 'u95' => '53.8%', 'u105' => '37.5%', 'o85' => '63.3%', 'o95' => '75.9%', 'o105' => '86.0%'],
        'faults_ft' => ['u235' => '32.0%', 'u245' => '86.0%', 'u255' => '37.5%', 'u265' => '86.0%', 'u275' => '37.5%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Rodez',
        'team2' => 'Auxerre',
        'faults_ht' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-'],
        'faults_ft' => ['u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'u275' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-', 'o275' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Sochaux',
        'team2' => 'Caen',
        'faults_ht' => ['u85' => '33.4%', 'u95' => '57.2%', 'u105' => '74.1%', 'o85' => '63.3%', 'o95' => '44.9%', 'o105' => '32.7%'],
        'faults_ft' => ['u235' => '20.4%', 'u245' => '63.3%', 'u255' => '74.1%', 'u265' => '63.3%', 'u275' => '74.1%', 'o235' => '63.3%', 'o245' => '70.9%', 'o255' => '63.3%', 'o265' => '70.9%', 'o275' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '27-07-2019',
        'team1' => 'Le Mans',
        'team2' => 'Lens',
        'faults_ht' => ['u85' => '75.9%', 'u95' => '86.0%', 'u105' => '74.1%', 'o85' => '70.2%', 'o95' => '44.9%', 'o105' => '32.7%'],
        'faults_ft' => ['u235' => '20.4%', 'u245' => '63.3%', 'u255' => '74.1%', 'u265' => '63.3%', 'u275' => '74.1%', 'o235' => '70.2%', 'o245' => '54.1%', 'o255' => '70.2%', 'o265' => '54.1%', 'o275' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '29-07-2019',
        'team1' => 'Lorient',
        'team2' => 'Paris FC',
        'faults_ht' => ['u85' => '48.6%', 'u95' => '50.0%', 'u105' => '74.1%', 'o85' => '70.2%', 'o95' => '44.9%', 'o105' => '32.7%'],
        'faults_ft' => ['u235' => '20.4%', 'u245' => '63.3%', 'u255' => '74.1%', 'u265' => '63.3%', 'u275' => '74.1%', 'o235' => '70.2%', 'o245' => '54.1%', 'o255' => '70.2%', 'o265' => '54.1%', 'o275' => '70.9%']
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
              <h4 class="mb-0">
                <span class="text-muted fw-light">Statistics /</span> Faults Analysis
              </h4>
              <?php include 'includes/statistics-filter-modal.php'; ?>
            </div>

            <!-- Faults Statistics Table -->
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <style>
                    .faults-table {
                      border: 2px solid #dee2e6;
                      font-size: 0.85rem;
                    }
                    .faults-table thead th {
                      background-color: #106147;
                      color: white;
                      font-weight: 600;
                      text-align: center;
                      vertical-align: middle;
                      border: 1px solid #dee2e6;
                      padding: 0.75rem 0.5rem;
                      white-space: nowrap;
                    }
                    .faults-table thead tr:nth-child(2) th {
                      background-color: #1a8a6b;
                    }
                    .faults-table tbody td {
                      text-align: center;
                      vertical-align: middle;
                      border: 1px solid #dee2e6;
                      padding: 0.5rem 0.4rem;
                    }
                    .faults-table tbody td:first-child,
                    .faults-table tbody td:nth-child(2),
                    .faults-table tbody td:nth-child(3),
                    .faults-table tbody td:nth-child(4) {
                      text-align: left;
                      font-weight: 500;
                    }
                    .faults-table tbody tr:hover {
                      background-color: rgba(16, 97, 71, 0.05);
                    }
                    /* Section dividers */
                    .faults-table thead th:nth-child(4),
                    .faults-table tbody td:nth-child(4) {
                      border-right: 2px solid #dee2e6;
                    }
                    .faults-table thead th:nth-child(10),
                    .faults-table tbody td:nth-child(10) {
                      border-right: 2px solid #dee2e6;
                    }
                  </style>
                  <table class="table table-bordered faults-table">
                    <thead>
                      <tr>
                        <th rowspan="2">League</th>
                        <th rowspan="2">Date</th>
                        <th rowspan="2">Team 1</th>
                        <th rowspan="2">Team 2</th>
                        <th colspan="6">Faults Half Time</th>
                        <th colspan="10">Faults Full Time</th>
                      </tr>
                      <tr>
                        <th>U 8.5</th>
                        <th>U 9.5</th>
                        <th>U 10.5</th>
                        <th>O 8.5</th>
                        <th>O 9.5</th>
                        <th>O 10.5</th>
                        <th>U 23.5</th>
                        <th>U 24.5</th>
                        <th>U 25.5</th>
                        <th>U 26.5</th>
                        <th>U 27.5</th>
                        <th>O 23.5</th>
                        <th>O 24.5</th>
                        <th>O 25.5</th>
                        <th>O 26.5</th>
                        <th>O 27.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($accessDenied): ?>
                        <tr>
                          <td colspan="23" class="text-center py-5">
                            <div class="alert alert-warning" role="alert">
                              <h4 class="alert-heading"><i class="bx bx-lock-alt me-2"></i>Premium Feature</h4>
                              <p class="mb-2">Faults Statistics are available for <strong>Starter plan</strong> and above.</p>
                              <p class="mb-0">
                                <a href="plans.php" class="btn btn-warning">
                                  <i class="bx bx-rocket me-1"></i>Upgrade Now
                                </a>
                              </p>
                            </div>
                          </td>
                        </tr>
                      <?php else: ?>
                      <?php foreach ($faultsData as $match): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($match['league']); ?></td>
                        <td><?php echo htmlspecialchars($match['date']); ?></td>
                        <td><?php echo htmlspecialchars($match['team1']); ?></td>
                        <td><?php echo htmlspecialchars($match['team2']); ?></td>
                        <!-- Half Time -->
                        <td><?php echo htmlspecialchars($match['faults_ht']['u85']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ht']['u95']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ht']['u105']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ht']['o85']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ht']['o95']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ht']['o105']); ?></td>
                        <!-- Full Time -->
                        <td><?php echo htmlspecialchars($match['faults_ft']['u235']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['u245']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['u255']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['u265']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['u275']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['o235']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['o245']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['o255']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['o265']); ?></td>
                        <td><?php echo htmlspecialchars($match['faults_ft']['o275']); ?></td>
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
