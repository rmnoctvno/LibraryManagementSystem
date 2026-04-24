<?php 

session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // User is not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

include("database.php");

$categories = ['Trending Books', 'Fiction', 'Science'];
$books_by_category = [];
$isbn_by_category = [];

foreach ($categories as $category) {
    // Get books with their title, author, isbn, genre, and quantity
    $sql = "SELECT title, author, isbn, genre, quantity FROM books WHERE category = '$category' LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books_by_category[$category][] = $row;
            $isbn_by_category[$category][] = $row['isbn']; // Collect ISBN separately
        }
    } else {
        $books_by_category[$category] = [];
        $isbn_by_category[$category] = []; // Initialize empty array for ISBNs
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="catalog.css">

    <style>
    .book-quantity {
        position: absolute;
        font-size: 12px;
        color: #333;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }

    .book-card img {
        width: 100px;
        height: 150px;
        object-fit: cover;
    }

    .book-card {
        position: relative;
        width: 150px;
        height: 280px;
        text-align: center;
        padding: 10px;
    }

    .borrow-button {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Modal Styling */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        margin-top: 5px;
        margin-bottom: 5px;
        background-color: white;
        border-radius: 8px;
        width: 450px;
        text-align: left;
    }

    .modal-header {
        margin-bottom: 10px;
        width: 95.6%;
        background-color: #303F9F;
        color: white;
        padding: 10px;
        border-radius: 8px 8px 0 0;
        text-align: center;
    }

    .modal-footer {
        padding-left: 20px;
        padding-right: 20px;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .modal-footer button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .cancel-button {
        margin-left: 84px;
        margin-right: 20px;
        background-color: gray;
        color: white;
    }

    .confirm-button {
        background-color: #303F9F;
        color: white;
    }

    .modal-body {
        padding-left: 30px;
        padding-right: 30px;
    }

    .modal-body input {
        margin-top: 6px;
        width: 95%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .modal-body input[readonly] {
        background-color: #f0f0f0;
        /* Light gray background */
        color: #a0a0a0;
        /* Gray text color */
        cursor: not-allowed;
        /* Change the cursor to indicate it's not editable */
    }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <a class="nav-logo">
                <img src="icons/logo.png" alt="Logo" class="nav-logo-image">
                Library Management
            </a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link"><i class="bi bi-arrow-left-square-fill"></i> Back</a></li>
            </ul>
        </div>
    </nav>
    <header>
        <nav class="tabs">
            <a href="#" class="active">All</a>
            <a href="#" style="margin-right=350px">Genre</a>
            <a class="cartCss" style="font-size:35px ;padding-top: 0px; gap-left: 350px;" href="bookcart.php"
                class="nav-link"><i class="bi bi-bag"></i></a>
        </nav>
        <div class="search-bar">
            <div class="search-container">
                <div class="search-box">
                    <input type="text" class="input" placeholder=" Search... ">
                    <i class="searchbi bi-search"></i>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?php foreach ($categories as $category): ?>
        <section class="book-section">
            <h2><?php echo $category; ?></h2>
            <div class="book-carousel">
                <button class="carousel-button left">←</button>
                <div class="book-list">
                    <?php if (!empty($books_by_category[$category])): ?>
                    <?php foreach ($books_by_category[$category] as $index => $book): ?>
                    <div class="book-card">
                        <img src="icons/books/<?php echo file_exists("icons/books/{$isbn_by_category[$category][$index]}.jpg") ? htmlspecialchars($isbn_by_category[$category][$index]) : 'blankbook'; ?>.jpg"
                            alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <p class="book-title"><?php echo htmlspecialchars($book['title']); ?></p>
                        <p class="book-author">By <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="book-quantity">Available: <?php echo htmlspecialchars($book['quantity']); ?></p>
                        <button class="borrow-button" data-category="<?php echo htmlspecialchars($category); ?>"
                            data-title="<?php echo htmlspecialchars($book['title']); ?>"
                            data-author="<?php echo htmlspecialchars($book['author']); ?>"
                            data-isbn="<?php echo htmlspecialchars($book['isbn']); ?>"
                            data-genre="<?php echo htmlspecialchars($book['genre']); ?>"
                            data-quantity="<?php echo htmlspecialchars($book['quantity']); ?>">Borrow</button>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p>No books available in this category.</p>
                    <?php endif; ?>
                </div>
                <button class="carousel-button right">→</button>
            </div>
        </section>
        <?php endforeach; ?>
    </main>

    <!-- Modal -->
    <div class="modal" id="borrowModal">
        <div class="modal-content">
            <div class="modal-header">Confirm Borrowing Details</div>
            <div class="modal-body">

                <!-- New Genre Field -->
                <label for="modal-genre" style="display: none;"><strong>Genre:</strong></label>
                <input type="text" id="modal-genre" readonly style="display: none;">

                <label for="modal-title"><strong>Book Title:</strong></label>
                <input type="text" id="modal-title" readonly>

                <label for="modal-author"><strong>Author:</strong></label>
                <input type="text" id="modal-author" readonly>

                <label for="modal-isbn"><strong>ISBN:</strong></label>
                <input type="text" id="modal-isbn" readonly>

                <label for="modal-serial-number"><strong>Book Serial Number:</strong></label>
                <input type="text" id="modal-serial-number" oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                <label for="modal-username" style="display: none;"><strong>Username:</strong></label>
                <input type="text" id="modal-username" value="<?php echo $_SESSION['username'] ?? ''; ?>" readonly
                    style="display: none;">

                <label for="modal-rfid" style="display: none;"><strong>RFID:</strong></label>
                <input type="text" id="modal-rfid" value="<?php echo $_SESSION['rfid'] ?? ''; ?>" readonly
                    style="display: none;">

                <label for="modal-loan-duration"><strong>Borrow Duration:</strong></label>
                <input type="text" id="modal-loan-duration" value="7 Days" readonly>

                <label for="modal-due-date"><strong>Due Date:</strong></label>
                <input type="text" id="modal-due-date" readonly>

                <p>By confirming, you agree to return the book by the due date. Late returns will incur a fine of ₱10
                    per day.</p>
                <p>NOTE: Please proceed to librarian for book serial number.</p>
            </div>
            <div class="modal-footer">
                <button class="cancel-button" id="modalCancel">Cancel</button>
                <button class="confirm-button">Confirm Borrow</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    const modal = document.getElementById('borrowModal');
    const cancelButton = document.getElementById('modalCancel');
    const confirmButton = document.querySelector('.confirm-button');
    const borrowButtons = document.querySelectorAll('.borrow-button');

    // Function to clear all modal textboxes
    function clearModalFields() {
        document.getElementById('modal-genre').value = '';
        document.getElementById('modal-title').value = '';
        document.getElementById('modal-author').value = '';
        document.getElementById('modal-isbn').value = '';
        document.getElementById('modal-username').value = '';
        document.getElementById('modal-rfid').value = '';
        document.getElementById('modal-due-date').value = '';
    }

    function calculateDueDate() {
        const today = new Date();
        const dueDate = new Date(today);
        dueDate.setDate(today.getDate() + 7); // Add 7 days

        // Format the date to 'dd-MMM-yyyy'
        const year = dueDate.getFullYear();
        const month = dueDate.toLocaleString('default', {
            month: 'short'
        }); // Get short month name
        const day = String(dueDate.getDate()).padStart(2, '0'); // Pad single digits with a leading zero

        return `${day}-${month}-${year}`;
    }


    // Borrow button click event
    let selectedBookCard = null;

    borrowButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Fetch user profile via AJAX to get penalty information
            fetch('get_user_profile.php')
                .then(response => response.json())
                .then(user => {
                    // Check if the user has a penalty
                    if (user.penalty) {
                        // If user has a penalty, show the alert with penalty due date
                        Swal.fire({
                            icon: 'warning',
                            title: 'Penalty Outstanding',
                            text: `You have a recent penalty. Please borrow again in \\ ${user.penaltydue}`,
                            confirmButtonText: 'OK'
                        });
                        return; // Stop further processing
                    }

                    // Populate the modal with book details
                    document.getElementById('modal-genre').value = button.getAttribute(
                        'data-genre');
                    document.getElementById('modal-title').value = button.getAttribute(
                        'data-title');
                    document.getElementById('modal-author').value = button.getAttribute(
                        'data-author');
                    document.getElementById('modal-isbn').value = button.getAttribute('data-isbn');

                    // Populate the modal with user details
                    document.getElementById('modal-username').value = user.username;
                    document.getElementById('modal-rfid').value = user.rfidnum;

                    // Calculate and set the due date (7 days from today)
                    document.getElementById('modal-due-date').value = calculateDueDate();

                    // Show the modal
                    modal.style.display = 'flex';

                    // Store the reference to the book card for hiding later
                    selectedBookCard = button.closest('.book-card');
                })
                .catch(error => {
                    console.error('Error fetching user profile:', error);
                });
        });
    });


    // Cancel button click event
    cancelButton.addEventListener('click', () => {
        clearModalFields(); // Clear fields when modal is closed
        modal.style.display = 'none';
        selectedBookCard = null; // Reset selected book card if modal is canceled
    });

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            clearModalFields(); // Clear fields when modal is closed
            modal.style.display = 'none';
            selectedBookCard = null; // Reset selected book card if modal is closed
        }
    });

    // Confirm button click event with AJAX for inserting data
    confirmButton.addEventListener('click', () => {
        const genre = document.getElementById('modal-genre').value;
        const title = document.getElementById('modal-title').value;
        const author = document.getElementById('modal-author').value;
        const isbn = document.getElementById('modal-isbn').value;
        const serialNumber = document.getElementById('modal-serial-number').value; // Get book serial number
        const username = document.getElementById('modal-username').value;
        const rfid = document.getElementById('modal-rfid').value;
        const duration = 7; // Borrow duration in days
        const duedate = document.getElementById('modal-due-date').value;

        // Check if the serial number is provided
        if (!serialNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Serial Number',
                text: 'Please enter the book serial number before confirming.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Send data to the server via AJAX
        fetch('insert_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    rfidnum: rfid,
                    book: title,
                    username: username,
                    genre: genre,
                    author: author,
                    isbn: isbn,
                    bookserial: serialNumber,
                    duration: duration,
                    duedate: duedate,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Book Added to Bag!',
                        text: 'You have successfully added the book to your Bag.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'catalog.php';
                    });

                    modal.style.display = 'none';
                    clearModalFields();
                } else if (data.message === 'You can only borrow 3 books!') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit Reached',
                        text: 'You can only borrow 3 books!',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Adding to Bag',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Unexpected Error',
                    text: 'An unexpected error occurred.',
                    confirmButtonText: 'OK'
                });
            });
    });

    // On page load, check if any books have been borrowed and hide/show those cards based on quantity and borrowed status
    window.addEventListener('DOMContentLoaded', () => {
        let borrowedBooks = JSON.parse(localStorage.getItem('borrowedBooks')) || [];

        document.querySelectorAll('.book-card').forEach(card => {
            const borrowButton = card.querySelector('.borrow-button');
            const isbn = borrowButton.getAttribute('data-isbn');
            const quantity = parseInt(borrowButton.getAttribute(
                'data-quantity')); // Get the quantity of the book

            // Don't hide the book card based on the quantity here

            $isbn = isbn;

            // Check if the book is already borrowed
            fetch('check_borrowed.php?isbn=' + isbn)
                .then(response => response.json())
                .then(data => {
                    if (data.borrowed) {
                        // If the book is borrowed, hide the book card
                        document.getElementById('bookCard').style.display = 'none';
                    } else {
                        // If the book is not borrowed, show the book card
                        document.getElementById('bookCard').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        document.querySelectorAll('.book-card').forEach(card => {
            const borrowButton = card.querySelector('.borrow-button');
            const isbn = borrowButton.getAttribute('data-isbn');
            const quantity = parseInt(borrowButton.getAttribute('data-quantity'));

            // If the quantity is 0, hide the book card
            if (quantity <= 0) {
                card.style.display = 'none';
            } else {
                card.style.display = 'block';
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>

<?php
$conn->close();
?>