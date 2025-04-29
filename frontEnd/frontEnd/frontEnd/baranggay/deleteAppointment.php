<?php
include '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['appointment_id'];

  $stmt = $con->prepare("DELETE FROM appointments WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: appointmentsRequest.php");
exit();
