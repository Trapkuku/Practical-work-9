<?php
error_reporting(E_ALL & ~E_DEPRECATED); // или полностью отключить ошибки на проде

require_once '_db.php';

class Room {
    public int $id;
    public string $name;
    public int $capacity;
    public string $status;
}

$stmt = $db->prepare("SELECT * FROM rooms ORDER BY name");
$stmt->execute();
$rooms = $stmt->fetchAll();

$result = array();

foreach($rooms as $room) {
    $r = new Room();
    $r->id = $room['id'];
    $r->name = $room['name'];
    $r->capacity = $room['capacity'];
    $r->status = $room['status'];
    $result[] = $r;
}

header('Content-Type: application/json');
echo json_encode($result);
?>
