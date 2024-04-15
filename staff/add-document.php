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
$username = $description = $file_name = $file_type = '';
$file_content = null; // Initialize as null
$error = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $username = trim($_POST["username"]);
    $description = trim($_POST["description"]);
    $file_name = $_FILES['file']['name'];
    $file_type = $_FILES['file']['type'];
    $file_content = file_get_contents($_FILES['file']['tmp_name']);

    if (empty($username) || empty($description) || empty($file_name) || empty($file_type) || empty($file_content)) {
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

                // Insert document details into document table
                $sql = "INSERT INTO document (user_id, emp_code, description, filename, file_content, file_type) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("isssss", $user_id, $emp_code, $description, $file_name, $file_content, $file_type);
                    if ($stmt->execute()) {
                        // Redirect after successful insertion
                        header("Location: staff-details.php?user_id=" . $user_id);
                        exit;
                    } else {
                        $error = "Error occurred while adding document. Please try again.";
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

require 'header.php'; // navigation and links
// Close connection
$conn->close();
?>
<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Add New Document</h1>

    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
            <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $username; ?>" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
            <input type="text" id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="file" class="block text-gray-700 font-bold mb-2">Select File:</label>
            <input type="file" id="file" name="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Document
            </button>
        </div>
    </form>
</div>

</body>

<?php require 'footer.php'; // footer of the page ?>
