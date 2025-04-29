<?php
session_start();
if ($_POST['otp'] == $_SESSION['otp']) {
    header("Location: reset_password.php");
} else {
    echo "Incorrect OTP. <a href='verify_otp.php'>Try again</a>";
}
?>
