<?php

session_start();
include '../../db_connection.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Fetch user details from the database
$user = null;
if ($user_id) {
  $query = "SELECT first_name, middle_name, last_name, name_extension,birthdate, email FROM users WHERE user_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <base href="/frontEnd/frontEnd/resident/" />
  <title>Bayanihan Hub - Review Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../public/css/home.css" />
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
    }
    footer a {
      text-decoration: none;
    }
  </style>
</head>
<body>

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
    <a href="../../signin.php" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>
</div>

  <!-- Toggle Button -->
  <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
  </button>

  <!-- Header -->
  <div class="text-light" style="background-color: #001a4f">
    <div class="container brand-header d-flex align-items-center py-3">
      <img src="../media/logo2.png" alt="Bayanihan Hub Logo" width="90" height="90" style="border-radius: 50%" />
      <h3 class="mx-3 mt-2">Bayanihan Hub</h3>
    </div>
  </div>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <h3>Are you sure you want to log out?</h3>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
          <a href="../login/signin.php" class="btn btn-danger px-4 ms-3">Yes, Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <main class="container px-3 mt-4">
    <h2 class="mb-4">Submit Request Form</h2>
    <form action="../../submit_request.php" method="POST" enctype="multipart/form-data">
      <!-- Row 1 -->
      <div class="row mb-3">
        <div class="col-md-2">
          <label class="form-label">First Name *</label>
          <input type="text" class="form-control" name="first_name" required value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Middle Name</label>
          <input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($user['middle_name'] ?? ''); ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Last Name *</label>
          <input type="text" class="form-control" name="last_name" required value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Name Extension</label>
          <input type="text" class="form-control" name="name_extension" value="<?php echo htmlspecialchars($user['name_extension'] ?? ''); ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Birthdate *</label>
          <input type="date" class="form-control" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" />
        </div>
      </div>

      <!-- Row 2 -->
      <div class="row mb-3">
        <div class="col-md-2">
          <label class="form-label">Age</label>
          <input type="number" class="form-control" name="age" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Birth Place *</label>
          <input type="text" class="form-control" name="birth_place" required />
        </div>
        <div class="col-md-2">
          <label class="form-label">Citizenship *</label>
          <input type="text" class="form-control" name="citizenship" required />
        </div>
        <div class="col-md-2">
          <label class="form-label">Civil Status *</label>
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
        <div class="col-md-2">
          <label class="form-label">Gender *</label>
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

      <!-- Row 3 -->
      <div class="row mb-3">
        <div class="col-md-3">
          <label class="form-label">Email *</label>
          <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Residence Since</label>
          <input type="date" class="form-control" name="residence_since" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Residence Duration</label>
          <input type="number" class="form-control" name="residence_duration" placeholder="Years" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Type of Request *</label>
          <select class="form-control" name="type_of_request" required>
            <option value="Barangay Clearance">Barangay Clearance</option>
            <option value="Business Permit">Business Permit</option>
            <option value="Indigency Certificate">Indigency Certificate</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Valid ID *</label>
          <input type="file" class="form-control" name="valid_id" required />
        </div>
      </div>

      <!-- Buttons -->
      <div class="row mt-5">
        <div class="col-md-12 d-flex justify-content-end">
          <button type="reset" class="btn btn-secondary me-3 px-4">Clear</button>
          <button type="submit" class="btn btn-primary px-4">Submit</button>
        </div>
      </div>
    </form>
  </main>

  <!-- Feedback Modal -->
  <div class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Request Submitted</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Your request form has been successfully submitted!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Optional Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Submission Failed</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        There was a problem submitting your request. Please try again.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
  </div>
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
    </style>

<footer class=" text-light py-4 mt-5">
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
          <li><a href="../login/signin.php" class="text-light text-decoration-none">Logout</a></li>
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
  // Show modal if the 'submitted' parameter is present in the URL
  document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('submitted') === '1') {
    const submitModal = new bootstrap.Modal(document.getElementById('submitModal'));
    submitModal.show();
  } else if (urlParams.get('submitted') === '0') {
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  }
});
</script>
</body>
</html>
