<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

// Include database connection
require_once '../db.php';

// Check if qualification_no is provided in the URL
if (!isset($_GET['qualification_no']) || empty($_GET['qualification_no'])) {
    header("location: user-details.php");
    exit;
}

// Retrieve qualification_no from the URL parameter
$qualification_no = $_GET['qualification_no'];

// Prepare a delete statement
$sql = "DELETE FROM qualification WHERE qualification_no = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $qualification_no);
    
    if ($stmt->execute()) {
        header("location: user-details.php");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}
$conn->close();
?>
