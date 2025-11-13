<?php
$pageTitle = "Forgot Password - Super Stats Football";
$pageDescription = "Reset your Super Stats Football password";
include 'includes/auth-header.php';
?>

  <!-- Content -->

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Forgot Password -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <?php include 'includes/auth-logo.php'; ?>
            <h4 class="mb-1 text-center">Forgot Password?</h4>
            <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>
            <form id="formAuthentication" class="mb-6" action="login.php">
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                  autofocus />
              </div>
              <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
            </form>
            <div class="text-center">
              <a href="login.php">
                <span><i class="icon-base bx bx-chevron-left scaleX-n1-rtl me-1"></i> Back to login</span>
              </a>
            </div>
          </div>
        </div>
        <!-- /Forgot Password -->
      </div>
    </div>
  </div>

  <!-- / Content -->

<?php include 'includes/auth-footer.php'; ?>
