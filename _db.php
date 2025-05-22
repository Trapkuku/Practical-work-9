<?php
$host = "mysql.railway.internal";        // это внутренний адрес для Railway
$port = 3306;
$username = "root";
$password = "aQmCyKNKWKAKEQNdUEkCjshVXbEoULgd"; // возьми свой из VARIABLES -> MYSQLPASSWORD
$database = "railway";                   // или свое имя БД

try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
