<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // User is not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

include 'database.php';

// Check if the penalty is due today for any user
$currentDate = date('Y-m-d'); // Get today's date

// Update the penalty details for users who have a penalty due today
$updatePenaltySql = "UPDATE users SET penalty = false, penaltynow = NULL, penaltydue = NULL 
                     WHERE penalty = true AND penaltydue = ?";
$updatePenaltyStmt = $conn->prepare($updatePenaltySql);
$updatePenaltyStmt->bind_param("s", $currentDate);
$updatePenaltyStmt->execute();
$updatePenaltyStmt->close();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>HOMEPAGE</title>
    <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style media="screen">
        .nav-link{
            font-family: default;
            font-weight: bold;
        }
        .nav-profSet{
            border-radius: 5px;
        }
        .nav-profSet:hover{
            background-color: white;
        }
        .nav-link:hover{
            color: #303F9F;
        }
    </style>
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img class="libraryLogo" src="icons/logo.png" alt="Library Logo"> 
            Library Management
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span><img class="profileLogo" src="icons/userp.png" alt=""></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-profSet">
                    <a class="nav-link" href="#"><i class="bi bi-person-circle"></i> Profile</a>
                </li>
                <!-- <li class="nav-profSet">
                    <a class="nav-link" href="#"><i class="bi bi-gear-fill"></i> Settings</a>
                </li> -->
                <li class="nav-profSet">
                    <a class="nav-link" href="index.php"><i class="bi bi-house-door-fill"></i> Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main content layout with Bootstrap grid -->
<span class="container">
    <span class="row">
        <!-- Left Sidebar for Menus -->
        <span class="sidebar">
            <ul class="list-group">
                <a href="homepage.php?page=dashboard" class="container-iconTitle" type="button">
                    <i class="icon bi bi-grid fa-lg"></i>
                    <span class="menuText">Dashboard</span>
                </a>
                <a href="homepage.php?page=dashboard_student" class="container-iconTitle" type="button">
                    <i class="icon bi bi-grid fa-lg"></i>
                    <span class="menuText">Dashboard</span>
                </a>
                <a href="homepage.php?page=books" class="container-iconTitle"  type="button">
                    <i class="icon bi bi-book fa-lg"></i>
                    <span class="menuText">Books</span>
                </a>
                <a href="homepage.php?page=booksborrowed" class="container-iconTitle"  type="button">
                    <i class="icon bi bi-book fa-lg"></i>
                    <span class="menuText">Books</span>
                </a>
                <a href="homepage.php?page=transactions" class="container-iconTitle" type="button">
                    <i class="icon bi bi-arrow-left-right fa-lg"></i>
                    <span class="menuText">Transactions</span>
                </a>
                <a href="homepage.php?page=members" class="container-iconTitle" type="button">
                    <i class="icon bi bi-people fa-lg"></i>
                    <span class="menuText">Members</span>
                </a>
                <a href="homepage.php?page=profile" class="container-iconTitle" type="button">
                    <i class="icon bi bi-person-circle fa-lg"></i>
                    <span class="menuText">Profile</span>
                </a>
                <!-- <a href="homepage.php?page=reports" class="container-iconTitle" type="button">
                    <i class="icon bi bi-flag fa-lg"></i>
                    <span class="menuText">Reports</span>
                </a> -->
                <a class="container-iconTitle" type="button" id="logoutButton" style="position: absolute; margin-top:500px; width:260px;" onclick="confirmLogout()">
                    <i class="icon bi bi-box-arrow-in-left fa-lg"></i>
                    <span class="menuText">Logout</span>
                </a>
            </ul>
        </span>

        <!-- Right Side for Main Dashboard -->
        <span class="main-content">

            <?php
            // Check if the user is logged in and if userType is set
            if (isset($_SESSION['userType'])) {
                $userType = $_SESSION['userType']; // Get the userType from session
            } else {
                $userType = 'guest'; // Default value in case userType is not set
            }

            // Check if a page is set in the URL
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                switch ($page) {
                    case 'dashboard':
                        if ($userType === 'STUDENT') {
                            include('dashboard_student.php'); // Include student dashboard
                        } else {
                            include('dashboard.php'); // Include default (admin/librarian) dashboard
                        }
                        break;
                    case 'dashboard_student':
                        if ($userType === 'STUDENT') {
                            include('dashboard_student.php'); // Include student dashboard
                        } else {
                            include('dashboard.php'); // Redirect to default dashboard if not student
                        }
                        break;
                    case 'books':
                        include('books.php'); // Include books content
                        break;
                    case 'booksborrowed':
                        include('booksborrowed.php'); // Include booksborrowed content
                        break;
                    case 'transactions':
                        include('transactions.php'); // Include transactions content
                        break;
                    case 'members':
                        include('members.php'); // Include members content
                        break;
                    case 'reports':
                        include('reports.php'); // Include reports content
                        break;
                    default:
                        // Redirect based on user type for the default page
                        if ($userType === 'STUDENT') {
                            include('dashboard_student.php'); // Default to student dashboard
                        } else {
                            include('dashboard.php'); // Default to admin/librarian dashboard
                        }
                        break;
                }
            } else {
                // No page specified, load the default page based on userType
                if ($userType === 'STUDENT') {
                    include('dashboard_student.php'); // Default to student dashboard
                } else {
                    include('dashboard.php'); // Default to admin/librarian dashboard
                }
            }
            ?>
        </span>
    </span>
