<?php
session_start();
if (!isset($_SESSION['reset_email'])) header("Location: forgot_password.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Set New Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/signin.css" />
</head>

<body>
  <!-- Header -->
  <div class="container d-flex align-items-center mt-2">
    <img src="../media/logo2.png" alt="Bayanihan Logo" width="90" height="90" class="rounded-circle" />
    <div class="ms-4">
      <h2>Bayanihan Hub <span style="color: #f35d00">Reset Password</span></h2>
    </div>
  </div>

  <!-- Main Section -->
  <div class="mt-2" style="background-color: #130d33;">
    <div class="container">
      <div class="row justify-content-center align-items-center gap-5" style="min-height: 70vh;">
        <!-- Left Side - Logo -->
        <div class="col-lg-5 col-md-6 col-12 d-flex justify-content-center mt-2">
          <img src="../media/logo.png" alt="Bayanihan Logo" class="rounded-circle img-fluid" />
        </div>

        <!-- Right Side - Reset Password Form -->
        <div class="col-lg-5 col-md-6 col-12 mb-4 mt-4">
          <div class="p-4 text-white text-center rounded w-80 mx-auto" style="background-color: #04569d;">
            <h3 class="mb-3">Set New Password</h3>
            <form id="resetForm" action="update_password.php" method="POST">
              <div class="mb-3 text-start">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group">
                  <input type="password" name="password" id="password" class="form-control" required />
                  <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                    <i class="fa-solid fa-eye-slash" style="color: grey;"></i>
                  </button>
                </div>
                <small id="password-strength-text" class="form-text mt-1"></small>
              </div>

              <div class="mb-3 text-start">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" required />
                  <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword" tabindex="-1">
                    <i class="fa-solid fa-eye-slash" style="color: grey;"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="btn w-100 text-light" style="background-color: #f35d00;">Reset Password</button>
              <div class="text-center mt-3">
                <a href="signin.php" class="text-light text-decoration-none">Back to Login</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-dark text-center py-4 mt-4 bg-white">
    © 2025 Bayanihan Hub. All Rights Reserved.
  </footer>

  <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm_password');

    toggleConfirmPassword.addEventListener('click', function () {
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Password strength checker
    const strengthText = document.getElementById('password-strength-text');
    let currentStrength = 0; // 0 = none, 1 = weak, 2 = medium, 3 = strong

    password.addEventListener('input', function () {
      const val = password.value;
      let strength = 0;

      if (val.length >= 8) strength++;
      if (/[A-Z]/.test(val)) strength++;
      if (/[0-9]/.test(val)) strength++;
      if (/[^A-Za-z0-9]/.test(val)) strength++;

      currentStrength = strength;

      if (val.length === 0) {
        strengthText.textContent = '';
      } else if (strength <= 1) {
        strengthText.textContent = 'Weak';
        strengthText.style.color = 'red';
      } else if (strength === 2 || strength === 3) {
        strengthText.textContent = 'Medium';
        strengthText.style.color = 'orange';
      } else {
        strengthText.textContent = 'Strong';
        strengthText.style.color = 'green';
      }
    });

    // Prevent form submission if password is weak
    const resetForm = document.getElementById('resetForm');

    resetForm.addEventListener('submit', function (e) {
      if (currentStrength <= 1) {
        e.preventDefault();
        alert('Password is too weak. Please choose a stronger password.');
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
