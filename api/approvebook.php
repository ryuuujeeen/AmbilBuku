<?php
require("connection.php");
if(isset($_GET['TransactionID'])){
    //approve
    $transactionid = $_GET['TransactionID'];
    $queryapprove = "UPDATE transaction_detail SET approve = 1 WHERE Transaction_ID = '$transactionid'";
    mysqli_query($GLOBALS['dbconnect'],$queryapprove);
    header("Location: bookingfacility.php");
    // echo $queryapprove;
}
?>