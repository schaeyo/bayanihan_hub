<?php
include 'db_connection.php'; 
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
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

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $name_extension = $_POST['name_extension'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $residence_since = $_POST['residence_since'] ?? '';
    $house_number = $_POST['house_number'] ?? '';
    $street = $_POST['street'] ?? '';
    $barangay = $_POST['barangay'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_image = $user['profile_image']; // Keep current image by default

    // Password update
    if (!empty($new_password) && $new_password == $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password_sql = "UPDATE users SET password = '$hashed_password' WHERE user_id = '$user_id'";
        $con->query($update_password_sql);
    }

    // Profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
        $fileName = basename($_FILES['profilePic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowedExt)) {
            $uploadFileDir = 'profile_photo/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true); // Create dir if not exists
            }

            $newFileName = uniqid('profile_photo', true) . '.' . $fileExt;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_image = $newFileName;
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    }

    // Update user info
    $update_sql = "UPDATE users SET 
                    first_name = '$first_name', 
                    middle_name = '$middle_name', 
                    last_name = '$last_name', 
                    name_extension = '$name_extension', 
                    birthdate = '$birthdate', 
                    email = '$email', 
                    phone_number = '$phone_number', 
                    occupation = '$occupation', 
                    residence_since = '$residence_since', 
                    house_number = '$house_number', 
                    street = '$street', 
                    barangay = '$barangay', 
                    profile_image = '$profile_image'
                WHERE user_id = '$user_id'";

    if ($con->query($update_sql) === TRUE) {
        header("Location: frontEnd/baranggay/accountinfo.php");
        exit();
    } else {
        echo "Error updating record: " . $con->error;
    }
}

$con->close();
?>
