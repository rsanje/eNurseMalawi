<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: home.php");
    exit;
}

// Include required files
require_once 'db.php';
require 'header.php';

$user_id = $_SESSION["user_id"];

$sql = "SELECT *
        FROM users u
        LEFT JOIN nurse n ON u.user_id = n.user_id
        WHERE u.user_id = ?";
$userDetails = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $userDetails = $result->fetch_assoc();
        } else {
            echo "User details not found.";
        }
    } else {
        echo "Error fetching user details.";
    }
    $stmt->close();
} else {
    echo "Error preparing statement.";
}
?>

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

     <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-xl font-bold mb-2">User Details</h1>
        <p><strong>Username:</strong> <?php echo $userDetails['username']; ?></p>
        <p><strong>Full Name:</strong> <?php echo $userDetails['first_name'] . ' ' . $userDetails['other_names'] . ' ' . $userDetails['last_name']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $userDetails['birth_date']; ?></p> 
        <p><strong>Place of Birth:</strong> <?php echo $userDetails['place_of_birth']; ?></p> 
        <p><strong>Nationality:</strong> <?php echo $userDetails['nationality']; ?></p>  
        <p><strong>National ID:</strong> <?php echo $userDetails['national_id']; ?></p>            
        <p><strong>Gender:</strong> <?php echo $userDetails['gender']; ?></p>
        <p><strong>Email:</strong> <?php echo $userDetails['email']; ?></p> 
        <p><strong>Phone:</strong> <?php echo $userDetails['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $userDetails['address']; ?></p>        
        <p class="mt-4"><a href="edit-user.php?user_id=<?php echo $user_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit Details</a></p>      
    </div>

     <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-xl font-bold mb-2">Nurse Registration Details</h1>
        <p><strong>Registration Number:</strong> <?php echo $userDetails['reg_no']; ?></p>
        <p><strong>Speciality:</strong> <?php echo $userDetails['speciality']; ?></p>
        <p><strong>Registration Status:</strong> <?php echo $userDetails['reg_status']; ?></p>
        <p class='mt-4'><a href="edit-nurse.php?user_id=<?php echo $user_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold  py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit Details</a></p> 
    </div>

</div>
<div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
    <?php
        // Include employment history file
      require_once 'view-employment.php';
    ?>

      <!-- Employment -->
    <div class="flex items-center justify-center">
        <a href="add-employment.php" class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Employment</a>
    </div>
    </div>
<div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
    <?php
        // Include education history file
      require_once 'view-qualification.php';
    ?>

    <div class="flex items-center justify-center">
        <a href="add-qualification.php" class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Qualification</a>
    </div>
    </div>

  
    <!-- Qualifications -->
    <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
    <?php
        // Include document history file
      require_once 'view-document.php';
    ?>

    <div class="flex items-center justify-center">
        <a href="add-document.php" class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Documents</a>
    </div>
    </div>

<?php require 'footer.php'; ?>
