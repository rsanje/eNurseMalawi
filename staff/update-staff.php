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
require_once '../db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an update statement
    $sql = "UPDATE users INNER JOIN staff ON users.user_id = staff.user_id 
    SET users.first_name = ?, users.last_name = ?, staff.emp_code = ?, staff.department = ?, staff.position = ?, staff.role = ?, users.national_id = ?, users.birth_date = ? 
        WHERE users.user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssi", $_POST['first_name'], $_POST['last_name'], $_POST['emp_code'], $_POST['department'], $_POST['position'], $_POST['role'], $_POST['national_id'], $_POST['birth_date'], $_POST['user_id']);
        
        if ($stmt->execute()) {
            header("Location: staff-details.php?user_id=" . $_POST['user_id']);
        } else {
            $error = "Error occurred while adding qualification. Please try again.";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>
