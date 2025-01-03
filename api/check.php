<?php
require("connection.php"); 

// Query to get all tables in the database
$sql = "SHOW TABLES FROM `defaultdb`"; 
$result = $GLOBALS['dbconnect']->query($sql);

if ($result->num_rows > 0) {
    // Loop through each table
    while ($row = $result->fetch_assoc()) {
        $tableName = $row["Tables_in_defaultdb"];
        echo "<h3>Table: $tableName</h3>";

        // Query to fetch data from the current table
        $tableQuery = "SELECT * FROM `$tableName`";
        // $tableQuery = "DROP TABLE transaksi";
        $tableResult = $GLOBALS['dbconnect']->query($tableQuery);

        if ($tableResult->num_rows > 0) {
            // Display column headers
            echo "<table border='1'><tr>";
            while ($field = $tableResult->fetch_field()) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "</tr>";

            // Display data rows
            while ($dataRow = $tableResult->fetch_assoc()) {
                echo "<tr>";
                foreach ($dataRow as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data found in table $tableName.<br>";
        }
    }
} else {
    echo "No tables found.";
}
?>
