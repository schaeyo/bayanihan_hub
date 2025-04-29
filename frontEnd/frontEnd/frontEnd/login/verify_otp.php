<?php
session_start();
if (!isset($_SESSION['reset_email'])) header("Location: forgot_password.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Verify OTP</title>
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
      <h2>Bayanihan Hub <span style="color: #f35d00">OTP Verification</span></h2>
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

        <!-- Right Side - OTP Form -->
        <div class="col-lg-5 col-md-6 col-12 mb-4 mt-4">
          <div class="p-4 text-white text-center rounded w-80 mx-auto" style="background-color: #04569d;">
            <h3 class="mb-3">Verify OTP</h3>
            <form action="check_otp.php" method="POST">
              <div class="mb-3 text-start">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" name="otp" id="otp" class="form-control" required />
              </div>
              <button type="submit" class="btn w-100 text-light" style="background-color: #f35d00;">Verify</button>
              <div class="text-center mt-3">
                <a href="forgot_password.php" class="text-light text-decoration-none">Back</a>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
