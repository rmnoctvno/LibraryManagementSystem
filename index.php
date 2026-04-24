<?php
session_start();

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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>UCC Library</title>
  <style media="screen">
/* Reset and body styling */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  font-family: Arial, sans-serif;
  scroll-behavior: smooth;
}

/* Navbar styling */
.navbar {
  background-color: #303F9F;
  padding: 10px 20px;
}

.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-logo {
  display: flex;
  align-items: center;
  color: #ffffff;
  text-decoration: none;
  font-size: 18px;
  font-weight: bold;
}

.nav-logo-image {
  width: 30px;
  height: 30px;
  margin-right: 10px; 
}

.nav-menu {
  list-style: none;
  display: flex;
}

.nav-menu li {
  margin-left: 30px;
}

.nav-link {
  color: #ffffff;
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
  padding: 8px;
  border-radius: 6px;
}

.nav-link:hover {
  color: #dddddd;
}

.navbar .nav-link:hover {
  background-color: White;
  color: #303F9F;
}

/* Header and button styling */
.div-header {
  padding-left: 30px;
  font-family: Arial, sans-serif;
}

.btnLogin {
  margin-top: 17px;
  margin-left: 40px;
  height: 40px;
  width: 100px;
  background-color: #303F9F;
  color: White;
  border-radius: 10px;
  border: none;
  font-size: 15px;
  cursor: pointer;
}

.btnCatalog {
  margin-top: 17px;
  margin-left: 10px;
  height: 40px;
  width: 140px;
  background-color: #303F9F;
  color: White;
  border-radius: 10px;
  border: none;
  font-size: 15px;
  cursor: pointer;
}

/* Heading and paragraph styling */
.h1hd {
  padding-right: 40px;
  padding-left: 40px;
  padding-bottom: 10px;
  font-size: 70px;
}

.phd {
  color: #424243;
  padding-left: 40px;
}

.phead {
  color: #3F51B5;
}

/* Landing page image styling */
.landPagepic { 
  height: 450px;
  width: 655px;
  float: right;
}

/* Section styling */
section {
  margin-top: 30px;
}

#home {
  padding-top: 30px;
  padding-bottom: 20px;
  background-color: white;
  height: 500px;
}

#catalog{
  padding-top: 30px;
  background-color: white;
  height: 500px;
}

#about {
  background-color: #white;
  height: 500px;
}

#contact {
  background-color: #303F9F;
  height: 200px;
}

/* #mybooks {
  background-color: #d9dee1;
  height: 500px;
} */

/* Additional section styling */

.about-container{
  height: 450px;

/* Rfid Section */
}
.container-rfid {
  max-width: 1200px;
  margin: auto;
  padding: 20px;
  text-align: center;
}
.h1-rfid {
  text-align: center;
  font-size: 2rem;
  margin-bottom: 30px;
  color: #333;
}
.steps {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
}
.step {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  width: 300px;
  text-align: left;
}
.step img {
  width: 80px;
  margin-bottom: 20px;
}
.step h2 {
  font-size: 20px;
  color: #333;
  margin-bottom: 20px;
}
.step p {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.5;
}

/* About Section */
.container {
  display: flex;
  justify-content: center;
  min-height: 50vh;
  text-align: left;
}
.illustration {
  margin-right: 40px;
}
.illustration img {
  max-width: 500px;
  height: 500px;
}
.text {
  max-width: 500px;
}
.text h1 {
  font-size: 24px;
  margin-top: 100px;
  margin-bottom: 30px;
}
.text p {
  font-size: 16px;
  line-height: 1.5;
}
 
/* footer section */

