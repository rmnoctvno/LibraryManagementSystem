<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: index.php");
    exit();
}

include("database.php");

// Retrieve all items in the bookcart table for the current user
$username = $_SESSION['username']; // Assuming username is stored in session
$sql = "SELECT * FROM bookcart WHERE username = '$username'";
$result = $conn->query($sql);
$cart_books = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Bag</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="bookcart.css">
    <style>
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

    .remove-button {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #ff4d4d;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .remove-button:hover {
        background-color: #e60000;
    }

    .proceedBtn {
        align-items: center;
        appearance: button;
        background-color: #303F9F;
        border-radius: 8px;
        border-style: none;
        box-shadow: rgba(255, 255, 255, 0.26) 0 1px 2px inset;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: flex;
        flex-direction: row;
        flex-shrink: 0;
        font-family: "RM Neue", sans-serif;
        font-size: 100%;
        line-height: 1.15;
        margin: 0;
        padding: 10px 21px;
        text-align: center;
        text-transform: none;
        transition: color .13s ease-in-out, background .13s ease-in-out, opacity .13s ease-in-out, box-shadow .13s ease-in-out;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        position: absolute;
        right: 725px;
        top: 500px;
    }

    .proceedBtn:active {
        background-color: rgb(41, 54, 138);
    }

    .proceedBtn:hover {
        background-color: rgb(71, 92, 231);
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
                <li><a href="catalog.php" class="nav-link"><i class="bi bi-arrow-left-square-fill"></i> Back</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section class="book-section">
            <h2>My Book Bag</h2>
            <button class="proceedBtn" role="button">Borrow</button>
            <div class="book-list">
                <?php if (!empty($cart_books)): ?>
                <?php foreach ($cart_books as $book): ?>
                <div class="book-card" data-isbn="<?php echo htmlspecialchars($book['isbn']); ?>">
                    <img src="icons/books/<?php echo file_exists("icons/books/{$book['isbn']}.jpg") ? htmlspecialchars($book['isbn']) : 'blankbook'; ?>.jpg"
                        alt="<?php echo htmlspecialchars($book['book']); ?>">
                    <p class="book-title"><?php echo htmlspecialchars($book['book']); ?></p>
                    <p class="book-author">By <?php echo htmlspecialchars($book['author']); ?></p>
                    <button class="remove-button"
                        data-serial="<?php echo htmlspecialchars($book['bookserial']); ?>">Remove</button>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>No books in your bag yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Handle Remove Button Click
    document.querySelectorAll('.remove-button').forEach(button => {
        button.addEventListener('click', () => {
            const bookSerial = button.getAttribute(
                'data-serial'); // Get book serial from the button's data-serial attribute

            console.log({
                bookSerial
            });

            // Show confirmation dialog before removing the book
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to remove this book?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with removing the book
                    fetch('remove_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                bookserial: bookSerial
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Removed from Bag',
                                    text: data.message,
                                }).then(() => {
                                    // Reload the cart or remove the book element
                                    button.closest('.book-card').remove();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error',
                                text: 'An unexpected error occurred. Please try again.',
                            });
                        });
                }
            });
        });
    });

    // Handle Proceed Button Click
    document.querySelector('.proceedBtn').addEventListener('click', () => {
        const cartBooks = [];

        // Collect all the books in the cart
        document.querySelectorAll('.book-card').forEach(card => {
            const book = {
                bookserial: card.querySelector('.remove-button').getAttribute('data-serial'),
                bookTitle: card.querySelector('.book-title').textContent,
                author: card.querySelector('.book-author').textContent,
                isbn: card.getAttribute('data-isbn'),
                genre: card.querySelector('.book-genre')?.textContent ||
                    'Unknown' // Ensure genre is added
            };
            cartBooks.push(book);
        });

        if (cartBooks.length > 0) {
            // Show confirmation SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to borrow these books?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send a request to insert books into the borrowedbooks table
                    fetch('insert_borrow.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                books: cartBooks
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Books Borrowed Successfully!',
                                    text: data.message
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error',
                                text: 'An unexpected error occurred. Please try again.'
                            });
                        });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No Books in Bag',
                text: 'Please add books to your bag before proceeding.'
            });
        }
    });
    </script>

</body>

</html>

<?php
$conn->close();
?>