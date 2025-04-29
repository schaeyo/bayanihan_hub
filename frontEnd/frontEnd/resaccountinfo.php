<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /frontEnd/login/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

// Process form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $name_extension = $_POST['name_extension'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $residence_since = $_POST['residence_since'] ?? '';
    $house_address = $_POST['house_address'] ?? '';
    $barangay = $_POST['barangay'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_image = $user['profile_image']; // Retain existing image by default

    // Password update
    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $con->query("UPDATE users SET password = '$hashed_password' WHERE user_id = '$user_id'");
    } elseif (!empty($new_password) || !empty($confirm_password)) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: /frontEnd/resident/accountInfo.php");
        exit;
    }

    // Handle profile photo upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilePic']['tmp_name'];
        $fileName = basename($_FILES['profilePic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowedExt)) {
            $uploadDir = 'profile_photo/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = uniqid('profile_photo_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_image = $newFileName;
            } else {
                $_SESSION['error'] = "Error uploading file.";
                header("Location: /frontEnd/resident/accountInfo.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and GIF allowed.";
            header("Location: /frontEnd/resident/accountInfo.php");
            exit;
        }
    }

    // Update user info
    $update_sql = "UPDATE users SET 
        first_name = '$first_name',
        middle_name = '$middle_name',
        last_name = '$last_name',
        name_extension = '$name_extension',
        birthdate = '$birthdate',
        civil_status = '$civil_status',
        email = '$email',
        gender = '$gender',
        phone_number = '$phone_number',
        occupation = '$occupation',
        residence_since = '$residence_since',
        house_address = '$house_address',
        barangay = '$barangay',
        profile_image = '$profile_image'
        WHERE user_id = '$user_id'";

    if ($con->query($update_sql) === TRUE) {
        $_SESSION['success'] = "Account updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating account: " . $con->error;
    }

    header("Location: /frontEnd/resident/accountInfo.php");
    exit;
}

$con->close();
?>
