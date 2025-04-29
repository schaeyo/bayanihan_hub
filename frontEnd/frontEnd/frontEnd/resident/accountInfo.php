<?php
session_start();

// Include the database connection file
include '../../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /frontEnd/login/signin.php");
    exit;
}

// Make sure $user_id is properly initialized from the session
$user_id = $_SESSION['user_id']; 

// Check if the database connection is established
if (!$con) {
    die("Database connection failed.");
}

// Handle account information form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    // Retrieve form data and sanitize
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($con, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $name_extension = mysqli_real_escape_string($con, $_POST['name_extension']);
    $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $occupation = mysqli_real_escape_string($con, $_POST['occupation']);
    $residence_since = mysqli_real_escape_string($con, $_POST['residence_since']);
    $house_address = mysqli_real_escape_string($con, $_POST['house_address']);
    $barangay = mysqli_real_escape_string($con, $_POST['barangay']);
    
    // Check if a profile picture is uploaded
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $profile_image = basename($_FILES['profilePic']['name']);
        $upload_dir = 'profile_photo/';
        $upload_file = $upload_dir . $profile_image;
        
        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $upload_file)) {
            // File uploaded successfully, update user profile image
        } else {
            // Get existing profile image if upload fails
            $query = "SELECT profile_image FROM users WHERE user_id = $user_id";
            $result = mysqli_query($con, $query);
            $user_data = mysqli_fetch_assoc($result);
            $profile_image = $user_data['profile_image'];
        }
    } else {
        // Get existing profile image if no new file uploaded
        $query = "SELECT profile_image FROM users WHERE user_id = $user_id";
        $result = mysqli_query($con, $query);
        $user_data = mysqli_fetch_assoc($result);
        $profile_image = $user_data['profile_image'];
    }

    // Update user information in the database
    $query = "UPDATE users SET
                first_name = '$first_name',
                middle_name = '$middle_name',
                last_name = '$last_name',
                name_extension = '$name_extension',
                birthdate = '$birthdate',
                civil_status = '$civil_status',
                email = '$email',
                gender = '$gender',
                phone_number = '$phone_number',
                occupation = '$occupation',
                residence_since = '$residence_since',
                house_address = '$house_address',
                barangay = '$barangay',
                profile_image = '$profile_image'
              WHERE user_id = $user_id";

    if (mysqli_query($con, $query)) {
        // After successful update, redirect with 'saved' status
        header('Location: accountInfo.php?status=saved');
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_password'])) {
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Password strength check
    if (!preg_match('/[A-Z]/', $new_password)) {
        $password_error = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $password_error = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $password_error = "Password must contain at least one number.";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
        $password_error = "Password must contain at least one special character.";
    } elseif (strlen($new_password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $query = "UPDATE users SET password = '$hashed_password' WHERE user_id = $user_id";

        if (mysqli_query($con, $query)) {
            // Redirect after password change
            header('Location: accountInfo.php?status=password_changed');
            exit();
        } else {
            $password_error = "Error updating password: " . mysqli_error($con);
        }
    }
}

// Fetch user details for display
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bayanihan Hub - Account Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../public/css/home.css" />
  <style>
    .sidebar {
      background-color: #001a4f; /* Sidebar color */
      color: white;
      height: 100vh;
      padding: 20px;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      overflow-y: auto;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      margin: 10px 0;
      padding: 10px;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #001a4f; /* Darker shade for hover/active */
    }
    .toggle-btn:hover {
      background-color: #001a4f;
    }

    /* Add margin to main content to compensate for sidebar width */
    main {
      margin-left: 0;
    }

    /* Responsive adjustments */
    @media (min-width: 768px) {
      main {
        margin-left: 250px;
      }
    }

    footer {
      background-color: #001a4f;
    }
    .profile-image-container {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    overflow: hidden;
    border-radius: 50%;
    position: relative;
    border: 2px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    cursor: pointer;
  }

  .profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .profile-image-container:hover {
    border-color: #001a4f;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
  }

  .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 26, 79, 0.5);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 50%;
  }

  .profile-image-container:hover .image-overlay {
    opacity: 1;
  }

  .image-overlay i {
    font-size: 1.5rem;
    margin-bottom: 5px;
  }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="mt-5">
    <a href="accountInfo.php" class="active p-2"><i class="bi bi-person-circle mx-3"></i>Hi, <?php echo htmlspecialchars($user['first_name']); ?></a>
    <a href="resident_dashboard.php"><i class="bi bi-house-door"></i> Home</a>
    <a href="submitReqForm.php"><i class="bi bi-file-earmark-check"></i> Submit Request</a>
    <a href="reportEmergency.php"><i class="bi bi-clipboard-check"></i> Report Emergencies</a>
    <a href="viewAnnouncements.php"><i class="bi bi-people"></i> View Announcements</a>
    <a href="bookAppointment.php"><i class="bi bi-megaphone"></i> Appointments</a>
    <a href="submitFeedback.php"><i class="bi bi-chat-dots"></i> Submit Feedback</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

