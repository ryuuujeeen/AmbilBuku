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

// Fetch the ISBN from the URL
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Fetch book details along with the user ID (assuming the user is logged in)
    $query = "SELECT * FROM books b
    JOIN penulis pn ON b.Author_ID = pn.Author_ID
    JOIN genre g ON b.Genre_ID = g.Genre_ID
    JOIN penerbit pg ON b.Penerbit_ID = pg.Penerbit_ID
     WHERE ISBN = '$isbn'";
    //  echo $query;
    $result = mysqli_query($GLOBALS['dbconnect'], $query);
    $row = mysqli_fetch_assoc($result);

    // Step 1: Fetch transactions from the database
    $transQuery = "SELECT 
                        GROUP_CONCAT(t.ISBN ORDER BY t.ISBN) AS BookList,
                        td.Borrow_date
                    FROM 
                        transaksi t
                    JOIN 
                        transaction_detail td ON t.Transaction_ID = td.Transaction_ID
                    WHERE 
                        MONTH(td.Borrow_date) = 11
                    GROUP BY 
                        t.Transaction_ID, td.Borrow_date;";
    $transResult = mysqli_query($GLOBALS['dbconnect'], $transQuery);
    // echo $transQuery ."<BR>";
    $transactions = [];
    while ($transRow = mysqli_fetch_assoc($transResult)) {
        $transactions[] = explode(',', $transRow['BookList']);
    }

    // Step 2: Save transactions to a JSON file
    $jsonFilePath = "transactions.json";
    file_put_contents($jsonFilePath, json_encode($transactions));

    // Step 3: Get the input ISBN from the request
    if (!isset($_GET['isbn'])) {
        die("No ISBN provided.");
    }
    $input_isbn = trim($_GET['isbn']);
    
    // Step 4: Run the Python script
    //change this based on ur device please
    //chen
    $pythonPath = "C:\\Users\\micha\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";  // Python path need to be change based on the device python location -chen

    
    $scriptPath = "C:\\xampp\\htdocs\\AmbilBuku\\api\\apriori_recommendation.py";  // Python script path
    $command = escapeshellcmd("$pythonPath $scriptPath " . escapeshellarg($input_isbn));
    $output = shell_exec($command);
    // echo $command . "<br>";  // For debugging

    if ($output === null) {
        die("Error running Apriori script.");
    }
    // echo "Output:<br>";
    // echo "<pre>$output</pre>";

    // Step 5: Decode the recommendations
    $recommendations = json_decode($output, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding recommendations: " . json_last_error_msg());
    }

    //check tabel rekomendasi
    $querycheckrekomen="SELECT * FROM rekomendasi";
    $querycheckrekomenrun=mysqli_query($GLOBALS['dbconnect'],$querycheckrekomen);
    $rowrekomen=mysqli_num_rows($querycheckrekomenrun);
    if($rowrekomen){
        $queryresetrekomen = "DELETE FROM rekomendasi WHERE types = 1";
        mysqli_query($GLOBALS['dbconnect'],$queryresetrekomen);
        // echo $queryresetrekomen;
        //store ke tabel rekomendasi
        foreach ($recommendations as $recommendedIsbn) {
            $queryrekomen = "INSERT INTO rekomendasi (ISBN,Types) VALUES($recommendedIsbn,1)";
            // echo $queryrekomen."<BR>";
            mysqli_query($GLOBALS['dbconnect'],$queryrekomen);
        }
    }
    else{
         //store ke tabel rekomendasi
        foreach ($recommendations as $recommendedIsbn) {
            $queryrekomen = "INSERT INTO rekomendasi (ISBN,Types) VALUES($recommendedIsbn,1)";
            // echo $queryrekomen."<BR>";
            mysqli_query($GLOBALS['dbconnect'],$queryrekomen);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" type="text/css" href="../CSS/bookdetails-styles.css">
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
    <div class="details-container">
        <img src="../assets/sorcerers-stone-us-childrens-edition.jpg" class="book-det-img">
        
        <div class="bookdesc-container">
            <h1 class="book-title"><?php echo $row["Judul_Buku"];?></h1>
            <p class="author"><?php echo $row["Author_Name"];?></p>
        
            <p class="desc">
            <?php echo $row["Sinopsis"];?>
            </p>

            <?php
            if($row["Stock"]>0){
            ?>
                <h4 class="status">Available</span></h4>
            
            <?php
            }
            else{
            ?>
                <h4 class="status1">No Stock Available For Borrow</span></h4>
            <?php
            }
            ?>

            <table class="book-details">
                <tr>
                    <th>Genre(s)</th>
                    <td><?php echo $row["Genre_Name"];?></td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td><?php echo $row["ISBN"];?></td>
                </tr>
                <tr>
                    <th>Publisher</th>
                    <td><?php echo $row["Penerbit_Name"];?></td>
                </tr>
                <tr>
                    <th>Publication Date</th>
                    <td><?php echo date('d/m/Y', strtotime($row["Tanggal_Terbit"])); ?></td>
                </tr>
                <tr>
                    <td>
                        <a href="addcart.php?ISBN=<?php echo $row["ISBN"];?>&userid=<?php echo $user_id?>">
                        <?php
                        if($rowcheckrole['ROLE'] == "ADMIN"){
                        ?>
                        <?php
                        }else{
                        ?>
                            <button class="borrow-btn" type="button">Add To Cart</button>
                        <?php
                        }
                        ?>
                            
                        </a>
                    </td>
                </tr>
            </table>
            <br>
        </div>

        

        <hr>

        <br>

        <h4 id="suggested-title">Suggested for You because you like <?php echo $row["Judul_Buku"];?> </h4>
        <div class="suggested-box">
            <?php
            $queryrekomendasi="SELECT * FROM rekomendasi r JOIN books b ON r.ISBN = b.ISBN JOIN penulis pn ON b.Author_ID = pn.Author_ID WHERE r.Types = 1";
            $queryrekomendasirun = mysqli_query($GLOBALS['dbconnect'],$queryrekomendasi);
            if(mysqli_num_rows($queryrekomendasirun)>0){
                $count = 1;
                while($rowrekomendasi = mysqli_fetch_assoc($queryrekomendasirun)){
            ?>
                    <div class="book-container<?php echo $count; ?>">
                        <a href="bookdetails.php?isbn=<?php echo $rowrekomendasi['ISBN']; ?>"><img src="../assets/sorcerers-stone-us-childrens-edition.jpg" class="bookA"></a>
                        <div class="book-box">
                            <p id="booktitle1"><?php echo $rowrekomendasi['Judul_Buku']; ?></p>
                            <p id="author1"><?php echo $rowrekomendasi['Author_Name']; ?></p>
                            <p id="rate1"><?php echo $rowrekomendasi['Rating']; ?><span><i class="bi bi-star-fill"></i></span</p>
                        </div>
                    </div>
            <?php
                $count++;
                }
            }
            else{
                echo "Sorry no Recommendation Available!!";
            }
            ?>
            
        </div>
    </div>
    <script src="../JS/bookdetails.js"></script>
</body>

</html>