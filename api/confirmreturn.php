<?php
require("connection.php");
if(isset($_GET['TransactionID'])){
    //return 
    //if returned value is 0 = not yet returned
    //if the returned value is 1 = already returned
    $transactionid = $_GET['TransactionID'];
    $queryreturn = "UPDATE transaction_detail SET returned = 1, Return_date = NOW() WHERE Transaction_ID = '$transactionid'";
    mysqli_query($GLOBALS['dbconnect'],$queryreturn);
    header("Location: returnfacility.php");
    // echo $queryreturn;
}
?>