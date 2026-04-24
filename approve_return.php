<?php
include 'database.php'; // Database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Start a transaction for atomic operations
    $conn->begin_transaction();

    try {
        // Step 1: Fetch the book information (book title, isbn, rfidnum) based on the given id
        $fetchBookSql = "SELECT book, isbn, rfidnum FROM pendingreturn WHERE id = ?";
        $fetchStmt = $conn->prepare($fetchBookSql);
        $fetchStmt->bind_param("i", $id);
        $fetchStmt->execute();
        $result = $fetchStmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bookTitle = $row['book'];
            $isbn = $row['isbn'];  // Get ISBN
            $rfidnum = $row['rfidnum']; // Get RFID number of the user

            // Step 2: Calculate the fine for this user based on overdue books
            $fine = 0;
            $sqlFine = "
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN NOW() > duedate AND duedate IS NOT NULL THEN DATEDIFF(NOW(), duedate) * 10
                        ELSE 0
                    END
                ), 0) AS totalFine
                FROM borrowedbooks
                WHERE rfidnum = ?";
            $fineStmt = $conn->prepare($sqlFine);
            $fineStmt->bind_param("i", $rfidnum);
            $fineStmt->execute();
            $fineResult = $fineStmt->get_result();
            if ($fineResult && $fineRow = $fineResult->fetch_assoc()) {
                $fine = $fineRow['totalFine'];
            }

            // Step 3: Delete the record from pendingreturn table
            $deletePendingSql = "DELETE FROM pendingreturn WHERE id = ?";
            $deletePendingStmt = $conn->prepare($deletePendingSql);
            $deletePendingStmt->bind_param("i", $id);
            $deletePendingStmt->execute();

            // Step 4: Delete the same book from the borrowedbooks table
            $deleteBorrowedSql = "DELETE FROM borrowedbooks WHERE book = ?";
            $deleteBorrowedStmt = $conn->prepare($deleteBorrowedSql);
            $deleteBorrowedStmt->bind_param("s", $bookTitle);
            $deleteBorrowedStmt->execute();

            // Step 5: Update the quantity of the book in the books table
            $updateBookQuantitySql = "UPDATE books SET quantity = quantity + 1 WHERE title = ?";
            $updateBookStmt = $conn->prepare($updateBookQuantitySql);
            $updateBookStmt->bind_param("s", $bookTitle);
            $updateBookStmt->execute();

            // Step 6: Update penalty if fine > 0
            if ($fine > 0) {
                // Set penalty details for the user
                $penaltyNow = date('Y-m-d'); // Current date
                $penaltyDue = date('Y-m-d', strtotime('+3 days')); // 3 days from now

                $updatePenaltySql = "UPDATE users 
                                     SET penalty = 1, penaltynow = ?, penaltydue = ? 
                                     WHERE rfidnum = ?";
                $updatePenaltyStmt = $conn->prepare($updatePenaltySql);
                $updatePenaltyStmt->bind_param('ssi', $penaltyNow, $penaltyDue, $rfidnum);
                $updatePenaltyStmt->execute();
                $updatePenaltyStmt->close();
            }

            // Commit the transaction if all operations succeed
            $conn->commit();

            // Return the success response with ISBN and penalty information if applicable
            echo json_encode(["success" => true, "message" => "Book approved, removed, and quantity updated.", "isbn" => $isbn, "fine" => $fine]);
        } else {
            echo json_encode(["success" => false, "message" => "Book not found in pendingreturn."]);
        }

        // Close prepared statements
        $fetchStmt->close();
        $deletePendingStmt->close();
        $deleteBorrowedStmt->close();
        $updateBookStmt->close();
        $fineStmt->close();

    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid ID."]);
}

$conn->close();
?>
