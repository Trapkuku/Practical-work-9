<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HTML5 Бронювання кімнат в готелі (JavaScript/PHP/MySQL)</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://javascript.daypilot.org/demo/js/daypilot-all.min.js"></script>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<div class="main-frame">
    <header>
        <h1 id="logo">HTML5 Бронювання кімнат в готелі (JavaScript/PHP)</h1>
        <p id="claim">AJAX'овий Календар-застосунок з JavaScript/HTML5/jQuery</p>
    </header>

    <div class="toolbar">
        <span class="demo-label"></span>
        <label>Show rooms:
            <select id="filterRooms">
                <option value="all">All</option>
                <option value="1">1 bed</option>
                <option value="2">2 beds</option>
                <option value="4">4 beds</option>
            </select>
        </label>
        <label>Time range:
            <select id="timerange">
                <option value="month">Month</option>
                <option value="week">Week</option>
                <option value="day">Day</option>
            </select>
        </label>
        <label>
            <input type="checkbox" id="autoWidth"> Auto Cell Width
        </label>
    </div>
    <div class="scheduler-frame">
        <div id="dp"></div>
    </div>
    <footer>
        <address>(с)Автор лабораторної роботи: студент спеціальності "Економічна кібернетика", Балабанов Савелій Васильович</address>
    </footer>
</div>
<script src="js/app.js"></script>
</body>
</html>
