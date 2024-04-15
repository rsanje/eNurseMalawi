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
require_once 'db.php';
require 'header.php';

$qualification_no = isset($_GET['qualification_no']) ? $_GET['qualification_no'] : (isset($_POST['qualification_no']) ? $_POST['qualification_no'] : null);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an update statement
    $sql = "UPDATE qualification 
            SET user_id = ?, certificate = ?, program = ?, institution = ?, start_date = ?, end_date = ?, modules = ? , credits = ? , description = ?  
            WHERE qualification_no = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issssssssi", $_POST['user_id'], $_POST['certificate'], $_POST['program'], $_POST['institution'], $_POST['start_date'], $_POST['end_date'], $_POST['modules'], $_POST['credits'], $_POST['description'], $qualification_no);

        if ($stmt->execute()) {
            header("dashboard.php");            
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Fetch existing data
if (!empty($qualification_no) && $_SERVER["REQUEST_METHOD"] != "POST") {
    $sql = "SELECT user_id, certificate, program, institution, start_date, end_date, modules, description, credits FROM qualification WHERE qualification_no = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $qualification_no);
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


<body class="bg-gray-100">
<div class="container mx-auto px-4 mt-8">
    
    <form action="edit-qualification.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-2xl font-bold mb-4">Edit Qualification Details</h1>
      <input type="hidden" name="qualification_no" value="<?php echo htmlspecialchars($qualification_no); ?>">
      <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($data['user_id']); ?>">
        <div>
            <label>Certificate:</label>
            <input type="text" name="certificate" value="<?php echo htmlspecialchars($data['certificate']); ?>" required>
        </div>
        <div>
            <label>Institution:</label>
            <input type="text" name="institution" value="<?php echo htmlspecialchars($data['institution']); ?>" required>
        </div>
        <div>
            <label>Program:</label>
            <input type="text" name="program" value="<?php echo htmlspecialchars($data['program']); ?>" required>
        </div>
        <div>
            <label>Credits:</label>
            <input type="text" name="credits" value="<?php echo htmlspecialchars($data['credits']); ?>">
        </div>
        <div>
            <label>Description:</label>
            <input name="description" type="text" value="<?php echo htmlspecialchars($data['description']); ?>" required>
        </div>
        <div>
            <label>Module:</label>
            <input name="modules" type="text" value="<?php echo htmlspecialchars($data['modules']); ?>">
        </div>
        <div>
            <label>Start Date:</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($data['start_date']); ?>" required>
        </div>
        <div>
            <label>End Date:</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($data['end_date']); ?>">
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Details</button>
        </div>
    </form>
</div>

</body>

<?php require 'footer.php'; ?>
