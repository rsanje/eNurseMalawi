<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

// Include database connection
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an update statement
    $sql = "UPDATE users 
            SET 
            username = ?, 
            first_name = ?, 
            last_name = ?, 
            other_names = ?, 
            national_id = ?, 
            birth_date = ?, 
            place_of_birth = ?, 
            nationality = ?, 
            gender = ?, 
            email = ?, 
            phone = ?, 
            address = ?
            WHERE user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssssssi", 
            $_POST['username'], 
            $_POST['first_name'], 
            $_POST['last_name'], 
            $_POST['other_names'], 
            $_POST['national_id'], 
            $_POST['birth_date'], 
            $_POST['place_of_birth'], 
            $_POST['nationality'], 
            $_POST['gender'], 
            $_POST['email'], 
            $_POST['phone'], 
            $_POST['address'], 
            $_POST['user_id']
        );

        if ($stmt->execute()) {
            header("location: dashboard.php");  
            exit;
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
