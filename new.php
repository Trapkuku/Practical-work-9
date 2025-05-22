<?php
require_once '_db.php';

$rooms = $db->query('SELECT * FROM rooms');

$start = isset($_GET['start']) ? $_GET['start'] : '';
$end = isset($_GET['end']) ? $_GET['end'] : '';
$resource = isset($_GET['resource']) ? $_GET['resource'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Reservation</title>
    <link rel="stylesheet" href="CSS/style_new_reservation.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="modal">
    <form id="f" action="backend_create.php" method="post">
        <h1>New Reservation</h1>

        <label>Name:</label>
        <input type="text" name="name" value="" required />

        <label>Start:</label>
        <input type="text" name="start" value="<?php echo htmlspecialchars($start); ?>" required />

        <label>End:</label>
        <input type="text" name="end" value="<?php echo htmlspecialchars($end); ?>" required />

        <label>Room:</label>
        <select name="room">
            <?php foreach ($rooms as $room) {
                $selected = $resource == $room['id'] ? ' selected="selected"' : '';
                echo "<option value='{$room['id']}'$selected>{$room['name']}</option>";
            } ?>
        </select>

        <div class="btn">
            <input type="submit" value="Save" />
            <a href="#" onclick="cancelModal();return false;">Cancel</a>
        </div>


    </form>

</div>
<script>
    function cancelModal() {
        if (parent && parent.DayPilot && parent.DayPilot.Modal) {
        parent.DayPilot.Modal.close();
        } else {
        window.close();
        }
    }

    $("#f").on("submit", function(e){
        e.preventDefault();
        $.post("backend_create.php", $(this).serialize(), function(data){
            if (parent && parent.DayPilot && parent.DayPilot.Modal) {
                parent.DayPilot.Modal.close({result: "OK"});
            } else {
                window.close();
            }
        });
    });
</script>
</body>
</html>
