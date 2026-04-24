<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCC Library</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
.background {
    background-color: #f4f4f4;
    min-height: 100vh;
}

.position-absolute {
    position: absolute;
}

.libTitle {
    font-size: 1.5rem;
    font-weight: bold;
    color: #3F51B5;
    margin-left: 10px;
}

.logo {
    width: 50px;
    height: 50px;
}
</style>
<link rel="stylesheet" href="login.css">

<body>
    <div class="background"></div>
    <section>
        <span class="container-title">
            <img class="logo" src="icons/logo.png" alt="Library Logo">
            <span class="libTitle">Library Management</span>
            <div class="container-fluid p-2 d-flex justify-content-end">
                <button class="btn btn-primary position-absolute"
                    style="top: 10px; right: 10px; background-color: #3F51B5; border-color: #3F51B5; color: white;"
                    data-bs-toggle="modal" data-bs-target="#manualLoginModal">
                    Manual Login
                </button>
            </div>
        </span>
        <div class="containerMain">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div style="background: hsla(0, 0%, 100%, 0.55); backdrop-filter: blur(30px);"
                            class="card rounded-7 me-lg-n5">
                            <div class="card-header text-center">
                                <h3>Login</h3>
                            </div>
                            <div id="signIn" class="cardBod card-body">
                                <form action="#" method="post">
                                    <div class="form-floating mb-3 mt-3">
                                        <input style="opacity: 0 !important;" class="idTxtBox" type="number"
                                            name="studNumber" id="IDnum" placeholder="ID Number">
                                        <h3>Tap Your RFID</h3>
                                        <input style="opacity:0;">
                                    </div>
                                    <button style="display:none" type="submit" class="btn btn-primary w-100 rounded-7"
                                        name="signIn">Login</button>
                                </form>

                                <?php
                                    session_abort();
                                    session_start();

                                    if (isset($_POST['signIn'])) {
                                        include 'database.php';

                                        $rfidNum = $_POST['studNumber'];

                                        $stmt = $conn->prepare("SELECT * FROM users WHERE rfidnum = ?");
                                        $stmt->bind_param("s", $rfidNum);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();

                                            $_SESSION['loggedIn'] = true;
                                            $_SESSION['rfidnum'] = $row['rfidnum'];
                                            $_SESSION['username'] = $row['username'];
                                            $_SESSION['userType'] = $row['userType'];

                                            if ($_SESSION['userType'] == 'STUDENT') {
                                                header("Location: index.php");
                                                exit();
                                            } else {
                                                header("Location: homepage.php");
                                                exit();
                                            }
                                        } else {
                                            echo "
                                            <script>
                                                Swal.fire({
                                                    title: 'Account is not registered!',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK',
                                                    confirmButtonColor: '#3085d6'
                                                }).then(() => {
                                                    window.location.href = 'login.php'; 
                                                });
                                            </script>";
                                        }
                                    }
                                ?>

                            </div>
                            <div class="card-footer text-center" style="padding-top: 10%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Modal for Manual Login -->
<div class="modal fade" id="manualLoginModal" tabindex="-1" aria-labelledby="manualLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualLoginModalLabel">Manual Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add id to the form -->
                <form id="manualLoginForm" method="POST">
                    <div class="mb-3">
                        <label for="rfidnum" class="form-label">RFID Number</label>
                        <input type="text" class="form-control" id="rfidnum" name="rfidnum" placeholder="Enter RFID Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="manualLoginBtn" form="manualLoginForm" class="btn btn-primary" style="background-color: #3F51B5; border-color: #3F51B5; color: white;">Login</button>
            </div>

            <?php
            // Include database connection
            include 'database.php';

            if (isset($_POST['manualLoginBtn'])) {
                // Retrieve form data
                $rfidnum = $_POST['rfidnum'];
                $password = $_POST['password'];

                // Prepare SQL statement to check user
                $stmt = $conn->prepare("SELECT * FROM users WHERE rfidnum = ?");
                $stmt->bind_param("s", $rfidnum);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // User found, fetch data
                    $user = $result->fetch_assoc();

                    if ($password === $user['password']) {
                        // Set session variables
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['rfidnum'] = $user['rfidnum'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['userType'] = $user['userType'];

                        // Redirect based on user type
                        if ($_SESSION['userType'] == 'STUDENT') {
                            echo "<script>window.location.href = 'index.php';</script>";
                            exit();
                        } else {
                            echo "<script>window.location.href = 'homepage.php';</script>";
                            exit();
                        }
                    } else {
                        echo "<script>
                                Swal.fire({
                                    title: 'Invalid Password!',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = 'login.php';
                                });
                              </script>";
                        exit();
                    }
                } else {
                    echo "<script>
                            Swal.fire({
                                title: 'User not found!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'login.php';
                            });
                          </script>";
                    exit();
                }
            }
            ?>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<script>
$(document).ready(function() {
    let autoFocusEnabled = true;

    function enableAutoFocus() {
        if (autoFocusEnabled) {
            $('#IDnum').focus();

            $('body').mousemove(function() {
                $('#IDnum').focus();
            });

            $('body').click(function() {
                $('#IDnum').focus();
            });

            $('#IDnum').on('input', function() {
                if ($(this).val().length >= 8) {
                    $('form').submit();
                }
            });
        }
    }

    // Initially enable auto-focus behavior
    enableAutoFocus();

    // Disable focus behavior when modal opens
    $('#manualLoginModal').on('shown.bs.modal', function() {
        autoFocusEnabled = false;
        $('body').off('mousemove click'); // Unbind focus events
        $('#IDnum').off('input'); // Stop listening for input
    });

    // Re-enable focus behavior when modal closes
    $('#manualLoginModal').on('hidden.bs.modal', function() {
        autoFocusEnabled = true;
        enableAutoFocus(); // Rebind the focus behavior
    });
});
</script>