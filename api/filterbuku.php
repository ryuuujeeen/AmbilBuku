<?php
include("connection.php");

$queryfilter = " SELECT 
        tr.Transaction_ID,
        tr.User_ID,
        td.Borrow_date, 
        GROUP_CONCAT(b.Judul_Buku ORDER BY b.Judul_Buku SEPARATOR '; ') AS Judul_Buku_List
    FROM 
        transaksi tr
    JOIN 
        transaction_detail td ON tr.Transaction_ID = td.Transaction_ID
    JOIN 
        books b ON tr.ISBN = b.ISBN
    WHERE 
        MONTH(td.Borrow_date) = 11
    GROUP BY 
        tr.Transaction_ID
    ORDER BY 
        tr.Transaction_ID;
";

$queryfilterun = mysqli_query($GLOBALS['dbconnect'],$queryfilter);
echo "<table border='1'>";
echo "<tr>
<th>Transaction ID</th>
<th>Borrow_Date</th>
<th>Book Titles</th>
</tr>";

while ($row = mysqli_fetch_assoc($queryfilterun)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Transaction_ID']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Borrow_date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Judul_Buku_List']) . "</td>";
    echo "</tr>";
}

echo "</table>";


?>