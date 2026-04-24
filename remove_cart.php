<?php
session_start();
include("database.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Decode JSON input
    $bookSerial = $data['bookserial'] ?? ''; // Safely retrieve book serial

    if (!empty($bookSerial)) {
        // Retrieve the book details from the cart
        $getBookQuery = "SELECT book, isbn FROM bookcart WHERE bookserial = ?";
        $stmt = $conn->prepare($getBookQuery);
        $stmt->bind_param("s", $bookSerial);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $bookDetails = $result->fetch_assoc();
            $bookTitle = $bookDetails['book'];
            $isbn = $bookDetails['isbn'];

            // Delete the book from the cart
            $deleteQuery = "DELETE FROM bookcart WHERE bookserial = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("s", $bookSerial);
            if ($stmt->execute()) {
                // Increment the quantity of the book in the books table
                $updateQuantityQuery = "UPDATE books SET quantity = quantity + 1 WHERE isbn = ?";
                $stmt = $conn->prepare($updateQuantityQuery);
                $stmt->bind_param("s", $isbn);
                if ($stmt->execute()) {
                    echo json_encode([
                        "success" => true,
                        "message" => "The book '$bookTitle' was successfully removed from the cart.",
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to update book quantity.",
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to remove the book from the cart.",
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "The specified book was not found in the cart.",
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid book serial number provided.",
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method.",
    ]);
}

$conn->close();
?>
