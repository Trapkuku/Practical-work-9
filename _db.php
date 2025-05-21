<?php
$host = "shortline.proxy.rlwy.net";
$port = 58433;
$username = "root";
$password = "OyDNrxEeeGyzZqmjEcdOtcTpXUvcVZrA";
$database = "railway";

try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
