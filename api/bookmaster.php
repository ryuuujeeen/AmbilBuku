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
        $queryfilter = "WHERE b.Judul_Buku LIKE '%$searchinputs%'";
        $valueinput = $searchinputs;
    }
    else{
        $queryfilter="";
        $valueinput = "";
    }
}

$user_id = $_SESSION['user_id'];
$querycheckrole = "SELECT * FROM users WHERE User_ID = $user_id";
$resultcheckrole = mysqli_query($GLOBALS['dbconnect'],$querycheckrole);
$rowcheckrole = mysqli_fetch_assoc($resultcheckrole);
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
    <?php
        if($rowcheckrole['ROLE'] == "ADMIN"){
    ?>
        <a href="bookingfacility.php" class="title"><h3>AMBILBUKU</h3></a>
        <ul class="nav-list">
            <li><a class="contact" href="bookingfacility.php">Booking Facility</a></li>
            <li><a class="contact" href="returnfacility.php">Return Facility</a></li>
            <li><a class="home-active" href="bookmaster.php">Book Master</a></li>
            <li><a class="contact" href="borrowreport.php">Borrow Report</a></li>
        </ul>
    </div>
    <?php
        }
            else{
                ?>
                    <a href="index.php" class="title"><h3>AMBILBUKU</h3></a>
                        <ul class="nav-list">
                            <li><a class="contact" href="index.php">Home</a></li>
                            <li><a class="contact" href="contactus.php">Contact Us</a></li>
                            <li><a class="contact" href="viewcart.php">View My Cart</a></li>
                            <li><a class="home-active" href="bookmaster.php">Book Master</a></li>
                        </ul>
                    </div>
                <?php
            }
         ?>
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
            <form action="bookmaster.php" method="POST">
                <p>Search Book:</p>
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
        <h3>Book Master</h3>
        <table class="table-1">
            <tr>
                <th>ISBN</th>
                <th>JUDUL BUKU</th>
                <th>Genre</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun Terbit</th>
                <th width="15%">Action</th>
            </tr>
            <?php
                //fetch data not yet approved
                $queryfetch ="SELECT * FROM books b 
                JOIN penulis pl on b.Author_ID = pl.Author_ID 
                JOIN penerbit pb on b.Penerbit_ID = pb.Penerbit_ID 
                JOIN genre g on b.Genre_ID = g.Genre_ID
                ".$queryfilter."
                ORDER BY b.ISBN";
                // echo $queryfetch;
                $resultfetch=mysqli_query($GLOBALS['dbconnect'],$queryfetch);
                if(mysqli_num_rows($resultfetch)>0){
                    while($row=mysqli_fetch_assoc($resultfetch)){
            ?>
                        <tr>
                            <td><?php echo $row['ISBN'];?></td>
                            <td><?php echo $row['Judul_Buku'];?></td>
                            <td><?php echo $row['Genre_Name'];?></td>
                            <td><?php echo $row['Author_Name'];?></td>
                            <td><?php echo $row['Penerbit_Name'];?></td>
                            <td><?php echo $row['Tanggal_Terbit'];?></td>
                            <td width="15%">
                                <a href="bookdetails.php?isbn=<?php echo $row['ISBN'];?>">
                                    <button id="yes-btn" type="button">View Book</button>
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
                    </tr>
            <?php
                }
            ?>
        </table>
       
    </div>
    
    <script src="../JS/bookingfacility.js"></script>
</body>
</html>
