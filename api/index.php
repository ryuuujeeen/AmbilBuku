<?php
session_start(); 

require_once "connection.php";


if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header(header: "Location: /api/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="title"><h3>AMBILBUKU</h3></a>
            <ul class="nav-list">
                <li><a class="home-active" href="index.php">Home</a></li>
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

    <div class="search-bar-box">
        <i class="bi bi-search"></i>
        <form action="searchresult.php" method="POST">
            <input type="text" class="search-bar" placeholder="Search Your Books" name="search-input">
            <!-- <button type="submit">Search</button> -->
        </form>
    </div>

    <div class="main-container">
        <div class="leftside-box">
            <div class="trending-box">
                <h3>Trending Books</h3>
                <div class="trending-book-grid-box">
                    <?php
                    
                    $queryrandom = "SELECT 
                                        tr.isbn, 
                                        COUNT(tr.isbn) AS isbn_count,
                                        b.Judul_Buku,
                                        p.Author_Name
                                    FROM 
                                        transaksi tr
                                    JOIN books b ON tr.ISBN = b.ISBN
                                    JOIN penulis p ON b.Author_ID = p.Author_ID
                                    GROUP BY 
                                        tr.isbn  
                                    ORDER BY `isbn_count`  DESC
                                    LIMIT 6;";

                    $queryrandomrun = mysqli_query($GLOBALS['dbconnect'],$queryrandom);

                    if(mysqli_num_rows($queryrandomrun)>0){
                        $count=1;

                        while($rowrand = mysqli_fetch_assoc($queryrandomrun)){
                    ?>
                            <a href="bookdetails.php?isbn=<?php echo $rowrand["isbn"]?>" class="block<?php echo $count;?>">
                            <p class="trendbooktitle<?php echo $count;?>"><?php echo $rowrand["Judul_Buku"];?></p>
                            <p class="trendauthor<?php echo $count;?>"><?php echo $rowrand["Author_Name"];?></p>
                    </a>
                    <?php
                            $count++;
                        }
                    }
                    ?>
                </div>
            </div>
            <br>
            <div class="suggestions-box">
                <h3>Suggestions</h3>
                <div class="book-row-box">
                <?php
                    
                    $querysuggestion = "SELECT * FROM rekomendasi WHERE Types = 1 ORDER BY RAND() LIMIT 4";
                    $querysuggestionrun = mysqli_query($GLOBALS['dbconnect'],$querysuggestion);

                    if(mysqli_num_rows($querysuggestionrun)>0){
                        $count=1;

                        while($rowsgs = mysqli_fetch_assoc($querysuggestionrun)){
                    ?>
                    <a href="bookdetails.php?isbn=<?php echo $rowsgs["ISBN"]?>"><img src="../assets/data-mining-book.jpg" class="book-img<?php echo $count;?>"></a>
                    <?php
                            $count++;
                        }
                    }
                    else{
                        $querysuggestrandom = "SELECT * FROM books ORDER BY RAND() LIMIT 4";
                        $querysuggestrandomrun = mysqli_query($GLOBALS['dbconnect'],$querysuggestrandom);
                        $count=1;
                        while($rowsgs=mysqli_fetch_assoc($querysuggestrandomrun)){
                    ?>
                        <a href="bookdetails.php?isbn=<?php echo $rowsgs["ISBN"]?>"><img src="../assets/data-mining-book.jpg" class="book-img<?php echo $count;?>"></a>
                    <?php
                            $count++;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        
        <div class="rightside-box">
            <div class="banner-box">
                <p id="welcome-text">WELCOME TO AMBILBUKU!</p>
            <p id="welcome-text-desc">Meet you favorite book and meet our recommended books based on your favorite book!</p>
            <div class="banner-box-inside">
                <h2 id="ad-title">BOOK SEARCH</h2>
                <p id="ad-desc">Variety of Books<br>
                For Variety of Readers
                </p>
                <div class="img-demo"></div>
                <div class="ad-link-box">
                    <p id="ad-link">ambilbuku.com</p>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script>
        let openMenuAct = document.getElementById("toggle-user-dropdown");

        function openMenu()
        {
            openMenuAct.classList.toggle("open");
        }

        const searchInput = document.querySelector(".search-bar");

    </script>
</body>
</html>
