<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

// Include database connection
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['user_id'], $_POST['reg_no'], $_POST['speciality'])) {
        // Prepare an update statement
        $sql = "UPDATE nurse 
                SET reg_no = ?, speciality = ?
                WHERE user_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $_POST['reg_no'], $_POST['speciality'], $_POST['user_id']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                header("location: dashboard.php");  
                exit;
            } else {
                echo "Error updating record: " . $stmt->error;
            }
            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "All required fields must be filled.";
    }
} else {
    // If the request is not a POST request, redirect to home page
    header("location: home.php");
    exit;
}

// Close database connection
$conn->close();
?>
