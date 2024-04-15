<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_GET['user_id']; // Getting the user_id from the URL

$sql = "SELECT qualification_no, institution, certificate, start_date, end_date, credits, program, description, modules FROM qualification WHERE user_id = ? ORDER BY start_date DESC";

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

    <div class="container mx-auto  max-w">
        <h2 class="text-2xl font-bold my-4">Qualifications</h2>
        <?php if (!empty($qualifications)): ?>
            <table class="table-auto max-w">
                  <thead>
                    <tr>
                        <th>Institution</th>
                        <th>Program</th>
                        <th>Duration</th>
                        <th colspan="2">Description</th>
                        <th colspan="4">Module</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php foreach ($qualifications as $qualification): ?>
                    <tbody>
                        
                        <tr class="shadow-lg">
                            <td class="text-sm text-gray-700"> <?= htmlspecialchars($qualification['institution']); ?></td>
                            <td class="text-sm text-gray-700"> <?= htmlspecialchars($qualification['program']); ?></td>
                            <td class="text-sm text-gray-700"> <?= date('F Y', strtotime($qualification['start_date'])) . ' - ' . date('F Y', strtotime($qualification['end_date'])); ?></td>
                            <td colspan="2" class="text-sm text-gray-700"> <?= nl2br(htmlspecialchars($qualification['description'])); ?></td>
                            <td colspan="4" class="text-sm text-gray-700"> <?= nl2br(htmlspecialchars($qualification['modules'])); ?></td>
                            <td>
                                <a href="edit-qualification.php?qualification_no=<?= htmlspecialchars($qualification['qualification_no']); ?>" class=" text-sm text-blue-500 hover:text-blue-800">edit</a> |
                                <a href="delete-qualification.php?qualification_no=<?= htmlspecialchars($qualification['qualification_no']); ?>" class=" text-sm text-blue-500 hover:text-blue-800">delete</a> 

                            </td>
                        </tr>
                       
                    </tbody>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="text-lg text-gray-700">No qualifications found.</p>
        <?php endif; ?>
    </div>
