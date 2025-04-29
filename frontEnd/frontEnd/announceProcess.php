<?php
session_start();
include 'db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $schedule_date = $_POST['schedule_date'] ?? null;

    $filePath = null;

    // Handle file upload if a file is selected
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file_upload']['tmp_name'];
        $fileName = basename($_FILES['file_upload']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Create a unique filename to avoid overwrites
        $newFileName = uniqid('announcement_', true) . '.' . $fileExtension;
        $uploadDir = 'announcement/';
        $dest_path = $uploadDir . $newFileName;

        // Ensure 'announcement' folder exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $filePath = $dest_path;
        }
    }

    // Insert into announcements table
    $query = "INSERT INTO announcements (title, description, category, file_path, schedule_date, created_at)
              VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $title, $description, $category, $filePath, $schedule_date);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Announcement posted successfully!";
        header("Location: frontEnd/baranggay/announceManage.php?success=true");
    } else {
        $_SESSION['error'] = "Error posting announcement. Please try again.";
        header("Location: frontEnd/baranggay/announceManage.php?error=true");
    }
    exit();
}
?>
