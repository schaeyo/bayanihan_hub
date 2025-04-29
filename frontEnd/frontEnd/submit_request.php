<?php
// filepath: c:\Users\mnava\Desktop\frontEnd\frontEnd\submit_request.php
include 'db_connection.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? null;
    $last_name = $_POST['last_name'];
    $name_extension = $_POST['name_extension'] ?? null;
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'] ?? null;
    $birth_place = $_POST['birth_place'];
    $citizenship = $_POST['citizenship'];
    $civil_status = $_POST['civil_status'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $residence_since = !empty($_POST['residence_since']) ? $_POST['residence_since'] : null; // Validate residence_since
    $residence_duration = $_POST['residence_duration'] ?? null;
    $type_of_request = $_POST['type_of_request'];

    // Handle file upload
    $valid_id = $_FILES['valid_id'];
    $upload_dir = 'req_photos_resident/'; // Directory to save uploaded files
    $valid_id_path = $upload_dir . basename($valid_id['name']);

    // Ensure the uploads directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($valid_id['tmp_name'], $valid_id_path)) {
        // Insert data into the database
        $sql = "INSERT INTO requests (
                    first_name, middle_name, last_name, name_extension, birthdate, age, 
                    birth_place, citizenship, civil_status, gender, email, 
                    residence_since, residence_duration, type_of_request, valid_id_path
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "sssssssssssssss",
            $first_name, $middle_name, $last_name, $name_extension, $birthdate, $age,
            $birth_place, $citizenship, $civil_status, $gender, $email,
            $residence_since, $residence_duration, $type_of_request, $valid_id_path
        );

        if ($stmt->execute()) {
            header("Location: frontEnd/resident/submitReqForm.php?submitted=1"); 
            exit();
        } else {
            header("Location: frontEnd/resident/submitReqForm.php?submitted=0");
            exit();
        }
    } else {
        // Redirect back to the form with an error message for file upload
        header("Location: frontEnd/resident/submitReqForm.php");
        exit();
    }
}
?>
