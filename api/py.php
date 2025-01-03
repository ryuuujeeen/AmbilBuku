<?php
$pythonPath = "C:\\Users\\micha\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";  // Correct Python path
$scriptPath = "C:\\xampp\\htdocs\\AmbilBuku\\apriori_recommendation.py";

// Escape shell arguments to prevent issues with special characters
$command = escapeshellcmd("$pythonPath $scriptPath 2>&1");
$output = shell_exec($command);

// Check for errors
if ($output === null) {
    echo "Error: Could not execute the script.";
} else {
    echo "<pre>$output</pre>";
}
?>
