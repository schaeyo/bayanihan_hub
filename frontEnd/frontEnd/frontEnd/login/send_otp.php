<?php
session_start();
include '../../db_connection.php';
include 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    $query = $con->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['otp'] = $otp;

        $body = "<h3>Password Reset OTP</h3><p>Your OTP code is: <strong>$otp</strong></p>";
        $sent = createMailer($email, '', 'Reset your Bayanihan Hub password', $body);

        if ($sent === true) {
            header("Location: verify_otp.php");
        } else {
            echo "Failed to send OTP. Try again.";
        }
    } else {
        echo "Email not found in our records.";
    }
}
?>
