<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "db.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT institution, certificate, start_date, end_date, credits, program, description, modules FROM qualification WHERE user_id = ? ORDER BY start_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $qualifications = [];
    while ($row = $result->fetch_assoc()) {
        $qualifications[] = $row;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Qualifications</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold my-4">Qualifications</h2>
        <?php if (!empty($qualifications)): ?>
            <div class="grid md:grid-cols-2 gap-4">
                <?php foreach ($qualifications as $qualification): ?>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($qualification['certificate']); ?> - <?= htmlspecialchars($qualification['institution']); ?></h3>
                        <p class="text-md text-gray-700 mb-4"><strong>Program:</strong> <?= htmlspecialchars($qualification['program']); ?></p>
                        <p class="text-md text-gray-700 mb-4"><strong>Duration:</strong> <?= date('F Y', strtotime($qualification['start_date'])) . ' - ' . date('F Y', strtotime($qualification['end_date'])); ?></p>
                        <p class="text-md text-gray-700 mb-4"><strong>Description:</strong> <?= nl2br(htmlspecialchars($qualification['description'])); ?></p>
                        <p class="text-md text-gray-700"><strong>Modules:</strong> <?= nl2br(htmlspecialchars($qualification['modules'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-lg text-gray-700">No qualifications found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
