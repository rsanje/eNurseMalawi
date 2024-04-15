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

// Query to retrieve users joined with nurses
$sql = "SELECT u.user_id, u.first_name, u.last_name, n.reg_no, n.speciality, n.reg_status, u.gender
        FROM users u
        LEFT JOIN nurse n ON u.user_id = n.user_id";
$result = $conn->query($sql);

require 'header.php'; 
// Check if there are results
if ($result->num_rows > 0) {
    ?>
 

    <div class="container mx-auto px-4 mt-8 max-w-3xl">
        <h1 class="text-2xl font-bold mb-4">User List</h1>

        <!-- Display users in a table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Full Name</th>
                        <th class="px-4 py-2">Reg No</th>
                        <th class="px-4 py-2">Speciality</th>
                        <th class="px-4 py-2">Reg Status</th>
                        <th class="px-4 py-2">Gender</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Output data for each user
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['reg_no']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['speciality']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['reg_status']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['gender']; ?></td>
                            <td class="border px-4 py-2">
                                <a href="user-details.php?user_id=<?php echo $row['user_id']; ?>" class="text-blue-500 hover:underline">View Details</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} else {
    echo "No users found.";
}


// Close connection
$conn->close();

require 'footer.php';
?>
