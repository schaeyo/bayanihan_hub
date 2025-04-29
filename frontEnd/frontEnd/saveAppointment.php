<?php


include 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $appointment_date = $_POST['date'] ?? '';
    $message = $_POST['message'] ?? '';

    // Insert data into the database
    $sql = "INSERT INTO appointments (name, email, phone, appointment_date, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $appointment_date, $message);

    if ($stmt->execute()) {
        // Redirect back to the form with a success message
        header("Location: frontEnd/resident/bookAppointment.php?success=1");
        exit();
    } 
}
?>