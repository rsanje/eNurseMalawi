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

// Retrieve emp_code from the session
if (isset($_SESSION["emp_code"])) {
    $emp_code = $_SESSION["emp_code"];
} else {
    // Handle the case where emp_code is not set in the session
    $error = "Employee code not set in the session.";
    // Optional: Redirect or handle error as appropriate
}

// Include database connection
require_once '../db.php';

// Initialize variables
$username = $emp_name = $start_date = $end_date = $position = $emp_address = $location = $emp_phone = $emp_email = '';
$error = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $username = trim($_POST["username"]);
    $emp_name = trim($_POST["emp_name"]);
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);
    $position = trim($_POST["position"]);
    $emp_address = trim($_POST["emp_address"]);
    $location = trim($_POST["location"]);
    $emp_phone = trim($_POST["emp_phone"]);
    $emp_email = trim($_POST["emp_email"]);

    // Check if all required fields are filled
    if (empty($username) || empty($emp_name) || empty($start_date) || empty($position) || empty($emp_address) || empty($location) || empty($emp_phone)) {
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

                // Insert employment details into employment table
                $sql = "INSERT INTO employment (emp_code, user_id, emp_name, start_date, end_date, position, emp_address, location, emp_phone, emp_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sissssssss", $emp_code, $user_id, $emp_name, $start_date, $end_date, $position, $emp_address, $location, $emp_phone, $emp_email);
                    if ($stmt->execute()) {
                        // Redirect to employment list page after successful insertion
                        header("Location: staff-details.php?user_id=" . $user_id);
                        exit;
                    } else {
                        $error = "Error occurred while adding employment details. Please try again.";
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
    <h1 class="text-2xl font-bold mb-4">Add New Employment</h1>

    <!-- Display error Message -->
    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Add Employment Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
            <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        
        <div class="mb-4">
            <label for="emp_name" class="block text-gray-700 font-bold mb-2">Employee Name:</label>
            <input type="text" id="emp_name" name="emp_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-gray-700 font-bold mb-2">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-gray-700 font-bold mb-2">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="position" class="block text-gray-700 font-bold mb-2">Position:</label>
            <input type="text" id="position" name="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="emp_address" class="block text-gray-700 font-bold mb-2">Employee Address:</label>
            <input type="text" id="emp_address" name="emp_address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-gray-700 font-bold mb-2">Location:</label>
            <input type="text" id="location" name="location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="emp_phone" class="block text-gray-700 font-bold mb-2">Employee Phone:</label>
            <input type="tel" id="emp_phone" name="emp_phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="emp_email" class="block text-gray-700 font-bold mb-2">Employee Email:</label>
            <input type="email" id="emp_email" name="emp_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Employment</button>
            <a href="employment.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
        </div>
    </form>

   
</div>

</body>

<?php require 'footer.php'; ?>
