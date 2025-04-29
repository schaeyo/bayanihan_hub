<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user ID from session
    $user_id = $_SESSION['user_id'] ?? null;

    // Retrieve form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? null;
    $last_name = $_POST['last_name'];
    $name_extension = $_POST['name_extension'] ?? null;
    $feedback_date = $_POST['feedback_date'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $citizenship = $_POST['citizenship'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Insert data into the database
    $sql = "INSERT INTO feedback (
                user_id, first_name, middle_name, last_name, name_extension, feedback_date, 
                phone_number, email, citizenship, rating, comment
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param(
        "issssssssss", // Corrected type definition string
        $user_id, $first_name, $middle_name, $last_name, $name_extension, $feedback_date,
        $phone_number, $email, $citizenship, $rating, $comment
    );

    if ($stmt->execute()) {
        // Redirect back to the form with a success message
        header("Location: frontEnd/resident/submitFeedback.php?success=1");
        exit();
    } else {
        // Redirect back to the form with an error message
        header("Location: frontEnd/resident/submitFeedback.php?error=1");
        exit();
    }
}
?>
