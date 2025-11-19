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
    $fullName = trim($_POST['full_name'] ?? '');

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

    if (empty($fullName)) {
        $validationErrors[] = 'Full name is required';
    }

    // If no validation errors, attempt registration
    if (empty($validationErrors)) {
        $response = registerUser($email, $password, $fullName);

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

    // Form submission validation
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
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
