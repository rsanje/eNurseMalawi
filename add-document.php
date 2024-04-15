<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include required files
require_once 'config.php';
require_once 'db.php';

session_start(); 

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$description = $filename = "";
$description_err = $filename_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter a description for the document.";
    } else {
        $description = trim($_POST["description"]);
    }

    if (empty($_FILES["file"]["name"])) {
        $filename_err = "Please select a file to upload.";
    } else {
        $filename = basename($_FILES["file"]["name"]);
    }

    if (empty($description_err) && empty($filename_err)) {
             $sql = "INSERT INTO document (user_id, description, filename, file_content, file_type) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issss", $param_user_id, $param_description, $param_filename, $param_file_content, $param_file_type);

            $param_user_id = $_SESSION["user_id"];
            $param_description = $description;
            $param_filename = $filename;
            $param_file_content = file_get_contents($_FILES["file"]["tmp_name"]);
            $param_file_type = $_FILES["file"]["type"];

            if ($stmt->execute()) {
                header("location: dashboard.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

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
    <title>Upload Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6">Upload Document</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <input type="text" name="description" id="description" class="form-input mt-1 block w-full" value="<?php echo $description; ?>">
                <span class="text-red-500"><?php echo $description_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="file">File</label>
                <input type="file" name="file" id="file" class="form-input mt-1 block w-full">
                <span class="text-red-500"><?php echo $filename_err; ?></span>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn bg-blue-500 text-white px-4 py-2 rounded">Upload</button>
            </div>
        </form>
    </div>
</body>

</html>
