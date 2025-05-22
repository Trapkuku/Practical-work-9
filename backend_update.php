<?php
require_once '_db.php';

class Result {}

$stmt = $db->prepare("UPDATE reservations SET start = :start, end = :end, room_id = :room, name = :name, status = :status, paid = :paid WHERE id = :id");
$stmt->bindParam(':start', $_POST['start']);
$stmt->bindParam(':end', $_POST['end']);
$stmt->bindParam(':room', $_POST['room']);
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':status', $_POST['status']);
$stmt->bindParam(':paid', $_POST['paid']);
$stmt->bindParam(':id', $_POST['id']);
$stmt->execute();

$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);

?>