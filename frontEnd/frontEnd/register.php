<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize input
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Check if all fields are filled
    if (empty($username) || empty($email) ||empty($phone_number) || empty($password) || empty($confirmPassword)) {
        echo "<script>
                alert('All fields are required.');
                window.location.href = 'frontEnd/login/signup.php';
              </script>";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>
                alert('Passwords do not match.');
                window.location.href = 'frontEnd/login/signup.php';
              </script>";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (username, email, phone_number, password,role) VALUES (?, ?,?, ?, 'RESIDENT')";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $con->error);
    }

    $stmt->bind_param("ssss", $username ,  $email, $phone_number , $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>
                alert('Account created successfully!');
                window.location.href = 'frontEnd/login/signin.php';
              </script>";
    } else {
        echo "<script>
                alert('Error creating account. Please try again.');
                window.location.href = 'frontEnd/login/signin.php';
              </script>";
    }

    $stmt->close();
    $con->close();
}
?>