<?php
$host = "mysql.railway.internal"; 
$port = 3306;
$username = "root";
$password = "aQmCyKNKWKAKEQNdUEkCjshVXbEoULgd";
$database = "railway";

$db = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password, [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
]);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $db->exec("USE `$database`"); 
?>
