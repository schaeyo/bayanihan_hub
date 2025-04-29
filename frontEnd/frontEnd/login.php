<?php
session_start(); // Start the session to manage user login state

include 'db_connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameOrEmail = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($usernameOrEmail) || empty($password)) {
        echo "<script>
                alert('Please fill in both username/email and password.');
                window.location.href = 'frontEnd/login/signin.php';
              </script>";
        exit;
    }

    // ✅ Only check by username or email
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $con->error);
    }

    // ✅ Bind both parameters properly
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Debug: Check if entered password and stored hash match
        echo "Entered password: " . $password . "<br>";
        echo "Stored hash: " . $row['password'] . "<br>";
        
        // ✅ This is key:
        if (password_verify($password, $row['password'])) {
            // ✅ Use correct column name for user_id (adjust if different in your DB)
            $_SESSION['user_id'] = $row['user_id']; 
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // ✅ Redirect based on role
            if ($row['role'] == 'ADMIN') {
                header("Location: frontEnd/baranggay/admin_dashboard.php");
                exit;
            } elseif ($row['role'] == 'RESIDENT') {
                header("Location: frontEnd/resident/resident_dashboard.php");
                exit;
            } else {
                echo "<script>
                        alert('Unknown role.');
                        window.location.href = 'frontEnd/login/signin.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Invalid password.');
                    window.location.href = 'frontEnd/login/signin.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('No user found with that username or email.');
                window.location.href = 'frontEnd/login/signin.php';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request method.');
            window.location.href = 'frontEnd/login/signin.php';
          </script>";
}
