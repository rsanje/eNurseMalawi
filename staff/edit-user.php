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

// Fetch user details for editing
$user_id = $_GET['user_id'];
$sql = "SELECT * FROM users WHERE users.user_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $userDetails = $result->fetch_assoc();
    } else {
        die("Error fetching user details.");
    }
    $stmt->close();
} else {
    die("Error preparing statement.");
}

$conn->close();
?>

<div class="container mx-auto px-4 mt-8 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-2xl font-bold mb-4">Edit User Details</h1>
    <form action="update-user.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">      
        <input type="hidden" name="username" value="<?php echo $userDetails['username']; ?>" required>
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo $userDetails['first_name']; ?>" required>
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $userDetails['last_name']; ?>" required>
        </div>
        <div>
            <label>Other Names:</label>
            <input type="text" name="other_names" value="<?php echo $userDetails['other_names']; ?>">
        </div>
        <div>
            <label>National ID:</label>
            <input type="text" name="national_id" value="<?php echo $userDetails['national_id']; ?>" required>
        </div>
        <div>
            <label>Birth Date:</label>
            <input type="text" name="birth_date" value="<?php echo $userDetails['birth_date']; ?>" required>
        </div>
        <div>
            <label>Place of Birth:</label>
            <input type="text" name="place_of_birth" value="<?php echo $userDetails['place_of_birth']; ?>" required>
        </div>
        <div>
            <label>Nationality:</label>
            <input type="text" name="nationality" value="<?php echo $userDetails['nationality']; ?>" required>
        </div>
        <div>
            <label>Gender:</label>
             <select id="gender" name="gender" class="shadow appearance-none border rounded w-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="<?php echo $userDetails['gender']; ?>"><?php echo $userDetails['gender']; ?></option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
            </select>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $userDetails['email']; ?>">
        </div>
        <div>
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $userDetails['phone']; ?>" required>
        </div>
        <div>
            <label>Address:</label>
            <input type="text" name="address" value="<?php echo $userDetails['address']; ?>">
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Details</button>
        </div>
    </form>
</div>


<?php require 'footer.php'; ?>
