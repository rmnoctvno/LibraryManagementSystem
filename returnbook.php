<?php
include 'database.php'; // Include database connection

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate the book ID
    if (isset($input['id'])) {
        $bookId = intval($input['id']);

        // Start transaction for atomic operations
        $conn->begin_transaction();

        try {
            // Step 1: Fetch the book data from borrowedbooks table
            $fetchSql = "SELECT * FROM borrowedbooks WHERE id = ?";
            $fetchStmt = $conn->prepare($fetchSql);
            $fetchStmt->bind_param("i", $bookId);
            $fetchStmt->execute();
            $result = $fetchStmt->get_result();

            if ($result->num_rows > 0) {
                $bookData = $result->fetch_assoc();

                // Step 2: Insert the data into pendingreturn table
                $insertSql = "INSERT INTO pendingreturn (rfidnum, username, book, genre, author, isbn, duration, duedate, borrowed_at, status, bookserial)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertSql);
                $status = 'Pending'; // Define the variable for status

                $insertStmt->bind_param(
                    "sssssssssss",  // Bind parameters
                    $bookData['rfidnum'],
                    $bookData['username'],
                    $bookData['book'],
                    $bookData['genre'],
                    $bookData['author'],
                    $bookData['isbn'],  // Include ISBN here
                    $bookData['duration'],
                    $bookData['duedate'],
                    $bookData['borrowed_at'],
                    $status,
                    $bookData['bookserial'] // Include bookserial here
                );
                
                $insertStmt->execute();

                // Step 3: Update the status to 'Pending' in borrowedbooks table
                $updateSql = "UPDATE borrowedbooks SET status = 'Pending' WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("i", $bookId);
                $updateStmt->execute();

                // Commit the transaction
                $conn->commit();

                // Return success response
                echo json_encode(["success" => true, "message" => "Book return request is now pending."]);
            } else {
                echo json_encode(["success" => false, "message" => "Book not found."]);
            }

            // Close prepared statements
            $fetchStmt->close();
            $insertStmt->close();
            $updateStmt->close();
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid book ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>