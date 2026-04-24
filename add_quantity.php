<?php
include 'database.php'; // Include database connection

// Get the JSON input from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);

// Check if ISBN is provided
if (isset($data['isbn'])) {
    $isbn = $data['isbn'];

    // Check if the ISBN exists in the database
    $sqlCheck = "SELECT COUNT(*) AS count FROM books WHERE isbn = '$isbn'";
    $resultCheck = $conn->query($sqlCheck);
    if ($resultCheck && $resultCheck->num_rows > 0) {
        $row = $resultCheck->fetch_assoc();
        if ($row['count'] == 0) {
            echo json_encode(['success' => false, 'message' => 'ISBN not found']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error checking ISBN']);
        exit;
    }

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE isbn = ?");
    $stmt->bind_param("s", $isbn); // "s" means the parameter is a string

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity', 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing ISBN']);
}
?>
