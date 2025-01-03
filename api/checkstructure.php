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

        // Query to fetch the structure of the current table
        $structureQuery = "DESCRIBE `$tableName`";
        // $structureQuery = "DROP TABLE rekomendasi";
        $structureResult = $GLOBALS['dbconnect']->query($structureQuery);

        if ($structureResult->num_rows > 0) {
            // Display column headers for the table structure
            echo "<table border='1'><tr>";
            echo "<th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th>";
            echo "</tr>";

            // Display structure details
            while ($field = $structureResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($field['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Key']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Default']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Extra']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No structure found for table $tableName.<br>";
        }
    }
} else {
    echo "No tables found.";
}
?>
