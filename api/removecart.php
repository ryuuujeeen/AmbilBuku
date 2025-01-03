<?php
require("connection.php");
if(isset($_GET['cartid'])){
    //return 
    //if returned value is 0 = not yet returned
    //if the returned value is 1 = already returned
    $cart_id = $_GET['cartid'];
    echo $cart_id;

    $queryremove = "DELETE FROM cart WHERE cart_id = '$cart_id'";
    mysqli_query($GLOBALS['dbconnect'],$queryremove);
    header("Location: viewcart.php");
    // echo $queryreturn;
}
?>