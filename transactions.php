<?php
include 'database.php'; // Include database connection

// Fetch pending return books
$sql = "
    SELECT 
        username, 
        book,
        genre, 
        author, 
        duration, 
        duedate, 
        id, 
        rfidnum,
        bookserial  -- Add bookserial to the query
    FROM pendingreturn
";
$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die("Error fetching pending return books: " . $conn->error);
}

// Function to calculate fine based on RFID number
function calculateFine($rfidnum, $conn) {
    $sqlFine = "
        SELECT COALESCE(SUM(
            CASE 
                WHEN NOW() > duedate AND duedate IS NOT NULL THEN DATEDIFF(NOW(), duedate) * 10
                ELSE 0
            END
        ), 0) AS totalFine
        FROM borrowedbooks
        WHERE rfidnum = '$rfidnum'
    ";
    $fineResult = $conn->query($sqlFine);
    if ($fineResult && $fineRow = $fineResult->fetch_assoc()) {
        return $fineRow['totalFine'];
    }
    return 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Returns</title>
    <!-- SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style media="screen">
    h2 {
        margin-left: 10px;
        margin-top: 12px;
        margin-bottom: 20px;
    }

    li {
        padding-left: 8px;
        padding-right: 8px;
    }

    .nav-link {
        font-size: 15px;
        color: black;
        width: 100%;
        font-weight: medium;
    }

    .nav-link:hover,
    :focus {
        border-bottom: 3px solid #303F9F;
    }

    .search-container {
        display: flex;
        align-items: center;
        margin-top: 30px;
        margin-bottom: 10px;
    }

    .input {
        border-color: white;
        height: 40px;
        padding: 6px;
        border: none;
        border-radius: 14px;
        padding: 8px 12px;
        font-size: 16px;
        width: 350px;
        /* Adjust width as needed */
    }

    .search-box {
        height: 40px;
        width: 32%;
        background-color: white;
        border: none;
        border-radius: 14px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .searchbi {
        position: absolute;
        right: 10px;
        margin-left: 7px;
    }
    </style>
    <style>
    h2 {
        margin-left: 10px;
        margin-top: 12px;
        margin-bottom: 20px;
    }

    .table-container {
        margin: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th,
    .table td {
        text-align: left;
        padding: 8px;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #303F9F;
        color: white;
    }

    .approve-button {
        background-color: #4CAF50;
        color: white;
        height: 35px;
        width: 80px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    .approve-button:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <h2 style="color: #303F9F;">Transactions</h2>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="#">Return Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Overdue Books</a>
        </li>
    </ul>

    <h2 style="color: #303F9F; margin-left: 20px;">Pending Return Requests</h2>
    <div class="table-container">
        <table class="table" id="pendingReturnTable">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Book</th>
                    <th>Genre</th>
                    <th>Author</th>
                    <th>Duration</th>
                    <th>Due Date</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display each pending return book with fine
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fine = calculateFine($row['rfidnum'], $conn); // Calculate fine
                        $formattedDate = date('d-M-Y', strtotime($row['duedate'])); // Format due date
                        echo "
                        <tr id='row-{$row['id']}'>
                            <td>{$row['username']}</td>
                            <td>{$row['book']}</td>
                            <td>{$row['genre']}</td>
                            <td>{$row['author']}</td>
                            <td>{$row['duration']} days</td>
                            <td>{$formattedDate}</td>
                            <td>₱" . number_format($fine) . "</td>
                            <td>
                                <button class='approve-button' onclick='approveReturn({$row['id']}, \"{$row['bookserial']}\")'>Returned</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No pending return requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Serial Number Verification -->
    <div class="modal fade" id="verifySerialModal" tabindex="-1" aria-labelledby="verifySerialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifySerialModalLabel">Verify Book Serial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="verifySerialForm">
                        <div class="mb-3">
                            <label for="serialNumber" class="form-label">Enter Book Serial Number</label>
                            <input type="text" class="form-control" id="serialNumber" name="serialNumber"
                                placeholder="Enter Book Serial Number" required>
                        </div>
                        <input type="hidden" id="bookId" name="bookId">
                        <input type="hidden" id="rfidnum" name="rfidnum">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="verifySerialBtn">Verify</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let bookIdToApprove = null;
    let expectedSerial = null;

    // Function to open the modal for serial verification
    function approveReturn(bookId, serial) {
        bookIdToApprove = bookId;
        expectedSerial = serial;

        // Set the bookId dynamically in the modal
        document.getElementById('bookId').value = bookId;
        document.getElementById('rfidnum').value = serial;

        // Show the modal to verify serial number
        $('#verifySerialModal').modal('show');
    }

    // Handle Serial Number Verification
    document.getElementById('verifySerialBtn').addEventListener('click', function() {
        var serialNumber = document.getElementById('serialNumber').value;

        if (serialNumber === expectedSerial) {
            // If serial matches, proceed with approval
            approveBookReturn(bookIdToApprove);
        } else {
            // If serial does not match, reject the return
            Swal.fire(
                'Rejected!',
                'The serial number does not match. The return has been rejected.',
                'error'
            );
            $('#verifySerialModal').modal('hide'); // Hide the modal
        }
    });

    // Function to approve the return
    function approveBookReturn(bookId) {
        fetch(`approve_return.php?id=${bookId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`row-${bookId}`);
                    if (row) row.remove();
                    Swal.fire(
                        'Returned!',
                        'The the book has been returned.',
                        'success'
                    ).then(() => {
                        window.location.href = "homepage.php?page=transactions";
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'Failed to approve the return. Please try again.',
                        'error'
                    );
                }
                $('#verifySerialModal').modal('hide'); // Hide the modal
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'Something went wrong. Please try again later.',
                    'error'
                );
                $('#verifySerialModal').modal('hide'); // Hide the modal
            });
    }
    </script>
</body>

</html>