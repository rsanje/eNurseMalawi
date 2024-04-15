<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION["user_id"]; // Getting the user_id from the URL

// Prepare a statement with a parameterized query to fetch documents uploaded by the user
$sql = "SELECT document_id, description, filename, upload_date FROM document WHERE user_id = ? ORDER BY upload_date DESC";


$documents = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $documents[] = $row;
            }
        }
    } else {
        echo "Error executing SQL statement: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing SQL statement: " . $conn->error;
}
?>


<div class="container mx-auto px-4">
  <h2 class="text-3xl font-bold mb-6">Documents Uploaded</h2>
    <?php if (!empty($documents)) : ?>
      <table class="table-auto border">
        <thead>
            <tr>
                <th>Description</th>
                <th>Uploaded</th>
                <th>Filename</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $document) : ?>
                <tr>
                    <td class="text-sm text-gray-700"><?= $document['description']; ?></td>
                    <td class="text-sm text-gray-700"><?= date('F j, Y', strtotime($document['upload_date'])); ?></td>
                    <td class="text-sm text-gray-700">
                        <a href="../download-document.php?document_id=<?php echo $document['document_id']; ?>" class="text-blue-500 hover:underline"><?= $document['filename']; ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
        <p>No documents uploaded yet.</p>
    <?php endif; ?>
</div>

