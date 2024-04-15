<?php
session_start();

// Include requiredfiles
require_once 'db.php';
require 'header.php';
 
// Check if the user is already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $national_id = $_POST['national_id'];
    $birth_date = $_POST['birth_date'];
    $place_of_birth = $_POST['place_of_birth'];
    $nationality = $_POST['nationality'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    
    $sql = "INSERT INTO users 
        (username, password, email, phone, first_name, last_name, national_id, birth_date, place_of_birth, nationality, gender, address) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ssssssssssss", $username, $password, $email, $phone, $first_name, $last_name, $national_id, $birth_date, $place_of_birth, $nationality, $gender, $address);
        
        if($stmt->execute()){
            header("location: login.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
  <h1 class="text-2xl font-bold mb-4">Registration</h1>

    <!-- Registration Form -->
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

    <div class="mb-4">
        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
        <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Username" required>
    </div>    

    <div class="flex mb-4">
        <div class="mr-2 w-1/2">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Password" required>
        </div>
        <div class="ml-2 w-1/2">
            <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Confirm Password" required>
        </div>
    </div>

    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Email" required>
    </div>

    <div class="mb-4">
        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
        <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Phone" required>
    </div>

    <div class="flex mb-4">
        <div class="mr-2 w-1/2">
            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
            <input type="text" id="first_name" name="first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="First Name" required>
        </div>
        <div class="ml-2 w-1/2">
            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Last Name" required>
        </div>
    </div>

    <div class="mb-4">
        <label for="national_id" class="block text-gray-700 text-sm font-bold mb-2">National ID</label>
        <input type="text" id="national_id" name="national_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="National ID" required>
    </div>

    <div class="flex mb-4">
        <div class="mr-2 w-1/2">
            <label for="birth_date" class="block text-gray-700 text-sm font-bold mb-2">Date of Birth</label>
            <input type="date" id="birth_date" name="birth_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Birth Date" required>
        </div>
        <div class="ml-2 w-1/2">
            <label for="place_of_birth" class="block text-gray-700 text-sm font-bold mb-2">Place of Birth</label>
            <input type="text" id="place_of_birth" name="place_of_birth" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Place of Birth" required>
        </div>
    </div>

    <div class="mb-4">
        <label for="nationality" class="block text-gray-700 text-sm font-bold mb-2">Nationality</label>
        <input type="text" id="nationality" name="nationality" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Nationality" required>
    </div>

    <div class="mb-4">
        <label for="gender" class="block text-gray-700 text-sm font-bold mb-2">Gender</label>
        <select id="gender" name="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="" disabled selected hidden>Choose Your Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
        <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Address" required>
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register</button>
        <a href="login.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Back to Login</a>
    </div>
  </form>

    <?php if (isset($_GET['error'])): ?>
        <p class="text-red-500"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>
</div>

</body>
</html>

<?php require 'footer.php' ; ?>
