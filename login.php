<?php
$pageTitle = "Login - Super Stats Football";
$pageDescription = "Login to Super Stats Football Dashboard";
include 'includes/auth-header.php';
?>

  <!-- Content -->

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Login -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="index.php" class="app-brand-link gap-2">
                <img src="./assets/img/favicon/SuperStatsFootballLogo0.png" alt="Super Stats Football Logo" height="40"
                  class="app-brand-logo" />
                <span class="app-brand-text demo text-heading fw-bold">Super Stats Football</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1 text-center">Welcome to Super Stats Football!</h4>
            <p class="mb-6">Please sign-in to your account to access football statistics</p>

            <form id="formAuthentication" class="mb-6" action="index.php">
              <div class="mb-6">
                <label for="email" class="form-label">Email or Username</label>
                <input type="text" class="form-control" id="email" name="email-username"
                  placeholder="Enter your email or username" autofocus />
              </div>
              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-8">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                  <a href="forgot-password.php">
                    <span>Forgot Password?</span>
                  </a>
                </div>
              </div>
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
              </div>
            </form>

            <p class="text-center">
              <span>New on our platform?</span>
              <a href="register.php">
                <span>Create an account</span>
              </a>
            </p>
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>
  </div>

  <!-- / Content -->

<?php include 'includes/auth-footer.php'; ?>
