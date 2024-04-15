<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION["user_id"];// Getting the user_id from the URL

$sql = "SELECT emp_no, emp_name, start_date, end_date, position, emp_address, location, emp_phone, emp_email FROM employment WHERE user_id = ? ORDER BY start_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($emp_no, $emp_name, $start_date, $end_date, $position, $emp_address, $location, $emp_phone, $emp_email);
?>


    <div class="max-w mx-auto">
        <h2 class="text-3xl font-bold mb-6">Employment History</h2>

        <div class="overflow-x-auto rounded-md shadow-lg">
            
                <table class="table-auto max-w">
                    <thead>
                        <tr>
                            <th class="text-sm text-gray-700">Employer</th>
                            <th class="text-sm text-gray-700">Facility</th>
                            <th colspan="2" class="text-sm text-gray-700">Duration</th>                            
                            <th class="text-sm text-gray-700">Position</th>
                            <th colspan="2" class="text-sm text-gray-700">Address</th>
                            <th class="text-sm text-gray-700">Phone</th>
                            <th class="text-sm text-gray-700">Email</th> 
                            <th class="test-sm text-gray-700">Action</th>                           
                        </tr>
                    </thead>
                <?php while ($stmt->fetch()) { ?>
                    <tbody>
                        <tr>
                            <td class="text-sm text-gray-700"><?= $emp_name; ?></td> 
                            <td class="text-sm text-gray-700"><?= $location; ?></td>                            
                            <td colspan="2" class="text-sm text-gray-700"><?= $start_date; ?> - <?= $end_date; ?></td>
                            <td class="text-sm text-gray-700"><?= $position; ?></td>
                            <td colspan="2" class="text-sm text-gray-700"><?= $emp_address; ?></td>
                            <td class="text-sm text-gray-700"><?= $emp_phone; ?></td>
                            <td class="text-sm text-gray-700"><?= $emp_email; ?></td>
                            <td text-sm>
                                <!-- Assuming $user_id contains the ID of the user whose details are to be viewed -->
                                <a href="edit-employment.php?emp_no=<?php echo $emp_no; ?>" class=" text-sm text-blue-500 hover:text-blue-800">edit</a> |
                                <a href="delete-employment.php?emp_no=<?php echo $emp_no; ?>" class=" text-sm text-blue-500 hover:text-blue-800">delete</a> 
                            </td>
                        </tr>                        
                    </tbody>
                <?php } ?>
                </table>            
        </div>
    </div>
</body>

</html>
            
<?php
        } else {
            echo "No employment history found.";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}
?>
