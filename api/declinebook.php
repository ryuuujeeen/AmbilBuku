<?php
require("connection.php");
if(isset($_GET['TransactionID'])){
    //decline
    $transactionid = $_GET['TransactionID'];
    $querydecline = "UPDATE transaction_detail SET approve = 2 WHERE Transaction_ID = '$transactionid'";
    mysqli_query($GLOBALS['dbconnect'],$querydecline);
    header("Location: bookingfacility.php");
    // echo $querydecline;
}
?>