<?php
session_start();
include '../../db_connection.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Fetch user details from the database
$user = null;
if ($user_id) {
    $query = "SELECT first_name, last_name, email, house_address, phone_number FROM users WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Fetch total counts for dashboard cards
$totalResidents = 0;
$totalRequests = 0;
$totalEmergencies = 0;
$totalOfficials = 0;

// Fetch total residents
$residentQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'resident'";
$residentResult = $con->query($residentQuery);
if ($residentResult) {
    $totalResidents = $residentResult->fetch_assoc()['total'];
}

// Fetch total requests
$requestQuery = "SELECT COUNT(*) AS total FROM requests";
$requestResult = $con->query($requestQuery);
if ($requestResult) {
    $totalRequests = $requestResult->fetch_assoc()['total'];
}

// Fetch total emergencies
$emergencyQuery = "SELECT COUNT(*) AS total FROM emergency_reports";
$emergencyResult = $con->query($emergencyQuery);
if ($emergencyResult) {
    $totalEmergencies = $emergencyResult->fetch_assoc()['total'];
}

// Fetch total barangay officials
$officialsQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'admin'";
$officialsResult = $con->query($officialsQuery);
if ($officialsResult) {
    $totalOfficials = $officialsResult->fetch_assoc()['total'];
}

// Fetch residents
$residentsQuery = "SELECT first_name, last_name, email, house_address, phone_number FROM users WHERE role = 'resident'";
$residentsResult = $con->query($residentsQuery);
$residents = $residentsResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bayanihan Hub - Dashboard</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="style.d.css" />
    <link rel="stylesheet" href="../public/css/home.css" />
    <style>
      html {
        height: 100%;
      }
      body {
        min-height: 100%;
        display: flex;
        flex-direction: column;
      }
      main {
        flex: 1;
      }
      footer {
        margin-top: auto;
        background: #130d33;
      }
      .residents-table-container {
        max-height: 300px;
        overflow-y: auto;
      }
      .cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  margin-top: 30px;
  padding: 0 20px;
}

.card {
  padding: 25px;
  color: white;
  border-radius: 20px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  cursor: pointer;
  text-align: center;
}

.card h2 {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.card p {
  font-size: 1.1rem;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 22px rgba(0, 0, 0, 0.2);
}

.card.blue {
  background: linear-gradient(to right, #007bff, #0056b3);
}
.card.green {
  background: linear-gradient(to right, #28a745, #218838);
}
.card.yellow {
  background: linear-gradient(to right, #ffc107, #e0a800);
  color: #000;
}
.card.red {
  background: linear-gradient(to right, #dc3545, #c82333);
}

main section {
  margin: 40px 20px;
  padding: 20px;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}

.residents-table-container {
  overflow-x: auto;
}

.table {
  margin-top: 15px;
}

.table thead {
  background-color: #001a4f;
  color: white;
}

.table tbody tr:hover {
  background-color: #f1f1f1;
  transition: 0.3s;
}
      
    </style>
  </head>
  <body>
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

    <!-- Main Content -->
    <main>
      <section class="cards">
        <div class="card blue" onclick="showInfo('residents')">
        <h2 class="counter" id="totalResidents"><?php echo $totalResidents; ?></h2>
          <p>Total Residents</p>
        </div>
        <div class="card green" onclick="showInfo('requests')">
          <h2 id="totalRequests"><?php echo $totalRequests; ?></h2>
          <p>Total Requests</p>
        </div>
        <div class="card yellow" onclick="showInfo('emergencies')">
          <h2 id="totalEmergencies"><?php echo $totalEmergencies; ?></h2>
          <p>Total Emergencies</p>
        </div>
        <div class="card red" onclick="showInfo('officials')">
          <h2 id="totalOfficials"><?php echo $totalOfficials; ?></h2>
          <p>Barangay Officials</p>
        </div>
      </section>

      <!-- Profile Section -->
      <section>
  <h3 class="mb-4">Profile</h3>
  <div class="card p-4 shadow-sm text-black">
    <div class="row">
      <div class="col-md-6 mb-2">
        <strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
      </div>
      <div class="col-md-6 mb-2">
        <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
      </div>
      <div class="col-md-6 mb-2">
        <strong>House Address:</strong> <?php echo htmlspecialchars($user['house_address']); ?>
      </div>
      <div class="col-md-6 mb-2">
        <strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?>
      </div>
    </div>
  </div>
</section>

      <!-- Residents Section -->
      <section class="mt-5">
        <h3>Residents</h3>
        <div class="residents-table-container">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>House Address</th>
                <th>Phone Number</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($residents as $resident): ?>
                <tr>
                  <td><?php echo htmlspecialchars($resident['first_name']); ?></td>
                  <td><?php echo htmlspecialchars($resident['last_name']); ?></td>
                  <td><?php echo htmlspecialchars($resident['email']); ?></td>
                  <td><?php echo htmlspecialchars($resident['house_address']); ?></td>
                  <td><?php echo htmlspecialchars($resident['phone_number']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Utility Interruption Notifications -->
      <section class="mt-5">
        <h3>Send Utility Interruption Notifications</h3>
        <div class="card p-4">
          <form id="notificationForm" method="POST" action="send_notification.php">
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Type of Interruption *</label>
                <select class="form-control" name="type" required>
                  <option value="electric">Electric</option>
                  <option value="water">Water</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Date *</label>
                <input type="date" class="form-control" name="date" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Time *</label>
                <input type="time" class="form-control" name="time" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Duration *</label>
                <input type="text" class="form-control" name="duration" placeholder="e.g., 2 hours" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12">
                <label class="form-label">Reason *</label>
                <textarea class="form-control" name="reason" rows="3" required></textarea>
              </div>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary">Send Notification</button>
            </div>
          </form>
        </div>
      </section>
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
    <script>
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
      }

      function showInfo(type) {
        alert("Showing information for: " + type);
      }
    </script>
    <script>
  document.querySelectorAll('.counter').forEach(el => {
    let finalValue = parseInt(el.innerText);
    let count = 0;
    let increment = finalValue / 40;
    let interval = setInterval(() => {
      count += increment;
      if (count >= finalValue) {
        el.innerText = finalValue;
        clearInterval(interval);
      } else {
        el.innerText = Math.floor(count);
      }
    }, 20);
  });
</script>
    <script src="../public/js/toggle.js"></script>
  </body>
</html>