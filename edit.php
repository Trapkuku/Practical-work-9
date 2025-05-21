<?php
require_once '_db.php';


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) { die("Invalid reservation id"); }


$stmt = $db->prepare("SELECT * FROM reservations WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);


$rooms = $db->query('SELECT * FROM rooms');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="CSS/style_edit_reservation.css">
</head>
<body>
<div class="modal">
    <form id="f" action="backend_update.php" method="post">
        <h1>Edit Reservation</h1>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>">

        <label>Start:</label>
        <input type="text" name="start" value="<?php echo htmlspecialchars($reservation['start']); ?>" />

        <label>End:</label>
        <input type="text" name="end_date" value="<?php echo htmlspecialchars($reservation['end_date']); ?>" />

        <label>Room:</label>
        <select name="room">
            <?php foreach ($rooms as $room) {
                $selected = $reservation['room_id'] == $room['id'] ? ' selected="selected"' : '';
                echo "<option value='{$room['id']}'$selected>{$room['name']}</option>";
            } ?>
        </select>

        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($reservation['name']); ?>" />

        <label>Status:</label>
        <select name="status" id="status">
            <?php
            $options = array("New", "Confirmed", "Arrived", "CheckedOut", "Expired");
            foreach ($options as $option) {
                $selected = $option == $reservation['status'] ? ' selected="selected"' : '';
                echo "<option value='$option'$selected>$option</option>";
            }
            ?>
        </select>

        <label>Paid:</label>
        <select name="paid" id="paid">
            <?php
            $options = array(0, 50, 100);
            foreach ($options as $option) {
                $selected = $option == $reservation['paid'] ? ' selected="selected"' : '';
                echo "<option value='$option'$selected>{$option}%</option>";
            }
            ?>
        </select>

        <div class="btn">
            <input type="submit" value="Save" />
            <a href="javascript:window.close();">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