.footer {
  color: white;
  padding: 20px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.footer .section {
  flex: 1;
}
.footer .logo {
  display: flex;
  align-items: center;
  gap: 15px; /* Spacing between the logo and text */
}
.footer .logo img {
  margin-bottom: 100px;
  max-width: 50px;
  height: auto;
}
.logo img{
margin-left: 10px;
}
.footer .section h4 {
  margin-top: 20px;
  margin-bottom: 70px;
  font-size: 18px;
  font-weight: bold;
}
.footer .section p {
margin: 5px 0;
font-size: 14px;
}
.section p {
margin-top: 10px; 
font-size: 14px;
}
.footer .subscribe input[type="email"] {
  padding: 8px;
  border-radius: 20px;
  border: none;
  margin-right: 10px;
}
.footer .subscribe button {
  padding: 8px 12px;
  background-color: #556bd6;
  color: white;
  border: none;
  border-radius: 20px;
  cursor: pointer;
}
.footer .subscribe button:hover {
  background-color: #445ac1;
}

</style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
  <div class="nav-container">
    <a class="nav-logo">
      <img src="icons/logo.png" alt="Logo" class="nav-logo-image">
      Library Management
    </a>
    <ul class="nav-menu">
      <li><a href="#home" class="nav-link"><i class="bi bi-house-door-fill"></i> Home</a></li>
      <li><a href="catalog.php" class="nav-link"><i class="bi bi-journal-album"></i> Catalog</a></li>
      <li><a href="#about" class="nav-link"><i class="bi bi-info-circle-fill"></i> About</a></li>
      <li><a href="#contact" class="nav-link"><i class="bi bi-telephone-inbound-fill"></i> Contact Us</a></li>
      <li><a href="homepage.php" class="nav-link"><i class="bi bi-book-fill"></i> My Books</a></li>
    </ul>
  </div>
</nav>

<!-- Home Section -->
<section id="home">
  <div class="div-header">
    <img class="landPagepic" src="icons/herobook.png" alt="landpage logo">
    <h1 class="h1hd"><a class="phead">Discover, borrow,<br> and manage</a> your<br>favorite books <br>effortlessly.</h1>
    <p class="phd" id="phd">Already have an RFID card?</p>
    <!-- The Login button -->
    <button class="btnLogin" id="btnLogin"><i class="bi bi-box-arrow-in-right"></i> Login</button>
  </div>
</section>

<!-- About Section -->
<section id="card">
<h1 class="h1-rfid">How to Get an RFID Card</h1>
  <div class="container-rfid">
      <div class="steps">
        <div class="step">
          <img src="icons/rfid3pic.png" alt="Register Icon">
          <h2>Register for a Library Account</h2>
          <p>Visit the library in person. Complete the registration form with your personal details and provide a valid ID for verification.</p>
        </div>
        <div class="step">
          <img src="icons/rfid2pic.png" alt="Collect Icon">
          <h2>Collect Your RFID Card</h2>
          <p>Visit the library's membership desk during operating hours to collect your card. Bring your ID and the confirmation email for verification.</p>
        </div>
        <div class="step">
          <img src="icons/rfid1pic.png" alt="Login Icon">
          <h2>Use Your RFID Card</h2>
          <p>Use your RFID card at online login portals. Tap the card on the reader to log in automatically.</p>
        </div>
      </div>
    </div>
</section>

<!-- about section -->
<section id="about">
<div class="container">
  <div class="illustration">
      <img src="icons/herobook1.png" alt="Library Illustration">
  </div>
  <div class="text">
      <h1>About</h1>
      <p>
          Our library has been dedicated to enriching the lives of our members by providing 
          a diverse collection of books, digital resources, and educational programs. 
          We strive to be a center of learning, discovery, and creativity for people of all ages.
      </p>
  </div>
</div>
</section>

<!-- Contact Us Section -->
<section id="contact">
<div class="footer">
      <div class="section logo">
          <img src="icons/logo.png" alt="Library Logo">
          <div>
              <h4>Library Management</h4>
              <p>Copyright © 2019 Bootstrapdash</p>
              <p>All rights reserved.</p>
          </div>
      </div>
      <div class="section">
          <h4><i class="bi bi-telephone-inbound-fill"></i> Contact Us</h4>
          <p>librarymanagement@gmail.com</p>
          <p>09876543321</p>
      </div>
      <div class="section subscribe">
          <h4>Stay up to date</h4>
          <form>
              <input type="email" placeholder="Your email address" required>
              <button type="submit">Submit</button>
          </form>
      </div>
  </div>
</section>

<script>
  // Fetch user profile data asynchronously
  fetch('get_user_profile.php')
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        console.error(data.error);
      } else {
        var userType = data.userType; // Get userType from the response

        // Hide the login button for students
        if (userType === "STUDENT") {
          document.getElementById('btnLogin').style.display = 'none';
          document.getElementById('phd').style.display = 'none';
        }
        
        // You can use the userType variable elsewhere in your script if needed
        console.log("User Type: " + userType);
      }
    })
    .catch(error => {
      console.error('Error fetching user profile:', error);
    });

  // Login button functionality
  document.getElementById('btnLogin').onclick = function() {
    window.location.href = 'login.php'; // Redirect to login page
  };
</script>

</body>
</html>
