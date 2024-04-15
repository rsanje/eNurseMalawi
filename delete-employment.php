<?php
// Start the session and check user authentication and authorization
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Check if the user has the right permissions, assuming 'role' is stored in session
if ($_SESSION['role'] !== 'admin') {
    exit('You do not have permission to delete employment records. Contact Admin');
}

if (!isset($_GET['emp_no'])) {
    header("location: error.php?message=No employment No provided.");
    exit;
}

require_once 'db.php';

$emp_no = $_GET['emp_no'];

$sql = "DELETE FROM employment WHERE emp_no = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $emp_no);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $message = "Record deleted successfully.";
        } else {
            $message = "No record found or nothing to delete.";
        }
    } else {
        $message = "Error executing query: " . $conn->error;
    }
    $stmt->close();
} else {
    $message = "Error preparing query: " . $conn->error;
}
$conn->close();
header("location: user.php?message=" . urlencode($message));
exit;
?>
