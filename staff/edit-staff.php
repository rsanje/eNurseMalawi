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

// Fetch employee details for editing
$user_id = $_GET['user_id'];
$sql = "SELECT * FROM users INNER JOIN staff ON users.user_id = staff.user_id WHERE users.user_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $employeeDetails = $result->fetch_assoc();
    } else {
        die("Error fetching employee details.");
    }
    $stmt->close();
} else {
    die("Error preparing statement.");
}

// Close database connection
$conn->close();
?>

<body class="bg-gray-100">
<div class="container mx-auto px-4 mt-8">
    <h1 class="text-2xl font-bold mb-4">Edit Employee Details</h1>
    <form action="update-staff.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo $employeeDetails['first_name']; ?>" required>
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $employeeDetails['last_name']; ?>" required>
        </div>
        <div>
            <label>Employee Code:</label>
            <input type="text" name="emp_code" value="<?php echo $employeeDetails['emp_code']; ?>" required>
        </div>
        <div>
            <label>Department:</label>
            <input type="text" name="department" value="<?php echo $employeeDetails['department']; ?>" required>
        </div>
        <div>
            <label>Position:</label>
            <input type="text" name="position" value="<?php echo $employeeDetails['position']; ?>" required>
        </div>
         <div>
            <label>Role:</label>
            <input type="text" name="role" value="<?php echo $employeeDetails['role']; ?>" required>
        </div>
        <div>
            <label>National ID:</label>
            <input type="text" name="national_id" value="<?php echo $employeeDetails['national_id']; ?>" required>
        </div>
        <div>
            <label>Date of Birth:</label>
            <input type="text" name="birth_date" value="<?php echo $employeeDetails['birth_date']; ?>" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Details</button>
        </div>
    </form>
</div>
</body>

<?php require 'footer.php'; ?>
