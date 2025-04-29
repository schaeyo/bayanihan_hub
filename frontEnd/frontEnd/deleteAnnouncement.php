<?php
include 'db_connection.php'; // adjust path as needed

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // DELETE from the announcements table
    $stmt = $con->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: frontEnd/baranggay/announceManage.php?deleted=1");
        exit();
    } else {
        echo "Error deleting announcement.";
    }
} else {
    echo "No ID specified.";
}
?><?php
include 'db_connection.php'; // adjust path as needed

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // prevent SQL injection

    // Optional: delete the file too if needed
    $stmt = $con->prepare("SELECT file_path FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filePath);
    $stmt->fetch();
    $stmt->close();

    if (!empty($filePath) && file_exists($filePath)) {
        unlink($filePath); // delete the file
    }

    // Now delete the record
    $stmt = $con->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: frontEnd/baranggay/announceManage.php?deleted=1");
        exit();
    } else {
        echo "Failed to delete.";
    }
} else {
    echo "No ID provided.";
}
?>
