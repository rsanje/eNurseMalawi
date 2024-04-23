<?php
// MySQL server configuration
require 'db.php';

// Database name and SQL file path
$database_name = "nmcm";
$sql_file_path = "database.sql"; 

// Create database
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $database_name";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Connect to the newly created database
$conn->select_db($database_name);

// Read SQL file
$sql_queries = file_get_contents($sql_file_path);

// Execute SQL queries
if ($conn->multi_query($sql_queries) === TRUE) {
    echo "SQL file executed successfully";
} else {
    echo "Error executing SQL file: " . $conn->error;
}
 


// Close connection
$conn->close();
?>
<br>
<a href="home.php">Public view</a>
<br>
<a href="login.php">Nurse Login</a>
<br>
<a href="staff/home.php">Staff Login</a>
