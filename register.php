<?php
$pageTitle = "Register - Super Stats Football";
$pageDescription = "Create your Super Stats Football account";

require_once 'includes/api-helper.php';
require_once 'includes/UserManager.php';

// Handle registration form submission
$registerError = '';
$registerSuccess = false;
$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $fullName = trim($_POST['full_name'] ?? '');
    $plan = (int)($_POST['plan'] ?? UserManager::PLAN_FREE);

    // Validation
    if (empty($email)) {
        $validationErrors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = 'Invalid email format';
    }

    if (empty($password)) {
        $validationErrors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $validationErrors[] = 'Password must be at least 8 characters';
    }

    if ($password !== $confirmPassword) {
        $validationErrors[] = 'Passwords do not match';
    }

    if (empty($fullName)) {
        $validationErrors[] = 'Full name is required';
    }

    if ($plan < UserManager::PLAN_FREE || $plan > UserManager::PLAN_ULTIMATE) {
        $validationErrors[] = 'Invalid plan selected';
    }

    // If no validation errors, attempt registration
    if (empty($validationErrors)) {
        $response = registerUser($email, $password, $fullName, $plan);

        if ($response['success']) {
            $registerSuccess = true;
            // Redirect to dashboard or intended page
            $redirectTo = 'index.php';
            header('Location: ' . $redirectTo);
            exit;
        } else {
            $registerError = $response['error'] ?? 'Registration failed. Please try again.';
        }
    } else {
        $registerError = implode('<br>', $validationErrors);
    }
}

// Get plan information for display
$plans = [
    UserManager::PLAN_FREE => UserManager::getPlanFeatures(UserManager::PLAN_FREE),
    UserManager::PLAN_BASIC => UserManager::getPlanFeatures(UserManager::PLAN_BASIC),
    UserManager::PLAN_STANDARD => UserManager::getPlanFeatures(UserManager::PLAN_STANDARD),
    UserManager::PLAN_PREMIUM => UserManager::getPlanFeatures(UserManager::PLAN_PREMIUM),
    UserManager::PLAN_ULTIMATE => UserManager::getPlanFeatures(UserManager::PLAN_ULTIMATE),
];

