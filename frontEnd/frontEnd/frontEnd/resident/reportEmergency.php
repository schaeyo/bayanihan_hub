<?php
session_start();
include '../../db_connection.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Fetch user details from the database
$user = null;
if ($user_id) {
    $query = "SELECT first_name, middle_name, last_name, name_extension, phone_number, email FROM users WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Check if the success parameter is set in the URL
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bayanihan Hub - Report Emergency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../public/css/home.css" />
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="mt-5">
        <a href="accountInfo.php" class="active p-2">
  <i class="bi bi-person-circle mx-3"></i>
  Hi, <?php echo htmlspecialchars($user['first_name'] ?? 'Guest'); ?>
</a>
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

    <!-- Logo and Name Header -->
    <div class="text-light" style="background-color: #001a4f">
        <div class="container brand-header">
            <img src="../media/logo2.png" alt="Bayanihan Hub Logo" width="90px" height="90px" style="border-radius: 50%" />
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

    <!-- Success Modal -->
    <?php if ($success): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Report Submitted</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Your emergency report has been successfully submitted. Our team will review it and take the necessary actions.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Automatically show the modal when the page loads
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    </script>
    <?php endif; ?>

    <main class="container mt-4">
        <h2 class="mb-4 text-center">Report Emergency</h2>
        <form action="../../submit_emergency.php" method="POST" enctype="multipart/form-data">
            <!-- Form Fields -->
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
                    <label class="form-label">Date *</label>
                    <input type="date" class="form-control" name="report_date" required />
                </div>
            </div>
            <!-- Additional Fields -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="form-label">Time *</label>
                    <input type="time" class="form-control" name="report_time" required />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Phone Number *</label>
                    <input type="tel" class="form-control" name="phone_number" required value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Upload File (if any)</label>
                    <input type="file" class="form-control" name="file" />
                </div>
            </div>
            <!-- Emergency Details -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Type of Emergency *</label>
                    <select class="form-control" name="type_of_emergency" required>
                        <option value="Fire">Fire</option>
                        <option value="Medical">Medical</option>
                        <option value="Crime">Crime</option>
                        <option value="Natural Disaster">Natural Disaster</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Location *</label>
                    <input type="text" class="form-control" name="location" placeholder="Enter full address or nearest landmark" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Description of Incident *</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Provide details about the incident" required></textarea>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="row mt-4">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="reset" class="btn btn-secondary me-3 px-4">Clear</button>
                    <button type="submit" class="btn btn-danger px-4">Report Emergency</button>
                </div>
            </div>
        </form>
    </main>

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
</body>
</html>