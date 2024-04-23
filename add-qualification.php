<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include required files
require_once "db.php";
require 'header.php';


$institution = $certificate = $start_date = $end_date = $credits = $program = $description = $modules = "";
$credits_err = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $institution = trim($_POST["institution"]);
    $certificate = trim($_POST["certificate"]);
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);
    $credits = trim($_POST["credits"]);
    if (!is_numeric($credits)) {
        $credits_err = "Credits must be a number.";
    }
    $program = trim($_POST["program"]);
    $description = trim($_POST["description"]);
    $modules = trim($_POST["modules"]);

    if (empty($credits_err)) {
        $sql = "INSERT INTO qualification (user_id, institution, certificate, start_date, end_date, credits, program, description, modules) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssisss", $param_user_id, $institution, $certificate, $start_date, $end_date, $credits, $program, $description, $modules);
            $param_user_id = $_SESSION["user_id"];
            if ($stmt->execute()) {
                header("location: dashboard.php");
            } else {
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
    <title>Enter Qualifications</title>
</head>
<body>
    <div class="container mx-auto px-4">
        <h2 class="text-xl font-bold my-4">Enter Qualification Details</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label class="block">Name of Institution</label>
                <input type="text" name="institution" class="form-input mt-1 block w-full border-2 border-gray-300 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Certificate</label>
                <input type="text" name="certificate" class="form-input mt-1 block w-full border-2 border-gray-300 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Start Date</label>
                <input type="date" name="start_date" class="form-input mt-1 block w-full border-2 border-gray-300 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">End Date</label>
                <input type="date" name="end_date" class="form-input mt-1 block w-full border-2 border-gray-300 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Credits</label>
                <input type="number" name="credits" class="form-input mt-1 block w-full border-2 border-gray-300 p-2">
            </div>
            <div class="mb-4">
                <label class="block">Program Name</label>
                <input type="text" name="program" class="form-input mt-1 block w-full border-2 border-gray-300 p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Description of the program</label>
                <textarea name="description" class="form-textarea mt-1 block w-full border-2 border-gray-300 p-2" rows="4" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block">Modules</label>
                <textarea name="modules" class="form-textarea mt-1 block w-full border-2 border-gray-300 p-2" rows="4" required></textarea>
            </div>
            <div class="flex justify-end mt-4">
                <input type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>

<?php require 'footer.php'; ?>
