<?php


// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once 'db.php';
require 'header.php';

// Initialize variables
$emp_code = $_SESSION["emp_code"]; // Retrieve emp_code from session
$reg_no = $speciality = $reg_status = '';
$error = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $reg_no = trim($_POST["reg_no"]);
    $speciality = trim($_POST["speciality"]);
    $reg_status = trim($_POST["reg_status"]);

    if (empty($reg_no) || empty($speciality) || empty($reg_status)) {
        $error = "All required fields must be filled out.";
    } else {
        // Check if reg_no is unique
        $sql_check = "SELECT nurse_id FROM nurse WHERE reg_no = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("s", $reg_no);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $error = "Registration number already exists.";
            } else {
                // Check if username exists and retrieve user_id
                $username = trim($_POST["username"]);
                $sql = "SELECT user_id FROM users WHERE username = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($user_id);
                        $stmt->fetch();

                        // Insert nurse details into nurse table
                        $sql_insert = "INSERT INTO nurse (user_id, emp_code, reg_no, speciality, reg_status) VALUES (?, ?, ?, ?, ?)";
                        if ($stmt_insert = $conn->prepare($sql_insert)) {
                            $stmt_insert->bind_param("issss", $user_id, $emp_code, $reg_no, $speciality, $reg_status);
                            if ($stmt_insert->execute()) {
                                // Redirect after successful insertion
                                header("Location: user-details.php?user_id=" . $user_id);
                                exit;
                            } else {
                                $error = "Error occurred while adding nurse. Please try again.";
                            }
                        } else {
                            $error = "Error occurred. Please try again later.";
                        }
                    } else {
                        $error = "Username not found.";
                    }
                    $stmt->close();
                } else {
                    $error = "Error occurred. Please try again later.";
                }
            }
            $stmt_check->close();
        } else {
            $error = "Error occurred. Please try again later.";
        }
    }
}


$conn->close();
?>


<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Add New Nurse</h1>

    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
            <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="reg_no" class="block text-gray-700 font-bold mb-2">Registration Number:</label>
            <input type="text" id="reg_no" name="reg_no" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="speciality" class="block text-gray-700 font-bold mb-2">Speciality:</label>
            <input type="text" id="speciality" name="speciality" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="reg_status" class="block text-gray-700 font-bold mb-2">Registration Status:</label>
            <select id="reg_status" name="reg_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="Unregistered">Unregistered</option>    
                <option value="Registered">Registered</option>                
            </select>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Nurse
            </button>
        </div>
    </form>
</div>

</body>

<?php require 'footer.php'; ?>
