<?php
session_start();

// Include your database connection script
require '../db.php';

// Initialize variables
$username = $_POST['username'];
$password = $_POST['password'];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare a select statement with JOIN to fetch emp_code from staff table
    $sql = "SELECT users.user_id, users.username, users.password, staff.emp_code, staff.role 
            FROM users 
            JOIN staff ON users.user_id = staff.user_id 
            WHERE users.username = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);
        
        // Set parameters
        $param_username = $username;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            
            // Check if username exists, if yes then check password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($user_id, $username, $stored_password, $emp_code, $role);
                if ($stmt->fetch()) {
                    if ($password == $stored_password) {
                        // Password is correct, so start a new session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $user_id;
                        $_SESSION["emp_code"] = $emp_code;
                        $_SESSION["role"] = $role;  // Save role in session
                        
                        // Redirect user based on their role
                        if ($role == 'HR' || $role == 'admin') {
                            header("location: staff.php");
                        } elseif ($role == 'RO') {
                            header("location: dashboard.php");
                        } else {
                            // Redirect to a default page if the role is not recognized
                            header("location: user.php");
                        }
                        exit();
                    } else {
                        // Display an error message if password is not valid
                        $error = 'The password you entered was not valid.';
                    }
                }
            } else {
                // Display an error message if username doesn't exist
                $error = 'No account found with that username.';
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Login</title>
</head>
<body>
    <div class="max-w-md mx-auto mt-8 px-4 py-8 bg-white shadow-md rounded">
    <h2 class="text-2xl font-bold mb-4">Login</h2>
    <p class="mb-4">Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-4 flex justify-between">
            <label class="block text-gray-700 mr-4">Username</label>
            <input type="text" name="username" class="form-input rounded border-black flex-inline bg-gray-100" required>
        </div>    
        <div class="mb-4 flex justify-between">
            <label class="block text-gray-700 mr-4">Password</label>
            <input type="password" name="password" class="form-input rounded flex-inline bg-gray-200 " required>
        </div>
        <div class="mb-4">
            <input type="submit" value="Login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </div>
        <div>
            <span class="text-red-500"><?php echo $error; ?></span>
        </div>
    </form>
</div>

</body>
</html>
<?php require 'footer.php' ?>
