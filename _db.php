<?php
$host = "shortline.proxy.rlwy.net"; // или другой host, который даст Railway
$port = 58433;
$username = "root";
$password = "OyDNrxEeeGyzZqmjEcdOtcTpXUvcVZrA";
$database = "railway";

try {
    $db = new PDO(
        "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
        $username,
        $password
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>