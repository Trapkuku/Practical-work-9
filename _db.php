<?php
$host = "mysql.railway.internal";  // private network host для Railway
$port = 3306;
$username = "root";
$password = "aQmCyKNKWKAKEQNdUEkCjshVXbEoULgd"; // твой пароль из переменных Railway
$database = "railway";

$db = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
