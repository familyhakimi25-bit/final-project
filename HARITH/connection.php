<?php
// connection.php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'dbbarber';
$port = 3307; // sama macam yang kau guna dekat login & date-time

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
