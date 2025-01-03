<?php
session_start(); 
require("connection.php");

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$defaultStart = "2024-10-01";
$defaultEnd = "2024-12-25";
$transactionCounts = [];

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? $defaultStart;
    $endDate = $_POST['end_date'] ?? $defaultEnd;

    //tabel
    // Query to get transaction counts grouped by month
    $query = "
        SELECT DATE_FORMAT(Borrow_date, '%Y-%m') AS month, COUNT(*) AS count
        FROM transaction_detail
        WHERE Borrow_date BETWEEN '$startDate' AND '$endDate'
        GROUP BY DATE_FORMAT(Borrow_date, '%Y-%m')
        ORDER BY DATE_FORMAT(Borrow_date, '%Y-%m');
    ";
    // echo $query;
    $result = mysqli_query($GLOBALS['dbconnect'],$query);

    while ($row = mysqli_fetch_assoc($result)) {
        $transactionCounts[$row['month']] = $row['count'];
    }

    //apriori
    $transQuery = "SELECT 
                    GROUP_CONCAT(t.ISBN ORDER BY t.ISBN) AS BookList,
                    td.Borrow_date
                FROM 
                    transaksi t
                JOIN 
                    transaction_detail td ON t.Transaction_ID = td.Transaction_ID
                WHERE 
                    td.Borrow_date BETWEEN '$startDate' AND '$endDate'
                GROUP BY 
                    t.Transaction_ID, td.Borrow_date;";
    $transResult = mysqli_query($GLOBALS['dbconnect'], $transQuery);
    // echo $transQuery;
    $transactions = [];
    while ($transRow = mysqli_fetch_assoc($transResult)) {
        $transactions[] = explode(',', $transRow['BookList']);
    }

    // Step 2: Save transactions to a JSON file
    $jsonFilePath = "transactions2.json";
    file_put_contents($jsonFilePath, json_encode($transactions));

    $pythonPath = "C:\\Users\\micha\\AppData\\Local\\Programs\\Python\\Python310\\python.exe"; // Adjust as needed
    $scriptPath = "C:\\xampp\\htdocs\\AmbilBuku\\api\\admin_apriori.py";
    $command = escapeshellcmd("$pythonPath $scriptPath");
    $output = shell_exec($command);

    if ($output === null) {
        die("Error running Apriori script.");
    }
    
    // Step 4: Decode the recommendations
    $rules = json_decode($output, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding rules: " . json_last_error_msg());
    }
    
    // Step 5: Update the rules table
    if (!empty($rules)) {
        // Clear existing rules
        $queryClearRules = "DELETE FROM assoc_rules";
        mysqli_query($GLOBALS['dbconnect'], $queryClearRules);
    
        // Insert new rules
        foreach ($rules as $rule) {
            // Since lhs and rhs are arrays, we extract the first element
            $book1 = isset($rule['lhs'][0]) ? (int)$rule['lhs'][0] : null; 
            $book2 = isset($rule['rhs'][0]) ? (int)$rule['rhs'][0] : null; 
    
            // Ensure both Book1 and Book2 are valid integers before inserting
            if ($book1 !== null && $book2 !== null) {
                $support = $rule['support'];
                $confidence = $rule['confidence'];
    
                // Use prepared statements to prevent SQL injection
                $stmt = mysqli_prepare(
                    $GLOBALS['dbconnect'],
                    "INSERT INTO assoc_rules (Book1, Book2, support, confidence) VALUES (?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "iidd", $book1, $book2, $support, $confidence);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
                // echo "Inserted rule: If $book1 then $book2 (Support: $support, Confidence: $confidence)<br>";
            } else {
                echo "Skipping rule due to invalid Book1 or Book2 values.<br>";
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
    <title>Borrow Report</title>
    <link rel="stylesheet" type="text/css" href="../CSS/borrowreport-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="navbar">
        <a href="bookingfacility.php" class="title"><h3>AMBILBUKU</h3></a>
            <ul class="nav-list">
                <li><a class="contact" href="bookingfacility.php">Booking Facility</a></li>
                <li><a class="contact" href="returnfacility.php">Return Facility</a></li>
                <li><a class="contact" href="bookmaster.php">Book Master</a></li>
                <li><a class="home-active" href="">Borrow Report</a></li>
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
        <form method="post" action="borrowreport.php">
            <label for="start_date">Date From:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $defaultStart ?>">
            <label for="end_date">Date To:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo $defaultEnd ?>">
            <button type="submit">Submit</button>
        </form>

        <div class="chart-container" style="width: 80%; margin: 20px auto;">
            <canvas id="transactionChart"></canvas>
        </div>

        <script>
            const transactionData = <?= json_encode($transactionCounts) ?>;
            const labels = Object.keys(transactionData);
            const data = Object.values(transactionData);

            const ctx = document.getElementById('transactionChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions Count',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <h3>Apriori Results</h3>
        <table class="table-1">
            <tr>
                <th width ="15%">Book1</th>
                <th width ="15%">Book2</th>
                <th width ="50%">Description</th>
            </tr>
            <?php
                $queryfetch ="SELECT 
                    bo.Judul_Buku AS Book1_Title,
                    bo1.Judul_Buku AS Book2_Title,
                    ar.support, 
                    ar.confidence
                FROM 
                    assoc_rules ar
                JOIN 
                    books bo ON ar.Book1 = bo.ISBN
                JOIN 
                    books bo1 ON ar.Book2 = bo1.ISBN
                ORDER BY ar.id";
                $resultfetch=mysqli_query($GLOBALS['dbconnect'],$queryfetch);
                if(mysqli_num_rows($resultfetch)>0){
                    while($row=mysqli_fetch_assoc($resultfetch)){
                        $confidenceper = $row['confidence']*100;
            ?>
                        <tr>
                            <td width ="15%"><?php echo $row['Book1_Title'];?></td>
                            <td width ="15%"><?php echo $row['Book2_Title'];?></td>
                            <td width ="50%"><?php echo 'Jika User meminjam buku <strong>'.$row['Book1_Title'].'</strong> maka persentase User meminjam buku <strong>'.$row['Book2_Title'].'</strong> adalah <strong>'.$confidenceper . '</strong>%'; ?></td>
                        </tr>
            <?php
                    }
                } else{
            ?>
                    <tr>
                        <td width ="15%">NO DATA FOUND</td>
                        <td width ="15%">NO DATA FOUND</td>
                        <td width ="50%">NO DATA FOUND</td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </div>
    
    <script src="../JS/bookingfacility.js"></script>
</body>
</html>
