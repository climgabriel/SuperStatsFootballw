<?php
$pageTitle = "Cards Statistics - Super Stats Football";
$pageDescription = "Yellow and Red Cards Statistics";
$activePage = "cards";
include 'includes/app-header.php';

// Sample cards data
$cardsData = [
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '26-07-2019',
        'team1' => 'Genk',
        'team2' => 'Kortrijk',
        'cards_ht' => ['u05' => '33.4%', 'u15' => '57.2%', 'u25' => '69.4%', 'o05' => '57.2%', 'o15' => '51.0%', 'o25' => '86.5%'],
        'cards_ft' => ['u25' => '51.0%', 'u35' => '75.9%', 'u45' => '79.7%', 'u55' => '86.0%', 'u65' => '86.0%', 'o25' => '53.8%', 'o35' => '79.7%', 'o45' => '86.0%', 'o55' => '86.0%', 'o65' => '43.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Cercle Brugge',
        'team2' => 'Standard',
        'cards_ht' => ['u05' => '75.9%', 'u15' => '86.0%', 'u25' => '32.0%', 'o05' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'cards_ft' => ['u25' => '70.6%', 'u35' => '56.0%', 'u45' => '63.3%', 'u55' => '75.9%', 'u65' => '86.0%', 'o25' => '74.1%', 'o35' => '63.3%', 'o45' => '75.9%', 'o55' => '86.0%', 'o65' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'St Truiden',
        'team2' => 'Mouscron',
        'cards_ht' => ['u05' => '75.9%', 'u15' => '86.0%', 'u25' => '32.0%', 'o05' => '86.0%', 'o15' => '70.6%', 'o25' => '69.5%'],
        'cards_ft' => ['u25' => '70.6%', 'u35' => '56.0%', 'u45' => '63.3%', 'u55' => '43.9%', 'u65' => '50.0%', 'o25' => '74.1%', 'o35' => '63.3%', 'o45' => '43.9%', 'o55' => '50.0%', 'o65' => '70.9%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waasland-Beveren',
        'team2' => 'Club Brugge',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '27-07-2019',
        'team1' => 'Waregem',
        'team2' => 'Mechelen',
        'cards_ht' => ['u05' => '70.6%', 'u15' => '69.5%', 'u25' => '70.6%', 'o05' => '56.0%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'cards_ft' => ['u25' => '79.7%', 'u35' => '43.9%', 'u45' => '75.9%', 'u55' => '43.9%', 'u65' => '75.9%', 'o25' => '86.0%', 'o35' => '32.0%', 'o45' => '43.9%', 'o55' => '75.9%', 'o65' => '86.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Anderlecht',
        'team2' => 'Oostende',
        'cards_ht' => ['u05' => '70.2%', 'u15' => '37.5%', 'u25' => '70.2%', 'o05' => '54.1%', 'o15' => '63.3%', 'o25' => '74.1%'],
        'cards_ft' => ['u25' => '63.3%', 'u35' => '70.9%', 'u45' => '48.6%', 'u55' => '70.9%', 'u65' => '48.6%', 'o25' => '50.0%', 'o35' => '73.3%', 'o45' => '70.9%', 'o55' => '48.6%', 'o65' => '50.0%']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Charleroi',
        'team2' => 'Gent',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Belgium - Jupiler League',
        'date' => '28-07-2019',
        'team1' => 'Eupen',
        'team2' => 'Antwerp',
        'cards_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '33.4%', 'o25' => '57.2%'],
        'cards_ft' => ['u25' => '69.4%', 'u35' => '57.2%', 'u45' => '48.6%', 'u55' => '57.2%', 'u65' => '48.6%', 'o25' => '50.0%', 'o35' => '73.3%', 'o45' => '57.2%', 'o55' => '48.6%', 'o65' => '50.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '26-07-2019',
        'team1' => 'Stuttgart',
        'team2' => 'Hannover',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Dresden',
        'team2' => 'Nurnberg',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Holstein Kiel',
        'team2' => 'Sandhausen',
        'cards_ht' => ['u05' => '74.1%', 'u15' => '48.6%', 'u25' => '74.1%', 'o05' => '48.6%', 'o15' => '74.1%', 'o25' => '48.6%'],
        'cards_ft' => ['u25' => '74.1%', 'u35' => '48.6%', 'u45' => '74.1%', 'u55' => '48.6%', 'u65' => '74.1%', 'o25' => '48.6%', 'o35' => '74.1%', 'o45' => '48.6%', 'o55' => '74.1%', 'o65' => '48.6%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '27-07-2019',
        'team1' => 'Osnabruck',
        'team2' => 'Heidenheim',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Greuther Furth',
        'team2' => 'Erzgebirge Aue',
        'cards_ht' => ['u05' => '63.3%', 'u15' => '63.3%', 'u25' => '63.3%', 'o05' => '74.1%', 'o15' => '63.3%', 'o25' => '70.9%'],
        'cards_ft' => ['u25' => '78.6%', 'u35' => '86.3%', 'u45' => '94.0%', 'u55' => '48.6%', 'u65' => '74.1%', 'o25' => '94.0%', 'o35' => '94.0%', 'o45' => '48.6%', 'o55' => '74.1%', 'o65' => '94.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Hamburg',
        'team2' => 'Darmstadt',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Regensburg',
        'team2' => 'Bochum',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Wehen',
        'team2' => 'Karlsruhe',
        'cards_ht' => ['u05' => '51.0%', 'u15' => '86.5%', 'u25' => '75.9%', 'o05' => '86.0%', 'o15' => '32.0%', 'o25' => '86.0%'],
        'cards_ft' => ['u25' => '86.0%', 'u35' => '86.0%', 'u45' => '86.0%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '86.0%', 'o35' => '86.0%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '86.0%']
    ],
    [
        'league' => 'Germany - Bundesliga 2',
        'date' => '28-07-2019',
        'team1' => 'Bielefeld',
        'team2' => 'St Pauli',
        'cards_ht' => ['u05' => '70.6%', 'u15' => '69.5%', 'u25' => '48.6%', 'o05' => '50.0%', 'o15' => '73.3%', 'o25' => '50.0%'],
        'cards_ft' => ['u25' => '86.0%', 'u35' => '75.9%', 'u45' => '86.0%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '32.0%', 'o35' => '86.0%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Ajaccio',
        'team2' => 'Le Havre',
        'cards_ht' => ['u05' => '70.2%', 'u15' => '37.5%', 'u25' => '70.2%', 'o05' => '54.1%', 'o15' => '79.7%', 'o25' => '53.8%'],
        'cards_ft' => ['u25' => '79.7%', 'u35' => '43.9%', 'u45' => '50.0%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '73.3%', 'o35' => '50.0%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '86.0%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Chambly',
        'team2' => 'Valenciennes',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Clermont',
        'team2' => 'Chateauroux',
        'cards_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '43.9%', 'o25' => '35.8%'],
        'cards_ft' => ['u25' => '27.6%', 'u35' => '19.5%', 'u45' => '86.5%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '51.0%', 'o35' => '75.9%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '75.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Guingamp',
        'team2' => 'Grenoble',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Nancy',
        'team2' => 'Orleans',
        'cards_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'cards_ft' => ['u25' => '32.0%', 'u35' => '86.0%', 'u45' => '37.5%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '70.2%', 'o35' => '54.1%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Niort',
        'team2' => 'Troyes',
        'cards_ht' => ['u05' => '79.7%', 'u15' => '53.8%', 'u25' => '79.7%', 'o05' => '43.9%', 'o15' => '75.9%', 'o25' => '86.0%'],
        'cards_ft' => ['u25' => '32.0%', 'u35' => '86.0%', 'u45' => '37.5%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '70.2%', 'o35' => '54.1%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '54.1%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Rodez',
        'team2' => 'Auxerre',
        'cards_ht' => ['u05' => '-', 'u15' => '-', 'u25' => '-', 'o05' => '-', 'o15' => '-', 'o25' => '-'],
        'cards_ft' => ['u25' => '-', 'u35' => '-', 'u45' => '-', 'u55' => '-', 'u65' => '-', 'o25' => '-', 'o35' => '-', 'o45' => '-', 'o55' => '-', 'o65' => '-']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '26-07-2019',
        'team1' => 'Sochaux',
        'team2' => 'Caen',
        'cards_ht' => ['u05' => '33.4%', 'u15' => '57.2%', 'u25' => '69.4%', 'o05' => '57.2%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'cards_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'u55' => '63.3%', 'u65' => '70.9%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '63.3%', 'o55' => '70.9%', 'o65' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '27-07-2019',
        'team1' => 'Le Mans',
        'team2' => 'Lens',
        'cards_ht' => ['u05' => '75.9%', 'u15' => '86.0%', 'u25' => '32.0%', 'o05' => '86.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'cards_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'u55' => '70.2%', 'u65' => '54.1%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '70.2%', 'o55' => '54.1%', 'o65' => '70.9%']
    ],
    [
        'league' => 'French - Ligue 2',
        'date' => '29-07-2019',
        'team1' => 'Lorient',
        'team2' => 'Paris FC',
        'cards_ht' => ['u05' => '48.6%', 'u15' => '50.0%', 'u25' => '73.3%', 'o05' => '50.0%', 'o15' => '44.9%', 'o25' => '32.7%'],
        'cards_ft' => ['u25' => '20.4%', 'u35' => '63.3%', 'u45' => '74.1%', 'u55' => '70.2%', 'u65' => '54.1%', 'o25' => '63.3%', 'o35' => '70.9%', 'o45' => '70.2%', 'o55' => '54.1%', 'o65' => '70.9%']
    ]
];
?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
              <h4 class="mb-0">Cards Statistics</h4>
            </div>

            <!-- Cards Table -->
            <div class="card">
              <div class="card-body">
                <style>
                  /* Table styling matching 1x2.php, goals.php, and corners.php */
                  .cards-table {
                    border-collapse: collapse !important;
                  }

                  .cards-table th, .cards-table td {
                    white-space: nowrap;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0.5rem;
                    border: 1px solid var(--table-border) !important;
                  }

                  /* Sticky header row for vertical scrolling - only 3rd row */
                  .cards-table thead tr:nth-child(3) {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                  }

                  /* Sticky columns for horizontal scrolling - only team columns (3 & 4) */
                  .cards-table th:nth-child(3),
                  .cards-table td:nth-child(3) {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    background-color: inherit;
                    border-right: 1px solid var(--table-border) !important;
                  }

                  .cards-table th:nth-child(4),
                  .cards-table td:nth-child(4) {
                    position: sticky;
                    left: 120px;
                    z-index: 5;
                    background-color: inherit;
                  }

                  /* Higher z-index for sticky header cells that are also in sticky columns */
                  .cards-table thead th:nth-child(3),
                  .cards-table thead th:nth-child(4) {
                    z-index: 15;
                  }

                  /* Exterior borders 2px */
                  .cards-table thead tr:first-child th {
                    border-top-width: 2px !important;
                  }
                  .cards-table tbody tr:last-child td {
                    border-bottom-width: 2px !important;
                  }
                  .cards-table th:first-child,
                  .cards-table td:first-child {
                    border-left-width: 2px !important;
                  }
                  .cards-table th:last-child,
                  .cards-table td:last-child {
                    border-right-width: 2px !important;
                  }

                  /* Team columns - Column 3 and 4 */
                  .cards-table th:nth-child(3),
                  .cards-table td:nth-child(3) {
                    border-left-width: 2px !important;
                  }
                  .cards-table th:nth-child(4),
                  .cards-table td:nth-child(4) {
                    border-right-width: 2px !important;
                  }

                  /* Cards Half Time section - thicker borders */
                  .cards-table td:nth-child(5),
                  .cards-table thead tr:nth-child(1) th:nth-child(5),
                  .cards-table thead tr:nth-child(2) th:nth-child(3) {
                    border-left-width: 2px !important;
                  }

                  .cards-table td:nth-child(10),
                  .cards-table thead tr:nth-child(1) th:nth-child(10),
                  .cards-table thead tr:nth-child(2) th:nth-child(8) {
                    border-right-width: 2px !important;
                  }

                  /* Cards Full Time section - thicker borders */
                  .cards-table td:nth-child(11),
                  .cards-table thead tr:nth-child(1) th:nth-child(11),
                  .cards-table thead tr:nth-child(2) th:nth-child(9) {
                    border-left-width: 2px !important;
                  }

                  .cards-table td:nth-child(20),
                  .cards-table thead tr:nth-child(1) th:nth-child(20),
                  .cards-table thead tr:nth-child(2) th:nth-child(18) {
                    border-right-width: 2px !important;
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
                  .cards-table tbody tr:nth-child(odd) {
                    background-color: var(--table-row-odd);
                  }
                  .cards-table tbody tr:nth-child(even) {
                    background-color: var(--table-row-even);
                  }
                  .cards-table tbody tr:hover {
                    background-color: var(--table-row-hover);
                    transition: background-color 0.2s ease;
                  }

                  /* Background colors for sticky cells to match row colors */
                  .cards-table tbody tr:nth-child(odd) td:nth-child(3),
                  .cards-table tbody tr:nth-child(odd) td:nth-child(4) {
                    background-color: var(--table-row-odd);
                  }

                  .cards-table tbody tr:nth-child(even) td:nth-child(3),
                  .cards-table tbody tr:nth-child(even) td:nth-child(4) {
                    background-color: var(--table-row-even);
                  }

                  .cards-table tbody tr:hover td:nth-child(3),
                  .cards-table tbody tr:hover td:nth-child(4) {
                    background-color: var(--table-row-hover);
                  }

                  /* Ensure sticky header cells maintain their background colors */
                  .cards-table thead tr:nth-child(1) th:nth-child(3),
                  .cards-table thead tr:nth-child(1) th:nth-child(4) {
                    background-color: var(--brand-primary-darker);
                  }

                  .cards-table thead tr:nth-child(2) th:nth-child(3),
                  .cards-table thead tr:nth-child(2) th:nth-child(4) {
                    background-color: var(--brand-primary);
                  }

                  .cards-table thead tr:nth-child(3) th:nth-child(3),
                  .cards-table thead tr:nth-child(3) th:nth-child(4) {
                    background-color: var(--brand-primary-light);
                  }

                  /* Force white text for all header rows */
                  .cards-table thead th {
                    color: #FFFFFF !important;
                  }

                  /* Force section borders - Separate O columns from U columns in Half Time */
                  /* Left border before O 0.5 (column 8 in table, 4th th in row 3) */
                  .cards-table thead tr:nth-child(3) th:nth-child(4) {
                    border-left: 2px solid var(--table-border) !important;
                  }
                  .cards-table tbody tr td:nth-child(8) {
                    border-left: 2px solid var(--table-border) !important;
                  }

                  /* Right border after O 2.5 (column 10 in table, 6th th in row 3) */
                  .cards-table thead tr:nth-child(3) th:nth-child(6) {
                    border-right: 2px solid var(--table-border) !important;
                  }
                  .cards-table tbody tr td:nth-child(10) {
                    border-right: 2px solid var(--table-border) !important;
                  }

                  /* Force section borders - Separate O columns from U columns in Full Time */
                  /* Left border before O 2.5 (column 16 in table, 12th th in row 3) */
                  .cards-table thead tr:nth-child(3) th:nth-child(12) {
                    border-left: 2px solid var(--table-border) !important;
                  }
                  .cards-table tbody tr td:nth-child(16) {
                    border-left: 2px solid var(--table-border) !important;
                  }

                  /* Right border after O 6.5 (column 20 in table, 16th th in row 3) */
                  .cards-table thead tr:nth-child(3) th:nth-child(16) {
                    border-right: 2px solid var(--table-border) !important;
                  }
                  .cards-table tbody tr td:nth-child(20) {
                    border-right: 2px solid var(--table-border) !important;
                  }
                </style>

                <div class="table-responsive">
                  <table class="table table-sm cards-table">
                    <thead>
                      <tr style="background-color: var(--brand-primary-darker); color: white; font-weight: 700;">
                        <th rowspan="3" class="align-middle league-col">LEAGUE</th>
                        <th rowspan="3" class="align-middle date-col">DATE</th>
                        <th rowspan="3" class="align-middle team-col">1</th>
                        <th rowspan="3" class="align-middle team-col">2</th>
                        <th colspan="16" class="text-center">CARDS</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary); color: white; font-weight: 600;">
                        <th colspan="6" class="text-center">Half Time</th>
                        <th colspan="10" class="text-center">Full Time</th>
                      </tr>
                      <tr style="background-color: var(--brand-primary-light); color: white; font-weight: 500;">
                        <th class="data-col">U 0.5</th>
                        <th class="data-col">U 1.5</th>
                        <th class="data-col">U 2.5</th>
                        <th class="data-col">O 0.5</th>
                        <th class="data-col">O 1.5</th>
                        <th class="data-col">O 2.5</th>
                        <th class="data-col">U 2.5</th>
                        <th class="data-col">U 3.5</th>
                        <th class="data-col">U 4.5</th>
                        <th class="data-col">U 5.5</th>
                        <th class="data-col">U 6.5</th>
                        <th class="data-col">O 2.5</th>
                        <th class="data-col">O 3.5</th>
                        <th class="data-col">O 4.5</th>
                        <th class="data-col">O 5.5</th>
                        <th class="data-col">O 6.5</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($cardsData as $match): ?>
                        <tr>
                          <td class="league-col"><?php echo htmlspecialchars($match['league']); ?></td>
                          <td class="date-col"><?php echo htmlspecialchars($match['date']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team1']); ?></td>
                          <td class="team-col"><?php echo htmlspecialchars($match['team2']); ?></td>

                          <!-- Cards Half Time -->
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ht']['u05']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ht']['u15']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ht']['u25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ht']['o05']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ht']['o15']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ht']['o25']); ?></td>

                          <!-- Cards Full Time -->
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['u25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['u35']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['u45']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['u55']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['u65']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['o25']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['o35']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['o45']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['o55']); ?></td>
                          <td class="data-col"><?php echo htmlspecialchars($match['cards_ft']['o65']); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          <!-- / Content -->

<?php include 'includes/app-footer.php'; ?>
