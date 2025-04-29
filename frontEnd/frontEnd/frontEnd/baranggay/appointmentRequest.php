<?php
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
// Optional: Verify if current user is an admin
// Example: if ($_SESSION['role'] !== 'admin') { header("Location: unauthorized.php"); exit(); }


$query = "SELECT * FROM appointments ORDER BY appointment_date DESC";
$result = $con->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bayanihan Hub - Appointments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../public/css/home.css" />
</head>

<body class="d-flex flex-column min-vh-100">
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
      <div class="mt-5">
        <a href="accountInfo.php" class="active p-2"><i class="bi bi-person-circle mx-3"></i>Hi, <?php echo htmlspecialchars($user['first_name']); ?></a>
        <a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Home</a>
        <a href="data-analytics.php"><i class="bi bi-graph-up"></i> Data Analytics</a>
        <a href="appointmentRequest.php"><i class="bi bi-calendar-check"></i> Appointment Requests</a>
        <a href="reviewRequest.php"><i class="bi bi-file-earmark-check"></i> Review Request</a>
        <a href="manageUsers.php"><i class="bi bi-people"></i>  Manage Users and Roles</a>
        <a href="announceManage.php"><i class="bi bi-megaphone"></i> Announcement Management</a>
        <a href="emergencyResponse.php"><i class="bi bi-exclamation-triangle"></i> Emergency Response Coordination</a>
        <a href="viewFeedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a>
        <a href="dbManage.php"><i class="bi bi-database"></i> Data Management</a>
        <a href="../login/signin.php" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right"></i> Logout</a>
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
<!-- Toggle Button -->
<button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

<!-- Header -->
<div class="text-light" style="background-color: #001a4f">
  <div class="container brand-header">
    <img src="../media/logo2.png" alt="Bayanihan Hub Logo" width="90" height="90" style="border-radius: 50%;" />
    <h3 class="mx-3 mt-2">Bayanihan Hub</h3>
  </div>
</div>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Appointment Requests</h2>
    <table class="table table-bordered table-striped">
      <thead class="table-background-primary text-light">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Date</th>
          <th>Message</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= htmlspecialchars($row['status'] ?? 'Pending') ?></td>
            <td>
              <form action="updateAppointmentStatus.php" method="POST" class="d-inline">
                <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
              </form>
              <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">
                Delete
              </button>
              
              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="deleteModalLabel<?= $row['id'] ?>">Confirm Deletion</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                      <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                      <h4 class="mt-2">Are you sure you want to delete this appointment?</h4>
                      <p>Appointment for: <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                      Date: <strong><?= htmlspecialchars($row['appointment_date']) ?></strong></p>
                      <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                      <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                      <form action="deleteAppointment.php" method="POST" class="d-inline">
                        <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger px-4">Yes, Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
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
      .table-background-primary {
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
<script src="../public/js/toggle.js"></script>
</body>
</html>