<!-- Header -->
<div class="text-light" style="background-color: #001a4f">
  <div class="container brand-header">
    <img src="../media/logo2.png" alt="Bayanihan Hub Logo" width="90" height="90" style="border-radius: 50%;" />
    <h3 class="mx-3 mt-2">Bayanihan Hub</h3>
  </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h3>Are you sure you want to log out?</h3>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="button" class="btn btn-secondary px-4 text-center" data-bs-dismiss="modal">Cancel</button>
        <a href="../login/signin.php" class="btn btn-danger text-center px-4 ms-3">Yes, Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<main class="container mt-4">
  <h2>My Account Information</h2>
  
  <!-- Display errors if any -->
  <?php if (isset($password_error)): ?>
    <div class="alert alert-danger"><?php echo $password_error; ?></div>
  <?php endif; ?>

  <form action="accountInfo.php" method="POST" enctype="multipart/form-data">
    <div class="row align-items-start">
      <!-- Profile Section -->
<div class="col-md-3 text-center">
  <label for="profilePic">Choose Profile</label>
  <div class="border rounded p-3">
    <div class="profile-image-container" onclick="openImageModal()">
      <?php if (!empty($user['profile_image'])): ?>
        <img src="profile_photo/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" class="profile-image">
      <?php else: ?>
        <img src="default.jpg" alt="Default Profile Picture" class="profile-image">
      <?php endif; ?>
      <div class="image-overlay">
        <i class="bi bi-zoom-in"></i> Click to enlarge
      </div>
    </div>
  </div>
  <div class="mt-3">
    <label for="profilePic" class="form-label">Upload New Profile Picture</label>
    <input class="form-control" type="file" id="profilePic" name="profilePic" accept="image/*">
  </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <?php if (!empty($user['profile_image'])): ?>
          <img src="profile_photo/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" class="img-fluid" style="max-height: 70vh;">
        <?php else: ?>
          <img src="default.jpg" alt="Default Profile Picture" class="img-fluid" style="max-height: 70vh;">
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

      <!-- Personal Information -->
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-3">
            <label>First Name *</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required />
          </div>
          <div class="col-md-3">
            <label>Middle Name</label>
            <input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($user['middle_name']); ?>" />
          </div>
          <div class="col-md-3">
            <label>Last Name *</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required />
          </div>
          <div class="col-md-3">
            <label>Name Extension</label>
            <input type="text" class="form-control" name="name_extension" value="<?php echo htmlspecialchars($user['name_extension']); ?>" />
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-3">
            <label>Birthdate *</label>
            <input type="date" class="form-control" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" required />
          </div>
          <div class="col-md-3">
            <label>Civil Status *</label>
            <select class="form-control" name="civil_status" required>
              <?php
              $statuses = ["Single", "Married", "Widowed", "Separated", "Divorced"];
              foreach ($statuses as $status) {
                $selected = $user['civil_status'] === $status ? 'selected' : '';
                echo "<option value=\"$status\" $selected>$status</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Email *</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
          </div>
          <div class="col-md-3">
            <label>Gender *</label>
            <select class="form-control" name="gender" required>
              <?php
              $genders = ["male" => "Male", "female" => "Female", "LGBTQIA" => "LGBTQIA+"];
              foreach ($genders as $value => $label) {
                $selected = $user['gender'] === $value ? 'selected' : '';
                echo "<option value=\"$value\" $selected>$label</option>";
              }
              ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Info -->
    <div class="row mt-4">
      <div class="col-md-3">
        <label>Phone Number *</label>
        <input type="tel" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required />
      </div>
      <div class="col-md-3">
        <label>Occupation</label>
        <input type="text" class="form-control" name="occupation" value="<?php echo htmlspecialchars($user['occupation']); ?>" />
      </div>
      <div class="col-md-3">
        <label>Residence Since</label>
        <input type="text" class="form-control" name="residence_since" value="<?php echo htmlspecialchars($user['residence_since']); ?>" />
      </div>
    </div>

    <!-- Home Address -->
    <h4 class="mt-4">Home Address</h4>
    <div class="row">
      <div class="col-md-6">
        <label>House Address</label>
        <input type="text" class="form-control" name="house_address" value="<?php echo htmlspecialchars($user['house_address']); ?>" />
      </div>
      <div class="col-md-3">
        <label>Barangay</label>
        <input type="text" class="form-control" name="barangay" value="Sta. Lucia" readonly style="background-color: #e9ecef; color: #6c757d;" />
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="reset" class="btn btn-secondary w-100">Clear</button>
        <button type="submit" name="save" class="btn btn-primary w-100 mx-2">Save</button>
      </div>
    </div>
  </form>

  <!-- Password Change Section -->
  <form action="accountInfo.php" method="POST" id="passwordChangeForm">
    <h4 class="mt-4">Change Password</h4>
    <div class="row">
      <div class="col-md-6">
        <label>New Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="newPassword" name="new_password" oninput="checkPasswordStrength()" required />
          <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('newPassword')">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
        <div id="passwordStrengthMessage" class="mt-2"></div>
      </div>
      <div class="col-md-6">
        <label>Confirm New Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required />
          <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" onclick="togglePasswordVisibility('confirmPassword')">
            <i class="bi bi-eye" id="eyeConfirmIcon"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
      <button type="button" class="btn btn-danger" id="changePasswordBtn">Change Password</button>
    </div>

    <!-- Modal for Password Change Confirmation -->
    <div class="modal fade" id="confirmChangePasswordModal" tabindex="-1" aria-labelledby="confirmChangePasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center">
            <h3>Are you sure you want to change your password?</h3>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="save_password" class="btn btn-danger px-4 ms-3">Yes, Change Password</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Success Modal for Account Info -->
  <?php if (isset($_GET['status']) && $_GET['status'] === 'saved'): ?>
    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="saveModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Your account information has been successfully saved.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      var saveModal = new bootstrap.Modal(document.getElementById('saveModal'));
      saveModal.show();
    </script>
  <?php endif; ?>

  <!-- Success Modal for Password Change -->
  <?php if (isset($_GET['status']) && $_GET['status'] === 'password_changed'): ?>
    <div class="modal fade" id="passwordChangedModal" tabindex="-1" aria-labelledby="passwordChangedModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="passwordChangedModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Your password has been successfully changed.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      var passwordChangedModal = new bootstrap.Modal(document.getElementById('passwordChangedModal'));
      passwordChangedModal.show();
    </script>
  <?php endif; ?>
</main>

<footer class="text-light py-4 mt-5">
  <div class="container">
    <div class="row">
      <!-- Barangay Info -->
      <div class="col-md-3">
        <img src="../media/logo.png" alt="Logo" width="60" height="60" class="mb-2 rounded-circle">
        <h5>Barangay Information</h5>
        <p><strong>Barangay Name, Province</strong></p>
        <p><strong>Office Hours:</strong><br>Monday - Friday: 8:00 AM - 5:00 PM</p>
        <p><strong>Contact Info:</strong><br>Email: info@barangay.gov.ph</p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-3">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="resident_dashboard.php" class="text-light text-decoration-none">Home</a></li>
          <li><a href="submitReqForm.php" class="text-light text-decoration-none">Submit Request</a></li>
          <li><a href="reportEmergency.php" class="text-light text-decoration-none">Report Emergencies</a></li>
          <li><a href="viewAnnouncement.php" class="text-light text-decoration-none">View Announcements</a></li>
          <li><a href="bookAppointment.php" class="text-light text-decoration-none">Appointments</a></li>
          <li><a href="submitFeedback.php" class="text-light text-decoration-none">Submit Feedback</a></li>
        </ul>
      </div>

      <!-- Account Links -->
      <div class="col-md-3">
        <h5>My Account</h5>
        <ul class="list-unstyled">
          <li><a href="accountInfo.php" class="text-light text-decoration-none">Profile</a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="text-light text-decoration-none">Logout</a></li>
        </ul>
      </div>

      <!-- Social Links -->
      <div class="col-md-3">
        <h5>Follow Us</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-light text-decoration-none"><i class="bi bi-globe me-1"></i>Official Website</a></li>
          <li><a href="#" class="text-light text-decoration-none"><i class="bi bi-facebook me-1"></i>Facebook</a></li>
          <li><a href="#" class="text-light text-decoration-none"><i class="bi bi-twitter me-1"></i>Twitter</a></li>
        </ul>
      </div>
    </div>

    <hr class="border-secondary my-3" />
    <p class="text-center mb-0">&copy; <?php echo date("Y"); ?> Bayanihan Hub. All Rights Reserved.</p>
  </div>
</footer>

<script src="../public/js/toggle.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Button to open the confirmation modal
    document.getElementById('changePasswordBtn').addEventListener('click', function() {
      var newPassword = document.getElementById('newPassword').value;
      var confirmPassword = document.getElementById('confirmPassword').value;

      // Basic validation before showing modal
      if (newPassword === "") {
        alert("Please enter a new password.");
        return;
      }
      
      if (confirmPassword === "") {
        alert("Please confirm your password.");
        return;
      }
      
      if (newPassword !== confirmPassword) {
        alert("Passwords do not match!");
        return;
      }
      
      // Show the confirmation modal
      var modal = new bootstrap.Modal(document.getElementById('confirmChangePasswordModal'));
      modal.show();
    });
  });

  // Toggle password visibility
  function togglePasswordVisibility(passwordFieldId) {
    var passwordField = document.getElementById(passwordFieldId);
    var iconId = passwordFieldId === 'newPassword' ? 'eyeIcon' : 'eyeConfirmIcon';
    var icon = document.getElementById(iconId);
    
    if (passwordField.type === "password") {
      passwordField.type = "text";
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
    } else {
      passwordField.type = "password";
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
    }
  }

  // Password strength checker
  function checkPasswordStrength() {
    var password = document.getElementById("newPassword").value;
    var strengthMessage = document.getElementById("passwordStrengthMessage");
    var strength = "Weak";

    // Regular expressions for password strength
    var lowerCase = /[a-z]/;
    var upperCase = /[A-Z]/;
    var numbers = /[0-9]/;
    var specialCharacters = /[!@#$%^&*(),.?":{}|<>]/;

    if (password.length >= 8 && lowerCase.test(password) && upperCase.test(password) && numbers.test(password) && specialCharacters.test(password)) {
      strength = "Strong";
    } else if (password.length >= 6 && lowerCase.test(password) && upperCase.test(password)) {
      strength = "Medium";
    }

    strengthMessage.textContent = "Password Strength: " + strength;
    strengthMessage.style.color = strength === "Strong" ? "green" : (strength === "Medium" ? "orange" : "red");
  }
</script>
<script>
  // Function to open the image modal
  function openImageModal() {
    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
  }

  // Initialize any bootstrap components once the document is loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Any additional initialization can go here
  });
</script>
</body>
</html>