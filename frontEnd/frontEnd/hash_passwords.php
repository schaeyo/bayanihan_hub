<?php
include 'db_connection.php';  // Include the database connection file

// Select all users with their passwords
$sql = "SELECT user_id, password FROM users";  // Assuming your table is 'users' and it has 'user_id' and 'password'
$result = $conn->query($sql);

// Loop through each user and hash their password
while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $plainPassword = $row['password'];  // Get the plain-text password from the database

    // Hash the password
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);  // Hash the password

    // Update the password in the database with the hashed version
    $updateSql = "UPDATE users SET password = ? WHERE user_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $hashedPassword, $user_id);  // "si" means string and integer
    $stmt->execute();
}

echo "Passwords have been hashed and updated!";
?>