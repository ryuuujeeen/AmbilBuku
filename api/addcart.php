<?php
include("connection.php");
if(isset($_GET['ISBN'])){
    $isbn = $_GET['ISBN'];
    $user_id = $_GET['userid'];
    //check lastest transactionID
    $checklastID = "SELECT * FROM transaksi ORDER BY Transaction_ID DESC LIMIT 1";
    $result = mysqli_query($GLOBALS['dbconnect'],$checklastID);
    $row = mysqli_fetch_assoc($result);

    $lastid = $row['Transaction_ID'];
    $numericPart = (int)substr($lastid, 2);
    $numericPart++;
    $newTransactionID = "TR" . str_pad($numericPart, 3, '0', STR_PAD_LEFT);
    // echo $newTransactionID;
    
    //INSERT INTO cart
    $queryinserttr = "INSERT INTO cart (User_ID,ISBN) VALUES('$user_id','$isbn')";
    mysqli_query($GLOBALS['dbconnect'],$queryinserttr);
    // if (mysqli_query($GLOBALS['dbconnect'], $queryinserttr)) {
    //     echo "Record inserted successfully";
    // } else {
    //     echo "Error: " . mysqli_error($GLOBALS['dbconnect']);
    // }
    // echo $queryinserttr."<BR>"; 

    header("Location: viewcart.php?isbn=$isbn");
}
?>