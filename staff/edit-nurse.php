<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

// Include database connection
require_once '../db.php';
require 'header.php';

// Check if user_id is provided in the URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header("location: staff.php");
    exit;
}

// Fetch nurse details for editing
$user_id = $_GET['user_id'];
$sql = "SELECT users.username, users.first_name, users.last_name, nurse.reg_no, nurse.speciality, nurse.reg_status 
        FROM users 
        INNER JOIN nurse ON users.user_id = nurse.user_id 
        WHERE users.user_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $nurseDetails = $result->fetch_assoc();
        } else {
            echo "Nurse details not found.";
        }
    } else {
        echo "Error fetching nurse details.";
    }
    $stmt->close();
} else {
    echo "Error preparing statement.";
}

// Close database connection
$conn->close();
?>

<div class="container mx-auto px-4 mt-8 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-2xl font-bold mb-4">Edit Nurse Registration Details</h1>
    <p>Username: <?php echo htmlspecialchars($nurseDetails['username']); ?></p>
    <p>Full Name: <?php echo htmlspecialchars($nurseDetails['first_name'] . ' ' . $nurseDetails['last_name']); ?></p>
    <form action="update-nurse.php" method="post" >
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <div>
            <label>Registration Number:</label>
            <input type="text" name="reg_no" value="<?php echo htmlspecialchars($nurseDetails['reg_no']); ?>" required>
        </div>
        <div>
            <label>Speciality:</label>
            <input type="text" name="speciality" value="<?php echo htmlspecialchars($nurseDetails['speciality']); ?>" required>
        </div>
        <div>
            <label>Registration Status:</label>            
            <select id="reg_status" name="reg_status" class="shadow appearance-none border rounded w-2xl py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="<?php echo htmlspecialchars($nurseDetails['reg_status']); ?>"><?php echo htmlspecialchars($nurseDetails['reg_status']); ?></option> 
                <option value="Unregistered">Unregistered</option>    
                <option value="Registered">Registered</option>                
            </select>
          </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Details</button>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>
