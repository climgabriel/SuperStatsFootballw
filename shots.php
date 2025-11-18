<?php
require_once 'config.php';

$pageTitle = "Shots Statistics - Super Stats Football";
$pageDescription = "Shots on Target Statistics";
$activePage = "shots";

// Include API helper and authentication
require_once 'includes/api-helper.php';
require_once 'includes/auth-middleware.php';

// Get filter parameters from URL
$leagueFilter = isset($_GET['leagues']) ? explode(',', $_GET['leagues']) : null;
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : null;
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : null;

// Calculate days ahead from date range
$daysAhead = 7; // Default
if ($dateTo) {
    $daysAhead = max(1, ceil((strtotime($dateTo) - time()) / 86400));
}

// Fetch data from backend API
$apiResponse = getShotsStatistics($daysAhead, $leagueFilter, 50, 0);

// Process API response
$shotsData = [];
if ($apiResponse['success'] && isset($apiResponse['data']['fixtures'])) {
    // Transform backend data to match our table format
    foreach ($apiResponse['data']['fixtures'] as $fixture) {
        $shotsData[] = [
            'league' => $fixture['league_name'] ?? '-',
            'date' => isset($fixture['fixture_date']) ? date('d-m-Y', strtotime($fixture['fixture_date'])) : '-',
            'team1' => $fixture['home_team'] ?? '-',
            'team2' => $fixture['away_team'] ?? '-',
            'total_shots_ft' => [
                '1' => isset($fixture['total_shots_ft_1']) ? round($fixture['total_shots_ft_1'] * 100, 1) . '%' : '-',
                'x' => isset($fixture['total_shots_ft_x']) ? round($fixture['total_shots_ft_x'] * 100, 1) . '%' : '-',
                '2' => isset($fixture['total_shots_ft_2']) ? round($fixture['total_shots_ft_2'] * 100, 1) . '%' : '-'
            ],
            'sot_ft' => [
                '1' => isset($fixture['sot_ft_1']) ? round($fixture['sot_ft_1'] * 100, 1) . '%' : '-',
                'x' => isset($fixture['sot_ft_x']) ? round($fixture['sot_ft_x'] * 100, 1) . '%' : '-',
                '2' => isset($fixture['sot_ft_2']) ? round($fixture['sot_ft_2'] * 100, 1) . '%' : '-'
            ],
            'total_shots_ht' => [
                'u95' => isset($fixture['total_shots_ht_u95']) ? round($fixture['total_shots_ht_u95'] * 100, 1) . '%' : '-',
                'u105' => isset($fixture['total_shots_ht_u105']) ? round($fixture['total_shots_ht_u105'] * 100, 1) . '%' : '-',
                'u115' => isset($fixture['total_shots_ht_u115']) ? round($fixture['total_shots_ht_u115'] * 100, 1) . '%' : '-',
                'u125' => isset($fixture['total_shots_ht_u125']) ? round($fixture['total_shots_ht_u125'] * 100, 1) . '%' : '-',
                'o95' => isset($fixture['total_shots_ht_o95']) ? round($fixture['total_shots_ht_o95'] * 100, 1) . '%' : '-',
                'o105' => isset($fixture['total_shots_ht_o105']) ? round($fixture['total_shots_ht_o105'] * 100, 1) . '%' : '-',
                'o115' => isset($fixture['total_shots_ht_o115']) ? round($fixture['total_shots_ht_o115'] * 100, 1) . '%' : '-',
                'o125' => isset($fixture['total_shots_ht_o125']) ? round($fixture['total_shots_ht_o125'] * 100, 1) . '%' : '-'
            ],
            'total_shots_ft_ou' => [
                'u225' => isset($fixture['total_shots_ft_u225']) ? round($fixture['total_shots_ft_u225'] * 100, 1) . '%' : '-',
                'u235' => isset($fixture['total_shots_ft_u235']) ? round($fixture['total_shots_ft_u235'] * 100, 1) . '%' : '-',
                'u245' => isset($fixture['total_shots_ft_u245']) ? round($fixture['total_shots_ft_u245'] * 100, 1) . '%' : '-',
                'u255' => isset($fixture['total_shots_ft_u255']) ? round($fixture['total_shots_ft_u255'] * 100, 1) . '%' : '-',
                'u265' => isset($fixture['total_shots_ft_u265']) ? round($fixture['total_shots_ft_u265'] * 100, 1) . '%' : '-',
                'o225' => isset($fixture['total_shots_ft_o225']) ? round($fixture['total_shots_ft_o225'] * 100, 1) . '%' : '-',
                'o235' => isset($fixture['total_shots_ft_o235']) ? round($fixture['total_shots_ft_o235'] * 100, 1) . '%' : '-',
                'o245' => isset($fixture['total_shots_ft_o245']) ? round($fixture['total_shots_ft_o245'] * 100, 1) . '%' : '-',
                'o255' => isset($fixture['total_shots_ft_o255']) ? round($fixture['total_shots_ft_o255'] * 100, 1) . '%' : '-',
                'o265' => isset($fixture['total_shots_ft_o265']) ? round($fixture['total_shots_ft_o265'] * 100, 1) . '%' : '-'
            ],
            'sot_ht' => [
                'u25' => isset($fixture['sot_ht_u25']) ? round($fixture['sot_ht_u25'] * 100, 1) . '%' : '-',
                'u35' => isset($fixture['sot_ht_u35']) ? round($fixture['sot_ht_u35'] * 100, 1) . '%' : '-',
                'u45' => isset($fixture['sot_ht_u45']) ? round($fixture['sot_ht_u45'] * 100, 1) . '%' : '-',
                'o25' => isset($fixture['sot_ht_o25']) ? round($fixture['sot_ht_o25'] * 100, 1) . '%' : '-',
                'o35' => isset($fixture['sot_ht_o35']) ? round($fixture['sot_ht_o35'] * 100, 1) . '%' : '-',
                'o45' => isset($fixture['sot_ht_o45']) ? round($fixture['sot_ht_o45'] * 100, 1) . '%' : '-'
            ],
            'sot_ft_ou' => [
                'u65' => isset($fixture['sot_ft_u65']) ? round($fixture['sot_ft_u65'] * 100, 1) . '%' : '-',
                'u75' => isset($fixture['sot_ft_u75']) ? round($fixture['sot_ft_u75'] * 100, 1) . '%' : '-',
                'u85' => isset($fixture['sot_ft_u85']) ? round($fixture['sot_ft_u85'] * 100, 1) . '%' : '-',
                'u95' => isset($fixture['sot_ft_u95']) ? round($fixture['sot_ft_u95'] * 100, 1) . '%' : '-',
                'u105' => isset($fixture['sot_ft_u105']) ? round($fixture['sot_ft_u105'] * 100, 1) . '%' : '-',
                'o65' => isset($fixture['sot_ft_o65']) ? round($fixture['sot_ft_o65'] * 100, 1) . '%' : '-',
                'o75' => isset($fixture['sot_ft_o75']) ? round($fixture['sot_ft_o75'] * 100, 1) . '%' : '-',
                'o85' => isset($fixture['sot_ft_o85']) ? round($fixture['sot_ft_o85'] * 100, 1) . '%' : '-',
                'o95' => isset($fixture['sot_ft_o95']) ? round($fixture['sot_ft_o95'] * 100, 1) . '%' : '-',
                'o105' => isset($fixture['sot_ft_o105']) ? round($fixture['sot_ft_o105'] * 100, 1) . '%' : '-'
            ]
        ];
    }
}

