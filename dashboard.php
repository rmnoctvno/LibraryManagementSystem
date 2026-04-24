<?php
// Include database connection file
include 'database.php'; // Ensure that you have the correct database connection file

// Fetch the total number of books from the books table
$sqlBooks = "SELECT COUNT(*) AS totalBooks FROM books"; // Replace 'books' with your actual table name
$resultBooks = $conn->query($sqlBooks);

// Fetch the total number of students from the users table
$sqlUsers = "SELECT COUNT(*) AS totalStudents FROM users WHERE userType = 'STUDENT'"; // Replace 'users' with your actual users table name
$resultUsers = $conn->query($sqlUsers);

// Fetch the total number of borrowed books from the borrowedbooks table
$sqlBorrowed = "SELECT COUNT(*) AS totalBorrowed FROM borrowedbooks"; // Replace 'borrowedbooks' with your actual borrowed books table name
$resultBorrowed = $conn->query($sqlBorrowed);

// Fetch the total number of pending returns from the pendingreturn table
$sqlPendingReturns = "SELECT COUNT(*) AS totalPendingReturns FROM pendingreturn"; // Replace 'pendingreturn' with your actual pending returns table name
$resultPendingReturns = $conn->query($sqlPendingReturns);

// Check if the query was successful and fetch the count of books
$totalBooks = 0; // Default value if no data is found
if ($resultBooks && $rowBooks = $resultBooks->fetch_assoc()) {
    $totalBooks = $rowBooks['totalBooks']; // Get the total count of books
} else {
    echo "Error fetching book data: " . $conn->error;
}

// Check if the query was successful and fetch the count of students
$totalStudents = 0; // Default value if no data is found
if ($resultUsers && $rowUsers = $resultUsers->fetch_assoc()) {
    $totalStudents = $rowUsers['totalStudents']; // Get the total count of students
} else {
    echo "Error fetching user data: " . $conn->error;
}

// Check if the query was successful and fetch the count of borrowed books
$totalBorrowed = 0; // Default value if no data is found
if ($resultBorrowed && $rowBorrowed = $resultBorrowed->fetch_assoc()) {
    $totalBorrowed = $rowBorrowed['totalBorrowed']; // Get the total count of borrowed books
} else {
    echo "Error fetching borrowed books data: " . $conn->error;
}

// Check if the query was successful and fetch the count of pending returns
$totalPendingReturns = 0; // Default value if no data is found
if ($resultPendingReturns && $rowPendingReturns = $resultPendingReturns->fetch_assoc()) {
    $totalPendingReturns = $rowPendingReturns['totalPendingReturns']; // Get the total count of pending returns
} else {
    echo "Error fetching pending returns data: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <style media="screen">
    *{  
        position: relative;
    }
    h2{
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .card{
        margin-right: 20px;
    }
    .bgbook{
        background-color: black;
        height: 20px;
        width: 20px;
    }
    .card-title{
        margin-top: 10px;
    }
    .card-text{
        color: #303F9F;
    }
    .overview{
        margin-left: 5px;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    </style>
  </head>
<body>
<h2 style="color: #303F9F;">Dashboard</h2>
<p class="overview">Overview</p>
<div class="card-group">
    <span class="card" style="border-radius: 15px;">
        <span class="shadow-sm p-3 card-body">
        <div style="background-color: #512DA8; height: 65px; width: 70px;
        border-radius: 50px;">
        <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/tbooks.png" class="rounded-bottom" alt="booklogo">
        </div>
        <h6 class="card-title">Total Books</h6>
        <h1 class="card-text"><?php echo $totalBooks; ?></h1>
        </span>
    </span>
    <span class="card" style="border-radius: 15px;">
        <span class="shadow-sm p-3 card-body">
        <div style="background-color: #FF9500; height: 65px; width: 70px;
        border-radius: 50px;">
        <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/users.png" class="rounded-bottom" alt="memberslogo">
        </div>
        <h6 class="card-title">Members</h6>
        <h1 class="card-text"><?php echo $totalStudents; ?></h1>
        </span>
    </span>
    <span class="card" style="border-radius: 15px;">
        <span class="shadow-sm p-3 card-body">
        <div style="background-color: #FFCC00; height: 65px; width: 70px;
        border-radius: 50px;">
        <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/tborrow.png" class="rounded-bottom" alt="borrowlogo">
        </div>
        <h6 class="card-title">Borrowed</h6>
        <h1 class="card-text"><?php echo $totalBorrowed; ?></h1>
        </span>
    </span>
    <span class="card" style="border-radius: 15px;">
        <span class="shadow-sm p-3 card-body">
        <div style="background-color: #34C759; height: 65px; width: 70px;
        border-radius: 50px;">
        <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/treturn.png" class="rounded-bottom" alt="returnlog">
        </div>
        <h6 class="card-title">Pending Return</h6>
        <h1 class="card-text"><?php echo $totalPendingReturns; ?></h1>
        </span>
    </span>
</div>
</body>
</html>