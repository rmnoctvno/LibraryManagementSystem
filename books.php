<?php
include 'database.php'; // Include database connection

// Handle fetching book details for update
if (isset($_GET['get_book_id'])) {
    $get_book_id = $_GET['get_book_id'];

    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $get_book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    echo json_encode($book); // Return the book details as JSON
    exit();
}

// Handle book addition
if (isset($_POST['addBook'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $published_date = $_POST['published_date'];
    $genre = $_POST['genre'];

    $sql = "INSERT INTO books (title, author, isbn, published_date, genre) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $author, $isbn, $published_date, $genre);

    if ($stmt->execute()) {
        echo "<script>Swal.fire('Book added successfully!');</script>";
    } else {
        echo "<script>Swal.fire('Error adding book.');</script>";
    }

    $stmt->close();
}


// Handle book update
if (isset($_POST['updateBook'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $published_date = $_POST['published_date'];
    $genre = $_POST['genre'];

    $sql = "UPDATE books SET title = ?, author = ?, isbn = ?, published_date = ?, genre = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $author, $isbn, $published_date, $genre, $id);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    title: 'Book updated successfully!',
                    icon: 'success'
                }).then(() => {
                    window.location.href = 'homepage.php?page=books';
                });
              </script>";
    } else {
        echo "<script>Swal.fire('Error updating book.');</script>";
    }

    $stmt->close();
}


// Handle book deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare the SQL statement for deletion
    $delete_sql = "DELETE FROM books WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        // Output JavaScript for successful deletion
        echo "<script>
                Swal.fire({
                    title: 'Book deleted successfully!',
                    icon: 'success'
                }).then(() => {
                    window.location.href = 'homepage.php?page=books'; // Redirect after confirmation
                });
              </script>";
        exit(); // Prevent further execution after redirect
    } else {
        echo "<script>Swal.fire('Error deleting book.');</script>";
    }

    $delete_stmt->close(); // Close statement
}

// Fetch existing books from the database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>
<style media="screen">
.addBtn {
    color: White;
    background-color: #303F9F;
    margin-left: 660px;
    width: 140px;
    height: 50px;
    border-radius: 10px;
    border: none;
    cursor: pointer;

}

.addbi {
    margin-right: 6px;
}

button:active {
    opacity: 0.8;
}

.modal.open {
    display: flex;
    align-items: center;
    justify-content: center;
}

.inner-modal {
    background-color: White;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
    padding: 15px 25px;
    text-align: center;
    height: 580px;
    width: 570px;
}

.inner-modal h2 {
    margin: 0;
}

