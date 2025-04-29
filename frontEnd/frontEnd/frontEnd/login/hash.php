<?php
include '../../db_connection.php';
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Select all users
$sql = "SELECT user_id, password FROM users";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['user_id'];
        $password = $row['password'];

        // Check if password looks already hashed (SHA256 = 64 hex characters)
        if (strlen($password) != 64 || !ctype_xdigit($password)) {
            // Hash the password
            $hashedPassword = hash('sha256', $password);

            // Update the password in the database
            $update = "UPDATE users SET password = '$hashedPassword' WHERE user_id = $id";
            $con->query($update);

            echo "Password for user ID $id has been hashed.<br>";
        } else {
            echo "Password for user ID $id already hashed. Skipped.<br>";
        }
    }
} else {
    echo "No users found.";
}

$con->close();
?>