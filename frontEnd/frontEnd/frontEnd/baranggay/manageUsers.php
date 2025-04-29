<?php
// filepath: c:\Users\mnava\Desktop\frontEnd\frontEnd\frontEnd\baranggay\manageUsers.php

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

// Handle Promote to Admin, Change Role, or Remove Account Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $action = $_POST['action'];
    $user_id = $_POST['user_id'];

    if ($action === 'promote') {
        // Update the role to admin
        $update_query = "UPDATE users SET role = 'admin' WHERE user_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } elseif ($action === 'remove') {
        // Remove the user account
        $delete_query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $con->prepare($delete_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } elseif ($action === 'change_role' && isset($_POST['new_role'])) {
        // Change the user's role
        $new_role = $_POST['new_role'];
        $update_query = "UPDATE users SET role = ? WHERE user_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("si", $new_role, $user_id);
        $stmt->execute();
    }

    // Redirect to avoid form resubmission
    header("Location: manageUsers.php");
    exit();
}

// Fetch all users from the database with optional search and filter
$search = $_GET['search'] ?? '';
$filter_role = $_GET['filter_role'] ?? '';
$users = [];

$sql = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name, email, role 
        FROM users 
        WHERE (role = ? OR ? = '') 
          AND (CONCAT(first_name, ' ', last_name) LIKE ? OR email LIKE ?)";
$stmt = $con->prepare($sql);
$search_param = '%' . $search . '%';
$stmt->bind_param("ssss", $filter_role, $filter_role, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bayanihan Hub - Manage Users</title>
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
                <i class="bi bi-person-circle mx-3"></i>Hi, <?php echo htmlspecialchars($user['first_name']); ?>
            </a>
            <a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Home</a>
            <a href="data-analytics.php"><i class="bi bi-graph-up"></i> Data Analytics</a>
            <a href="appointmentRequest.php"><i class="bi bi-calendar-check"></i> Appointment Requests</a>
            <a href="reviewRequest.php"><i class="bi bi-file-earmark-check"></i> Review Request</a>
            <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users and Roles</a>
            <a href="announceManage.php"><i class="bi bi-megaphone"></i> Announcement Management</a>
            <a href="emergencyResponse.php"><i class="bi bi-exclamation-triangle"></i> Emergency Response Coordination</a>
            <a href="viewFeedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a>
            <a href="dbManage.php"><i class="bi bi-database"></i> Data Management</a>
            <a href="../../signin.php" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <!-- Toggle Button -->
    <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Header -->
    <div class="text-light" style="background-color: #001a4f">
        <div class="container brand-header">
            <img src="../media/logo2.png" alt="Bayanihan Hub Logo" width="90px" height="90px" style="border-radius: 50%" />
            <h3 class="mx-3 mt-2">Bayanihan Hub</h3>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mt-2 p-3">
        <div class="container">
            <h2>Manage Users</h2>

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

            <!-- Filter and Search -->
            <form method="GET" action="manageUsers.php" class="d-flex mb-3">
                <input 
                    class="form-control me-2" 
                    type="search" 
                    name="search" 
                    placeholder="Search by name or email" 
                    value="<?= htmlspecialchars($search) ?>" 
                    style="width: 300px"
                />
                <select name="filter_role" class="form-select me-2" style="width: 200px">
                    <option value="" <?= $filter_role === '' ? 'selected' : '' ?>>All Roles</option>
                    <option value="resident" <?= $filter_role === 'resident' ? 'selected' : '' ?>>Resident</option>
                    <option value="admin" <?= $filter_role === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <button class="btn btn-primary" type="submit">Filter</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>
                                    <td>
    <?php if ($user['role'] === 'resident'): ?>
        <!-- Button to trigger the promote to admin modal -->
        <button class="btn btn-success btn-sm" onclick="confirmPromote(<?= $user['user_id'] ?>)">Promote to Admin</button>
    <?php endif; ?>
    
    <!-- Button to trigger the delete modal -->
    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $user['user_id'] ?>)">Remove Account</button>
    
    <form method="POST" action="manageUsers.php" style="display: inline;">
        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
        <select name="new_role" class="form-select form-select-sm d-inline-block" style="width: auto;">
            <option value="resident" <?= $user['role'] === 'resident' ? 'selected' : '' ?>>Resident</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <button class="btn btn-warning btn-sm" name="action" value="change_role">Change Role</button>
    </form>
</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal for Account Deletion -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this account? This action cannot be undone!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="manageUsers.php" style="display: inline;">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <button type="submit" class="btn btn-danger" name="action" value="remove">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Promote to Admin -->
<div class="modal fade" id="promoteToAdminModal" tabindex="-1" aria-labelledby="promoteToAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promoteToAdminModalLabel">Confirm Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to promote this resident to an admin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="promoteForm" method="POST" action="manageUsers.php" style="display: inline;">
                    <input type="hidden" name="user_id" id="promoteUserId">
                    <button type="submit" class="btn btn-success" name="action" value="promote">Yes, Promote</button>
                </form>
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
    <script>
    // Function to set user ID for account deletion
    function confirmDelete(userId) {
        document.getElementById('deleteUserId').value = userId;
        new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
    }

    // Function to set user ID for promotion
    function confirmPromote(userId) {
        document.getElementById('promoteUserId').value = userId;
        new bootstrap.Modal(document.getElementById('promoteToAdminModal')).show();
    }
</script>
</body>
</html>