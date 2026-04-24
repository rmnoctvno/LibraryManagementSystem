<?php
session_start();
include 'database.php'; // Include database connection

// Check if the user is logged in (session should contain RFID number)
if (!isset($_SESSION['rfidnum'])) {
    echo json_encode(['error' => 'Not logged in']); // Return an error if no RFID number in session
    exit();
}

$rfidnum = $_SESSION['rfidnum']; // Get RFID number from session

// Fetch user data and penalty details from the database
$sql = "SELECT rfidnum, username, email, program, userType, penalty, penaltydue FROM users WHERE rfidnum = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfidnum);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user data exists
if ($user) {
    echo json_encode($user); // Return user data and penalty details as JSON
} else {
    echo json_encode(['error' => 'User not found']); // Return error if no user found
}
?>
