<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Delete query
    $sql = "DELETE FROM borrowedbooks WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}
?>
