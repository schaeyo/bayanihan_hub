<?php

// Database configuration
$host = "localhost"; // MySQL server (use "127.0.0.1" if "localhost" doesn't work)
$username = "root"; // Replace with your MySQL username
$password = "@Marvin23"; // Replace with your MySQL password
$database = "bayanihan_hub"; // Replace with your database name

// Create a connection
$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
