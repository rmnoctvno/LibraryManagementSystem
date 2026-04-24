<?php
session_start();
include("database.php");

$data = json_decode(file_get_contents('php://input'), true);
$cartBooks = $data['books']; // Extract books array

// Assuming session contains user info
$username = $_SESSION['username'];
$rfidnum = $_SESSION['rfidnum']; // Assuming you store the RFID number in the session

// Check the count of currently borrowed books
$sqlBorrowedCount = "SELECT COUNT(*) AS borrowed_count FROM borrowedbooks 
                     WHERE rfidnum = ? AND status = 'Borrowed'";
if ($stmtBorrowedCount = $conn->prepare($sqlBorrowedCount)) {
    $stmtBorrowedCount->bind_param("s", $rfidnum);
    $stmtBorrowedCount->execute();
    $stmtBorrowedCount->bind_result($borrowedCount);
    $stmtBorrowedCount->fetch();
    $stmtBorrowedCount->close();
}

if ($borrowedCount >= 3) {
    // User already borrowed 3 books
    $response = [
        'success' => false,
        'message' => 'You already have 3 borrowed books. Please return them before borrowing more.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    $conn->close();
    exit();
}

// Check if user borrowed books today
$sqlBorrowedToday = "SELECT 1 FROM borrowedbooks 
                     WHERE rfidnum = ? AND DATE(borrowed_at) = CURDATE() AND status = 'Borrowed' LIMIT 1";
if ($stmtBorrowedToday = $conn->prepare($sqlBorrowedToday)) {
    $stmtBorrowedToday->bind_param("s", $rfidnum);
    $stmtBorrowedToday->execute();
    $stmtBorrowedToday->store_result(); // Store result for checking if any row was found
    $borrowedToday = $stmtBorrowedToday->num_rows; // Number of rows returned
    $stmtBorrowedToday->close();
}

if ($borrowedToday > 0) {
    $response = [
        'success' => false,
        'message' => 'You already borrowed books today. Please return your current books before borrowing more.'
    ];
    echo json_encode($response);
    exit();
}

// Initialize a flag to check if the books are successfully inserted
$allBooksInserted = true;
$dueDate = date('Y-m-d', strtotime("+7 days")); // Set due date 7 days from now

foreach ($cartBooks as $book) {
    $bookTitle = $book['bookTitle'];
    $author = $book['author'];
    $isbn = $book['isbn'];

    echo "ISBN: " . $isbn;

    // Query to fetch the genre
    $sqlGenre = "SELECT genre FROM bookcart WHERE username = ? AND book = ? LIMIT 1";
    if ($genreStmt = $conn->prepare($sqlGenre)) {
        $genreStmt->bind_param('ss', $username, $bookTitle);
        $genreStmt->execute();
        $genreStmt->bind_result($genre);
        $genreStmt->fetch();
        if (!$genre) {
            $genre = 'Unknown';
        }
        $genreStmt->close();
    }

    $duration = isset($book['duration']) ? $book['duration'] : 7;
    $bookserial = isset($book['bookserial']) ? $book['bookserial'] : '';
    $duedate = isset($book['duedate']) ? $book['duedate'] : $dueDate;

    // Insert into borrowedbooks table
    $sql = "INSERT INTO borrowedbooks (rfidnum, book, username, genre, author, isbn, duration, duedate, borrowed_at, status, bookserial) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Borrowed', ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssssssiss', $rfidnum, $bookTitle, $username, $genre, $author, $isbn, $duration, $duedate, $bookserial);

        if (!$stmt->execute()) {
            $allBooksInserted = false;
            break;
        }
        $stmt->close();
    } else {
        $allBooksInserted = false;
        break;
    }

    // Insert into pendingreturn table
    $sqlPending = "INSERT INTO pendingreturn (rfidnum, book, username, genre, author, isbn, duration, duedate, borrowed_at, status, bookserial) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Borrowed', ?)";
    if ($stmtPending = $conn->prepare($sqlPending)) {
        $stmtPending->bind_param('ssssssiss', $rfidnum, $bookTitle, $username, $genre, $author, $isbn, $duration, $duedate, $bookserial);

        if (!$stmtPending->execute()) {
            $allBooksInserted = false;
            break;
        }
        $stmtPending->close();
    } else {
        $allBooksInserted = false;
        break;
    }
}


// If all books were inserted successfully, delete them from the bookcart
if ($allBooksInserted) {
    $deleteSql = "DELETE FROM bookcart WHERE username = ?";
    if ($deleteStmt = $conn->prepare($deleteSql)) {
        $deleteStmt->bind_param('s', $username);
        if ($deleteStmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Books borrowed successfully.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Books borrowed, but failed to remove them from the cart.'
            ];
        }
        $deleteStmt->close();
    } else {
        $response = [
            'success' => false,
            'message' => 'Database error: ' . $conn->error
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Failed to borrow all books.'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>