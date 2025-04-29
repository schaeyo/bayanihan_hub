<?php
include '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['appointment_id'];
  $action = $_POST['action'];

  $status = ($action === 'approve') ? 'Approved' : 'Rejected';

  $stmt = $con->prepare("UPDATE appointments SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $id);
  $stmt->execute();
}

header("Location: appointmentsRequest.php");
exit();
