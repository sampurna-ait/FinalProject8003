
<?php
function getDbConnection() {
    $servername = "localhost"; // Database host
    $username = "root";        // Database username
    $password = "";            // Database password
    $dbname = "MarvelVerse";   // Database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>