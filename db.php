<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // leave empty if default XAMPP
$db   = 'laundry_db';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
  die('Connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>
