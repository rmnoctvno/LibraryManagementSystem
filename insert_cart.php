<?php
include('database.php'); // Database connection

// Get the JSON input from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);

// Extract the values
$rfidnum = $data['rfidnum'];
$book = $data['book'];
$username = $data['username'];
$genre = $data['genre'];
$author = $data['author'];
$isbn = $data['isbn'];
$bookserial = $data['bookserial']; // Get book serial number
$duration = $data['duration'];
$duedate = $data['duedate'];

// Check the current number of books in the cart for this user
$cart_count_sql = "SELECT COUNT(*) FROM bookcart WHERE rfidnum = ? AND status = 'In Cart'";
$cart_count_stmt = $conn->prepare($cart_count_sql);
$cart_count_stmt->bind_param("s", $rfidnum);
$cart_count_stmt->execute();
$cart_count_stmt->bind_result($cart_count);
$cart_count_stmt->fetch();
$cart_count_stmt->close();

if ($cart_count >= 3) {
    // User already has 3 books in the cart
    echo json_encode(['success' => false, 'message' => 'You can only borrow 3 books!']);
    $conn->close();
    exit();
}

// Check the current book quantity
$check_sql = "SELECT quantity FROM books WHERE isbn = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $isbn);
$check_stmt->execute();
$check_stmt->store_result();
$check_stmt->bind_result($current_quantity);

if ($check_stmt->fetch()) {
    // Check if the book is available
    if ($current_quantity > 0) {
        // Insert the borrowing details into the bookcart table
        $insert_sql = "INSERT INTO bookcart (rfidnum, book, username, genre, author, isbn, bookserial, duration, duedate, borrowed_at, status)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'In Cart')";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssssssis", $rfidnum, $book, $username, $genre, $author, $isbn, $bookserial, $duration, $duedate);

        if ($insert_stmt->execute()) {
            // Update the quantity in the books table
            $update_sql = "UPDATE books SET quantity = quantity - 1 WHERE isbn = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("s", $isbn);
            if ($update_stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } else {
        // Book is not available (quantity is 0)
        echo json_encode(['success' => false, 'message' => 'Book is not available.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Book not found.']);
}

$conn->close();
?>
