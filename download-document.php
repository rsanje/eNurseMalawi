<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config.php and db.php files
require_once 'config.php';
require_once 'db.php';

// Check if document_id parameter is set in the URL
if (isset($_GET['document_id'])) {
    // Get the document_id from the URL parameter
    $document_id = $_GET['document_id'];

    // Prepare a select statement to fetch document details
    $sql = "SELECT description, filename, file_content, file_type FROM document WHERE document_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $document_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if document exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($description, $filename, $file_content, $file_type);

                // Fetch document details
                if ($stmt->fetch()) {
                    // Set appropriate content type header based on file type
                    header("Content-type: $file_type");
                    // Set filename for download
                    header("Content-Disposition: attachment; filename=\"$filename\"");
                    // Output file content
                    echo $file_content;
                    exit;
                }
            } else {
                // Document not found, redirect to an error page or handle the error accordingly
                header("location: error.php");
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

// If document_id parameter is not set or document is not found, redirect to an error page or handle the error accordingly
header("location: view_documents.php");
exit;
?>

