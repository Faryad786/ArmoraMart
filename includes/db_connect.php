<?php
// Database configuration
$servername = "localhost";
$username = "root";  // default XAMPP username
$password = "";      // default XAMPP password
$dbname = "grocery_store";

// Create connection
try {
    // First check if database exists
    $temp_conn = new mysqli($servername, $username, $password);
    if (!$temp_conn->select_db($dbname)) {
        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        $temp_conn->query($sql);
    }
    $temp_conn->close();

    // Now connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to utf8
    $conn->set_charset("utf8");

} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>