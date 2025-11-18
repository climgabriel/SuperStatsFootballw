<!doctype html>

<html lang="en" class="layout-content-navbar" data-assets-path="./assets/" data-template="navbar-only-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?php echo isset($pageTitle) ? $pageTitle : 'Super Stats Football'; ?></title>

  <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Super Stats Football - Football Statistics Dashboard'; ?>" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="./assets/img/favicon/SuperStatsFootballLogo0.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="./assets/vendor/fonts/iconify-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/core.css" />
  <link rel="stylesheet" href="./assets/css/demo.css?v=<?php echo time(); ?>" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>
  <script src="./assets/js/config.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">

      <!-- Layout page -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="index.php">
              <img src="./assets/img/favicon/SuperStatsFootballLogo0.png" alt="Super Stats Football Logo" height="40"
                class="app-brand-logo" />
              <span class="app-brand-text demo menu-text fw-bold ms-2">Super Stats Football</span>
            </a>

            <!-- Navbar Navigation -->
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Navigation Menu -->
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item">
                  <a class="nav-link fs-5" href="index.php">Super</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link fs-5" href="plans.php">Plans</a>
                </li>
                <!-- User Dropdown -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown ms-3">
                  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <i class="bx bx-user-circle" style="font-size: 2.5rem; color: #FFE418;"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <i class="bx bx-user-circle" style="font-size: 2.5rem;"></i>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0">John Doe</h6>
                            <small class="text-body-secondary">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="login.php">
                        <i class="icon-base bx bx-log-in icon-md me-3"></i><span>Login</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="register.php">
                        <i class="icon-base bx bx-user-plus icon-md me-3"></i><span>Register</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="forgot-password.php">
                        <i class="icon-base bx bx-key icon-md me-3"></i><span>Forgot Password</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="account-settings.php">
                        <i class="icon-base bx bx-user icon-md me-3"></i><span>Account Settings</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);">
                        <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Functional Navbar -->
        <nav class="bg-body-tertiary border-bottom">
          <div class="container-xxl">
            <ul class="nav nav-pills nav-fill py-3">
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == '1x2') ? ' active' : ''; ?>" href="1x2.php">1X2</a>
              </li>
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == 'goals') ? ' active' : ''; ?>" href="goals.php">GOALS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == 'corners') ? ' active' : ''; ?>" href="corners.php">CORNERS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == 'cards') ? ' active' : ''; ?>" href="cards.php">CARDS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == 'shots') ? ' active' : ''; ?>" href="shots.php">SHOTS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == 'faults') ? ' active' : ''; ?>" href="faults.php">FAULTS</a>
              </li>
              <li class="nav-item">
                <a class="nav-link<?php echo (isset($activePage) && $activePage == 'offsides') ? ' active' : ''; ?>" href="offsides.php">OFFSIDES</a>
              </li>
            </ul>
          </div>
        </nav>
        <!-- / Functional Navbar -->

        <!-- Ads Promo Bar -->
        <div class="bg-body-tertiary border-bottom ads-promo-bar">
          <div class="container-xxl">
            <div class="ads-section-wrapper">
              <!-- Top League Section -->
              <div class="ads-top-league">
                <h6 class="ads-top-league-title">TOP League</h6>
                <div class="ads-league-grid">
                  <div class="ads-league-cell">
                    <div class="ads-league-cell-number">I</div>
                    <div class="ads-league-cell-content">Top 1 ad</div>
                  </div>
                  <div class="ads-league-cell">
                    <div class="ads-league-cell-number">II</div>
                    <div class="ads-league-cell-content">Top 2 ad</div>
                  </div>
                  <div class="ads-league-cell">
                    <div class="ads-league-cell-number">III</div>
                    <div class="ads-league-cell-content">Top 3 ad</div>
                  </div>
                </div>
              </div>

              <!-- Other Ads Section -->
              <div class="ads-other-section">
                <div class="ads-other-grid">
                  <div class="ads-grid-item ads-cell">ad 4</div>
                  <div class="ads-grid-item ads-cell">ad 5</div>
                  <div class="ads-grid-item ads-cell">ad 6</div>
                  <div class="ads-grid-item ads-cell">ad 7</div>
                  <div class="ads-grid-item ads-cell">ad 8</div>
                  <div class="ads-grid-item ads-cell">ad 9</div>
                  <div class="ads-grid-item ads-cell">ad 10</div>
                  <div class="ads-grid-item ads-cell">ad 11</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- / Ads Promo Bar -->
