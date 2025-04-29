<?php
session_start();
include '../../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /frontEnd/login/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profilePic']['tmp_name'];
    $fileName = basename($_FILES['profilePic']['name']);
    $uploadDir = 'profile_photo';
    $uploadPath = $uploadDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        $updateQuery = "UPDATE users SET profile_image = '$fileName' WHERE user_id = $user_id";
        mysqli_query($con, $updateQuery);
    }
}

header("Location: accountInfo.php");
exit;
?>
