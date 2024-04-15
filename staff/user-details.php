<?php

global $user_id;
// Start session and check if user is logged in
session_start();

// Include database connection
require_once '../db.php';
require 'header.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if user_id is provided in the URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header("location: user.php");
    exit;
}



// Fetch user details based on user_id
$user_id = $_GET['user_id'];
$sql = "SELECT *
        FROM users u
        LEFT JOIN nurse n ON u.user_id = n.user_id
        WHERE u.user_id = ?";
$userDetails = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $userDetails = $result->fetch_assoc();
        } else {
            echo "User details not found.";
        }
    } else {
        echo "Error fetching user details.";
    }
    $stmt->close();
} else {
    echo "Error preparing statement.";
}
?>



<div class="container mx-auto px-4 mt-8">
    <!-- Display user details -->
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-xl font-bold mb-2">User Details</h1>
        <p><strong>Username:</strong> <?php echo $userDetails['username']; ?></p>
        <p><strong>Full Name:</strong> <?php echo $userDetails['first_name'] . ' ' . $userDetails['other_names'] . ' ' . $userDetails['last_name']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $userDetails['birth_date']; ?></p> 
        <p><strong>Place of Birth:</strong> <?php echo $userDetails['place_of_birth']; ?></p> 
        <p><strong>Nationality:</strong> <?php echo $userDetails['nationality']; ?></p>  
        <p><strong>National ID:</strong> <?php echo $userDetails['national_id']; ?></p>            
        <p><strong>Gender:</strong> <?php echo $userDetails['gender']; ?></p>
        <p><strong>Email:</strong> <?php echo $userDetails['email']; ?></p> 
        <p><strong>Phone:</strong> <?php echo $userDetails['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $userDetails['address']; ?></p>        
        <p class="mt-4"><a href="edit-user.php?user_id=<?php echo $user_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit Details</a></p>      
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-xl font-bold mb-2">Nurse Registration Details</h1>
        <p><strong>Registration Number:</strong> <?php echo $userDetails['reg_no']; ?></p>
        <p><strong>Speciality:</strong> <?php echo $userDetails['speciality']; ?></p>
        <p><strong>Registration Status:</strong> <?php echo $userDetails['reg_status']; ?></p>
        <p class='mt-4'><a href="edit-nurse.php?user_id=<?php echo $user_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold  py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit Details</a></p> 
    </div>
    
     <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
                   
        <?php
            // Include education history file
            require_once 'view-qualification.php';
        ?>
    </div>

    <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
        <?php
            // Include education history file
            require_once 'view-employment.php';
        ?>
    </div>

    <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
               
        <?php
            // Include education history file
            require_once 'view-document.php';
        ?>
    </div>


    
</div>

</body>
</html>
