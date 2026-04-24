<?php
include 'database.php';

// Function to add a book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookTitle = $_POST['bookTitle'];
    $bookAuthor = $_POST['bookAuthor'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO books (title, author) VALUES (?, ?)");
    $stmt->bind_param("ss", $bookTitle, $bookAuthor);
    
    if ($stmt->execute()) {
        echo "Book added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    exit; // End the script here after adding the book
}

// Function to fetch books
$sql = "SELECT * FROM books"; // Adjust the table name as necessary
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row; // Collect the books
    }
}

echo json_encode($books); // Return books as JSON
?>
