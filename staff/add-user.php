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
require 'header.php';

// Initialize variables
$emp_code = $_SESSION["emp_code"]; // Retrieve emp_code from session
$username = $password = $first_name = $last_name = $other_names = $national_id = $birth_date = $place_of_birth = $nationality = $gender = $email = $phone = $address = '';
$error = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]); 
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $other_names = trim($_POST["other_names"]);
    $national_id = trim($_POST["national_id"]);
    $birth_date = trim($_POST["birth_date"]);
    $place_of_birth = trim($_POST["place_of_birth"]);
    $nationality = trim($_POST["nationality"]);
    $gender = trim($_POST["gender"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    if (empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($national_id) || empty($birth_date) || empty($place_of_birth) || empty($nationality) || empty($gender) || empty($phone)) {
        $error = "All required fields must be filled out.";
    } else {
        $sql = "INSERT INTO users (emp_code, username, password, first_name, last_name, other_names, national_id, birth_date, place_of_birth, nationality, gender, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssssssssss", $emp_code, $username, $password, $first_name, $last_name, $other_names, $national_id, $birth_date, $place_of_birth, $nationality, $gender, $email, $phone, $address);
            if ($stmt->execute()) {
                header("Location: users.php");
                exit;
            } else {
                $error = "Error occurred while adding user. Please try again.";
            }
        } else {
            $error = "Error occurred. Please try again later.";
        }
    }
}

// Close connection
$conn->close();
?>

<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Add New User</h1>

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
            <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
            <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name:</label>
            <input type="text" id="first_name" name="first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name:</label>
            <input type="text" id="last_name" name="last_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="other_names" class="block text-gray-700 font-bold mb-2">Other Names:</label>
            <input type="text" id="other_names" name="other_names" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="national_id" class="block text-gray-700 font-bold mb-2">National ID:</label>
            <input type="text" id="national_id" name="national_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="birth_date" class="block text-gray-700 font-bold mb-2">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="place_of_birth" class="block text-gray-700 font-bold mb-2">Place of Birth:</label>
            <input type="text" id="place_of_birth" name="place_of_birth" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="nationality" class="block text-gray-700 font-bold mb-2">Nationality:</label>
            <input type="text" id="nationality" name="nationality" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="gender" class="block text-gray-700 font-bold mb-2">Gender:</label>
            <select id="gender" name="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="Other" disabled>Choose gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
            <textarea id="address" name="address" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add User
            </button>
        </div>
    </form>
</div>

</body>

<?php require 'footer.php'; ?>
