<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// XAMPP MySQL connection settings
$host = "127.0.0.1";
$dbuser = "root";      // Default XAMPP username
$dbpass = "";          // Default XAMPP password (blank)
$database = "aduk8_db"; // Your database name

// Attempt connection
try {
    $conn = mysqli_connect($host, $dbuser, $dbpass, $database);
    
} catch (Exception $e) {
    die("<h3>❌ Error connecting to MySQL!</h3><p>" . $e->getMessage() . "</p>");
}
?>