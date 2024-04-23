<?php
session_start();

require 'header.php';
require_once 'db.php';

// Function to search the database
function searchDatabase($search_term) {
    global $conn;
    $sql = "SELECT * FROM users 
            LEFT JOIN nurse ON users.user_id = nurse.user_id 
            WHERE national_id = '$search_term' OR reg_no = '$search_term'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
 echo '<div class="overflow-x-auto">';
            echo '<table class="min-w-full bg-white shadow-md rounded">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="px-4 py-2 text-left">Name</th>';
            echo '<th class="px-4 py-2 text-left">Reg No</th>';
            echo '<th class="px-4 py-2 text-left">Reg Status</th>';
            echo '<th class="px-4 py-2 text-left">Speciality</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="border px-4 py-2">' . $row["first_name"] . ' ' . $row["last_name"] . '</td>';
                echo '<td class="border px-4 py-2">' . $row["reg_no"] . '</td>';
                echo '<td class="border px-4 py-2">' . $row["reg_status"] . '</td>';
                echo '<td class="border px-4 py-2">' . $row["speciality"] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        
    } else {
        echo "0 results found";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search'];
    searchDatabase($search_term);
}
?>


    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Search Database</h1>
        <form method="post">
            <div class="flex items-center mb-4">
                <label for="search" class="mr-2">Search:</label>
                <input type="text" id="search" name="search" class="border rounded px-4 py-2" placeholder="Enter National ID or Reg No" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
        </form>
        <div class="flex items-center mb-2">
          <p>
            Welcome to eNUrse Malawi. You can Use the search function to verify Nurses or Midwives using their National ID or registration number.
          </p>

        </div>
    </div>


<?php require 'footer.php'; ?>