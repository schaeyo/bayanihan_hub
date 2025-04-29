<?php
session_start();

include '../../db_connection.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Fetch user details
$user = null;
if ($user_id) {
    $query = "SELECT first_name FROM users WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Check if we're in edit mode
$edit_mode = false;
$edit_announcement = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = "SELECT * FROM announcements WHERE id = ?";
    $edit_stmt = $con->prepare($edit_query);
    $edit_stmt->bind_param("i", $edit_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    if ($edit_result->num_rows > 0) {
        $edit_announcement = $edit_result->fetch_assoc();
        $edit_mode = true;
    }
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
  <title>Announcement Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../public/css/home.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
</head>
<body>
  <style>
    footer {
        margin-top: auto;
        background: #130d33;

  }
  </style>

<!-- Sidebar, header, and modal here (unchanged) -->
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
      <div class="mt-5">
        <a href="accountInfo.php" class="active p-2"><i class="bi bi-person-circle mx-3"></i>Hi, <?php echo htmlspecialchars($user['first_name'] ?? 'User'); ?></a>
        <a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Home</a>
        <a href="data-analytics.php"><i class="bi bi-graph-up"></i> Data Analytics</a>
        <a href="appointmentRequest.php"><i class="bi bi-calendar-check"></i> Appointment Requests</a>
        <a href="reviewRequest.php"><i class="bi bi-file-earmark-check"></i> Review Request</a>
        <a href="manageUsers.php"><i class="bi bi-people"></i>  Manage Users and Roles</a>
        <a href="announceManage.php"><i class="bi bi-megaphone"></i> Announcement Management</a>
        <a href="emergencyResponse.php"><i class="bi bi-exclamation-triangle"></i> Emergency Response Coordination</a>
        <a href="viewFeedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a>
        <a href="dbManage.php"><i class="bi bi-database"></i> Data Management</a>
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
        <img
          src="../media/logo2.png"
          alt="Bayanihan Hub Logo"
          width="90px"
          height="90px"
          style="border-radius: 50%"
        />
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

    <div class="container mt-4">
  <h2><?php echo $edit_mode ? 'Edit Announcement' : 'Create New Announcement' ?></h2>
  
  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Success</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Announcement posted successfully!</div>
        <div class="modal-footer"><button class="btn btn-success" data-bs-dismiss="modal">OK</button></div>
      </div>
    </div>
  </div>
  
  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Deleted</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Announcement deleted successfully.</div>
        <div class="modal-footer"><button class="btn btn-danger" data-bs-dismiss="modal">OK</button></div>
      </div>
    </div>
  </div>
  
  <!-- Update Success Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Updated</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Announcement updated successfully!</div>
        <div class="modal-footer"><button class="btn btn-info" data-bs-dismiss="modal">OK</button></div>
      </div>
    </div>
  </div>

  <form action="<?php echo $edit_mode ? '../../updateAnnouncement.php' : '../../announceProcess.php'; ?>" method="POST" enctype="multipart/form-data">
    <?php if ($edit_mode): ?>
      <input type="hidden" name="announcement_id" value="<?php echo $edit_announcement['id']; ?>">
    <?php endif; ?>
    
    <div class="row mb-3">
      <label class="col-md-3 col-form-label">Announcement Title:</label>
      <div class="col-md-9">
        <input type="text" name="title" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_announcement['title']) : ''; ?>" required />
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-md-3 col-form-label">Description:</label>
      <div class="col-md-9">
        <textarea name="description" class="form-control" rows="3" required><?php echo $edit_mode ? htmlspecialchars($edit_announcement['description']) : ''; ?></textarea>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-md-3 col-form-label">Category:</label>
      <div class="col-md-9">
        <select name="category" class="form-select" required>
          <option value="Barangay Events" <?php echo ($edit_mode && $edit_announcement['category'] == 'Barangay Events') ? 'selected' : ''; ?>>Barangay Events</option>
          <option value="Public Notice" <?php echo ($edit_mode && $edit_announcement['category'] == 'Public Notice') ? 'selected' : ''; ?>>Public Notice</option>
          <option value="Emergency Alerts" <?php echo ($edit_mode && $edit_announcement['category'] == 'Emergency Alerts') ? 'selected' : ''; ?>>Emergency Alerts</option>
          <option value="Community Program" <?php echo ($edit_mode && $edit_announcement['category'] == 'Community Program') ? 'selected' : ''; ?>>Community Program</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-md-3 col-form-label">Upload File:</label>
      <div class="col-md-9">
        <input type="file" name="file_upload" class="form-control" />
        <?php if ($edit_mode && !empty($edit_announcement['file_path'])): ?>
          <div class="mt-2">
            <small class="text-muted">Current file: <?php echo basename($edit_announcement['file_path']); ?></small>
            <input type="hidden" name="existing_file" value="<?php echo htmlspecialchars($edit_announcement['file_path']); ?>">
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-md-3 col-form-label">Schedule Date:</label>
      <div class="col-md-9 d-flex">
        <input type="date" name="schedule_date" class="form-control me-2" value="<?php echo $edit_mode ? htmlspecialchars($edit_announcement['schedule_date']) : ''; ?>" required />
        <button type="reset" class="btn btn-secondary me-2">Clear</button>
        <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update' : 'Post'; ?></button>
        <?php if ($edit_mode): ?>
          <a href="announceManage.php" class="btn btn-outline-secondary ms-2">Cancel</a>
        <?php endif; ?>
      </div>
    </div>
  </form>
</div>

<div class="container mt-5">
  <h2>List of Past Announcements</h2>

  <!-- Category Filter Pills -->
  <ul class="nav nav-pills justify-content-center mb-4">
    <li class="nav-item"><a class="nav-link <?= !isset($_GET['category']) ? 'active' : '' ?>" href="announceManage.php">All</a></li>
    <li class="nav-item"><a class="nav-link <?= ($_GET['category'] ?? '') === 'Barangay Events' ? 'active' : '' ?>" href="?category=Barangay Events">Barangay Events</a></li>
    <li class="nav-item"><a class="nav-link <?= ($_GET['category'] ?? '') === 'Public Notice' ? 'active' : '' ?>" href="?category=Public Notice">Public Notice</a></li>
    <li class="nav-item"><a class="nav-link <?= ($_GET['category'] ?? '') === 'Emergency Alerts' ? 'active' : '' ?>" href="?category=Emergency Alerts">Emergency Alerts</a></li>
    <li class="nav-item"><a class="nav-link <?= ($_GET['category'] ?? '') === 'Community Program' ? 'active' : '' ?>" href="?category=Community Program">Community Program</a></li>
  </ul>

  <div class="row g-4">
    <?php foreach ($announcements as $announce): ?>
      <div class="col-md-4">
        <div class="border p-3 shadow-sm">
          <?php if (!empty($announce['file_path'])): ?>
            <img src="../../<?= htmlspecialchars($announce['file_path']) ?>" class="img-fluid mb-2" style="max-height:150px;" alt="Attachment">
          <?php else: ?>
            <img src="../media/announcement-placeholder.jpg" class="img-fluid mb-2" alt="Default Image">
          <?php endif; ?>
          <h5><?= htmlspecialchars($announce['title']) ?></h5>
          <span class="badge bg-secondary mb-2"><?= htmlspecialchars($announce['category']) ?></span>
          <p><?= nl2br(htmlspecialchars($announce['description'])) ?></p>
          <p><small>Scheduled on: <?= htmlspecialchars($announce['schedule_date']) ?></small></p>
          <p><small>Posted on: <?= htmlspecialchars($announce['created_at']) ?></small></p>
          <a href="announceManage.php?edit=<?= $announce['id'] ?>" class="btn btn-warning btn-sm me-2">Edit</a>
          <a href="../../deleteAnnouncement.php?id=<?= $announce['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
        </div>
      </div>
    <?php endforeach; ?>
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
      .appointment-form {
        background-color: #f8f8f8;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(182, 37, 37, 0.1);
      }
      .form-label {
        font-weight: bold;
      }
      .btn-submit {
        background-color: #001a4f;
        color: white;
      }
      .btn-submit:hover {
        background-color: #090549;
      }
      
    </style>
    
<footer class="text-light py-4 mt-5" style="background-color: #130d33;">
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
          <li><a href="admin_dashboard.php" class="text-light text-decoration-none">Home</a></li>
          <li><a href="data-analytics.php" class="text-light text-decoration-none">Data Analytics</a></li>
          <li><a href="reviewRequest.php" class="text-light text-decoration-none">Review Request</a></li>
          <li><a href="manageUsers.php" class="text-light text-decoration-none">Manage Users</a></li>
          <li><a href="announceManage.php" class="text-light text-decoration-none">Announcements</a></li>
          <li><a href="emergencyResponse.php" class="text-light text-decoration-none">Emergency Response</a></li>
          <li><a href="dbManage.php" class="text-light text-decoration-none">Data Management</a></li>
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
    
<script>
  document.addEventListener('DOMContentLoaded', () => {
  if (window.location.search.includes('success')) {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();

    // Clean URL after showing modal
    if (history.pushState) {
      const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
      window.history.pushState({ path: newUrl }, '', newUrl);
    }
  
      
      // Clean URL after showing modal
      if (history.pushState) {
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.pushState({path: newUrl}, '', newUrl);
      }
    }

    // Check for deleted parameter
    if (window.location.search.includes('deleted')) {
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
      
      // Clean URL after showing modal
      if (history.pushState) {
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.pushState({path: newUrl}, '', newUrl);
      }
    }
    
    // Check for updated parameter
    if (window.location.search.includes('updated')) {
      const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
      updateModal.show();
      
      // Clean URL after showing modal
      if (history.pushState) {
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.pushState({path: newUrl}, '', newUrl);
      }
    }
  });
</script>
<script>
// Function to get query parameters
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Show modals based on query parameters
document.addEventListener('DOMContentLoaded', function () {
    if (getQueryParam('success') === 'true') {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }
    if (getQueryParam('updated') === 'true') {
        var updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
        updateModal.show();
    }
    if (getQueryParam('deleted') === 'true') {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
});
</script>
<script src="../public/js/toggle.js"></script>
</body>
</html>