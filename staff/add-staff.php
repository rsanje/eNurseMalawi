<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once '../db.php';

// Initialize variables
$username = $emp_code = $department = $position = $role = $start_date = $email = $phone = "";
$error = '';

 function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    // Check if the date is valid and matches the format exactly
    if ($d && $d->format($format) === $date) {
        return $d->format('Ymd'); // Convert to SQL date format
    } else {
        return false; // Invalid date, handle accordingly
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $username = trim($_POST["username"]);
    $emp_code = trim($_POST["emp_code"]);
    $department = trim($_POST["department"]);
    $position = trim($_POST["position"]);
    $role = trim($_POST["role"]);
     $start_date = validateDate(trim($_POST["start_date"]), 'Y-m-d');
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

   
    // Check if all required fields are filled
    if (empty($username) || empty($emp_code) || empty($department) || empty($position) || empty($role) || empty($start_date) || empty($email) || empty($phone)) {
        $error = "All fields are required.";
    } else {
        // Check if username exists and retrieve user_id
        $sql = "SELECT user_id FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($user_id);
                $stmt->fetch();

                // Insert staff details into staff table





                $sql = "INSERT INTO staff (emp_code, user_id, department, position, start_date, email, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                   $stmt->bind_param("sississs", $emp_code, $user_id, $department, $position, $start_date, $email, $phone, $role);              
                    echo "Start Date: $start_date <br>";
 
                    if ($stmt->execute()) {
                        // Redirect to staff list page after successful insertion
                        header("Location: staff-details.php?user_id=" . $user_id);
                        exit;
                    } else {
                         $error = "Error occurred while adding qualification. Please try again.";
                    }
                }
            } else {
                $error = "Username not found.";
            }
            $stmt->close();
        } else {
            $error = "Error occurred. Please try again later.";
        }
    }
}

require 'header.php';
// Close connection
$conn->close();
?>

<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Add New Staff</h1>

    <!-- display error Message -->
    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Add Staff Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
            <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="emp_code" class="block text-gray-700 font-bold mb-2">Emp Code:</label>
            <input type="text" id="emp_code" name="emp_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="department" class="block text-gray-700 font-bold mb-2">Department:</label>
            <input type="text" id="department" name="department" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-gray-700 font-bold mb-2">Position:</label>
            <input type="text" id="position" name="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-gray-700 font-bold mb-2">Role:</label>
            <input type="text" id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
    <label for="start_date" class="block text-gray-700 font-bold mb-2">Start Date:</label>
    <input type="date" id="start_date" name="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="yyyy-mm-dd">
</div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Staff</button>
            <a href="staff.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
        </div>
    </form>
</div>

</body>

<?php require 'footer.php'; ?>
