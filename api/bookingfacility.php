<?php
session_start(); 
require("connection.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
$valueinput="";
$queryfilter="";
if(isset($_POST['search'])){
    $searchinputs = $_POST['search'];
    // echo $searchinput;
    if($searchinputs!=""){
        $queryfilter = " AND tr.Transaction_ID = '$searchinputs'";
        $valueinput = $searchinputs;
    }
    else{
        $queryfilter="";
        $valueinput = "";
    }
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
        <a href="bookingfacility.php" class="title"><h3>AMBILBUKU</h3></a>
            <ul class="nav-list">
                <li><a class="home-active" href="bookingfacility.php">Booking Facility</a></li>
                <li><a class="contact" href="returnfacility.php">Return Facility</a></li>
                <li><a class="contact" href="bookmaster.php">Book Master</a></li>
                <li><a class="contact" href="borrowreport.php">Borrow Report</a></li>
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
        <div class="row-group">
            <form action="bookingfacility.php" method="POST">
                <p>Select a Transaction No:</p>
                <?php
                if($valueinput!=""){
                ?>
                    <input type="text" name="search" id="search" value="<?php echo $valueinput;?>">
                <?php
                }
                else{
                ?>
                    <input type="text" name="search" id="search">
                <?php
                }
                ?>
            </form>
        </div>
        <h3>REQUEST LIST</h3>
        <table class="table-1">
            <tr>
                <th>Transaction No.</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Borrow Date</th>
                <th>Until Date</th>
                <th width="15%">Action</th>
            </tr>
            <?php
                //fetch data not yet approved
                $queryfetch ="SELECT tr.Transaction_ID, tr.User_ID, us.User_Name, tr.ISBN, bo.Judul_Buku, td.Borrow_date, td.Until_date, td.approve FROM transaksi tr
                JOIN transaction_detail td ON tr.Transaction_ID = td.Transaction_ID
                JOIN users us ON tr.User_ID = us.User_ID
                JOIN books bo ON tr.ISBN = bo.ISBN 
                WHERE td.approve = 0 ".$queryfilter."
                ORDER BY tr.Transaction_ID";
                $resultfetch=mysqli_query($GLOBALS['dbconnect'],$queryfetch);
                if(mysqli_num_rows($resultfetch)>0){
                    while($row=mysqli_fetch_assoc($resultfetch)){
            ?>
                        <tr>
                            <td><?php echo $row['Transaction_ID'];?></td>
                            <td><?php echo $row['User_ID'];?></td>
                            <td><?php echo $row['User_Name'];?></td>
                            <td><?php echo $row['ISBN'];?></td>
                            <td><?php echo $row['Judul_Buku'];?></td>
                            <td><?php echo $row['Borrow_date'];?></td>
                            <td><?php echo $row['Until_date'];?></td>
                            <td width="15%">
                                <!-- approve column in db default = 0 || approve = 1 || declined = 2 -->
                                <a href="approvebook.php?TransactionID=<?php echo $row['Transaction_ID'];?>">
                                    <button id="yes-btn" type="button">Approve</button>
                                </a>
                                <a href="declinebook.php?TransactionID=<?php echo $row['Transaction_ID'];?>">
                                    <button id="no-btn" type="button">Decline</button>
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
                        <td>NO DATA FOUND</td>
                        <td>NO DATA FOUND</td>
                        <td>NO DATA FOUND</td>
                    </tr>
            <?php
                }
            ?>
        </table>
       
    </div>
    
    <script src="../JS/bookingfacility.js"></script>
</body>
</html>
