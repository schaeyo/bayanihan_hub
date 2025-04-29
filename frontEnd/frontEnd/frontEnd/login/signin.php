<?php
include '../../db_connection.php';

session_start(); // Start a session to manage user login state
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log In</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/signin.css" />
</head>

<body>
  <!-- Header -->
  <main class="flex-grow-1">
    <div class="container d-flex align-items-center mt-2">

    <img src="../media/logo2.png" alt="Bayanihan Logo" width="90" height="90" class="rounded-circle" />
    <div class="ms-4">
      <h2>Bayanihan Hub <span style="color: #f35d00">Log In</span></h2>
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

        <!-- Right Side - Login Form -->
        <div class="col-lg-5 col-md-6 col-12 mb-4 mt-4">
          <div class="p-4 text-white text-center rounded w-80 mx-auto" style="background-color: #04569d">
            <h2 class="mt-1 mb-4">LOG IN</h2>
            <form action="../../login.php" method="POST">
            <div class="mt-3">
                <input type="text" name="username" class="form-control mb-4" placeholder="Email/Username" required />
          <div class="password-container mb-1 position-relative">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
            <i id="togglePassword" class="fa fa-eye position-absolute"
            style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%); z-index: 10; font-size: 1.2rem;  "></i>

          </div>
     <button id="loginBtn" type="submit" class="btn form-control mt-2 text-light" style="background-color: #f35d00">
      Login
    </button>
    <div class="text-end mt-2">
  <a href="forgot_password.php" class="text-light text-decoration-none">Forgot Password?</a>
</div>
  </div>
</form>
            <div class="d-flex align-items-center my-3">
              <hr class="flex-grow-1" />
              <span class="mx-2">OR</span>
              <hr class="flex-grow-1" />
            </div>
          <!-- <div class="facebook-login mb-3">
              <a href="#" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="fab fa-facebook-f"></i>
                <span>Log in with Facebook</span>
              </a>
            </div> -->

            <div class="signupacc d-flex justify-content-center mt-5 gap-2">
              <h4>
                <a href="signup.php" class="text-info text-decoration-none">Create Account</a>
              </h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!--Modal para sa Feedback-->
  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Login Successful</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>You have successfully logged in. Redirecting to your dashboard...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
            OK
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Failed Modal -->
  <div class="modal fade" id="failedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Login Failed</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>The username or password you entered is incorrect. Please try again.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>`111111111111111111 
  </div>

  <!-- Input Validation Modal -->
  <div class="modal fade" id="validationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Input Required</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Please fill in both the username/email and password fields before logging in.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>
  </div> 

  <!-- Footer -->
  <footer class="text-dark text-center py-4 mt-4">
    © 2025 Bayanihan Hub. All Rights Reserved.
  </footer>

  <!-- <script src="js/passwordToggle.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
  document.getElementById("togglePassword").addEventListener("click", function () {
    const passwordField = document.getElementById("password");
    const isVisible = passwordField.type === "text";
    passwordField.type = isVisible ? "password" : "text";
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>
</html>