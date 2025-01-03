<?php
// Database connection parameters
// $servername = "localhost";
// $username = "admin";
// $password = "PejuangSkripsi";
// $dbname = "ambilbukutest";

$servername = "mysql-3bf26fe9-ambilbuku.d.aivencloud.com";
$port = 18466;
$username = "avnadmin";
$password = "AVNS_tWs-qyzQz4ybm0KQEcP";
$dbname = "defaultdb";

// Create connection
// $GLOBALS['dbconnect'] = new mysqli($servername, $username, $password, $dbname);
$GLOBALS['dbconnect'] = new mysqli($servername, $username, $password, $dbname, $port);


// Check connection
if ($GLOBALS['dbconnect']->connect_error) {
    die("Connection failed: " . $GLOBALS['dbconnect']->connect_error);
}
else{
    // echo "Connected successfully!";

}
