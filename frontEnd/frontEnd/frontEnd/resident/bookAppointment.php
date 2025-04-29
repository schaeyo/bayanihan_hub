<?php

session_start();
include '../../db_connection.php';

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Fetch user details from the database
$user = null;
if ($user_id) {
  $query = "SELECT first_name,last_name, phone_number, email FROM users WHERE user_id = ?";
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
    <title>Bayanihan Hub - Review Request</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons CDN -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />
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
    <!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header border-0">
        <h5 class="modal-title w-100 text-success" id="successModalLabel">Success!</h5>
      </div>
      <div class="modal-body">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
        <p class="mt-3 mb-0">Your appointment has been successfully booked.</p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Okay</button>
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

    <!-- Appointment Form Section -->
    <div class="container mt-5">
      <h2 class="text-center mb-4">Appointment Booking</h2>
      <form action="../../saveAppointment.php" method="POST">
      <div class="mb-3">
  <label for="name" class="form-label">Name:</label>
  <input
    type="text"
    class="form-control"
    id="name"
    name="name"
    placeholder="Enter your full name"
    value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>"
    required
  />
</div>
          <div class="mb-3">
  <label for="email" class="form-label">Email:</label>
  <input
    type="email"
    class="form-control"
    id="email"
    name="email"
    placeholder="Enter your email"
    value="<?php echo htmlspecialchars($user['email']); ?>"
    required
  />
</div>

        <div class="col-md-">
  <label class="form-label">Phone Number *</label>
  <input type="tel" class="form-control" name="phone_number" required pattern="[0-9]{11}" title="Please enter an 11-digit phone number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" />
</div>

        <div class="mb-3">
          <label for="date" class="form-label">Date:</label>
          <input
            type="date"
            class="form-control"
            id="date"
            name="date"
            required
          />
          <div class="mb-3">
  <label for="service" class="form-label">Select Service:</label>
  <select class="form-select" id="service" name="service" required>
    <option value="" disabled selected>Select a service</option>
    <option value="Barangay ID">Barangay ID</option>
    <option value="Police Assistance">Police Assistance</option>
    <option value="Health Services">Health Services</option>
    <option value="Senior Citizen Services">Senior Citizen Services</option>
    <option value="Business Registration">Business Registration</option>
    <option value="Funeral Request">Funeral Request</option>
    <option value="Barangay Inquiries and Requests">Barangay Inquiries and Requests</option>
    <option value="Event Permit">Event Permit</option>
    <option value="National ID Registration Assistance">National ID Registration Assistance</option>
    <option value="Mediation / Settlement">Mediation / Settlement</option>
    <option value="Complaint Filing">Complaint Filing</option>
  </select>
</div>

        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message:</label>
          <textarea
            class="form-control"
            id="message"
            name="message"
            rows="3"
            placeholder="Enter additional details (optional)"
          ></textarea>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-submit w-100">
            Book Appointment
          </button>
        </div>
      </form>
    </div>


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
    <script src="../public/js/sidebar.js"></script>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
  });
</script>
<?php endif; ?>
  </body>
</html>