// Fallback to sample data if API fails or returns no data
if (empty($shotsData)) {
    $shotsData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'total_shots_ft' => ['1' => '33.4%', 'x' => '57.2%', '2' => '69.4%'],
        'sot_ft' => ['1' => '57.2%', 'x' => '51.0%', '2' => '86.5%'],
        'total_shots_ht' => ['u95' => '51.0%', 'u105' => '75.9%', 'u115' => '79.7%', 'u125' => '86.0%', 'o95' => '86.0%', 'o105' => '53.8%', 'o115' => '79.7%', 'o125' => '86.0%'],
        'total_shots_ft_ou' => ['u225' => '86.0%', 'u235' => '43.9%', 'u245' => '75.9%', 'u255' => '79.7%', 'u265' => '86.0%', 'o225' => '86.0%', 'o235' => '53.8%', 'o245' => '79.7%', 'o255' => '86.0%', 'o265' => '43.9%'],
        'sot_ht' => ['u25' => '75.9%', 'u35' => '79.7%', 'u45' => '86.0%', 'o25' => '86.0%', 'o35' => '53.8%', 'o45' => '79.7%'],
        'sot_ft_ou' => ['u65' => '86.0%', 'u75' => '86.0%', 'u85' => '43.9%', 'u95' => '75.9%', 'u105' => '79.7%', 'o65' => '86.0%', 'o75' => '86.0%', 'o85' => '53.8%', 'o95' => '79.7%', 'o105' => '79.7%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'total_shots_ft' => ['1' => '75.9%', 'x' => '86.0%', '2' => '32.0%'],
        'sot_ft' => ['1' => '86.0%', 'x' => '70.6%', '2' => '69.5%'],
        'total_shots_ht' => ['u95' => '70.6%', 'u105' => '56.0%', 'u115' => '63.3%', 'u125' => '75.9%', 'o95' => '86.0%', 'o105' => '74.1%', 'o115' => '63.3%', 'o125' => '75.9%'],
        'total_shots_ft_ou' => ['u225' => '86.0%', 'u235' => '70.9%', 'u245' => '56.0%', 'u255' => '63.3%', 'u265' => '75.9%', 'o225' => '86.0%', 'o235' => '74.1%', 'o245' => '63.3%', 'o255' => '75.9%', 'o265' => '63.3%'],
        'sot_ht' => ['u25' => '56.0%', 'u35' => '63.3%', 'u45' => '75.9%', 'o25' => '86.0%', 'o35' => '74.1%', 'o45' => '63.3%'],
        'sot_ft_ou' => ['u65' => '75.9%', 'u75' => '86.0%', 'u85' => '70.9%', 'u95' => '56.0%', 'u105' => '63.3%', 'o65' => '75.9%', 'o75' => '86.0%', 'o85' => '74.1%', 'o95' => '63.3%', 'o105' => '63.3%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'total_shots_ft' => ['1' => '74.1%', 'x' => '48.6%', '2' => '74.1%'],
        'sot_ft' => ['1' => '48.6%', 'x' => '74.1%', '2' => '48.6%'],
        'total_shots_ht' => ['u95' => '74.1%', 'u105' => '48.6%', 'u115' => '74.1%', 'u125' => '48.6%', 'o95' => '74.1%', 'o105' => '48.6%', 'o115' => '74.1%', 'o125' => '48.6%'],
        'total_shots_ft_ou' => ['u225' => '74.1%', 'u235' => '48.6%', 'u245' => '48.6%', 'u255' => '74.1%', 'u265' => '48.6%', 'o225' => '74.1%', 'o235' => '48.6%', 'o245' => '74.1%', 'o255' => '48.6%', 'o265' => '48.6%'],
        'sot_ht' => ['u25' => '74.1%', 'u35' => '48.6%', 'u45' => '74.1%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '48.6%'],
        'sot_ft_ou' => ['u65' => '74.1%', 'u75' => '48.6%', 'u85' => '48.6%', 'u95' => '74.1%', 'u105' => '48.6%', 'o65' => '74.1%', 'o75' => '48.6%', 'o85' => '74.1%', 'o95' => '48.6%', 'o105' => '74.1%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'total_shots_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'sot_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'total_shots_ht' => ['u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-'],
        'total_shots_ft_ou' => ['u225' => '-', 'u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'o225' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-'],
        'sot_ht' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-'],
        'sot_ft_ou' => ['u65' => '-', 'u75' => '-', 'u85' => '-', 'u95' => '-', 'u105' => '-', 'o65' => '-', 'o75' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'total_shots_ft' => ['1' => '63.3%', 'x' => '63.3%', '2' => '63.3%'],
        'sot_ft' => ['1' => '74.1%', 'x' => '63.3%', '2' => '70.9%'],
        'total_shots_ht' => ['u95' => '78.6%', 'u105' => '86.3%', 'u115' => '94.0%', 'u125' => '48.6%', 'o95' => '74.1%', 'o105' => '94.0%', 'o115' => '94.0%', 'o125' => '48.6%'],
        'total_shots_ft_ou' => ['u225' => '74.1%', 'u235' => '94.0%', 'u245' => '86.3%', 'u255' => '94.0%', 'u265' => '48.6%', 'o225' => '74.1%', 'o235' => '94.0%', 'o245' => '94.0%', 'o255' => '48.6%', 'o265' => '74.1%'],
        'sot_ht' => ['u25' => '94.0%', 'u35' => '86.3%', 'u45' => '94.0%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '94.0%'],
        'sot_ft_ou' => ['u65' => '94.0%', 'u75' => '48.6%', 'u85' => '74.1%', 'u95' => '94.0%', 'u105' => '86.3%', 'o65' => '94.0%', 'o75' => '48.6%', 'o85' => '74.1%', 'o95' => '94.0%', 'o105' => '94.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'total_shots_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'sot_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'total_shots_ht' => ['u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-'],
        'total_shots_ft_ou' => ['u225' => '-', 'u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'o225' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-'],
        'sot_ht' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-'],
        'sot_ft_ou' => ['u65' => '-', 'u75' => '-', 'u85' => '-', 'u95' => '-', 'u105' => '-', 'o65' => '-', 'o75' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'total_shots_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'sot_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'total_shots_ht' => ['u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-'],
        'total_shots_ft_ou' => ['u225' => '-', 'u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'o225' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-'],
        'sot_ht' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-'],
        'sot_ft_ou' => ['u65' => '-', 'u75' => '-', 'u85' => '-', 'u95' => '-', 'u105' => '-', 'o65' => '-', 'o75' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'total_shots_ft' => ['1' => '51.0%', 'x' => '86.5%', '2' => '75.9%'],
        'sot_ft' => ['1' => '86.0%', 'x' => '32.0%', '2' => '86.0%'],
        'total_shots_ht' => ['u95' => '86.0%', 'u105' => '86.0%', 'u115' => '86.0%', 'u125' => '63.3%', 'o95' => '70.9%', 'o105' => '86.0%', 'o115' => '86.0%', 'o125' => '63.3%'],
        'total_shots_ft_ou' => ['u225' => '70.9%', 'u235' => '86.0%', 'u245' => '86.0%', 'u255' => '86.0%', 'u265' => '63.3%', 'o225' => '70.9%', 'o235' => '86.0%', 'o245' => '86.0%', 'o255' => '63.3%', 'o265' => '70.9%'],
        'sot_ht' => ['u25' => '86.0%', 'u35' => '86.0%', 'u45' => '86.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '86.0%'],
        'sot_ft_ou' => ['u65' => '86.0%', 'u75' => '63.3%', 'u85' => '70.9%', 'u95' => '86.0%', 'u105' => '86.0%', 'o65' => '86.0%', 'o75' => '63.3%', 'o85' => '70.9%', 'o95' => '86.0%', 'o105' => '86.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'total_shots_ft' => ['1' => '70.6%', 'x' => '69.5%', '2' => '48.6%'],
        'sot_ft' => ['1' => '50.0%', 'x' => '73.3%', '2' => '50.0%'],
        'total_shots_ht' => ['u95' => '86.0%', 'u105' => '75.9%', 'u115' => '86.0%', 'u125' => '63.3%', 'o95' => '70.9%', 'o105' => '32.0%', 'o115' => '86.0%', 'o125' => '63.3%'],
        'total_shots_ft_ou' => ['u225' => '70.9%', 'u235' => '86.0%', 'u245' => '75.9%', 'u255' => '86.0%', 'u265' => '63.3%', 'o225' => '70.9%', 'o235' => '32.0%', 'o245' => '86.0%', 'o255' => '63.3%', 'o265' => '70.9%'],
        'sot_ht' => ['u25' => '86.0%', 'u35' => '75.9%', 'u45' => '86.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '32.0%'],
        'sot_ft_ou' => ['u65' => '86.0%', 'u75' => '63.3%', 'u85' => '70.9%', 'u95' => '86.0%', 'u105' => '75.9%', 'o65' => '86.0%', 'o75' => '63.3%', 'o85' => '70.9%', 'o95' => '32.0%', 'o105' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'total_shots_ft' => ['1' => '70.2%', 'x' => '37.5%', '2' => '70.2%'],
        'sot_ft' => ['1' => '54.1%', 'x' => '79.7%', '2' => '53.8%'],
        'total_shots_ht' => ['u95' => '79.7%', 'u105' => '43.9%', 'u115' => '50.0%', 'u125' => '63.3%', 'o95' => '70.9%', 'o105' => '73.3%', 'o115' => '50.0%', 'o125' => '63.3%'],
        'total_shots_ft_ou' => ['u225' => '70.9%', 'u235' => '86.0%', 'u245' => '43.9%', 'u255' => '50.0%', 'u265' => '63.3%', 'o225' => '70.9%', 'o235' => '73.3%', 'o245' => '50.0%', 'o255' => '63.3%', 'o265' => '70.9%'],
        'sot_ht' => ['u25' => '86.0%', 'u35' => '43.9%', 'u45' => '50.0%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '73.3%'],
        'sot_ft_ou' => ['u65' => '50.0%', 'u75' => '63.3%', 'u85' => '70.9%', 'u95' => '86.0%', 'u105' => '43.9%', 'o65' => '50.0%', 'o75' => '63.3%', 'o85' => '70.9%', 'o95' => '73.3%', 'o105' => '50.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'total_shots_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'sot_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'total_shots_ht' => ['u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-'],
        'total_shots_ft_ou' => ['u225' => '-', 'u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'o225' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-'],
        'sot_ht' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-'],
        'sot_ft_ou' => ['u65' => '-', 'u75' => '-', 'u85' => '-', 'u95' => '-', 'u105' => '-', 'o65' => '-', 'o75' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'total_shots_ft' => ['1' => '79.7%', 'x' => '53.8%', '2' => '79.7%'],
        'sot_ft' => ['1' => '43.9%', 'x' => '43.9%', '2' => '35.8%'],
        'total_shots_ht' => ['u95' => '27.6%', 'u105' => '19.5%', 'u115' => '86.5%', 'u125' => '63.3%', 'o95' => '70.9%', 'o105' => '51.0%', 'o115' => '75.9%', 'o125' => '63.3%'],
        'total_shots_ft_ou' => ['u225' => '70.9%', 'u235' => '75.9%', 'u245' => '19.5%', 'u255' => '86.5%', 'u265' => '63.3%', 'o225' => '70.9%', 'o235' => '51.0%', 'o245' => '75.9%', 'o255' => '63.3%', 'o265' => '70.9%'],
        'sot_ht' => ['u25' => '75.9%', 'u35' => '19.5%', 'u45' => '86.5%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '51.0%'],
        'sot_ft_ou' => ['u65' => '75.9%', 'u75' => '63.3%', 'u85' => '70.9%', 'u95' => '75.9%', 'u105' => '19.5%', 'o65' => '86.5%', 'o75' => '63.3%', 'o85' => '70.9%', 'o95' => '51.0%', 'o105' => '75.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'total_shots_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'sot_ft' => ['1' => '-', 'x' => '-', '2' => '-'],
        'total_shots_ht' => ['u95' => '-', 'u105' => '-', 'u115' => '-', 'u125' => '-', 'o95' => '-', 'o105' => '-', 'o115' => '-', 'o125' => '-'],
        'total_shots_ft_ou' => ['u225' => '-', 'u235' => '-', 'u245' => '-', 'u255' => '-', 'u265' => '-', 'o225' => '-', 'o235' => '-', 'o245' => '-', 'o255' => '-', 'o265' => '-'],
        'sot_ht' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-'],
        'sot_ft_ou' => ['u65' => '-', 'u75' => '-', 'u85' => '-', 'u95' => '-', 'u105' => '-', 'o65' => '-', 'o75' => '-', 'o85' => '-', 'o95' => '-', 'o105' => '-']
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
              <h4 class="mb-0">Shots Statistics</h4>
              <?php include 'includes/statistics-filter-modal.php'; ?>
            </div>

            <!-- Shots Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* Table styling matching other statistics pages */
                  .shots-table {
                    border-collapse: collapse !important;
                    font-size: 0.75rem;
                  }

                  .shots-table th, .shots-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.4rem 0.3rem;
                    border: 1px solid var(--table-border) !important;
                  }

                  /* Sticky header row for vertical scrolling - only 3rd row */
                  .shots-table thead tr:nth-child(3) {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                  }

                  /* Sticky columns for horizontal scrolling - only team columns (3 & 4) */
                  .shots-table th:nth-child(3),
                  .shots-table td:nth-child(3) {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    background-color: inherit;
                    border-right: 1px solid var(--table-border) !important;
                  }

                  .shots-table th:nth-child(4),
                  .shots-table td:nth-child(4) {
                    position: sticky;
                    left: 120px;
                    z-index: 5;
                    background-color: inherit;
                  }

                  /* Higher z-index for sticky header cells that are also in sticky columns */
                  .shots-table thead th:nth-child(3),
                  .shots-table thead th:nth-child(4) {
                    z-index: 15;
                  }

                  /* Exterior borders 2px */
                  .shots-table thead tr:first-child th {
                    border-top-width: 2px !important;
                  }
                  .shots-table tbody tr:last-child td {
                    border-bottom-width: 2px !important;
                  }
                  .shots-table th:first-child,
                  .shots-table td:first-child {
                    border-left-width: 2px !important;
                  }
                  .shots-table th:last-child,
                  .shots-table td:last-child {
                    border-right-width: 2px !important;
                  }

                  /* Team columns - Column 3 and 4 */
                  .shots-table th:nth-child(3),
                  .shots-table td:nth-child(3) {
                    border-left-width: 2px !important;
                  }
                  .shots-table th:nth-child(4),
                  .shots-table td:nth-child(4) {
                    border-right-width: 2px !important;
                  }

                  /* Major section dividers */
                  .shots-table td:nth-child(5),
                  .shots-table thead tr th:nth-child(5) {
                    border-left-width: 2px !important;
                  }

                  .shots-table td:nth-child(8),
                  .shots-table thead tr th:nth-child(8) {
                    border-left-width: 2px !important;
                  }

                  .shots-table td:nth-child(11),
                  .shots-table thead tr th:nth-child(11) {
                    border-left-width: 2px !important;
                  }

                  /* Treat columns 15-18 (O 9.5 to O 12.5) as one big section with 2px borders */
                  .shots-table td:nth-child(15),
                  .shots-table thead tr th:nth-child(15) {
                    border-left-width: 2px !important;
                  }

                  .shots-table td:nth-child(18),
                  .shots-table thead tr th:nth-child(18) {
                    border-right-width: 2px !important;
                  }

                  /* Treat columns 24-28 (O 22.5 to O 26.5) as one big section with 2px borders */
                  .shots-table td:nth-child(24),
                  .shots-table thead tr th:nth-child(24) {
                    border-left-width: 2px !important;
                  }

                  .shots-table td:nth-child(28),
                  .shots-table thead tr th:nth-child(28) {
                    border-right-width: 2px !important;
                  }

                  /* Treat columns 29-31 (U 2.5, U 3.5, U 4.5) as one big section with 2px borders */
                  .shots-table td:nth-child(29),
                  .shots-table thead tr th:nth-child(29) {
                    border-left-width: 2px !important;
                  }

                  .shots-table td:nth-child(31),
                  .shots-table thead tr th:nth-child(31) {
                    border-right-width: 2px !important;
                  }

                  /* Treat columns 35-39 (U 6.5 to U 10.5) as one big section with 2px borders */
                  .shots-table td:nth-child(35),
                  .shots-table thead tr th:nth-child(35) {
                    border-left-width: 2px !important;
                  }

                  .shots-table td:nth-child(39),
                  .shots-table thead tr th:nth-child(39) {
                    border-right-width: 2px !important;
                  }

                  .league-col {
                    text-align: left !important;
                    min-width: 160px;
                  }

                  .date-col {
                    min-width: 90px;
                  }

                  .team-col {
                    min-width: 100px;
                  }

                  .data-col-sm {
                    min-width: 45px;
                    font-size: 0.7rem;
                  }

                  /* Alternating row colors - Green theme */
                  .shots-table tbody tr:nth-child(odd) {
                    background-color: var(--table-row-odd);
                  }
                  .shots-table tbody tr:nth-child(even) {
                    background-color: var(--table-row-even);
                  }
                  .shots-table tbody tr:hover {
                    background-color: var(--table-row-hover);
                    transition: background-color 0.2s ease;
                  }

                  /* Background colors for sticky cells to match row colors */
                  .shots-table tbody tr:nth-child(odd) td:nth-child(3),
                  .shots-table tbody tr:nth-child(odd) td:nth-child(4) {
                    background-color: var(--table-row-odd);
                  }

                  .shots-table tbody tr:nth-child(even) td:nth-child(3),
                  .shots-table tbody tr:nth-child(even) td:nth-child(4) {
                    background-color: var(--table-row-even);
                  }

                  .shots-table tbody tr:hover td:nth-child(3),
                  .shots-table tbody tr:hover td:nth-child(4) {
                    background-color: var(--table-row-hover);
                  }

                  /* Ensure sticky header cells maintain their background colors */
                  .shots-table thead tr:nth-child(1) th:nth-child(3),
                  .shots-table thead tr:nth-child(1) th:nth-child(4) {
                    background-color: var(--brand-primary-darker);
                  }

                  .shots-table thead tr:nth-child(2) th:nth-child(3),
                  .shots-table thead tr:nth-child(2) th:nth-child(4) {
                    background-color: var(--brand-primary);
                  }

                  .shots-table thead tr:nth-child(3) th:nth-child(3),
                  .shots-table thead tr:nth-child(3) th:nth-child(4) {
                    background-color: var(--brand-primary-light);
                  }

                  /* Force white text for all header rows */
                  .shots-table thead th {
                    color: #FFFFFF !important;
                  }
                </style>

                <div class="table-responsive">
                  <table class="table table-sm shots-table">
                    <thead>
                      <tr style="background-color: var(--brand-primary-darker); color: white; font-weight: 700;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="3" class="text-center">TOTAL SHOTS</th>
                        <th colspan="3" class="text-center">SHOTS ON TARGET</th>
                        <th colspan="18" class="text-center">TOTAL SHOTS</th>
                        <th colspan="16" class="text-center">SHOTS ON TARGET</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary); color: white; font-weight: 600;">
                        <th colspan="3" class="text-center">Full Time</th>
                        <th colspan="3" class="text-center">Full Time</th>
                        <th colspan="8" class="text-center">Half Time</th>
                        <th colspan="10" class="text-center">Full Time</th>
                        <th colspan="6" class="text-center">Half Time</th>
                        <th colspan="10" class="text-center">Full Time</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary-light); color: white; font-weight: 500;">
                        <th class="data-col-sm">1</th>
                        <th class="data-col-sm">X</th>
                        <th class="data-col-sm">2</th>
                        <th class="data-col-sm">1</th>
                        <th class="data-col-sm">X</th>
                        <th class="data-col-sm">2</th>
                        <th class="data-col-sm">U 9.5</th>
                        <th class="data-col-sm">U 10.5</th>
                        <th class="data-col-sm">U 11.5</th>
                        <th class="data-col-sm">U 12.5</th>
                        <th class="data-col-sm">O 9.5</th>
                        <th class="data-col-sm">O 10.5</th>
                        <th class="data-col-sm">O 11.5</th>
                        <th class="data-col-sm">O 12.5</th>
                        <th class="data-col-sm">U 22.5</th>
                        <th class="data-col-sm">U 23.5</th>
                        <th class="data-col-sm">U 24.5</th>
                        <th class="data-col-sm">U 25.5</th>
                        <th class="data-col-sm">U 26.5</th>
                        <th class="data-col-sm">O 22.5</th>
                        <th class="data-col-sm">O 23.5</th>
                        <th class="data-col-sm">O 24.5</th>
                        <th class="data-col-sm">O 25.5</th>
                        <th class="data-col-sm">O 26.5</th>
                        <th class="data-col-sm">U 2.5</th>
                        <th class="data-col-sm">U 3.5</th>
                        <th class="data-col-sm">U 4.5</th>
                        <th class="data-col-sm">O 2.5</th>
                        <th class="data-col-sm">O 3.5</th>
                        <th class="data-col-sm">O 4.5</th>
                        <th class="data-col-sm">U 6.5</th>
                        <th class="data-col-sm">U 7.5</th>
                        <th class="data-col-sm">U 8.5</th>
                        <th class="data-col-sm">U 9.5</th>
                        <th class="data-col-sm">U 10.5</th>
                        <th class="data-col-sm">O 6.5</th>
                        <th class="data-col-sm">O 7.5</th>
                        <th class="data-col-sm">O 8.5</th>
                        <th class="data-col-sm">O 9.5</th>
                        <th class="data-col-sm">O 10.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($shotsData as $match): ?>
                        <tr>
                          <td class="league-col"><?php echo htmlspecialchars($match['league']); ?></td>
                          <td class="date-col"><?php echo htmlspecialchars($match['date']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team1']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team2']); ?></td>

                          <!-- Total Shots Full Time -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft']['1']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft']['x']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft']['2']); ?></td>

                          <!-- Shots on Target Full Time -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft']['1']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft']['x']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft']['2']); ?></td>

                          <!-- Total Shots Half Time -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['u95']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['u105']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['u115']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['u125']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['o95']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['o105']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['o115']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ht']['o125']); ?></td>

                          <!-- Total Shots Full Time Over/Under -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['u225']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['u235']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['u245']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['u255']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['u265']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['o225']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['o235']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['o245']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['o255']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['total_shots_ft_ou']['o265']); ?></td>

                          <!-- Shots on Target Half Time -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ht']['u25']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ht']['u35']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ht']['u45']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ht']['o25']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ht']['o35']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ht']['o45']); ?></td>

                          <!-- Shots on Target Full Time Over/Under -->
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['u65']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['u75']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['u85']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['u95']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['u105']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['o65']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['o75']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['o85']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['o95']); ?></td>
                          <td class="data-col-sm"><?php echo htmlspecialchars($match['sot_ft_ou']['o105']); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <div class="alert alert-info mt-3">
                  <strong>Note:</strong> This table shows a sample of the shots statistics. Full implementation with all matches can be loaded from the database or API.
                </div>
              </div>
            </div>

          </div>
          <!-- / Content -->
<?php include 'includes/app-footer.php'; ?>
