<?php
session_start(); 
require("connection.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Facility</title>
    <link rel="stylesheet" type="text/css" href="../CSS/bookingfacility-styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="title"><h3>AMBILBUKU</h3></a>
            <ul class="nav-list">
            <li><a class="home" href="index.php">Home</a></li>
            <li><a class="contact" href="contactus.php">Contact Us</a></li>
            <li><a class="home-active" href="viewcart.php">View My Cart</a></li>
            <li><a class="contact" href="bookmaster.php">Book Master</a></li>
            </ul>
        </div>
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
    
    <div class="main-container">
        <h3>CART LIST</h3>
        <table class="table-1">
            <tr>
                <!-- <th>Transaction No.</th> -->
                <th>User ID</th>
                <th>Name</th>
                <th>Book ID</th>
                <th>Book Name</th>
                <!-- <th>Borrow Date</th> -->
                <!-- <th>Until Date</th> -->
                <th width="8%">Action</th>
            </tr>
            <?php
                //fetch data not yet approved
                $queryfetch ="SELECT c.User_ID, us.User_Name,c.ISBN,bo.Judul_Buku,c.cart_id FROM cart c 
                JOIN users us ON c.User_ID = us.User_ID
                JOIN books bo ON c.ISBN = bo.ISBN WHERE c.User_ID = $user_id
                ORDER BY c.ISBN";
                // echo $queryfetch;
                $resultfetch=mysqli_query($GLOBALS['dbconnect'],$queryfetch);
                if(mysqli_num_rows($resultfetch)>0){
                    while($row=mysqli_fetch_assoc($resultfetch)){
            ?>
                        <tr>
                            <!-- <td><?php echo $row['Transaction_ID'];?></td> -->
                            <td><?php echo $row['User_ID'];?></td>
                            <td><?php echo $row['User_Name'];?></td>
                            <td><?php echo $row['ISBN'];?></td>
                            <td><?php echo $row['Judul_Buku'];?></td>
                            <!-- <td><?php echo $row['Borrow_date'];?></td> -->
                            <!-- <td><?php echo $row['Until_date'];?></td> -->
                            <td width="8%">
                                <a href="removecart.php?cartid=<?php echo $row['cart_id'];?>">
                                    <button id="no-btn" type="button">Remove Book</button>
                                </a>
                            </td>
                        </tr>
            <?php
                    }
                } else{
            ?>
                    <tr>
                        <td>NO DATA FOUND</td>
                        <td>NO DATA FOUND</td>
                        <td>NO DATA FOUND</td>
                        <td>NO DATA FOUND</td>
                        <td>NO DATA FOUND</td>
                    </tr>
            <?php
                }
            ?>
        </table>
       
    </div>
    <a href="bookingprocess.php?userid=<?php echo $user_id?>">
        <button id="yes-btn" type="button">Book The Books</button>
    </a>
    <script src="../JS/contactus.js"></script>
</body>
</html>

