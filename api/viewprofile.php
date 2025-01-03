<?php
session_start(); 
require("connection.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$querycheckrole = "SELECT * FROM users WHERE User_ID = $user_id";
$resultcheckrole = mysqli_query($GLOBALS['dbconnect'],$querycheckrole);
$rowcheckrole = mysqli_fetch_assoc($resultcheckrole);

if(isset($_POST['editsavebtn'])){
    $newpassword = $_POST['input_pw'];

    $queryupdate = "UPDATE users SET User_Password = '$newpassword' WHERE User_ID = '$user_id'";
    mysqli_query($GLOBALS['dbconnect'],$queryupdate);
    header("Location: viewprofile.php");
    // echo $queryupdate;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" type="text/css" href="../CSS/viewprofile-styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar">
        <?php
            if($rowcheckrole['ROLE'] == "ADMIN"){
                ?>
                <a href="bookingfacility.php" class="title"><h3>AMBILBUKU</h3></a>
                    <ul class="nav-list">
                        <li><a class="home" href="bookingfacility.php">Booking Facility</a></li>
                        <li><a class="contact" href="returnfacility.php">Return Facility</a></li>
                        <li><a class="contact" href="bookmaster.php">Book Master</a></li>
                        <li><a class="contact" href="borrowreport.php">Borrow Report</a></li>
                    </ul>
                </div>
                <?php
            }
            else{
                ?>
                    <a href="index.php" class="title"><h3>AMBILBUKU</h3></a>
                        <ul class="nav-list">
                            <li><a class="home-active" href="index.php">Home</a></li>
                            <li><a class="contact" href="contactus.php">Contact Us</a></li>
                            <li><a class="contact" href="viewcart.php">View My Cart</a></li>
                            <li><a class="contact" href="bookmaster.php">Book Master</a></li>
                        </ul>
                    </div>
                <?php
            }
         ?>
        
        <img class="profile" src="../assets/defaultprofilepics.png" onclick="openMenu();">
        
        <div class="user-dropdown-box">
            <div class="user-dropdown-menu" id="toggle-user-dropdown">
                <ul class="user-dropdown-list">
                    <li><i class="bi-person-circle"></i><a href="viewprofile.php">View Profile</a></li>
                    <li><i class="bi bi-door-open"></i><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>  
    </nav>

    <h1>PROFILE INFORMATION</h1>
        
    <div class="profile-box">   
        <form class="profile-content" method="POST" action="viewprofile.php">   
            <label>User ID</label> <br>
            <input type="text" class="input-id" value="<?php echo $rowcheckrole["User_ID"];?>" disabled> <br><br>   
            
            <label>Full Name</label> <br>
            <input type="text" class="input-name" value="<?php echo $rowcheckrole["User_Name"];?> " disabled> <br><br>
            
            <label>Password</label> <br>
            <span class="bi bi-eye-slash pw-toggle-btn"></span>
            <input type="password" name="input_pw" class="input-pw" value="<?php echo $rowcheckrole["User_Password"];?>" readonly disabled> <br><br>

            <label>Role</label> <br>
            <input type="text" value="<?php echo $rowcheckrole["ROLE"];?>" disabled> <br>

            <button class="editsavebtn" name="editsavebtn" type="button" onclick="changeLabelButton()">Edit Profile</button>
        </form>
    </div>
    

    <script src="../JS/viewprofile.js"></script>
</body>
</html>