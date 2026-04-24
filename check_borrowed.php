<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['isbn'])) {
        $isbn = $_GET['isbn'];

        // Check the quantity of the book in the books table
        $sql = "SELECT quantity FROM books WHERE isbn = '$isbn'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $quantity = $row['quantity'];

            // If quantity is 0 or less, consider the book as borrowed
            if ($quantity <= 0) {
                echo json_encode(['borrowed' => true]);
            } else {
                // Check if the book is borrowed (even if quantity is available, it could still be borrowed)
                $sql = "SELECT * FROM borrowedbooks WHERE isbn = '$isbn'";
                $borrowedResult = $conn->query($sql);

                if ($borrowedResult->num_rows > 0) {
                    // Book is already borrowed
                    echo json_encode(['borrowed' => true]);
                } else {
                    // Book is not borrowed
                    echo json_encode(['borrowed' => false]);
                }
            }
        } else {
            echo json_encode(['error' => 'Book not found']);
        }
    } else {
        echo json_encode(['error' => 'ISBN is required']);
    }
}

$conn->close();
?>
