<?php
session_start();
include 'db_connection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the announcement ID
    $id = $_POST['announcement_id'];
    
    // Validate and sanitize inputs
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $schedule_date = $_POST['schedule_date'];
    $existing_file = $_POST['existing_file'] ?? '';
    
    // Initialize file path variable
    $file_path = $existing_file;
    
    // Check if a new file was uploaded
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $upload_dir = "uploads/";
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate a unique filename
        $filename = uniqid() . '_' . basename($_FILES['file_upload']['name']);
        $target_file = $upload_dir . $filename;
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
            $file_path = $target_file;
            
            // Remove old file if it exists and is different
            if (!empty($existing_file) && $existing_file != $file_path && file_exists($existing_file)) {
                unlink($existing_file);
            }
        }
    }
    
    // Update the announcement in the database
    $query = "UPDATE announcements SET 
              title = ?, 
              description = ?, 
              category = ?, 
              schedule_date = ?, 
              file_path = ?,
              updated_at = NOW() 
              WHERE id = ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $title, $description, $category, $schedule_date, $file_path, $id);
    
    if ($stmt->execute()) {
        // Redirect back to the announcements page with success message
        header("Location: frontEnd/baranggay/announceManage.php?updated=true");
        exit;
    } else {
        // Handle database error
        echo "Error updating announcement: " . $con->error;
    }
} else {
    // If the form wasn't submitted properly, redirect back
    header("Location: frontEnd/baranggay/announceManage.php?");
    exit;
}
?>