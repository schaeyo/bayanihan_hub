<?php
// filepath: c:\Users\mnava\Desktop\frontEnd\frontEnd\frontEnd\baranggay\dbManage.php

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

// Fetch data for Requests, Emergency Reports, and Feedback
$requests = [];
$emergency_reports = [];
$feedbacks = [];

// Fetch Requests
$request_query = "SELECT request_id, first_name AND last_name AS resident_name, status, date_submitted FROM requests ORDER BY date_submitted DESC";
$request_result = $con->query($request_query);
while ($row = $request_result->fetch_assoc()) {
    $requests[] = $row;
}

// Fetch Emergency Reports
$emergency_query = "SELECT emergency_id, first_name AND last_name AS resident_name, type_of_emergency, status, report_date FROM emergency_reports ORDER BY report_date DESC";
$emergency_result = $con->query($emergency_query);
while ($row = $emergency_result->fetch_assoc()) {
    $emergency_reports[] = $row;
}

// Fetch Feedback
$feedback_query = "SELECT feedback_id, first_name AND last_name AS resident_name, comment, status, feedback_date FROM feedback ORDER BY feedback_date DESC";
$feedback_result = $con->query($feedback_query);
while ($row = $feedback_result->fetch_assoc()) {
    $feedbacks[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bayanihan Hub - Data Management</title>
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
    <div class="mt-2 p-3">
        <div class="container">
            <h2>Data Management</h2>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="dataTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab" aria-controls="requests" aria-selected="true">Requests</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab" aria-controls="emergency" aria-selected="false">Emergency Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="feedback-tab" data-bs-toggle="tab" data-bs-target="#feedback" type="button" role="tab" aria-controls="feedback" aria-selected="false">Feedback</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="dataTabsContent">
                <!-- Requests Tab -->
                <div class="tab-pane fade show active" id="requests" role="tabpanel" aria-labelledby="requests-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Request ID</th>
                                    <th>Resident Name</th>
                                    <th>Status</th>
                                    <th>Date Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($requests) > 0): ?>
                                    <?php foreach ($requests as $request): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($request['request_id']) ?></td>
                                            <td><?= htmlspecialchars($request['resident_name']) ?></td>
                                            <td><?= htmlspecialchars($request['status']) ?></td>
                                            <td><?= htmlspecialchars(date("M d, Y", strtotime($request['date_submitted']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No requests found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Emergency Reports Tab -->
                <div class="tab-pane fade" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Emergency ID</th>
                                    <th>Resident Name</th>
                                    <th>Emergency Type</th>
                                    <th>Status</th>
                                    <th>Report Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($emergency_reports) > 0): ?>
                                    <?php foreach ($emergency_reports as $report): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($report['emergency_id']) ?></td>
                                            <td><?= htmlspecialchars($report['resident_name']) ?></td>
                                            <td><?= htmlspecialchars($report['type_of_emergency']) ?></td>
                                            <td><?= htmlspecialchars($report['status']) ?></td>
                                            <td><?= htmlspecialchars(date("M d, Y", strtotime($report['report_date']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No emergency reports found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Feedback Tab -->
                <div class="tab-pane fade" id="feedback" role="tabpanel" aria-labelledby="feedback-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Feedback ID</th>
                                    <th>Resident Name</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Feedback Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($feedbacks) > 0): ?>
                                    <?php foreach ($feedbacks as $feedback): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($feedback['feedback_id']) ?></td>
                                            <td><?= htmlspecialchars($feedback['resident_name']) ?></td>
                                            <td><?= htmlspecialchars($feedback['comment']) ?></td>
                                            <td><?= htmlspecialchars($feedback['status']) ?></td>
                                            <td><?= htmlspecialchars(date("M d, Y", strtotime($feedback['feedback_date']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No feedback found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
</body>
</html>