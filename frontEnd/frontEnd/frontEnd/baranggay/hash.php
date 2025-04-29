<?php

session_start();
include '../../db_connection.php';
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch all users
$sql = "SELECT user_id, password FROM users";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];
        $currentPassword = $row['password'];

        // Check if password is already hashed (very basic check)
        if (password_get_info($currentPassword)['algo'] == 0) {
            // Not hashed yet
            $hashedPassword = password_hash($currentPassword, PASSWORD_DEFAULT);

            // Update the password to the hashed version
            $update = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt = $con->prepare($update);
            $stmt->bind_param("si", $hashedPassword, $userId);
            $stmt->execute();
            echo "Password for user ID $userId has been hashed.<br>";
        } else {
            echo "Password for user ID $userId is already hashed.<br>";
        }
    }
} else {
    echo "No users found.";
}

$con->close();
?>
