<?php
include 'db_connection.php';

// New plain text password
$newPassword = '1234';

// Hash it securely
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the resident's password (change the username or email if needed)
$sql = "UPDATE users SET password = ? WHERE username = 'resident'";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $hashed);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Resident's password updated successfully.";
} else {
    echo "Update failed or no changes made.";
}

$stmt->close();
$con->close();
