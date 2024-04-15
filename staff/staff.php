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

// Query to retrieve employee details
$sql = "SELECT users.first_name, users.last_name, staff.emp_code, staff.department, staff.position, users.user_id
        FROM users
        INNER JOIN staff ON users.user_id = staff.user_id";

$employees = [];
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }
    $result->free();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>

<div class="container mx-auto px-4 mt-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-4">List of Employees</h1>

    <!-- Display employees in a table -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Full Name</th>
                    <th class="px-4 py-2">Employee Code</th>
                    <th class="px-4 py-2">Department</th>
                    <th class="px-4 py-2">Position</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee) : ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></td>
                        <td class="border px-4 py-2"><?php echo $employee['emp_code']; ?></td>
                        <td class="border px-4 py-2"><?php echo $employee['department']; ?></td>
                        <td class="border px-4 py-2"><?php echo $employee['position']; ?></td>
                        <td class="border px-4 py-2">
                            <a href="staff-details.php?user_id=<?php echo $employee['user_id']; ?>" class="text-blue-500 hover:underline">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'footer.php'; ?>
