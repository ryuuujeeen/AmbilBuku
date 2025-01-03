<?php
include("connection.php");
if(isset($_GET['userid'])){
    $user_id = $_GET['userid'];
    //check lastest transactionID
    $checklastID = "SELECT * 
                    FROM transaksi 
                    ORDER BY CAST(SUBSTRING(Transaction_ID, 3) AS UNSIGNED) DESC 
                    LIMIT 1;
                    ";
    $result = mysqli_query($GLOBALS['dbconnect'],$checklastID);
    $row = mysqli_fetch_assoc($result);

    $lastid = $row['Transaction_ID'];
    $numericPart = (int)substr($lastid, 2);
    $numericPart++;
    $newTransactionID = "TR" . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

    // echo $newTransactionID . "<br>";

    

    //FETCH ALL DATA FROM CART
    $fetchcart = "SELECT * FROM cart WHERE User_ID = '$user_id'";
    $resultfetch = mysqli_query($GLOBALS['dbconnect'],$fetchcart);
    if(mysqli_num_rows($resultfetch)>0){
        while($row=mysqli_fetch_assoc($resultfetch)){
            $isbnn = $row['ISBN'];
            echo $isbnn;
            //INSERT INTO transaksi 
            $queryinserttr = "INSERT INTO transaksi (Transaction_ID,User_ID,ISBN) VALUES('$newTransactionID','$user_id','$isbnn')";
            mysqli_query($GLOBALS['dbconnect'],$queryinserttr);
            // echo $queryinserttr."<BR>"; 
        }
           //INSERT INTO transaction_detail
            $queryinserttd = "INSERT INTO transaction_detail(Transaction_ID,Borrow_Date,Until_Date) VALUES ('$newTransactionID',NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY))";
            mysqli_query($GLOBALS['dbconnect'],$queryinserttd);
            // echo $queryinserttd;
    }
    
    
 

    //remove the temp data from cart
    $removetemp = "DELETE FROM cart WHERE User_ID = $user_id";
    mysqli_query($GLOBALS['dbconnect'],$removetemp);

    header("Location: index.php");
}
?>