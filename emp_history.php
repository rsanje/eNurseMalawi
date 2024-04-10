<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include required files
require_once 'db.php';

session_start(); // Initialize the session

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT emp_name, start_date, end_date, position, emp_address, location, emp_phone, emp_email FROM employment WHERE user_id = ? ORDER BY start_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($emp_name, $start_date, $end_date, $position, $emp_address, $location, $emp_phone, $emp_email);
?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Employment History</title>
                <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            </head>

            <body class="bg-gray-100 p-6">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-3xl font-bold mb-6">Employment History</h2>
                    <div class="overflow-x-auto rounded-md shadow-lg">                        
                        <?php while ($stmt->fetch()) { ?>
                            <div class="mid-white p-6  shadow-lg">
                                <h3 class="text-xl font-semibold mb-2"><?php echo $emp_name; ?> - <?php echo $position; ?></h3>
                                <p class="text-md text-gray-700 mb4"><strong>Facility: </strong><?php echo $location; ?></p>
                                <p class="text-md text-gray-700 mb4"><strong>Duration: </strong><?php echo $start_date; ?> - <?php echo $end_date; ?></p>
                                <p class="text-md text-gray-700 mb4"><strong>Address: </strong><?php echo $emp_address; ?></p>
                                <p class="text-md text-gray-700 mb4"><strong>Phone: </strong><?php echo $emp_phone; ?></p>
                                <p class="text-md text-gray-700 mb4"><strong>Email: </strong><?php echo $emp_email; ?></p>
                            </div>
                        <?php } ?>                           
                    </div>
                </div>
            </body>

            </html>

<?php
        } else {
            echo "No employment history found.";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection temporaliry disabled because of the error checkers on top
// $conn->close();
?>
