<?php
// Include the database connection
include 'database.php'; 

// Initialize fine calculation
$totalFine = 0;
$totalBooksBorrowed = 0; // Variable to store the total number of books borrowed
$totalDueBooks = 0; // Variable to store the total number of due books

// Get the RFID from the session
if (isset($_SESSION['rfidnum'])) {
    $rfid = $_SESSION['rfidnum'];
}

try {
    // Check if RFID is provided
    if (!empty($rfid)) {
        // Query to dynamically calculate the fine for each overdue book based on RFID
        $sqlFine = "
            SELECT COALESCE(SUM(
                CASE 
                    WHEN NOW() > duedate AND duedate IS NOT NULL THEN DATEDIFF(NOW(), duedate) * 10
                    ELSE 0
                END
            ), 0) AS totalFine
            FROM borrowedbooks
            WHERE rfidnum = '$rfid';
        ";
        
        // Query to count the total number of borrowed books based on RFID
        $sqlBooksBorrowed = "
            SELECT COUNT(*) AS totalBooks
            FROM borrowedbooks
            WHERE rfidnum = '$rfid';
        ";

        // Query to count the number of overdue books (due books)
        $sqlDueBooks = "
            SELECT COUNT(*) AS totalDueBooks
            FROM borrowedbooks
            WHERE rfidnum = '$rfid' AND duedate < NOW() AND duedate IS NOT NULL;
        ";
    } else {
        throw new Exception("RFID is required.");
    }

    // Execute the query to calculate fine
    $resultFine = $conn->query($sqlFine);
    if ($resultFine) {
        $rowFine = $resultFine->fetch_assoc();
        if ($rowFine && isset($rowFine['totalFine'])) {
            $totalFine = $rowFine['totalFine'];
        } else {
            $totalFine = 0;  // If no fine is calculated
        }
    } else {
        throw new Exception("Query failed: " . $conn->error);
    }

    // Execute the query to get the total books borrowed
    $resultBooksBorrowed = $conn->query($sqlBooksBorrowed);
    if ($resultBooksBorrowed) {
        $rowBooks = $resultBooksBorrowed->fetch_assoc();
        if ($rowBooks && isset($rowBooks['totalBooks'])) {
            $totalBooksBorrowed = $rowBooks['totalBooks'];
        } else {
            $totalBooksBorrowed = 0; // If no books borrowed
        }
    } else {
        throw new Exception("Query failed: " . $conn->error);
    }

    // Execute the query to get the total due books
    $resultDueBooks = $conn->query($sqlDueBooks);
    if ($resultDueBooks) {
        $rowDueBooks = $resultDueBooks->fetch_assoc();
        if ($rowDueBooks && isset($rowDueBooks['totalDueBooks'])) {
            $totalDueBooks = $rowDueBooks['totalDueBooks'];
        } else {
            $totalDueBooks = 0; // If no due books
        }
    } else {
        throw new Exception("Query failed: " . $conn->error);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $totalFine = "Error calculating fine"; // Fallback error message
    $totalBooksBorrowed = "Error fetching books"; // Fallback error message for books borrowed
    $totalDueBooks = "Error fetching due books"; // Fallback error message for due books
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
          <div style="background-color: #FFCC00; height: 65px; width: 70px;
          border-radius: 50px;">
          <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/tborrow.png" class="rounded-bottom" alt="borrowlogo">
          </div>
          <h6 class="card-title">Borrowed</h6>
          <h1 class="card-text"><?php echo $totalBooksBorrowed; ?></h1>
          </span>
      </span>
      <span class="card" style="border-radius: 15px;">
          <span class="shadow-sm p-3 card-body">
          <div style="background-color: #34C759; height: 65px; width: 70px;
          border-radius: 50px;">
          <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/treturn.png" class="rounded-bottom" alt="returnlog">
          </div>
          <h6 class="card-title">Not Returned</h6>
          <h1 class="card-text"><?php echo $totalBooksBorrowed; ?></h1>
          </span>
      </span>
      <span class="card" style="border-radius: 15px;">
          <span class="shadow-sm p-3 card-body">
          <div style="background-color: #F44336; height: 65px; width: 70px;
          border-radius: 50px;">
          <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/tbooks.png" class="rounded-bottom" alt="booklogo">
          </div>
          <h6 class="card-title">Due Books</h6>
          <h1 class="card-text"><?php echo $totalDueBooks; ?></h1> <!-- Display total due books -->
          </span>
      </span>   
      <span class="card" style="border-radius: 15px;">
          <span class="shadow-sm p-3 card-body">
          <div style="background-color: orange; height: 65px; width: 70px;
          border-radius: 50px;">
          <img style="size: 50px; margin-top: 11px; margin-left: 13px;" src="icons/receipt-item.png" class="rounded-bottom" alt="memberslogo">
          </div>
          <h6 class="card-title">Fine</h6>
          <h1 class="card-text"><?php echo $totalFine; ?> ₱</h1>
          </span>
      </span>
  </div>

  </body>
</html>
