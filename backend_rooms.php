<?php
require_once '_db.php';

$stmt = $db->prepare("SELECT * FROM rooms");
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

class Room {}
$roomList = array();

foreach($rooms as $r) {
    $room = new Room();
    $room->id = $r['id'];
    $room->name = $r['name'];
    $room->capacity = $r['capacity'];
    $room->status = $r['status'];
    $roomList[] = $room;
}

header('Content-Type: application/json');
echo json_encode($roomList);
?>