</span>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>

function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log me out!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php'; // Redirect to logout.php
        }
    });
}
</script>

<script>
    document.querySelector('.nav-link[href="#"]').addEventListener('click', function (e) {
    e.preventDefault();
    
    // Check if the profile modal already exists
    const profileModal = document.getElementById('profileModal');
    if (!profileModal) return; // Ensure the modal is present in the DOM

    // Your existing AJAX call to populate the profile modal
    $.ajax({
        url: 'get_user_profile.php',
        type: 'GET',
        success: function (data) {
            const user = JSON.parse(data); // Parse the JSON response

            document.getElementById('profileUsername').textContent = user.username;
            document.getElementById('profileEmail').textContent = user.email || 'N/A';
            document.getElementById('profileProgram').textContent = user.program || 'N/A';
            document.getElementById('profileUserType').textContent = user.userType;

            const profileModalInstance = new bootstrap.Modal(profileModal);
            profileModalInstance.show();
        },
        error: function () {
            Swal.fire('Error fetching profile data.');
        }
    });
});

</script>

<script>

document.querySelector('a[href="homepage.php?page=profile"]').addEventListener('click', function (e) {
    e.preventDefault();  // Prevent the default link behavior

    // Check if the profile modal already exists
    const profileModal = document.getElementById('profileModal');
    if (!profileModal) return; // Ensure the modal is present in the DOM

    // Your existing AJAX call to populate the profile modal
    $.ajax({
        url: 'get_user_profile.php',
        type: 'GET',
        success: function (data) {
            const user = JSON.parse(data); // Parse the JSON response

            document.getElementById('profileUsername').textContent = user.username;
            document.getElementById('profileEmail').textContent = user.email || 'N/A';
            document.getElementById('profileProgram').textContent = user.program || 'N/A';
            document.getElementById('profileUserType').textContent = user.userType;

            // Show the profile modal using Bootstrap
            const profileModalInstance = new bootstrap.Modal(profileModal);
            profileModalInstance.show();
        },
        error: function () {
            Swal.fire('Error fetching profile data.');
        }
    });
});

</script>

<script>
    function manageMenuVisibility(userType) {
    // Convert userType to lowercase for case-insensitive comparison
    const normalizedUserType = userType.toLowerCase();
    
    if (normalizedUserType === 'student') {
        // Hide specific sidebar menu items for students
        const sidebarItemsToHide = document.querySelectorAll(
            '.sidebar a[href="homepage.php?page=dashboard"],' +
            '.sidebar a[href="homepage.php?page=transactions"],' +
            '.sidebar a[href="homepage.php?page=members"],' +
            '.sidebar a[href="homepage.php?page=reports"],' +
            '.sidebar a[href="homepage.php?page=books"]'  // Hide "Books" link for students
        );

        sidebarItemsToHide.forEach(item => {
            item.style.display = 'none';
        });

        // Optionally hide navigation links except "Home"
        const navLinksToHide = document.querySelectorAll('.nav-profSet a.nav-link:not([href="index.php"])');
        navLinksToHide.forEach(link => {
            link.style.display = 'none';
        });

    } else if (normalizedUserType === 'admin' || normalizedUserType === 'librarian') {
        // Hide specific sidebar menu items for admins and librarians
        const sidebarItemsToHide = document.querySelectorAll(
            '.sidebar a[href="homepage.php?page=profile"],' +
            '.sidebar a[href="homepage.php?page=dashboard_student"],' +
            '.sidebar a[href="homepage.php?page=booksborrowed"]' // Hide "Books Borrowed" link for admins and librarians
        );

        sidebarItemsToHide.forEach(item => {
            item.style.display = 'none';
        });

        // Optionally hide navigation links except "Home"
        const homeNavItem = document.querySelector('.nav-profSet a.nav-link[href="index.php"]');
        if (homeNavItem) {
            homeNavItem.parentElement.style.display = 'none'; // Hide the parent <li> element for admins/librarians
        }
    }
}


// Fetch user type on page load
document.addEventListener('DOMContentLoaded', function () {
    // Example AJAX call to get user type
    $.ajax({
        url: 'get_user_profile.php',
        type: 'GET',
        success: function (data) {
            const user = JSON.parse(data); // Parse the JSON response
            const userType = user.userType; // Get user type from response

            // Call the function to manage menu visibility based on user type
            manageMenuVisibility(userType);
        },
        error: function () {
            console.error('Error fetching user type.');
        }
    });
});
</script>

<!-- Profile Modal -->
<div class="modal" id="profileModal" tabindex="0" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <i class="bi bi-person-circle" style="font-size: 2rem; margin-right: 10px;"></i>
                <h5 class="modal-title">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Username:</strong> <span id="profileUsername"></span></p>
                <p><strong>Email:</strong> <span id="profileEmail"></span></p>
                <p><strong>Program:</strong> <span id="profileProgram"></span></p>
                <p><strong>User Type:</strong> <span id="profileUserType"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
