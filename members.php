<?php
include 'database.php';

if (isset($_POST['addMember'])) {
    // Capture form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $program = $_POST['program'];
    $rfidnum = $_POST['rfidnum'];  // Ensure rfidnum is passed in the form as well.
    $userType = $_POST['userType'];
    $yearAndSection = $_POST['yearAndSection'];

    // Prepare the SQL query to insert the new member into the database
    $sql = "INSERT INTO users (rfidnum, username, email, program, userType, yearAndSection) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $rfidnum, $username, $email, $program, $userType, $yearAndSection);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // SweetAlert message and redirect to the users list page
        echo "<script>
            Swal.fire({
                title: 'User added successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'homepage.php?page=members'; // Redirect to members list after success
            });
        </script>";
    } else {
        echo "<script>alert('Error adding member.');</script>";
    }
}



if (isset($_POST['editMember'])) {
    $id = $_POST['editId'];
    $rfidnum = $_POST['rfidnum'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $program = $_POST['program'];
    $userType = $_POST['userType'];
    $yearAndSection = $_POST['yearAndSection'];

    $sql = "UPDATE users SET rfidnum = ?, username = ?, email = ?, program = ?, userType = ?, yearAndSection = ? WHERE idusers = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $rfidnum, $username, $email, $program, $userType, $yearAndSection, $id);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'User edited successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'homepage.php?page=members'; // Redirect to members list after success
            });
        </script>";
    } else {
        echo "<script>alert('Error updating member.');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style media="screen">
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

    .addBtn {
        color: White;
        background-color: #303F9F;
        margin-left: 550px;
        width: 150px;
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

    /* Modal Styling */

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
        height: 600px;
        width: 570px;
    }

    .inner-modal h2 {
        margin: 0;
    }

    .inner-modal p {
        line-height: 24px;
        margin: 10px 0;
    }

    form {
        margin-top: 30px;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    /* Button styles */
    .btn-popup {
        color: white;
        background-color: #303F9F;
        margin: 10px;
        border-radius: 8px;
        height: 50px;
        width: 120px;
        border-color: white;
    }

    .form-label {
        text-align: left;
        display: block;
    }

    /* Button container for modal */
    .modal-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .btn-popup {
        color: white;
        background-color: #303F9F;
        border-radius: 8px;
        height: 50px;
        width: 120px;
        border-color: white;
        display: inline-block;
        text-align: center;
        padding: 0;
        font-size: 16px;
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

    .addBtn {
        color: White;
        background-color: #303F9F;
        margin-left: 550px;
        width: 150px;
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
    </style>
</head>

<body>
    <div class="container mt-3">
        <h2 style="color: #303F9F;">Members</h2>
        <div class="search-container mb-3">
            <div class="search-box">
                <input type="text" class="input form-control" placeholder="Search..." id="searchInput">
                <i class="bi bi-search"></i>
            </div>
            <!-- Add Members Button -->
            <button class="addBtn ms-auto" data-bs-toggle="modal" data-bs-target="#membersModal">
                <i class="bi bi-plus-circle-fill"></i> Add Member
            </button>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="color: white; background-color: #303F9F;" scope="col">RFID Number</th>
                    <th style="color: white; background-color: #303F9F;" scope="col">Username</th>
                    <th style="color: white; background-color: #303F9F;" scope="col">Email</th>
                    <th style="color: white; background-color: #303F9F;" scope="col">Program</th>
                    <th style="color: white; background-color: #303F9F;" scope="col">User Type</th>
                    <th style="color: white; background-color: #303F9F;" scope="col">Year & Section</th>
                    <th style="color: white; background-color: #303F9F;" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
    include 'database.php';
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['rfidnum'] . "</td>
                    <td>" . $row['username'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['program'] . "</td>
                    <td>" . $row['userType'] . "</td>
                    <td>" . $row['yearAndSection'] . "</td>
                    <td>
                        <button type='button' onclick='confirmDelete(" . $row['idusers'] . ")' class='bi-trash bi-trash3-fill'></button>
                        <button type='button' class='bi-pencil bi-pencil-square'
                                data-bs-toggle='modal'
                                data-bs-target='#editModal" . $row['idusers'] . "'>
                        </button>
                    </td>
                </tr>";

            // Modal for Editing Member
            echo "<div class='modal fade' id='editModal" . $row['idusers'] . "' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <form method='POST' action=''>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='editModalLabel'>Edit Member</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <input type='hidden' name='editId' value='" . $row['idusers'] . "'>
                                    <div class='row mb-3'>
                                        <div class='col-md-6'>
                                            <label for='rfidnum' class='form-label'>RFID Number</label>
                                            <input type='text' class='form-control' name='rfidnum' value='" . $row['rfidnum'] . "' required>
                                        </div>
                                        <div class='col-md-6'>
                                            <label for='username' class='form-label'>Username</label>
                                            <input type='text' class='form-control' name='username' value='" . $row['username'] . "' required>
                                        </div>
                                    </div>
                                    <div class='row mb-3'>
                                        <div class='col-md-6'>
                                            <label for='yearAndSection' class='form-label'>Year and Section</label>
                                            <input type='text' class='form-control' name='yearAndSection' value='" . $row['yearAndSection'] . "' required>
                                        </div>
                                        <div class='col-md-6'>
                                            <label for='email' class='form-label'>Email</label>
                                            <input type='email' class='form-control' name='email' value='" . $row['email'] . "' required>
                                        </div>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='program' class='form-label'>Program</label>
                                        <select class='form-select' name='program' required>
                                            <option value='College Of Business and Accountancy'" . ($row['program'] == "College Of Business and Accountancy" ? ' selected' : '') . ">College Of Business and Accountancy</option>
                                            <option value='College Of Criminal Justice Education'" . ($row['program'] == "College Of Criminal Justice Education" ? ' selected' : '') . ">College Of Criminal Justice Education</option>
                                            <option value='College Of Engineering'" . ($row['program'] == "College Of Engineering" ? ' selected' : '') . ">College Of Engineering</option>
                                            <option value='College Of Law'" . ($row['program'] == "College Of Law" ? ' selected' : '') . ">College Of Law</option>
                                            <option value='College Of Liberal Arts and Sciences'" . ($row['program'] == "College Of Liberal Arts and Sciences" ? ' selected' : '') . ">College Of Liberal Arts and Sciences</option>
                                        </select>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='userType' class='form-label'>User Type</label>
                                        <select class='form-select' name='userType' required>
                                            <option value='ADMIN'" . ($row['userType'] == "ADMIN" ? ' selected' : '') . ">ADMIN</option>
                                            <option value='LIBRARIAN'" . ($row['userType'] == "LIBRARIAN" ? ' selected' : '') . ">LIBRARIAN</option>
                                            <option value='STUDENT'" . ($row['userType'] == "STUDENT" ? ' selected' : '') . ">STUDENT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='submit' name='editMember' class='btn-popup'>Save Changes</button>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>No members found</td></tr>";
    }
?>
</tbody>

        </table>
    </div>
    <div class="modal fade" id="membersModal" tabindex="-1" aria-labelledby="membersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="membersModalLabel">Add New Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rfidnum" class="form-label">RFID Number</label>
                                <!-- Change to text type and add oninput event to validate input -->
                                <input type="text" class="form-control" id="rfidnum" name="rfidnum" required oninput="validateRFID()">
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="yearAndSection" class="form-label">Year and Section</label>
                                <input type="text" class="form-control" id="yearAndSection" name="yearAndSection" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-select" id="program" name="program" required>
                                <option value="College Of Business and Accountancy">College Of Business and Accountancy</option>
                                <option value="College Of Criminal Justice Education">College Of Criminal Justice Education</option>
                                <option value="College Of Engineering">College Of Engineering</option>
                                <option value="College Of Law">College Of Law</option>
                                <option value="College Of Liberal Arts and Sciences">College Of Liberal Arts and Sciences</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userType" class="form-label">User Type</label>
                            <select class="form-select" id="userType" name="userType" required>
                                <option value="ADMIN">ADMIN</option>
                                <option value="LIBRARIAN">LIBRARIAN</option>
                                <option value="STUDENT">STUDENT</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="addMember" class="btn-popup">Add Member</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <?php foreach ($result as $row): ?>
<div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="editId" value="<?php echo $row['id']; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="rfidnum" class="form-label">RFID Number</label>
                            <input type="text" class="form-control" name="rfidnum" value="<?php echo $row['rfidnum']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="yearAndSection" class="form-label">Year and Section</label>
                            <input type="text" class="form-control" name="yearAndSection" value="<?php echo $row['yearAndSection']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="program" class="form-label">Program</label>
                        <select class="form-select" name="program" required>
                            <option value="College Of Business and Accountancy" <?php echo $row['program'] == "College Of Business and Accountancy" ? 'selected' : ''; ?>>College Of Business and Accountancy</option>
                            <option value="College Of Criminal Justice Education" <?php echo $row['program'] == "College Of Criminal Justice Education" ? 'selected' : ''; ?>>College Of Criminal Justice Education</option>
                            <option value="College Of Engineering" <?php echo $row['program'] == "College Of Engineering" ? 'selected' : ''; ?>>College Of Engineering</option>
                            <option value="College Of Law" <?php echo $row['program'] == "College Of Law" ? 'selected' : ''; ?>>College Of Law</option>
                            <option value="College Of Liberal Arts and Sciences" <?php echo $row['program'] == "College Of Liberal Arts and Sciences" ? 'selected' : ''; ?>>College Of Liberal Arts and Sciences</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="userType" class="form-label">User Type</label>
                        <select class="form-select" name="userType" required>
                            <option value="ADMIN" <?php echo $row['userType'] == "ADMIN" ? 'selected' : ''; ?>>ADMIN</option>
                            <option value="LIBRARIAN" <?php echo $row['userType'] == "LIBRARIAN" ? 'selected' : ''; ?>>LIBRARIAN</option>
                            <option value="STUDENT" <?php echo $row['userType'] == "STUDENT" ? 'selected' : ''; ?>>STUDENT</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editMember" class="btn-popup">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function validateRFID() {
        var rfidnum = document.getElementById('rfidnum').value;
        // Remove any character that is not a number (0-9)
        rfidnum = rfidnum.replace(/[^0-9]/g, '');  // Replace non-numeric characters with an empty string
        document.getElementById('rfidnum').value = rfidnum; // Set the sanitized value back to the input field
    }
</script>
<script>
function confirmDelete(userId) {
    Swal.fire({
        title: 'Do you want to delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make an AJAX request to delete the user
            $.ajax({
                url: 'delete_user.php',  // PHP file to handle the user deletion
                type: 'POST',  // POST request
                data: { delete_id: userId },  // Send the userId to be deleted
                success: function(response) {
                    // After successful deletion, show success message and redirect
                    Swal.fire({
                        title: 'User deleted successfully!',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'homepage.php?page=members';  // Redirect to users list after success
                    });
                },
                error: function() {
                    Swal.fire('Error deleting user.');
                }
            });
        }
    });
}
</script>

<script>
    // Search functionality
    $(document).ready(function () {
        $('#searchInput').on('keyup', function () {
            var searchText = $(this).val().toLowerCase(); // Get search input and convert to lowercase
            $('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().includes(searchText));
            });
        });
    });
</script>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>