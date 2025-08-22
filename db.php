<?php
$servername = "localhost";
$username = "root";    // default XAMPP
$password = "";        // default XAMPP
$dbname = "animal_care";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
