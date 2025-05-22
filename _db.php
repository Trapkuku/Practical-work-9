<?php
// Railway connection parameters (проверь их в интерфейсе Railway!)
$host = "mysql.railway.internal";   // или host из Railway
$port = 3306;
$username = "root";
$password = "aQmCyKNKWKAKEQNdUEkCjshVXbEoULgd"; // возьми пароль из Railway MYSQLPASSWORD
$database = "railway";             // имя базы тоже возьми из Railway MYSQLDATABASE

try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Выведет ошибку на экран (в продакшене — лучше писать в лог)
    die("Database connection failed: " . $e->getMessage());
}
?>