.inner-modal p {
    line-height: 24px;
    margin: 10px 0;
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

.searchbi {
    position: absolute;
    right: 10px;
    margin-left: 7px;
}

.search-container {
    display: flex;
    align-items: center;
    margin-top: 30px;
    margin-bottom: 10px;

}

form {
    margin-top: 30px;
    margin-left: 10px;
    margin-right: 10px;
    margin-bottom: 10px;
}

.btn-popup {
    color: white;
    background-color: #303F9F;
    margin: 10px;
    border-radius: 8px;
    height: 50px;
    width: 100px;
    border-color: white;
}

.form-label {
    text-align: left;
    display: block;
}


.table-container {
    margin-left: 20px;
    margin-right: 20px;
}

.bi-trash {
    background-color: red;
    color: white;
    height: 35px;
    width: 35px;
    border-radius: 5px;
    border: none;
}

.bi-pencil {
    background-color: Green;
    color: white;
    height: 35px;
    width: 35px;
    border-radius: 5px;
    border: none;
}
</style>

<body>
    <div class="container mt-3">
        <h2 style="color: #303F9F;">Books</h2>
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="input" placeholder=" Search... " id="searchInput"><i
                    class="searchbi bi-search"></i></input>
            </div>
            <!--Add Book Button -->
            <button class="addBtn" id="openModal"><i class="addbi bi-plus-circle-fill fa-lg"></i>Add Book</button>
        </div>
    </div>

    <div class="modal" id="modal">
        <div class="inner-modal">
            <h2>Add a New Book</h2>
            <form method="POST" action="homepage.php?page=books">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <input type="text" class="form-control" id="genre" name="genre" required>
                </div>
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" required>
                </div>
                <div class="mb-3">
                    <label for="published_date" class="form-label">Published Date</label>
                    <input type="date" class="form-control" id="published_date" name="published_date" required>
                </div>
                <button type="submit" name="addBook" class="btn-popup">Add Book</button>
                <button id="closeModal" class="btn-popup">Cancel</button>
            </form>
        </div>
    </div>

    <div class="modal" id="updateModal">
        <div class="inner-modal">
            <h2>Update Book</h2>
            <form method="POST" action="homepage.php?page=books">
                <input type="hidden" id="updateId" name="id">
                <div class="mb-3">
                    <label for="updateTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="updateTitle" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="updateAuthor" class="form-label">Author</label>
                    <input type="text" class="form-control" id="updateAuthor" name="author" required>
                </div>
                <div class="mb-3">
                    <label for="updateGenre" class="form-label">Genre</label>
                    <input type="text" class="form-control" id="updateGenre" name="genre" required>
                </div>
                <div class="mb-3">
                    <label for="updateISBN" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="updateISBN" name="isbn" required>
                </div>
                <div class="mb-3">
                    <label for="updatePublishedDate" class="form-label">Published Date</label>
                    <input type="date" class="form-control" id="updatePublishedDate" name="published_date" required>
                </div>
                <button type="submit" name="updateBook" class="btn-popup">Update Book</button>
                <button id="closeUpdateModal" class="btn-popup" type="button">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    const openBtn = document.getElementById("openModal");
    const closeBtn = document.getElementById("closeModal");
    const modal = document.getElementById("modal");

    openBtn.addEventListener("click", () => {
        modal.classList.add("open");
    });

    closeBtn.addEventListener("click", () => {
        modal.classList.remove("open");
    });
    </script>
</body>
<div class="table-container">
    <h2 style="margin-top: 13px; margin-bottom: 13px; color: #303F9F;;" class="mt-5">Existing Books</h2>
    <table id="#myTable" class="table table-striped">
        <thead>
            <tr>
                <th style="color: white; background-color: #303F9F">Title</th>
                <th style="color: white; background-color: #303F9F;">Author</th>
                <th style="color: white; background-color: #303F9F;">Genre</th>
                <th style="color: white; background-color: #303F9F;">Published Date</th>
                <th style="color: white; background-color: #303F9F;">Action</th>
            </tr>
        </thead>
</div>
<tbody>
    <?php
            // Display the list of books
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $formattedDate = date('d-M-Y', strtotime($row['published_date']));
                    echo "<tr;>
                            <td>{$row['title']}</td>
                            <td>{$row['author']}</td>
                            <td>{$row['genre']}</td> 
                            <td>{$formattedDate}</td>
                            <td>
                                <button class='bi-trash bi-trash3-fill' onclick='confirmDelete({$row['id']})'></button>
                                <button class='bi-pencil bi-pencil-square' onclick='confirmUpdate({$row['id']})'></button>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No books found.</td></tr>";
            }
        ?>
</tbody>
</table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<script>
function confirmDelete(bookId) {
    Swal.fire({
        title: 'Do you want to delete the book?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make an AJAX request to delete the book
            $.ajax({
                url: 'books.php',
                type: 'GET',
                data: {
                    delete_id: bookId
                },
                success: function(response) {
                    // After successful deletion, show success message and redirect
                    Swal.fire({
                        title: 'Book deleted successfully!',
                        icon: 'success'
                    }).then(() => {
                        window.location.href =
                        'homepage.php?page=books'; // Redirect here after success
                    });
                },
                error: function() {
                    Swal.fire('Error deleting book.');
                }
            });
        }
    });
}

function confirmUpdate(bookId) {
    $.ajax({
        url: 'books.php',
        type: 'GET',
        data: {
            get_book_id: bookId
        },
        success: function(data) {
            const book = JSON.parse(data);

            document.getElementById('updateId').value = book.id;
            document.getElementById('updateTitle').value = book.title;
            document.getElementById('updateAuthor').value = book.author;
            document.getElementById('updateISBN').value = book.isbn;
            document.getElementById('updatePublishedDate').value = book.published_date;
            document.getElementById('updateGenre').value = book.genre;

            updateModal.classList.add('open');
        },
        error: function() {
            Swal.fire('Error fetching book details.');
        }
    });

    // Close Update Modal
    const updateModal = document.getElementById('updateModal');
    const closeUpdateBtn = document.getElementById('closeUpdateModal');

    closeUpdateBtn.addEventListener('click', () => {
        updateModal.classList.remove('open');
    });

}
</script>