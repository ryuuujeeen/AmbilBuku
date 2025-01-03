<?php
session_start(); 
require("connection.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$querygetfullname="SELECT * FROM users WHERE User_ID = '$user_id'";
$queryfullnamerun = mysqli_query($GLOBALS['dbconnect'],$querygetfullname);
$row = mysqli_fetch_assoc($queryfullnamerun);
$fullname = $row['User_Name'];
// echo $fullname;

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $issuedesc = $_POST['issue-desc'];

    $queryinsertissue = "INSERT INTO contactus(User_ID,Fullname,Email,Issue_Desc) VALUES ('$user_id','$fullname','$email','$issuedesc')";
    // echo $queryinsertissue;
    mysqli_query($GLOBALS['dbconnect'],$queryinsertissue);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../CSS/contactus-styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="title"><h3>AMBILBUKU</h3></a>
            <ul class="nav-list">
                <li><a class="Home" href="index.php">Home</a></li>
                <li><a class="contact-active" href="contactus.php">Contact Us</a></li>
                <li><a class="contact" href="viewcart.php">View My Cart</a></li>
                <li><a class="contact" href="bookmaster.php">Book Master</a></li>
            </ul>
        </div>
        <img class="profile" src="../assets/blank-profile.png" onclick="openMenu();">
        
        <div class="user-dropdown-box">
            <div class="user-dropdown-menu" id="toggle-user-dropdown">
                <!-- <div class="user-dropdown-text-group">
                    <h3 class="user-dropdown-text1">[name]</h3>
                    <h4 class="user-dropdown-text2">[email]@gmail.com</h4>
                </div> -->
                <ul class="user-dropdown-list">
                    <li><i class="bi-person-circle"></i><a href="viewprofile.php">View Profile</a></li>
                    <li><i class="bi bi-door-open"></i><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>  
    </nav>
    
    <div class="leftside">
        <h3 class="contact-us-title-left">Contact Us</h3>
        <p class="contact-us-desc">
            Not sure what you need? <br>
            Our team at Library will be happy <br>
            to listen to you and help you find your <br>
            books at our Library.
            <br><br>
            <i class="bi bi-envelope"></i><span>library@gmail.com</span>
            <br>
            <i class="bi bi-telephone"></i><span>Support (+62)-XXX-XXXX-XXXX</span>
        </p>
    </div>
    
    <div class="rightside">
        <h3 class="contact-us-title">CONTACT US</h3>
        <form class="form-content" method="POST" action="contactus.php">
            <div class="form-desc">
                <a>We'd Love to Hear From You!</a>
                <br>
                <a>Let's Get in Touch By Filling Out This Form Below:</a>
                <br>
            </div>
            <label style="font-size: 14px;">Email</label> <br>
            <input class="input-email" placeholder="Type Your Email" type="text" name="email"><br>
            <label style="font-size: 14px;">Issue Description</label> <br>
            <textarea class="input-issue-desc" type="text" placeholder="Type Your Issue Description Here" name="issue-desc"></textarea> <br>

            <button class="submit-btn" name ="submit">Send Message</button>
        </form>
    </div>
    <script src="../JS/contactus.js"></script>
</body>
</html>