include 'includes/auth-header.php';
?>

  <!-- Content -->

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <?php include 'includes/auth-logo.php'; ?>
            <h4 class="mb-1 text-center">Create Your Account</h4>
            <p class="mb-6">Start your journey with Super Stats Football</p>

            <?php if ($registerError): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <strong>Registration Failed!</strong><br><?php echo $registerError; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($registerSuccess): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <strong>Success!</strong> Your account has been created. Redirecting...
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form id="formRegistration" class="mb-6" action="register.php" method="POST" novalidate>

              <!-- Full Name -->
              <div class="mb-6">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                  placeholder="Enter your full name"
                  value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                  required autofocus />
                <div class="invalid-feedback">Please enter your full name</div>
              </div>

              <!-- Email -->
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                  placeholder="Enter your email"
                  value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                  required />
                <div class="invalid-feedback">Please enter a valid email address</div>
              </div>

              <!-- Password -->
              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" required />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                <small class="text-muted">Minimum 8 characters, must include uppercase, lowercase, and digit</small>
                <div class="invalid-feedback">Password must be at least 8 characters with uppercase, lowercase, and digit</div>
              </div>

              <!-- Confirm Password -->
              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="confirm_password" required />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                <div class="invalid-feedback">Passwords do not match</div>
              </div>

              <!-- Subscription Plan -->
              <div class="mb-6">
                <label for="plan" class="form-label d-flex align-items-center">
                  <i class="bx bx-crown me-2" style="color: #106147;"></i>
                  Select Your Plan
                </label>
                <select class="form-select" id="plan" name="plan" required>
                  <?php foreach ($plans as $planId => $planInfo): ?>
                  <option value="<?php echo $planId; ?>"
                          <?php echo (isset($_POST['plan']) && (int)$_POST['plan'] === $planId) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($planInfo['name']); ?> -
                    <?php echo $planInfo['models']; ?> Model(s),
                    <?php echo $planInfo['leagues']; ?> Leagues
                    <?php if ($planId === UserManager::PLAN_FREE): ?>
                      (Free)
                    <?php endif; ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <small class="text-muted">You can upgrade your plan anytime from your account settings</small>
              </div>

              <!-- Terms and Conditions -->
              <div class="mb-6">
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="terms" name="terms" required />
                  <label class="form-check-label" for="terms">
                    I agree to the <a href="terms.php" target="_blank">Terms & Conditions</a> and
                    <a href="privacy.php" target="_blank">Privacy Policy</a>
                  </label>
                  <div class="invalid-feedback">You must agree to the terms and conditions</div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit" style="background-color: #106147; border-color: #106147;">
                  <i class="bx bx-user-plus me-1"></i> Create Account
                </button>
              </div>
            </form>

            <p class="text-center">
              <span>Already have an account?</span>
              <a href="login.php">
                <span>Sign in instead</span>
              </a>
            </p>

            <!-- Plan Comparison Info -->
            <div class="mt-6 pt-6 border-top">
              <p class="text-center text-muted mb-4">
                <small><i class="bx bx-info-circle me-1"></i>Choose the plan that fits your needs</small>
              </p>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="card border" style="border-color: #106147 !important;">
                    <div class="card-body p-3">
                      <h6 class="mb-2" style="color: #106147;">
                        <i class="bx bx-gift me-1"></i>Free Plan
                      </h6>
                      <ul class="list-unstyled mb-0 small">
                        <li><i class="bx bx-check text-success me-1"></i> 1 Prediction Model</li>
                        <li><i class="bx bx-check text-success me-1"></i> 5 Leagues Max</li>
                        <li><i class="bx bx-check text-success me-1"></i> Basic Statistics</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card border" style="border-color: #106147 !important;">
                    <div class="card-body p-3">
                      <h6 class="mb-2" style="color: #106147;">
                        <i class="bx bx-crown me-1"></i>Ultimate Plan
                      </h6>
                      <ul class="list-unstyled mb-0 small">
                        <li><i class="bx bx-check text-success me-1"></i> All 5 Prediction Models</li>
                        <li><i class="bx bx-check text-success me-1"></i> 5 Leagues Max</li>
                        <li><i class="bx bx-check text-success me-1"></i> Advanced Analytics</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>

  <!-- / Content -->

  <script>
  // Client-side form validation
  (function() {
    'use strict';

    const form = document.getElementById('formRegistration');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      const strength = getPasswordStrength(password);

      // You can add visual feedback here if needed
      if (password.length > 0 && password.length < 8) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
      } else if (password.length >= 8) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
      }
    });

    // Password match validation
    confirmPasswordInput.addEventListener('input', function() {
      if (this.value !== passwordInput.value) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
      } else if (this.value.length >= 8) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
      }
    });

    // Form submission validation
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      // Check password match
      if (passwordInput.value !== confirmPasswordInput.value) {
        event.preventDefault();
        confirmPasswordInput.classList.add('is-invalid');
        alert('Passwords do not match!');
        return false;
      }

      // Check password length
      if (passwordInput.value.length < 8) {
        event.preventDefault();
        passwordInput.classList.add('is-invalid');
        alert('Password must be at least 8 characters long!');
        return false;
      }

      form.classList.add('was-validated');
    }, false);

    // Password strength calculator (optional enhancement)
    function getPasswordStrength(password) {
      let strength = 0;
      if (password.length >= 8) strength++;
      if (password.length >= 12) strength++;
      if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
      if (/\d/.test(password)) strength++;
      if (/[^a-zA-Z\d]/.test(password)) strength++;
      return strength;
    }

    // Password toggle functionality
    document.querySelectorAll('.form-password-toggle .input-group-text').forEach(function(toggle) {
      toggle.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        const icon = this.querySelector('i');

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('bx-hide');
          icon.classList.add('bx-show');
        } else {
          input.type = 'password';
          icon.classList.remove('bx-show');
          icon.classList.add('bx-hide');
        }
      });
    });
  })();
  </script>

<?php include 'includes/auth-footer.php'; ?>
