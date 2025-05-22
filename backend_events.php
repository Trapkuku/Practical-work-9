<?php
require_once '_db.php';

$stmt = $db->prepare("SELECT * FROM reservations");
$stmt->execute();
$result = $stmt->fetchAll();

$events = array();

foreach($result as $row) {
    $events[] = array(
        "id" => $row['id'],
        "text" => $row['name'],
        "start" => $row['start'],
        "end" => $row['end'],
        "resource" => $row['room_id'],
        "status" => $row['status'],
        "paid" => $row['paid'],
        "bubbleHtml" => "Reservation details:" . $row['name']
    );
}

header('Content-Type: application/json');
echo json_encode($events);
exit;
?>
