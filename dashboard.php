<?php
session_start();

// Check if the user is not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: home.php");
    exit;
}

// Include config.php and db.php files
require_once 'config.php';
require_once 'db.php';


// Prepare a select statement to retrieve user details
$sql = "SELECT username, email, first_name, last_name, national_id, birth_date, place_of_birth, nationality, gender, address FROM users WHERE user_id = ?";

if($stmt = $conn->prepare($sql)){
    // Bind variable to the prepared statement as parameter
    $stmt->bind_param("i", $param_id);

    // Set parameter
    $param_id = $_SESSION["user_id"];

    // Attempt to execute the prepared statement
    if($stmt->execute()){
        // Store result
        $stmt->store_result();

        // Check if user exists
        if($stmt->num_rows == 1){                    
            // Bind result variables
            $stmt->bind_result($username, $email, $first_name, $last_name, $national_id, $birth_date, $place_of_birth, $nationality, $gender, $address);
            $stmt->fetch();
        } else{
            // Redirect to error page if user details not found
            header("location: error.php");
            exit;
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <p><strong>Username:</strong> <?php echo $username; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>First Name:</strong> <?php echo $first_name; ?></p>
        <p><strong>Last Name:</strong> <?php echo $last_name; ?></p>
        <p><strong>National ID:</strong> <?php echo $national_id; ?></p>
        <p><strong>Birth Date:</strong> <?php echo $birth_date; ?></p>
        <p><strong>Place of Birth:</strong> <?php echo $place_of_birth; ?></p>
        <p><strong>Nationality:</strong> <?php echo $nationality; ?></p>
        <p><strong>Gender:</strong> <?php echo $gender; ?></p>
        <p><strong>Address:</strong> <?php echo $address; ?></p>
    </div>

    <!-- Logout Button -->
    <div class="flex items-center justify-center">
        <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Logout</a>
    </div>
</div>

</body>
</html>
