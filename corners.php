<?php
require_once 'config.php';

$pageTitle = "Corners Statistics - Super Stats Football";
$pageDescription = "Corners Statistics and Analysis";
$activePage = "corners";

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
$cornersData = [];
$error = null;

// Fetch data from backend API (backend enforces access control)
$apiResponse = getCornersStatistics($daysAhead, $leagueFilter, 50, 0);

if ($apiResponse['success'] && isset($apiResponse['data']['fixtures'])) {
    // Transform backend data to match our table format
    foreach ($apiResponse['data']['fixtures'] as $fixture) {
        $cornersData[] = [
            'league' => $fixture['league_name'] ?? '-',
            'date' => isset($fixture['fixture_date']) ? date('d-m-Y', strtotime($fixture['fixture_date'])) : '-',
            'team1' => $fixture['home_team'] ?? '-',
            'team2' => $fixture['away_team'] ?? '-',
            'corners_ht' => [
                'u35' => isset($fixture['corners_ht_u35']) ? round($fixture['corners_ht_u35'] * 100, 1) . '%' : '-',
                'u45' => isset($fixture['corners_ht_u45']) ? round($fixture['corners_ht_u45'] * 100, 1) . '%' : '-',
                'u55' => isset($fixture['corners_ht_u55']) ? round($fixture['corners_ht_u55'] * 100, 1) . '%' : '-',
                'o35' => isset($fixture['corners_ht_o35']) ? round($fixture['corners_ht_o35'] * 100, 1) . '%' : '-',
                'o45' => isset($fixture['corners_ht_o45']) ? round($fixture['corners_ht_o45'] * 100, 1) . '%' : '-',
                'o55' => isset($fixture['corners_ht_o55']) ? round($fixture['corners_ht_o55'] * 100, 1) . '%' : '-'
            ],
            'corners_ft' => [
                'u85' => isset($fixture['corners_ft_u85']) ? round($fixture['corners_ft_u85'] * 100, 1) . '%' : '-',
                'u95' => isset($fixture['corners_ft_u95']) ? round($fixture['corners_ft_u95'] * 100, 1) . '%' : '-',
                'u105' => isset($fixture['corners_ft_u105']) ? round($fixture['corners_ft_u105'] * 100, 1) . '%' : '-',
                'u115' => isset($fixture['corners_ft_u115']) ? round($fixture['corners_ft_u115'] * 100, 1) . '%' : '-',
                'u125' => isset($fixture['corners_ft_u125']) ? round($fixture['corners_ft_u125'] * 100, 1) . '%' : '-',
                'o85' => isset($fixture['corners_ft_o85']) ? round($fixture['corners_ft_o85'] * 100, 1) . '%' : '-',
                'o95' => isset($fixture['corners_ft_o95']) ? round($fixture['corners_ft_o95'] * 100, 1) . '%' : '-',
                'o105' => isset($fixture['corners_ft_o105']) ? round($fixture['corners_ft_o105'] * 100, 1) . '%' : '-',
                'o115' => isset($fixture['corners_ft_o115']) ? round($fixture['corners_ft_o115'] * 100, 1) . '%' : '-',
                'o125' => isset($fixture['corners_ft_o125']) ? round($fixture['corners_ft_o125'] * 100, 1) . '%' : '-'
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
if (empty($cornersData)) {
    $cornersData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'corners_ht' => ['u35' => '33.4%', 'u45' => '57.2%', 'u55' => '69.4%', 'o35' => '57.2%', 'o45' => '51.0%', 'o55' => '86.5%'],
        'corners_ft' => ['u85' => '51.0%', 'u95' => '75.9%', 'u105' => '79.7%', 'u115' => '86.0%', 'u125' => '86.0%', 'o85' => '53.8%', 'o95' => '79.7%', 'o105' => '86.0%', 'o115' => '86.0%', 'o125' => '43.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'corners_ht' => ['u35' => '75.9%', 'u45' => '86.0%', 'u55' => '32.0%', 'o35' => '86.0%', 'o45' => '70.6%', 'o55' => '69.5%'],
        'corners_ft' => ['u85' => '70.6%', 'u95' => '56.0%', 'u105' => '63.3%', 'u115' => '75.9%', 'u125' => '86.0%', 'o85' => '74.1%', 'o95' => '63.3%', 'o105' => '75.9%', 'o115' => '86.0%', 'o125' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'St Truiden',
        'team2' => 'Mouscron',
        'corners_ht' => ['u35' => '75.9%', 'u45' => '86.0%', 'u55' => '32.0%', 'o35' => '86.0%', 'o45' => '70.6%', 'o55' => '69.5%'],
        'corners_ft' => ['u85' => '70.6%', 'u95' => '56.0%', 'u105' => '63.3%', 'u115' => '43.9%', 'u125' => '50.0%', 'o85' => '74.1%', 'o95' => '63.3%', 'o105' => '43.9%', 'o115' => '50.0%', 'o125' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waasland-Beveren',
        'team2' => 'Club Brugge',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waregem',
        'team2' => 'Mechelen',
        'corners_ht' => ['u35' => '70.6%', 'u45' => '69.5%', 'u55' => '70.6%', 'o35' => '56.0%', 'o45' => '79.7%', 'o55' => '53.8%'],
        'corners_ft' => ['u85' => '79.7%', 'u95' => '43.9%', 'u105' => '75.9%', 'u115' => '43.9%', 'u125' => '75.9%', 'o85' => '86.0%', 'o95' => '32.0%', 'o105' => '43.9%', 'o115' => '75.9%', 'o125' => '86.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Anderlecht',
        'team2' => 'Oostende',
        'corners_ht' => ['u35' => '70.2%', 'u45' => '37.5%', 'u55' => '70.2%', 'o35' => '54.1%', 'o45' => '63.3%', 'o55' => '74.1%'],
        'corners_ft' => ['u85' => '63.3%', 'u95' => '70.9%', 'u105' => '48.6%', 'u115' => '70.9%', 'u125' => '48.6%', 'o85' => '50.0%', 'o95' => '73.3%', 'o105' => '70.9%', 'o115' => '48.6%', 'o125' => '50.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Charleroi',
        'team2' => 'Gent',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Eupen',
        'team2' => 'Antwerp',
        'corners_ht' => ['u35' => '79.7%', 'u45' => '53.8%', 'u55' => '79.7%', 'o35' => '43.9%', 'o45' => '33.4%', 'o55' => '57.2%'],
        'corners_ft' => ['u85' => '69.4%', 'u95' => '57.2%', 'u105' => '48.6%', 'u115' => '57.2%', 'u125' => '48.6%', 'o85' => '50.0%', 'o95' => '73.3%', 'o105' => '57.2%', 'o115' => '48.6%', 'o125' => '50.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '26-07-2019',
        'team1' => 'Stuttgart',
        'team2' => 'Hannover',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Dresden',
        'team2' => 'Nurnberg',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'corners_ht' => ['u35' => '74.1%', 'u45' => '48.6%', 'u55' => '74.1%', 'o35' => '48.6%', 'o45' => '74.1%', 'o55' => '48.6%'],
        'corners_ft' => ['u85' => '74.1%', 'u95' => '48.6%', 'u105' => '74.1%', 'u115' => '48.6%', 'u125' => '74.1%', 'o85' => '48.6%', 'o95' => '74.1%', 'o105' => '48.6%', 'o115' => '74.1%', 'o125' => '48.6%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'corners_ht' => ['u35' => '63.3%', 'u45' => '63.3%', 'u55' => '63.3%', 'o35' => '74.1%', 'o45' => '63.3%', 'o55' => '70.9%'],
        'corners_ft' => ['u85' => '78.6%', 'u95' => '86.3%', 'u105' => '94.0%', 'u115' => '48.6%', 'u125' => '74.1%', 'o85' => '94.0%', 'o95' => '94.0%', 'o105' => '48.6%', 'o115' => '74.1%', 'o125' => '94.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'corners_ht' => ['u35' => '51.0%', 'u45' => '86.5%', 'u55' => '75.9%', 'o35' => '86.0%', 'o45' => '32.0%', 'o55' => '86.0%'],
        'corners_ft' => ['u85' => '86.0%', 'u95' => '86.0%', 'u105' => '86.0%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '86.0%', 'o95' => '86.0%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '86.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'corners_ht' => ['u35' => '70.6%', 'u45' => '69.5%', 'u55' => '48.6%', 'o35' => '50.0%', 'o45' => '73.3%', 'o55' => '50.0%'],
        'corners_ft' => ['u85' => '86.0%', 'u95' => '75.9%', 'u105' => '86.0%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '32.0%', 'o95' => '86.0%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'corners_ht' => ['u35' => '70.2%', 'u45' => '37.5%', 'u55' => '70.2%', 'o35' => '54.1%', 'o45' => '79.7%', 'o55' => '53.8%'],
        'corners_ft' => ['u85' => '79.7%', 'u95' => '43.9%', 'u105' => '50.0%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '73.3%', 'o95' => '50.0%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'corners_ht' => ['u35' => '79.7%', 'u45' => '53.8%', 'u55' => '79.7%', 'o35' => '43.9%', 'o45' => '43.9%', 'o55' => '35.8%'],
        'corners_ft' => ['u85' => '27.6%', 'u95' => '19.5%', 'u105' => '86.5%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '51.0%', 'o95' => '75.9%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '75.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Nancy',
        'team2' => 'Orleans',
        'corners_ht' => ['u35' => '79.7%', 'u45' => '53.8%', 'u55' => '79.7%', 'o35' => '43.9%', 'o45' => '75.9%', 'o55' => '86.0%'],
        'corners_ft' => ['u85' => '32.0%', 'u95' => '86.0%', 'u105' => '37.5%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '70.2%', 'o95' => '54.1%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Niort',
        'team2' => 'Troyes',
        'corners_ht' => ['u35' => '79.7%', 'u45' => '53.8%', 'u55' => '79.7%', 'o35' => '43.9%', 'o45' => '75.9%', 'o55' => '86.0%'],
        'corners_ft' => ['u85' => '32.0%', 'u95' => '86.0%', 'u105' => '37.5%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '70.2%', 'o95' => '54.1%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Rodez',
        'team2' => 'Auxerre',
        'corners_ht' => ['u35' => '-', 'u45' => '-', 'u55' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-'],
        'corners_ft' => ['u85' => '-', 'u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Sochaux',
        'team2' => 'Caen',
        'corners_ht' => ['u35' => '33.4%', 'u45' => '57.2%', 'u55' => '69.4%', 'o35' => '57.2%', 'o45' => '44.9%', 'o55' => '32.7%'],
        'corners_ft' => ['u85' => '20.4%', 'u95' => '63.3%', 'u105' => '74.1%', 'u115' => '63.3%', 'u125' => '70.9%', 'o85' => '63.3%', 'o95' => '70.9%', 'o105' => '63.3%', 'o115' => '70.9%', 'o125' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '27-07-2019',
        'team1' => 'Le Mans',
        'team2' => 'Lens',
        'corners_ht' => ['u35' => '75.9%', 'u45' => '86.0%', 'u55' => '32.0%', 'o35' => '86.0%', 'o45' => '44.9%', 'o55' => '32.7%'],
        'corners_ft' => ['u85' => '20.4%', 'u95' => '63.3%', 'u105' => '74.1%', 'u115' => '70.2%', 'u125' => '54.1%', 'o85' => '63.3%', 'o95' => '70.9%', 'o105' => '70.2%', 'o115' => '54.1%', 'o125' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '29-07-2019',
        'team1' => 'Lorient',
        'team2' => 'Paris FC',
        'corners_ht' => ['u35' => '48.6%', 'u45' => '50.0%', 'u55' => '73.3%', 'o35' => '50.0%', 'o45' => '44.9%', 'o55' => '32.7%'],
        'corners_ft' => ['u85' => '20.4%', 'u95' => '63.3%', 'u105' => '74.1%', 'u115' => '70.2%', 'u125' => '54.1%', 'o85' => '63.3%', 'o95' => '70.9%', 'o105' => '70.2%', 'o115' => '54.1%', 'o125' => '70.9%']
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
              <h4 class="mb-0">Corners Statistics</h4>
              <?php include 'includes/statistics-filter-modal.php'; ?>
            </div>

            <!-- Corners Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* Table styling matching 1x2.php and goals.php */
                  .corners-table {
                    border-collapse: collapse !important;
                  }

                  .corners-table th, .corners-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.5rem;
                    border: 1px solid var(--table-border) !important;
                  }

                  /* Sticky header row for vertical scrolling - only 3rd row */
                  .corners-table thead tr:nth-child(3) {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                  }

                  /* Sticky columns for horizontal scrolling - only team columns (3 & 4) */
                  .corners-table th:nth-child(3),
                  .corners-table td:nth-child(3) {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    background-color: inherit;
                    border-right: 1px solid var(--table-border) !important;
                  }

                  .corners-table th:nth-child(4),
                  .corners-table td:nth-child(4) {
                    position: sticky;
                    left: 120px;
                    z-index: 5;
                    background-color: inherit;
                  }

                  /* Higher z-index for sticky header cells that are also in sticky columns */
                  .corners-table thead th:nth-child(3),
                  .corners-table thead th:nth-child(4) {
                    z-index: 15;
                  }

                  /* Exterior borders 2px */
                  .corners-table thead tr:first-child th {
                    border-top-width: 2px !important;
                  }
                  .corners-table tbody tr:last-child td {
                    border-bottom-width: 2px !important;
                  }
                  .corners-table th:first-child,
                  .corners-table td:first-child {
                    border-left-width: 2px !important;
                  }
                  .corners-table th:last-child,
                  .corners-table td:last-child {
                    border-right-width: 2px !important;
                  }

                  /* Team columns - Column 3 and 4 */
                  .corners-table th:nth-child(3),
                  .corners-table td:nth-child(3) {
                    border-left-width: 2px !important;
                  }
                  .corners-table th:nth-child(4),
                  .corners-table td:nth-child(4) {
                    border-right-width: 2px !important;
                  }

                  /* Corners Half Time section - thicker borders */
                  .corners-table td:nth-child(5),
                  .corners-table thead tr:nth-child(1) th:nth-child(5),
                  .corners-table thead tr:nth-child(2) th:nth-child(3),
                  .corners-table thead tr:nth-child(3) th:nth-child(1) {
                    border-left: 2px solid var(--table-border) !important;
                  }

                  .corners-table td:nth-child(10),
                  .corners-table thead tr:nth-child(1) th:nth-child(10),
                  .corners-table thead tr:nth-child(2) th:nth-child(8),
                  .corners-table thead tr:nth-child(3) th:nth-child(6) {
                    border-right: 2px solid var(--table-border) !important;
                  }

                  /* Corners Full Time section - thicker borders */
                  .corners-table td:nth-child(11),
                  .corners-table thead tr:nth-child(1) th:nth-child(11),
                  .corners-table thead tr:nth-child(2) th:nth-child(9),
                  .corners-table thead tr:nth-child(3) th:nth-child(7) {
                    border-left: 2px solid var(--table-border) !important;
                  }

                  .corners-table td:nth-child(20),
                  .corners-table thead tr:nth-child(1) th:nth-child(20),
                  .corners-table thead tr:nth-child(2) th:nth-child(18),
                  .corners-table thead tr:nth-child(3) th:nth-child(16) {
                    border-right: 2px solid var(--table-border) !important;
                  }

                  .league-col {
                    text-align: left !important;
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
                  .corners-table tbody tr:nth-child(odd) {
                    background-color: var(--table-row-odd);
                  }
                  .corners-table tbody tr:nth-child(even) {
                    background-color: var(--table-row-even);
                  }
                  .corners-table tbody tr:hover {
                    background-color: var(--table-row-hover);
                    transition: background-color 0.2s ease;
                  }

                  /* Background colors for sticky cells to match row colors */
                  .corners-table tbody tr:nth-child(odd) td:nth-child(3),
                  .corners-table tbody tr:nth-child(odd) td:nth-child(4) {
                    background-color: var(--table-row-odd);
                  }

                  .corners-table tbody tr:nth-child(even) td:nth-child(3),
                  .corners-table tbody tr:nth-child(even) td:nth-child(4) {
                    background-color: var(--table-row-even);
                  }

                  .corners-table tbody tr:hover td:nth-child(3),
                  .corners-table tbody tr:hover td:nth-child(4) {
                    background-color: var(--table-row-hover);
                  }

                  /* Ensure sticky header cells maintain their background colors */
                  .corners-table thead tr:nth-child(1) th:nth-child(3),
                  .corners-table thead tr:nth-child(1) th:nth-child(4) {
                    background-color: var(--brand-primary-darker);
                  }

                  .corners-table thead tr:nth-child(2) th:nth-child(3),
                  .corners-table thead tr:nth-child(2) th:nth-child(4) {
                    background-color: var(--brand-primary);
                  }

                  .corners-table thead tr:nth-child(3) th:nth-child(3),
                  .corners-table thead tr:nth-child(3) th:nth-child(4) {
                    background-color: var(--brand-primary-light);
                  }

                  /* Force white text for all header rows */
                  .corners-table thead th {
                    color: #FFFFFF !important;
                  }

                  /* Force section borders - Separate O columns from U columns in Half Time */
                  /* Left border before O 3.5 (column 8 in table, 4th th in row 3) */
                  .corners-table thead tr:nth-child(3) th:nth-child(4) {
                    border-left: 2px solid var(--table-border) !important;
                  }
                  .corners-table tbody tr td:nth-child(8) {
                    border-left: 2px solid var(--table-border) !important;
                  }

                  /* Right border after O 5.5 (column 10 in table, 6th th in row 3) */
                  .corners-table thead tr:nth-child(3) th:nth-child(6) {
                    border-right: 2px solid var(--table-border) !important;
                  }
                  .corners-table tbody tr td:nth-child(10) {
                    border-right: 2px solid var(--table-border) !important;
                  }

                  /* Force section borders - Separate O columns from U columns in Full Time */
                  /* Left border before O 8.5 (column 16 in table, 12th th in row 3) */
                  .corners-table thead tr:nth-child(3) th:nth-child(12) {
                    border-left: 2px solid var(--table-border) !important;
                  }
                  .corners-table tbody tr td:nth-child(16) {
                    border-left: 2px solid var(--table-border) !important;
                  }

                  /* Right border after O 12.5 (column 20 in table, 16th th in row 3) */
                  .corners-table thead tr:nth-child(3) th:nth-child(16) {
                    border-right: 2px solid var(--table-border) !important;
                  }
                  .corners-table tbody tr td:nth-child(20) {
                    border-right: 2px solid var(--table-border) !important;
                  }
                </style>

                <div class="table-responsive">
                  <table class="table table-sm corners-table">
                    <thead>
                      <tr style="background-color: var(--brand-primary-darker); color: white; font-weight: 700;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="16" class="text-center">CORNERS</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary); color: white; font-weight: 600;">
                        <th colspan="6" class="text-center">Half Time</th>
                        <th colspan="10" class="text-center">Full Time</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary-light); color: white; font-weight: 500;">
                        <th class="data-col">U 3.5</th>
                        <th class="data-col">U 4.5</th>
                        <th class="data-col">U 5.5</th>
                        <th class="data-col">O 3.5</th>
                        <th class="data-col">O 4.5</th>
                        <th class="data-col">O 5.5</th>
                        <th class="data-col">U 8.5</th>
                        <th class="data-col">U 9.5</th>
                        <th class="data-col">U 10.5</th>
                        <th class="data-col">U 11.5</th>
                        <th class="data-col">U 12.5</th>
                        <th class="data-col">O 8.5</th>
                        <th class="data-col">O 9.5</th>
                        <th class="data-col">O 10.5</th>
                        <th class="data-col">O 11.5</th>
                        <th class="data-col">O 12.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($accessDenied): ?>
                        <tr>
                          <td colspan="20" class="text-center py-5">
                            <div class="alert alert-warning" role="alert">
                              <h4 class="alert-heading"><i class="bx bx-lock-alt me-2"></i>Premium Feature</h4>
                              <p class="mb-2">Corners Statistics are available for <strong>Starter plan</strong> and above.</p>
                              <p class="mb-0">
                                <a href="plans.php" class="btn btn-warning">
                                  <i class="bx bx-rocket me-1"></i>Upgrade Now
                                </a>
                              </p>
                            </div>
                          </td>
                        </tr>
                      <?php else: ?>
                      <?php foreach ($cornersData as $match): ?>
                        <tr>
                          <td class="league-col"><?php echo htmlspecialchars($match['league']); ?></td>
                          <td class="date-col"><?php echo htmlspecialchars($match['date']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team1']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team2']); ?></td>

                          <!-- Corners Half Time -->
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ht']['u35']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ht']['u45']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ht']['u55']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ht']['o35']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ht']['o45']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ht']['o55']); ?></td>

                          <!-- Corners Full Time -->
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['u85']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['u95']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['u105']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['u115']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['u125']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['o85']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['o95']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['o105']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['o115']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['corners_ft']['o125']); ?></td>
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
