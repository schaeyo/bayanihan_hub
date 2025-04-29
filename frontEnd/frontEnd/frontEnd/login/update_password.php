<?php
session_start();
include '../../db_connection.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_POST['password'] === $_POST['confirm_password']) {
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];

    $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $newPassword, $email);
    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        echo "Password successfully updated. <a href='signin.php'>Login</a>";
    } else {
        echo "Failed to update password. Try again.";
    }
} else {
    echo "Passwords do not match. <a href='reset_password.php'>Try again</a>";
}
?>
