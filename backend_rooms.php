<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require_once '_db.php';

class Room {
    public $id;
    public $name;
    public $capacity;
    public $status;
}

$capacity = isset($_GET['capacity']) ? intval($_GET['capacity']) : 0;

if ($capacity > 0) {
    $stmt = $db->prepare("SELECT * FROM rooms WHERE capacity = :capacity ORDER BY name");
    $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $db->prepare("SELECT * FROM rooms ORDER BY name");
    $stmt->execute();
}

$rooms = $stmt->fetchAll();

$result = array();

foreach($rooms as $room) {
    $r = new Room();
    $r->id = (int)$room['id'];
    $r->name = (string)$room['name'];
    $r->capacity = (int)$room['capacity'];
    $r->status = (string)$room['status'];
    $result[] = $r;
}

header('Content-Type: application/json');
echo json_encode($result);
?>
