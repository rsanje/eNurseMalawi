<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

// Include database connection
require_once '../db.php';
require 'header.php';

$emp_no = isset($_GET['emp_no']) ? $_GET['emp_no'] : (isset($_POST['emp_no']) ? $_POST['emp_no'] : null);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an update statement
    $sql = "UPDATE employment SET user_id = ?, emp_name = ?, location = ?, start_date = ?, end_date = ?, position = ?, emp_address = ?, emp_phone = ?, emp_email = ? WHERE emp_no = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssi", $_POST['user_id'],$_POST['emp_name'], $_POST['location'], $_POST['start_date'], $_POST['end_date'], $_POST['position'], $_POST['emp_address'], $_POST['emp_phone'], $_POST['emp_email'], $emp_no);

        if ($stmt->execute()) {
          header("Location: user-details.php?user_id=" . $_POST['user_id']);            
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Fetch existing data
if (!empty($emp_no) && $_SERVER["REQUEST_METHOD"] != "POST") {
    $sql = "SELECT user_id, emp_name, location, start_date, end_date, position, emp_address, emp_phone, emp_email FROM employment WHERE emp_no = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $emp_no);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $data = $result->fetch_assoc();
            } else {
                echo "No records found.";
            }
        } else {
            echo "Error fetching records: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>

<div class="container mx-auto px-4 mt-8">
    <h1 class="text-2xl font-bold mb-4">Edit Employment Details</h1>
    <form action="edit-employment.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <input type="hidden" name="emp_no" value="<?php echo htmlspecialchars($emp_no); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($data['user_id']); ?>">
        <div>
            <label>Employer:</label>
            <input type="text" name="emp_name" value="<?php echo htmlspecialchars($data['emp_name']); ?>" required>
        </div>
        <div>
            <label>Facility:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($data['location']); ?>" required>
        </div>
        <div>
            <label>Start Date:</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($data['start_date']); ?>" required>
        </div>
        <div>
            <label>End Date:</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($data['end_date']); ?>">
        </div>
        <div>
            <label>Position:</label>
            <input type="text" name="position" value="<?php echo htmlspecialchars($data['position']); ?>" required>
        </div>
        <div>
            <label>Address:</label>
            <input type="text" name="emp_address" value="<?php echo htmlspecialchars($data['emp_address']); ?>" required>
        </div>
        <div>
            <label>Phone:</label>
            <input type="text" name="emp_phone" value="<?php echo htmlspecialchars($data['emp_phone']); ?>" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="emp_email" value="<?php echo htmlspecialchars($data['emp_email']); ?>" required>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Details</button>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>
