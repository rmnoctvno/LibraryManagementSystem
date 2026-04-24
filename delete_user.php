<?php
include ("database.php");

if (isset($_POST['delete_id'])) {
    $userId = $_POST['delete_id'];

    // SQL query to delete the user
    $sql = "DELETE FROM users WHERE idusers = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Respond with a success message
        echo "User deleted successfully!";
    } else {
        // Respond with an error message
        echo "Error deleting user.";
    }
}

?>