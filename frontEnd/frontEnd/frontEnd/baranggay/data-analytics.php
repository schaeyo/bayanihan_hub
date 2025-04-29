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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bayanihan Hub - Account Information</title>

  <!-- Bootstrap CSS and Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../public/css/home.css" />

  <!-- Chart.js, jsPDF, html2canvas -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <style>
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .chart-container {
      height: 300px;
    }
    html {
      height: 100%;
    }
    body {
      min-height: 100%;
      display: flex;
      flex-direction: column;
    }
    footer{
      margin-top: auto;
      background-color: #001a4f;
    }
    .chart-type-toggle {
      cursor: pointer;
      padding: 5px 10px;
      background-color: #f8f9fa;
      border-radius: 5px;
      display: inline-flex;
      align-items: center;
      margin-bottom: 10px;
    }
    .chart-type-toggle:hover {
      background-color: #e2e6ea;
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
        <a href="../../signin.php" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right"></i> Logout</a>
      </div>
    </div>

  <!-- Toggle Button -->
  <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
  </button>

  <!-- Logo and Header -->
  <div class="text-light" style="background-color: #001a4f">
    <div class="container brand-header d-flex align-items-center py-2">
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

  <!-- Main Dashboard -->
  <div class="container-fluid">
    <div class="row header py-3">
      <div class="col-md-6"><h1 class="ms-3">Data Analytics Dashboard</h1></div>
      <div class="col-md-6 text-end pe-4">
        <button class="btn btn-light" onclick="exportToPDF()">Export Data</button>
      </div>
    </div>

    <div id="exportSection">
      <div class="row mt-4 g-4">
        <!-- Population by Age and Gender -->
<div class="col-md-6">
  <div class="card text-center bg-light">
    <div class="card-body">
      <h5 class="card-title">Population by Age Groups</h5>
      
      <div class="row mb-3">
        <div class="col-6">
          <label for="inputChildren" class="form-label">Children (0-14)</label>
          <div class="input-group">
            <span class="input-group-text bg-primary text-white">M</span>
            <input type="number" class="form-control" id="inputChildrenMale" value="1500" placeholder="Male">
            <span class="input-group-text bg-warning text-dark">F</span>
            <input type="number" class="form-control" id="inputChildrenFemale" value="1400" placeholder="Female">
          </div>
        </div>
        <div class="col-6">
          <label for="inputYouth" class="form-label">Youth (15-24)</label>
          <div class="input-group">
            <span class="input-group-text bg-primary text-white">M</span>
            <input type="number" class="form-control" id="inputYouthMale" value="1200" placeholder="Male">
            <span class="input-group-text bg-warning text-dark">F</span>
            <input type="number" class="form-control" id="inputYouthFemale" value="1300" placeholder="Female">
          </div>
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-6">
          <label for="inputAdults" class="form-label">Adults (25-64)</label>
          <div class="input-group">
            <span class="input-group-text bg-primary text-white">M</span>
            <input type="number" class="form-control" id="inputAdultsMale" value="2800" placeholder="Male">
            <span class="input-group-text bg-warning text-dark">F</span>
            <input type="number" class="form-control" id="inputAdultsFemale" value="2600" placeholder="Female">
          </div>
        </div>
        <div class="col-6">
          <label for="inputSeniors" class="form-label">Seniors (65+)</label>
          <div class="input-group">
            <span class="input-group-text bg-primary text-white">M</span>
            <input type="number" class="form-control" id="inputSeniorsMale" value="500" placeholder="Male">
            <span class="input-group-text bg-warning text-dark">F</span>
            <input type="number" class="form-control" id="inputSeniorsFemale" value="700" placeholder="Female">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        
        <!-- Residential Areas -->
        <div class="col-md-6">
          <div class="card text-center bg-light">
            <div class="card-body">
              <h5 class="card-title">Residential Areas</h5>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="inputSingleFamily" class="form-label">Single Family</label>
                  <input type="number" class="form-control" id="inputSingleFamily" value="750" placeholder="Units">
                </div>
                <div class="col-md-4">
                  <label for="inputMultiFamily" class="form-label">Multi Family</label>
                  <input type="number" class="form-control" id="inputMultiFamily" value="350" placeholder="Units">
                </div>
                <div class="col-md-4">
                  <label for="inputApartments" class="form-label">Apartments</label>
                  <input type="number" class="form-control" id="inputApartments" value="400" placeholder="Units">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="inputOccupied" class="form-label">Occupied</label>
                  <input type="number" class="form-control" id="inputOccupied" value="1200" placeholder="Units">
                </div>
                <div class="col-md-6">
                  <label for="inputVacant" class="form-label">Vacant</label>
                  <input type="number" class="form-control" id="inputVacant" value="300" placeholder="Units">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <!-- Population Chart -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Population by Age & Gender</h5>
                <div class="chart-type-toggle" id="populationChartToggle">
                  <i class="bi bi-bar-chart me-1"></i>
                  <span>Switch to Pie</span>
                </div>
              </div>
              <div class="chart-container"><canvas id="populationChart"></canvas></div>
            </div>
          </div>
        </div>
        
        <!-- Residential Chart -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Residential Distribution</h5>
                <div class="chart-type-toggle" id="residentialChartToggle">
                  <i class="bi bi-pie-chart me-1"></i>
                  <span>Switch to Bar</span>
                </div>
              </div>
              <div class="chart-container"><canvas id="residentialChart"></canvas></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Save Button -->
    <div class="text-center mt-4 mb-5">
      <button class="btn btn-success" onclick="updateCharts()">Update Charts</button>
      <button class="btn btn-primary" onclick="saveToDatabase()">Save to Database</button>
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
    
    <!-- footer section -->
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
      </div>
    </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../public/js/toggle.js"></script>
  <script>
    let populationChart, residentialChart;
    let populationChartType = 'bar';
    let residentialChartType = 'pie';
    
    // Toggle chart types
    document.getElementById('populationChartToggle').addEventListener('click', function() {
      populationChartType = populationChartType === 'bar' ? 'pie' : 'bar';
      this.innerHTML = populationChartType === 'bar' 
        ? '<i class="bi bi-bar-chart me-1"></i><span>Switch to Pie</span>' 
        : '<i class="bi bi-pie-chart me-1"></i><span>Switch to Bar</span>';
      updateCharts();
    });
    
    document.getElementById('residentialChartToggle').addEventListener('click', function() {
      residentialChartType = residentialChartType === 'pie' ? 'bar' : 'pie';
      this.innerHTML = residentialChartType === 'pie' 
        ? '<i class="bi bi-pie-chart me-1"></i><span>Switch to Bar</span>' 
        : '<i class="bi bi-bar-chart me-1"></i><span>Switch to Pie</span>';
      updateCharts();
    });

    function updateCharts() {
  // Don't show loading indicator on initial page load
  const isInitialLoad = !window.chartsInitialized;
  
  if (!isInitialLoad) {
    // Only show loading on subsequent updates, not on page load
    Swal.fire({
      title: 'Updating Charts',
      text: 'Please wait while we refresh your data visualization...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
  }
  // Get population data
  const childrenMale = parseInt(document.getElementById("inputChildrenMale").value) || 0;
  const childrenFemale = parseInt(document.getElementById("inputChildrenFemale").value) || 0;
  const youthMale = parseInt(document.getElementById("inputYouthMale").value) || 0;
  const youthFemale = parseInt(document.getElementById("inputYouthFemale").value) || 0;
  const adultsMale = parseInt(document.getElementById("inputAdultsMale").value) || 0;
  const adultsFemale = parseInt(document.getElementById("inputAdultsFemale").value) || 0;
  const seniorsMale = parseInt(document.getElementById("inputSeniorsMale").value) || 0;
  const seniorsFemale = parseInt(document.getElementById("inputSeniorsFemale").value) || 0;
  
  // Get residential data
  const singleFamily = parseInt(document.getElementById("inputSingleFamily").value) || 0;
  const multiFamily = parseInt(document.getElementById("inputMultiFamily").value) || 0;
  const apartments = parseInt(document.getElementById("inputApartments").value) || 0;
  
  // Population Chart
  const popCtx = document.getElementById("populationChart").getContext("2d");
  if (populationChart) populationChart.destroy();
  
  if (populationChartType === 'bar') {
    populationChart = new Chart(popCtx, {
      type: "bar",
      data: {
        labels: ["Children (0-14)", "Youth (15-24)", "Adults (25-64)", "Seniors (65+)"],
        datasets: [
          {
            label: "Male",
            data: [childrenMale, youthMale, adultsMale, seniorsMale],
            backgroundColor: "rgba(0, 123, 255, 0.7)",
            borderWidth: 1
          },
          {
            label: "Female",
            data: [childrenFemale, youthFemale, adultsFemale, seniorsFemale],
            backgroundColor: "rgba(255, 193, 7, 0.7)",
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Population'
            }
          }
        }
      }
    });
  } else {
    // For pie chart, we'll show total by age group
    const totalChildren = childrenMale + childrenFemale;
    const totalYouth = youthMale + youthFemale;
    const totalAdults = adultsMale + adultsFemale;
    const totalSeniors = seniorsMale + seniorsFemale;
    
    populationChart = new Chart(popCtx, {
      type: "pie",
      data: {
        labels: ["Children (0-14)", "Youth (15-24)", "Adults (25-64)", "Seniors (65+)"],
        datasets: [{
          data: [totalChildren, totalYouth, totalAdults, totalSeniors],
          backgroundColor: [
            "rgba(0, 123, 255, 0.7)",
            "rgba(255, 193, 7, 0.7)",
            "rgba(40, 167, 69, 0.7)",
            "rgba(220, 53, 69, 0.7)"
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const value = context.raw;
                const percentage = Math.round((value / total) * 100);
                return `${context.label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  }

  // Residential Chart
  const resCtx = document.getElementById("residentialChart").getContext("2d");
  if (residentialChart) residentialChart.destroy();
  
  if (residentialChartType === 'pie') {
    residentialChart = new Chart(resCtx, {
      type: "pie",
      data: {
        labels: ["Single Family", "Multi Family", "Apartments"],
        datasets: [{
          data: [singleFamily, multiFamily, apartments],
          backgroundColor: [
            "rgba(40, 167, 69, 0.7)",
            "rgba(255, 193, 7, 0.7)",
            "rgba(0, 123, 255, 0.7)"
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const value = context.raw;
                const percentage = Math.round((value / total) * 100);
                return `${context.label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  } else {
    residentialChart = new Chart(resCtx, {
      type: "bar",
      data: {
        labels: ["Single Family", "Multi Family", "Apartments"],
        datasets: [{
          label: "Units",
          data: [singleFamily, multiFamily, apartments],
          backgroundColor: [
            "rgba(40, 167, 69, 0.7)",
            "rgba(255, 193, 7, 0.7)",
            "rgba(0, 123, 255, 0.7)"
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Units'
            }
          }
        }
      }
    });
  }
  
  // Close the loading indicator and show success message only if not initial load
  if (!isInitialLoad) {
    setTimeout(() => {
      Swal.fire({
        icon: 'success',
        title: 'Charts Updated!',
        text: 'Your data visualization has been refreshed successfully.',
        timer: 2000,
        showConfirmButton: false
      });
    }, 600); // Small delay for better UX
  }
  
  // Mark that charts have been initialized
  window.chartsInitialized = true;
}

// Main document ready function

document.addEventListener('DOMContentLoaded', function() {
  // Initialize charts without showing the loading indicator
  updateCharts();
});
document.addEventListener('DOMContentLoaded', function() {
  // Initialize charts without showing the loading indicator
  updateCharts();
  
  // Setup the toggle event listeners
  document.getElementById('populationChartToggle').addEventListener('click', function() {
    populationChartType = populationChartType === 'bar' ? 'pie' : 'bar';
    this.innerHTML = populationChartType === 'bar' 
      ? '<i class="bi bi-bar-chart me-1"></i><span>Switch to Pie</span>' 
      : '<i class="bi bi-pie-chart me-1"></i><span>Switch to Bar</span>';
    updateCharts();
  });
  
  document.getElementById('residentialChartToggle').addEventListener('click', function() {
    residentialChartType = residentialChartType === 'pie' ? 'bar' : 'pie';
    this.innerHTML = residentialChartType === 'pie' 
      ? '<i class="bi bi-pie-chart me-1"></i><span>Switch to Bar</span>' 
      : '<i class="bi bi-bar-chart me-1"></i><span>Switch to Pie</span>';
    updateCharts();
  });
  
  // Add any other initialization code here
  addSaveModals();
});

function saveToDatabase() {
  // Show confirmation dialog first
  Swal.fire({
    title: 'Save Data to Database?',
    text: "This will update the barangay statistics in the database. Continue?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, Save Data',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      // Show loading indicator
      Swal.fire({
        title: 'Saving Data',
        text: 'Please wait while we update the database...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      // Population data
      const childrenMale = document.getElementById("inputChildrenMale").value;
      const childrenFemale = document.getElementById("inputChildrenFemale").value;
      const youthMale = document.getElementById("inputYouthMale").value;
      const youthFemale = document.getElementById("inputYouthFemale").value;
      const adultsMale = document.getElementById("inputAdultsMale").value;
      const adultsFemale = document.getElementById("inputAdultsFemale").value;
      const seniorsMale = document.getElementById("inputSeniorsMale").value;
      const seniorsFemale = document.getElementById("inputSeniorsFemale").value;
      
      // Residential data
      const singleFamily = document.getElementById("inputSingleFamily").value;
      const multiFamily = document.getElementById("inputMultiFamily").value;
      const apartments = document.getElementById("inputApartments").value;
      const occupied = document.getElementById("inputOccupied").value;
      const vacant = document.getElementById("inputVacant").value;

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "../../save_data.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      
      xhr.onload = function () {
        if (this.status === 200) {
          Swal.fire({
            icon: 'success',
            title: 'Data Saved Successfully!',
            text: 'The barangay statistics have been updated in the database.',
            confirmButtonColor: '#28a745'
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error Saving Data',
            text: 'There was a problem updating the database. Please try again.',
            confirmButtonColor: '#dc3545'
          });
        }
      };
      
      xhr.onerror = function() {
        Swal.fire({
          icon: 'error',
          title: 'Connection Error',
          text: 'Could not connect to the server. Please check your internet connection.',
          confirmButtonColor: '#dc3545'
        });
      };
      
      xhr.send(
        `childrenMale=${childrenMale}&childrenFemale=${childrenFemale}` +
        `&youthMale=${youthMale}&youthFemale=${youthFemale}` +
        `&adultsMale=${adultsMale}&adultsFemale=${adultsFemale}` +
        `&seniorsMale=${seniorsMale}&seniorsFemale=${seniorsFemale}` +
        `&singleFamily=${singleFamily}&multiFamily=${multiFamily}` +
        `&apartments=${apartments}&occupied=${occupied}&vacant=${vacant}`
      );
    }
  });
}

// Update the export function to also use SweetAlert
async function exportToPDF() {
  // Show loading indicator
  Swal.fire({
    title: 'Generating PDF',
    text: 'Please wait while we create your report...',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  try {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF("p", "mm", "a4");
    const content = document.getElementById("exportSection");

    pdf.setFontSize(18);
    pdf.text("Barangay Data Report", 15, 20);
    pdf.setFontSize(10);
    pdf.text("Generated on: " + new Date().toLocaleString(), 15, 27);

    const canvas = await html2canvas(content);
    const imgData = canvas.toDataURL("image/png");
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth() - 30;
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    pdf.addImage(imgData, 'PNG', 15, 30, pdfWidth, pdfHeight);
    pdf.save("barangay-data-report.pdf");
    
    // Show success message
    Swal.fire({
      icon: 'success',
      title: 'PDF Generated!',
      text: 'Your report has been downloaded successfully.',
      timer: 2000,
      showConfirmButton: false
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error Generating PDF',
      text: 'There was a problem creating your report. Please try again.',
      confirmButtonColor: '#dc3545'
    });
  }
}

    async function exportToPDF() {
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF("p", "mm", "a4");
      const content = document.getElementById("exportSection");

      pdf.setFontSize(18);
      pdf.text("Barangay Data Report", 15, 20);
      pdf.setFontSize(10);
      pdf.text("Generated on: " + new Date().toLocaleString(), 15, 27);

      const canvas = await html2canvas(content);
      const imgData = canvas.toDataURL("image/png");
      const imgProps = pdf.getImageProperties(imgData);
      const pdfWidth = pdf.internal.pageSize.getWidth() - 30;
      const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

      pdf.addImage(imgData, 'PNG', 15, 30, pdfWidth, pdfHeight);
      pdf.save("barangay-data-report.pdf");
    }

    function saveToDatabase() {
      // Population data
      const childrenMale = document.getElementById("inputChildrenMale").value;
      const childrenFemale = document.getElementById("inputChildrenFemale").value;
      const youthMale = document.getElementById("inputYouthMale").value;
      const youthFemale = document.getElementById("inputYouthFemale").value;
      const adultsMale = document.getElementById("inputAdultsMale").value;
      const adultsFemale = document.getElementById("inputAdultsFemale").value;
      const seniorsMale = document.getElementById("inputSeniorsMale").value;
      const seniorsFemale = document.getElementById("inputSeniorsFemale").value;
      
      // Residential data
      const singleFamily = document.getElementById("inputSingleFamily").value;
      const multiFamily = document.getElementById("inputMultiFamily").value;
      const apartments = document.getElementById("inputApartments").value;
      const occupied = document.getElementById("inputOccupied").value;
      const vacant = document.getElementById("inputVacant").value;

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "../../save_data.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onload = function () {
        alert(this.responseText);
      };
      
      xhr.send(
        `childrenMale=${childrenMale}&childrenFemale=${childrenFemale}` +
        `&youthMale=${youthMale}&youthFemale=${youthFemale}` +
        `&adultsMale=${adultsMale}&adultsFemale=${adultsFemale}` +
        `&seniorsMale=${seniorsMale}&seniorsFemale=${seniorsFemale}` +
        `&singleFamily=${singleFamily}&multiFamily=${multiFamily}` +
        `&apartments=${apartments}&occupied=${occupied}&vacant=${vacant}`
      );
    }

    // Initial Chart Load
    updateCharts();
  </script>
  <script>
    // Save Confirmation Modal
function addSaveModals() {
  // Create the modals if they don't already exist
  if (!document.getElementById('saveConfirmModal')) {
    const modalHTML = `
      <!-- Save Confirmation Modal -->
      <div class="modal fade" id="saveConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Save Data to Database?</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>This will update the barangay statistics in the database. Continue?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success" id="confirmSaveBtn">Yes, Save Data</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Loading Modal -->
      <div class="modal fade" id="saveLoadingModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body text-center p-4">
              <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <h5>Saving Data</h5>
              <p>Please wait while we update the database...</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Success Modal -->
      <div class="modal fade" id="saveSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title">Data Saved Successfully!</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <p class="mt-3">The barangay statistics have been updated in the database.</p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Error Modal -->
      <div class="modal fade" id="saveErrorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">Error Saving Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3" id="errorMessageText">There was a problem updating the database. Please try again.</p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    `;

    // Append modals to the body
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = modalHTML;
    document.body.appendChild(modalContainer);

    // Add event listener to the confirm save button
    document.getElementById('confirmSaveBtn').addEventListener('click', function() {
      // Hide confirmation modal
      const confirmModal = bootstrap.Modal.getInstance(document.getElementById('saveConfirmModal'));
      confirmModal.hide();
      
      // Execute the save operation
      executeSaveToDatabase();
    });
  }
}

// Modified saveToDatabase function to use modals
function saveToDatabase() {
  // Make sure the modals are added
  addSaveModals();
  
  // Show confirmation modal
  const confirmModal = new bootstrap.Modal(document.getElementById('saveConfirmModal'));
  confirmModal.show();
}

// Function to execute the actual database save
function executeSaveToDatabase() {
  // Show loading modal
  const loadingModal = new bootstrap.Modal(document.getElementById('saveLoadingModal'));
  loadingModal.show();

   // Set a timeout to hide the loading modal after 5 seconds
   setTimeout(function() {
    // Hide the loading modal after 5 seconds
    const loadingModalInstance = bootstrap.Modal.getInstance(document.getElementById('saveLoadingModal'));
    loadingModalInstance.hide();

    // Show the success or error modal (assuming success or error will still be checked later)
    if (this.status === 200) {
      const successModal = new bootstrap.Modal(document.getElementById('saveSuccessModal'));
      successModal.show();

    }
  }, 2000); // 5000 milliseconds = 5 seconds
  
  // Population data
  const childrenMale = document.getElementById("inputChildrenMale").value;
  const childrenFemale = document.getElementById("inputChildrenFemale").value;
  const youthMale = document.getElementById("inputYouthMale").value;
  const youthFemale = document.getElementById("inputYouthFemale").value;
  const adultsMale = document.getElementById("inputAdultsMale").value;
  const adultsFemale = document.getElementById("inputAdultsFemale").value;
  const seniorsMale = document.getElementById("inputSeniorsMale").value;
  const seniorsFemale = document.getElementById("inputSeniorsFemale").value;
  
  // Residential data
  const singleFamily = document.getElementById("inputSingleFamily").value;
  const multiFamily = document.getElementById("inputMultiFamily").value;
  const apartments = document.getElementById("inputApartments").value;
  const occupied = document.getElementById("inputOccupied").value;
  const vacant = document.getElementById("inputVacant").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "../../save_data.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  
  xhr.onload = function () {
    // Hide loading modal
    const loadingModalInstance = bootstrap.Modal.getInstance(document.getElementById('saveLoadingModal'));
    loadingModalInstance.hide();
    
    if (this.status === 200) {
      // Show success modal
      const successModal = new bootstrap.Modal(document.getElementById('saveSuccessModal'));
      successModal.show();
    } else {
      // Show error modal
      document.getElementById('errorMessageText').textContent = 'There was a problem updating the database. Please try again.';
      const errorModal = new bootstrap.Modal(document.getElementById('saveErrorModal'));
      errorModal.show();
    }
  };
  
  xhr.onerror = function() {
    // Hide loading modal
    const loadingModalInstance = bootstrap.Modal.getInstance(document.getElementById('saveLoadingModal'));
    loadingModalInstance.hide();
    
    // Show error modal
    document.getElementById('errorMessageText').textContent = 'Could not connect to the server. Please check your internet connection.';
    const errorModal = new bootstrap.Modal(document.getElementById('saveErrorModal'));
    errorModal.show();
  };
  
  xhr.send(
    `childrenMale=${childrenMale}&childrenFemale=${childrenFemale}` +
    `&youthMale=${youthMale}&youthFemale=${youthFemale}` +
    `&adultsMale=${adultsMale}&adultsFemale=${adultsFemale}` +
    `&seniorsMale=${seniorsMale}&seniorsFemale=${seniorsFemale}` +
    `&singleFamily=${singleFamily}&multiFamily=${multiFamily}` +
    `&apartments=${apartments}&occupied=${occupied}&vacant=${vacant}`
  );
}

// Call this when the page loads to ensure the modals are added to the DOM
document.addEventListener('DOMContentLoaded', addSaveModals);
  </script>
</body>
</html>