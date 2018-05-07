<?php
$servername ="localhost";
$username = "coinschedule_site";
$password = "43Pds\"sJLj+,JCKR";
$dbname = "coinschedule_site";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>