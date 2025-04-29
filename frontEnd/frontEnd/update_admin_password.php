<?php
include 'db_connection.php';

$hashed = password_hash('1234', PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ? WHERE username = 'admin' ,'resident'";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $hashed);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Password updated successfully.";
} else {
    echo "Password update failed or no change detected.";
}

$stmt->close();
$con->close();
