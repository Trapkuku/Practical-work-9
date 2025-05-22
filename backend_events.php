<?php
require_once '_db.php';

$stmt = $db->prepare("SELECT * FROM reservations");
$stmt->execute();
$result = $stmt->fetchAll();

class Event {}
$events = array();

foreach($result as $row) {
    if (empty($row['name']) || empty($row['start']) || empty($row['end']) || empty($row['room_id'])) {
        continue;
    }
    $e = new Event();
    $e->id = $row['id'];
    $e->text = $row['name'];
    $e->start = $row['start'];
    $e->end = $row['end'];
    $e->resource = $row['room_id'];
    $e->status = $row['status'];
    $e->paid = $row['paid'];
    $e->bubbleHtml = "Reservation details:<br/>".$e->text;
    $events[] = $e;
}

header('Content-Type: application/json');
echo json_encode($events);
?>
