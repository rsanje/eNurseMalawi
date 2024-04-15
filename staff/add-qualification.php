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
$username = $institution = $certificate = $start_date = $end_date = $credits = $program = $description = $modules = '';
$error = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $username = trim($_POST["username"]);
    $institution = trim($_POST["institution"]);
    $certificate = trim($_POST["certificate"]);
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);
    $credits = isset($_POST["credits"]) ? intval($_POST["credits"]) : null;
    $program = trim($_POST["program"]);
    $description = trim($_POST["description"]);
    $modules = trim($_POST["modules"]);

    if (empty($username) || empty($institution) || empty($certificate) || empty($start_date) || empty($program) || empty($description) || empty($modules)) {
        $error = "All required fields must be filled out.";
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
                $emp_code = $_SESSION["emp_code"];  // Retrieve emp_code from session

                // Insert qualification details into qualification table
                $sql = "INSERT INTO qualification (user_id, emp_code, institution, certificate, start_date, end_date, credits, program, description, modules) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("isssssisss", $user_id, $emp_code, $institution, $certificate, $start_date, $end_date, $credits, $program, $description, $modules);
                    if ($stmt->execute()) {
                        // Redirect after successful insertion
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
    <h1 class="text-2xl font-bold mb-4">Add New Qualification</h1>

    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
            <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $username; ?>" required>
        </div>
        <div class="mb-4">
            <label for="institution" class="block text-gray-700 font-bold mb-2">Institution:</label>
            <input type="text" id="institution" name="institution" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="certificate" class="block text-gray-700 font-bold mb-2">Certificate:</label>
            <input type="text" id="certificate" name="certificate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-gray-700 font-bold mb-2">Start Date:</label>
            <input type="date" id="start_date" name="start_date"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-gray-700 font-bold mb-2">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="credits" class="block text-gray-700 font-bold mb-2">Credits:</label>
            <input type="number" id="credits" name="credits" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="program" class="block text-gray-700 font-bold mb-2">Program:</label>
            <input type="text" id="program" name="program" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
            <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        <div class="mb-4">
            <label for="modules" class="block text-gray-700 font-bold mb-2">Modules:</label>
            <textarea id="modules" name="modules" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Qualification
            </button>
        </div>
    </form>
</div>

</body>

<?php require 'footer.php'; ?>
