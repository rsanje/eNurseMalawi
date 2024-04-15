<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

// Check if user_id is provided in the URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header("location: staff.php");
    exit;
}

// Include database connection
require_once '../db.php';
require 'header.php';

// Fetch employee details based on user_id
$user_id = $_GET['user_id'];
$sql = "SELECT users.username, users.first_name, users.last_name, staff.emp_code, staff.department, staff.role, staff.position, staff.start_date, staff.end_date, users.national_id, users.birth_date
        FROM users
        INNER JOIN staff ON users.user_id = staff.user_id
        WHERE users.user_id = ?";
$employeeDetails = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $employeeDetails = $result->fetch_assoc();
        } else {
            echo "Employee details not found.";
        }
    } else {
        echo "Error fetching employee details.";
    }
    $stmt->close();
} else {
    echo "Error preparing statement.";
}


?> 

<div class="container mx-auto px-4 mt-8 ">
    <h1 class="text-2xl font-bold mb-4">Employee Details</h1>

    <!-- Display employee details -->
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <p><strong>Username:</strong> <?php echo $employeeDetails['username']; ?></p>
        <p><strong>Full Name:</strong> <?php echo $employeeDetails['first_name'] . ' ' . $employeeDetails['last_name']; ?></p>
        <p><strong>Employee Code:</strong> <?php echo $employeeDetails['emp_code']; ?></p>
        <p><strong>Department:</strong> <?php echo $employeeDetails['department']; ?></p>
        <p><strong>Position:</strong> <?php echo $employeeDetails['position']; ?></p>
        <p><strong>Role:</strong> <?php echo $employeeDetails['role']; ?></p>
        <p><strong>National ID:</strong> <?php echo $employeeDetails['national_id']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $employeeDetails['birth_date']; ?></p>
        <p><strong>Employment Date:</strong> <?php echo $employeeDetails['start_date']; ?></p>
        <br>
        <p><a href="edit-staff.php?user_id=<?php echo $user_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit Details</a>
</p>


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

<?php 
require 'footer.php';
  // Close connection
$conn->close();

?>
