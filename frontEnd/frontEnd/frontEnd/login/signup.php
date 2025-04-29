<?php
include '../../db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/signin.css" />
</head>

<body>
  <!-- Header -->
  <div class="container d-flex align-items-center mt-2">
    <img src="../media/logo2.png" alt="Bayanihan Logo" width="90" height="90" class="rounded-circle" />
    <div class="ms-4">
      <h2>Bayanihan Hub <span style="color: #f35d00">Sign Up</span></h2>
    </div>
  </div>

  <!-- Main Section -->
  <div class="mt-2" style="background-color: #130d33">
    <div class="container">
      <div class="row justify-content-center align-items-center gap-5" style="min-height: 70vh">
        <!-- Left Side - Logo -->
        <div class="col-lg-5 col-md-6 col-12 d-flex justify-content-center mt-2">
          <img src="../media/logo.png" alt="Bayanihan Logo" class="rounded-circle img-fluid" />
        </div>

        <!-- Right Side - Sign Up Form -->
        <div class="col-lg-5 col-md-6 col-12 mb-4 mt-4">
          <div class="p-4 text-white text-center rounded w-80 mx-auto" style="background-color: #04569d">
            <h2 class="mt-1 mb-4">SIGN UP</h2>
            <form action="../../register.php" method="POST" class="mt-3">
  <input type="text" name="username" class="form-control mb-4" placeholder="Username" required />
  <input type="email" name="email" class="form-control mb-4" placeholder="Email Address" required />
  <input type="phone_number" name="phone_number" class="form-control mb-4" placeholder="Phone Number" required />
  <!-- Password Field -->
  <div class="password-container mb-4 position-relative">
    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
    <i id="togglePassword" class="fa fa-eye position-absolute"
       style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%);"></i>
  </div>

  <!-- Password Strength Indicator -->
<div id="passwordStrength" class="text-start mb-3 fw-semibold"></div>
  
  <!-- Confirm Password Field -->
  <div class="password-container mb-4 position-relative">
    <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required />
    <i id="toggleConfirmPassword" class="fa fa-eye position-absolute"
       style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%);"></i>
  </div>
  
  <!-- Password Mismatch Message -->
  <div id="passwordMismatch" class="text-danger text-start mt-2" style="display: none;">
    Passwords do not match.
  </div>
  
  <button type="submit" class="btn form-control mt-2 text-light" style="background-color: #f35d00">
    Sign Up
  </button>
</form>

              
                <h4>
                  <a href="signin.php" class="text-info text-decoration-none">Already have an account? Log In</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Account Created Confirmation Modal -->
  <div class="modal fade" id="signupSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Account Created</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Your account has been successfully created!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="redirectToLogin()">
            Go to Login
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-dark text-center py-4 mt-4">
    © 2025 Bayanihan Hub. All Rights Reserved.
  </footer>
  <script src="js/signup.js"></script>
  <script src="js/passwordStrength.js"></script>
  <script src="js/passwordToggle.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
  // Toggle Password Visibility for Password Field
  document.getElementById("togglePassword").addEventListener("click", function () {
    const passwordField = document.getElementById("password");
    const isVisible = passwordField.type === "text";
    passwordField.type = isVisible ? "password" : "text";
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });

  // Toggle Password Visibility for Confirm Password Field
  document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
    const confirmPasswordField = document.getElementById("confirmPassword");
    const isVisible = confirmPasswordField.type === "text";
    confirmPasswordField.type = isVisible ? "password" : "text";
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>

</html>