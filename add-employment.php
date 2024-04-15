<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include required files
require_once 'config.php';
require_once 'db.php';

session_start(); // Initialize the session

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$emp_name = $start_date = $end_date = $position = $emp_address = $location = $emp_phone = $emp_email = "";
$emp_name_err = $start_date_err = $end_date_err = $position_err = $emp_address_err = $location_err = $emp_phone_err = $emp_email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["emp_name"]))){
        $emp_name_err = "Please enter employer name.";
    } else{
        $emp_name = trim($_POST["emp_name"]);
    }

    
    if(empty(trim($_POST["start_date"]))){
        $start_date_err = "Please enter start date.";
    } else{
        $start_date = trim($_POST["start_date"]);
    }

    if(empty(trim($_POST["end_date"]))){
        $end_date_err = "Please enter end date.";
    } else{
        $end_date = trim($_POST["end_date"]);
    }

    if(empty(trim($_POST["position"]))){
        $position_err = "Please enter position.";
    } else{
        $position = trim($_POST["position"]);
    }

    if(empty(trim($_POST["emp_address"]))){
        $emp_address_err = "Please enter employment address.";
    } else{
        $emp_address = trim($_POST["emp_address"]);
    }

    if(empty(trim($_POST["location"]))){
        $location_err = "Please enter location.";
    } else{
        $location = trim($_POST["location"]);
    }

    if(empty(trim($_POST["emp_phone"]))){
        $emp_phone_err = "Please enter employers' phone.";
    } else{
        $emp_phone = trim($_POST["emp_phone"]);
    }

    if(empty(trim($_POST["emp_email"]))){
        $emp_email_err = "Please enter employers' email.";
    } else{
        $emp_email = trim($_POST["emp_email"]);
    }

    if(empty($emp_name_err) && empty($start_date_err) && empty($position_err) && empty($emp_address_err) && empty($location_err) && empty($emp_phone_err) && empty($emp_email_err)){
        $sql = "INSERT INTO employment (user_id, emp_name, start_date, end_date, position, emp_address, location, emp_phone, emp_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("issssssss", $param_user_id, $param_emp_name, $param_start_date, $param_end_date, $param_position, $param_emp_address, $param_location, $param_emp_phone, $param_emp_email);
            
            $param_user_id = $user_id;
            $param_emp_name = $emp_name;
            $param_start_date = $start_date;
            $param_end_date = !empty($end_date) ? $end_date : NULL;
            $param_position = $position;
            $param_emp_address = $emp_address;
            $param_location = $location;
            $param_emp_phone = $emp_phone;
            $param_emp_email = $emp_email;
            
            if($stmt->execute()){
                header("location: dashboard.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
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
    <title>Add Employment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 mt-8 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Add Employment</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-4">
            <label for="emp_name" class="block text-sm font-bold mb-2">Employer Name</label>
            <input type="text" id="emp_name" name="emp_name" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($emp_name_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $emp_name; ?>">
            <span class="text-sm text-red-500"><?php echo $emp_name_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-sm font-bold mb-2">Position</label>
            <input type="text" id="position" name="position" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($position_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $position; ?>">
            <span class="text-sm text-red-500"><?php echo $position_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-sm font-bold mb-2">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($start_date_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $start_date; ?>">
            <span class="text-sm text-red-500"><?php echo $start_date_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-sm font-bold mb-2">End Date</label>
            <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($end_date_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $end_date; ?>">
            <span class="text-sm text-red-500"><?php echo $end_date_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="emp_address" class="block text-sm font-bold mb-2">Employer Address</label>
            <input type="text" id="emp_address" name="emp_address" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($emp_address_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $emp_address; ?>">
            <span class="text-sm text-red-500"><?php echo $emp_address_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-sm font-bold mb-2">Location</label>
            <input type="text" id="location" name="location" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($location_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $location; ?>">
            <span class="text-sm text-red-500"><?php echo $location_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="emp_phone" class="block text-sm font-bold mb-2">Employer Phone</label>
            <input type="tel" id="emp_phone" name="emp_phone" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($emp_phone_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $emp_phone; ?>">
            <span class="text-sm text-red-500"><?php echo $emp_phone_err; ?></span>
        </div>
        <div class="mb-4">
            <label for="emp_email" class="block text-sm font-bold mb-2">Employment Email</label>
            <input type="email" id="emp_email" name="emp_email" class="w-full px-3 py-2 border rounded-md <?php echo (!empty($emp_email_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $emp_email; ?>">
            <span class="text-sm text-red-500"><?php echo $emp_email_err; ?></span>
        </div>
        <div class="mb-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
        </div>
    </form>
</div>

</body>
</html>
