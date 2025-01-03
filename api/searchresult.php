<?php
session_start(); 
require("connection.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search-input"])) {

    $searchinput = $_POST["search-input"];
    $search_query = mysqli_real_escape_string($GLOBALS['dbconnect'], $searchinput);
    // echo $searchinput."<BR>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link rel="stylesheet" type="text/css" href="../CSS/searchresult-styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="title"><h3>AMBILBUKU</h3></a>
            <ul class="nav-list">
                <li><a class="home" href="index.php">Home</a></li>
                <li><a class="contact" href="contactus.php">Contact Us</a></li>
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
    
    <div class="page-header">
        <div class="search-bar-box">
            <i class="bi bi-search"></i>
            <form action="searchresult.php" method="POST">
                <input type="text" class="search-bar" placeholder="Search Your Books" name="search-input">
                <!-- <button type="submit">Search</button> -->
            </form>
        </div>
        <p class="page-title">SEARCH RESULT</p>
    </div>
    <div class="main-container">
        <?php
            $querysearch = "SELECT b.Judul_Buku as judul, p.Author_Name as author, b.ISBN as isbn, b.rating as rating FROM books b
             JOIN penulis p ON b.Author_ID = p.Author_ID
             WHERE b.Judul_Buku LIKE '%$searchinput%'";
            
            // echo $querysearch."<BR>";
            $querysearchrun = mysqli_query($GLOBALS['dbconnect'],$querysearch);
            if(mysqli_num_rows($querysearchrun)>0){
                while($rowsearch = mysqli_fetch_assoc($querysearchrun)){
        ?>
                <div class="book-container1">
                    <a href="bookdetails.php?isbn=<?php echo $rowsearch['isbn'];?>"><img src="../assets/sorcerers-stone-us-childrens-edition.jpg" class="booking1"></a>
                    <div class="book-box">
                        <p id="booktitle1"><?php echo $rowsearch['judul'];?></p>
                        <p id="author1"><?php echo $rowsearch['author'];?></p>
                        <p id="rate1"><?php echo $rowsearch['rating'];?><span><i class="bi bi-star-fill"></i></span</p>
                    </div>
                </div>
        <?php
                }
            }
            else{
        ?>
                <div class="book-container1">
        <?php
                    echo "Maaf buku ".$searchinput." Tidak Ditemukan dalam Master Buku";
                    echo "</div>";
            }
        ?>
    </div>
    <script src="../JS/searchresult.js"></script>
</body>
</html>