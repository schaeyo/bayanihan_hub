<?php
// filepath: c:\Users\mnava\Desktop\frontEnd\frontEnd\frontEnd\resident\viewAnnouncements.php

session_start();
include '../../db_connection.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Fetch user details from the database
$user = null;
if ($user_id) {
    $query = "SELECT first_name FROM users WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Fetch announcements from the database
$category_filter = $_GET['category'] ?? null;
$announcements = [];

$sql = "SELECT * FROM announcements";
if ($category_filter) {
    $sql .= " WHERE category = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $category_filter);
} else {
    $stmt = $con->prepare($sql);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}
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
            <button
              type="button"
              class="btn btn-secondary px-4 text-center"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <a
              href="../login/signin.php"
              class="btn btn-danger text-center px-4 ms-3"
            >
              Yes, Logout
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4 mb-5">
        <h2 class="mb-4">Events and Announcements</h2>
        <!-- Filter Navigation -->
        <ul class="nav nav-pills justify-content-center mb-4">
            <li class="nav-item"><a class="nav-link <?= !$category_filter ? 'active' : '' ?>" href="viewAnnouncements.php">All</a></li>
            <li class="nav-item"><a class="nav-link <?= $category_filter === 'Barangay Events' ? 'active' : '' ?>" href="viewAnnouncements.php?category=Barangay Events">Barangay Events</a></li>
            <li class="nav-item"><a class="nav-link <?= $category_filter === 'Public Notice' ? 'active' : '' ?>" href="viewAnnouncements.php?category=Public Notice">Public Notice</a></li>
            <li class="nav-item"><a class="nav-link <?= $category_filter === 'Emergency Alerts' ? 'active' : '' ?>" href="viewAnnouncements.php?category=Emergency Alerts">Emergency Alerts</a></li>
            <li class="nav-item"><a class="nav-link <?= $category_filter === 'Community Program' ? 'active' : '' ?>" href="viewAnnouncements.php?category=Community Program">Community Program</a></li>
        </ul>

        <!-- Event Cards -->
        <div class="row g-4">
            <?php foreach ($announcements as $announcement): ?>
                <div class="col-md-4">
                    <div class="card text-light text-center p-3" style="background-color: #001a4f">
                        <?php if (!empty($announcement['file_path'])): ?>
                            <img src="../../<?= htmlspecialchars($announcement['file_path']) ?>" class="card-img-top" alt="Event Image" />
                        <?php else: ?>
                            <img src="../media/default.png" class="card-img-top" alt="Default Image" />
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($announcement['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($announcement['schedule_date']) ?></p>
                            <p class="card-text"><?= htmlspecialchars($announcement['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
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

