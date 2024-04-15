<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php';

// Check if document_id parameter is set in the URL
if (isset($_GET['document_id'])) {
   
    $document_id = $_GET['document_id'];

    $sql = "SELECT description, filename, file_content, file_type FROM document WHERE document_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $document_id);
        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($description, $filename, $file_content, $file_type);

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

