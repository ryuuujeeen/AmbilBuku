
<?php
// Aiven database connection details
$host = "mysql-3bf26fe9-ambilbuku.d.aivencloud.com";
$port = 18466;
$username = "avnadmin";
$password = "AVNS_tWs-qyzQz4ybm0KQEcP";
$database = "defaultdb";

// Path to the SQL file
$sqlFilePath = "C:\Users\micha\Downloads\suggestion.sql";

try {
    // Connect to Aiven database
    $conn = new mysqli($host, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Read the SQL file
    $sql = file_get_contents($sqlFilePath);
    if (!$sql) {
        throw new Exception("Failed to read SQL file: $sqlFilePath");
    }

    // Split SQL into individual statements
    $statements = explode(";", $sql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            // Execute each statement
            if (!$conn->query($statement)) {
                throw new Exception("Error executing query: " . $conn->error);
            }
        }
    }

    echo "Database imported successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the connection
    if (isset($conn) && $conn->ping()) {
        $conn->close();
    }
}
