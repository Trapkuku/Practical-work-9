<?php
require_once '_db.php';

// Обработка отправки формы (POST-запрос)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $capacity = intval($_POST['capacity']);
    $status = $_POST['status'];

    $stmt = $db->prepare("INSERT INTO rooms (name, capacity, status) VALUES (:name, :capacity, :status)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    // Відповідь для AJAX
    $response = [
        "result" => "OK",
        "message" => "Room added"
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Додати кімнату</title>
    <link rel="stylesheet" href="CSS/style_new_rooms.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="modal">
    <form id="roomForm" method="post">
        <h1>Додати кімнату</h1>
        <label>Назва:</label>
        <input type="text" name="name" required>

        <label>Місткість:</label>
        <select name="capacity">
            <option value="1">Single (1)</option>
            <option value="2">Double (2)</option>
            <option value="4">Family (4)</option>
        </select>

        <label>Статус:</label>
        <select name="status">
            <option value="Ready">Ready</option>
            <option value="Dirty">Dirty</option>
            <option value="Cleanup">Cleanup</option>
        </select>

        <div class="btn">
            <input type="submit" value="Зберегти">
            <button type="button" id="cancelBtn">Скасувати</button>
        </div>
    </form>
</div>
<script>
    function closeModal() {
        if (parent && parent.DayPilot && parent.DayPilot.Modal) {
            parent.DayPilot.Modal.close({result: "OK"});
        } else if (parent && parent.DayPilot && parent.DayPilot.ModalStatic) {
            parent.DayPilot.ModalStatic.close();
        } else {
            window.close();
        }
    }

    $("#cancelBtn").on("click", function () {
        closeModal();
    });

    $("#roomForm").on("submit", function(e){
        e.preventDefault();
        $.post("room_new.php", $(this).serialize(), function(data){
            if (data && data.result === "OK") {
                closeModal();
            } else {
                alert(data && data.message ? data.message : "Error!");
            }
        }, "json");
    });
</script>
</body>
</html>
