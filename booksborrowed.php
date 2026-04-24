<?php
include 'database.php'; // Include database connection

// Check if user is logged in and retrieve their RFID number
if (!isset($_SESSION['rfidnum'])) {
    die('User not logged in.');
}

$loggedInRfid = $_SESSION['rfidnum'];

// Fetch borrowed books for the logged-in user
$sql = "SELECT * FROM borrowedbooks WHERE rfidnum = '$loggedInRfid'";
$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die("Error fetching borrowed books: " . $conn->error);  // Show the error if the query fails
}
?>

<style>
/* Search bar */
.search-container {
    display: flex;
    align-items: center;
    margin-top: 30px;
    margin-bottom: 10px;
}

.input {
    border-color: white;
    height: 40px;
    padding-left: 16px;
    margin-right: 34px;
    border: none;
    border-radius: 14px;
    font-size: 16px;
    width: 380px;
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
    margin-left: 10px;
}

.searchbi {
    position: absolute;
    right: 10px;
    margin-left: 7px;
}

.table-container {
    margin-top: 40px;
    margin-left: 20px;
    margin-right: 20px;
}

.bi-return {
    background-color: #4CAF50;
    color: white;
    height: 35px;
    width: 60px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

/* Additional styling for table and buttons can go here */
</style>

<body>
    <div class="container mt-3">
        <h2 style="color: #303F9F;">Borrowed Books</h2>
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="input" placeholder="Search... " id="searchInput"><i
                    class="searchbi bi-search"></i></input>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="color: white; background-color: #303F9F;">Book</th>
                    <th style="color: white; background-color: #303F9F;">Genre</th>
                    <th style="color: white; background-color: #303F9F;">Author</th>
                    <th style="color: white; background-color: #303F9F;">ISBN</th>
                    <th style="color: white; background-color: #303F9F;">Duration</th>
                    <th style="color: white; background-color: #303F9F;">Due Date</th>
                    <th style="color: white; background-color: #303F9F;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
    // Display the list of borrowed books for the logged-in user
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $disabled = $row['status'] === 'Pending' ? 'disabled' : ''; // Check if the button should be disabled
            $buttonText = $row['status'] === 'Pending' ? 'Return' : 'Return'; // Set button text based on status
            $buttonStyle = $row['status'] === 'Pending' ? "style='background-color: #A9A9A9; cursor: not-allowed;'" : ''; // Set style for disabled button
        
            $formattedDueDate = date('d-M-Y', strtotime($row['duedate']));
        
            echo "<tr>
                    <td>{$row['book']}</td>
                    <td>{$row['genre']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['isbn']}</td>
                    <td>{$row['duration']} days</td>
                    <td>{$formattedDueDate}</td>
                    <td>{$row['status']}</td>
                </tr>";
        }
        
    } else {
        echo "<tr><td colspan='9'>No borrowed books found.</td></tr>";
    }
    ?>
            </tbody>

        </table>
    </div>

    <script>
    function returnBook(bookId) {
        Swal.fire({
            title: 'Do you want to return this borrowed book?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, return it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to update status to "Pending"
                fetch('returnbook.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: bookId
                        }) // Send the book ID
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Returned!',
                                text: 'The book return request is now pending.',
                            }).then(() => {
                                // Redirect after success
                                window.location.href = "homepage.php?page=booksborrowed";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update the return status.',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong while processing the request.',
                        });
                    });
            }
        });
    }
    </script>
    <script>
    // Search functionality
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase(); // Get search input and convert to lowercase
            $('tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().includes(searchText));
            });
        });
    });
    </script>
</body>