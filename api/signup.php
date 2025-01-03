<?php
require("connection.php");

if(isset($_POST['signup'])){
    $user_id = $_POST['user_id'];
    $user_name = $_POST['full_name'];
    $password = $_POST['password'];
    $passwordconf = $_POST['passwordconf'];

    //userid must be a integer
    if (!filter_var($user_id, FILTER_VALIDATE_INT)) {
        echo "<script>alert('User ID must be an integer!'); window.location.href = 'signup.php';</script>";
        exit;
    }
    else{
        // Validate passwords match
        if ($password !== $passwordconf) {
            echo "<script>alert('Passwords do not match!'); window.location.href = 'signup.php';</script>";
            exit;
        }
        else{
            //query insert
            $queryinsert = "INSERT INTO users(User_ID,User_Name,User_Password)VALUES('$user_id','$user_name','$password');";
            // echo $queryinsert;
            if(mysqli_query($GLOBALS['dbconnect'],$queryinsert)){
                echo "<script>
                        alert('Sign Up Berhasil!');
                        window.location.href = 'login.php';
                    </script>";
                exit;
            }else{
                echo "<script>
                        alert('Error: Unable to register user. Please try again.');
                        window.location.href = 'signup.php';
                    </script>";
                exit;
            }
            
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="CSS/loginsignin-styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="main-container-signup">
        <div class="logo-img">
            <img src="assets/lOGO.png" width="300px">
        </div>
        <div class="content">
            <form action="signup.php" method="POST">
                <div class="welcome-text">
                    <a style="font-weight: bold;">Welcome!</a> <br>
                    <a style="font-size: 13px;">Sign Up to Begin</a> <br>
                </div>
                <div class="input-id-box">
                    <i class="bi bi-person"></i>
                    <input class="input-id" placeholder="Enter User ID" name="user_id" required> <br>
                </div>
                <div class="input-name-box">
                    <i class="bi bi-person"></i>
                    <input class="input-name" placeholder="Enter Full name" name="full_name" required> <br>
                </div>
                <div class="input-pw-box">
                    <i class="bi bi-lock"></i> <span class="bi bi-eye-slash pw-toggle-btn"></span>
                    <input class="input-pw" placeholder="Enter Password" type="password" name="password" required> 
                    <br>     
                </div>
                <div class="input-confirm-pw-box">
                    <i class="bi bi-lock"></i> <span class="bi bi-eye-slash pw-toggle-btn"></span>
                    <input class="input-confirm-pw" placeholder="Confirm Password" type="password" name="passwordconf" required> 
                    <br>     
                </div>
                <button class="btn" name ="signup">Sign Up</button>
                <div class="signup-link">
                    <p>Already have an account? <a href="login.php" style="color: #F68B1F;">Log In</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="JS/loginsignup.js"></script>
</body>
</html>