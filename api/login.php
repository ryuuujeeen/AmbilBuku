<?php
session_start(); // Start the session
require("connection.php"); 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //set login error false
    $loginerr = false;

    $userid = mysqli_real_escape_string($GLOBALS['dbconnect'], $_POST['userid']);
    $password = mysqli_real_escape_string($GLOBALS['dbconnect'], $_POST['password']);
    
    //check role
    $querycheckrole = "SELECT * FROM users WHERE User_ID = $userid";
    $resultcheckrole = mysqli_query($GLOBALS['dbconnect'],$querycheckrole);
    $rowcheckrole = mysqli_fetch_assoc($resultcheckrole);


    $query = "SELECT * FROM users WHERE User_ID = '$userid' AND User_Password = '$password'";
    // echo $query . "<br>"; 

    $queryrun = mysqli_query($GLOBALS['dbconnect'], $query);

    if (!$queryrun) {
        die("Query failed: " . mysqli_error($GLOBALS['dbconnect']));
    }

    $queryresult = mysqli_fetch_assoc($queryrun);

    if ($queryresult) {
        $_SESSION['user_id'] = $userid;
        // echo "User found!";
        if($rowcheckrole['ROLE']=="ADMIN" || $rowcheckrole['ROLE']=="STAFF" ){
            header("Location: bookingfacility.php");
            exit; 
        }
        else{
            header("Location: index.php");
            exit; 
        }
    } else {
        // echo "No user found.";
        $loginerr=true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Log In</title>
    <link rel="stylesheet" type="text/css" href="../CSS/loginsignin-styles.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="main-container-login">
        <div class="logo-img">
            <img src="../assets/lOGO.png" width="300px">
        </div>
        <div class="content">
            <form method="POST" action="">
                <div class="welcome-text">
                    <a style="font-weight: bold;">Welcome Back!</a> <br>
                    <a style="font-size: 13px;">Login to Continue</a> <br>
                </div>
                <div class="input-id-box">
                    <i class="bi bi-person"></i>
                    <input type="text" class="input-id" placeholder="Enter User ID" name="userid"> <br>
                </div>
                <div class="input-pw-box">
                    <i class="bi bi-lock"></i> <span class="bi bi-eye-slash pw-toggle-btn"></span>
                    <input type="password" class="input-pw" placeholder="Enter Password" name="password"> 
                    <br>     
                </div>
                <button class="btn">Login</button>
                <div class="signup-link">
                    <p>Don't have an account? <a href="signup.php" style="color: #F68B1F;">Sign Up</a></p>
                </div>
            </form>
            <div id="alert-box" class="alert-box">
                <p>Incorrect User ID or Password!</p>
                <button onclick="closeAlert()">Close</button>
            </div>
        </div>
    </div>
    <script src="../JS/loginsignup.js"></script>

    <!-- aler-box css in css file ~chen -->
    <!-- alert-box script -->
    <script>
        function closeAlert() {
            document.getElementById('alert-box').style.display = 'none';
        }

        <?php if ($loginerr): ?>
            document.getElementById('alert-box').style.display = 'block';
        <?php endif; ?>
    </script>
</body>
</html>