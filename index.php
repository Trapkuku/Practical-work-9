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
<style>
    .scheduler_default_rowheader_inner {
        border-right: 1px solid #ccc;
    }
    .scheduler_default_rowheader.scheduler_default_rowheadercol2 {
        background: #fff;
    }
    .scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
        top: 2px;
        bottom: 2px;
        left: 2px;
        background-color: transparent;
        border-left: 5px solid #1a9d13; /* green по умолчанию */
        border-right: 0px none;
    }
    .status_dirty.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
        border-left: 5px solid #ea3624; /* red */
    }
    .status_cleanup.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
        border-left: 5px solid #f9ba25; /* orange */
    }
    .status_ready.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner {
        border-left: 5px solid #1a9d13; /* green */
    }


</style>
<div class="main-frame">
    <header>
        <h1 id="logo">HTML5 Бронювання кімнат в готелі (JavaScript/PHP)</h1>
        <p id="claim">AJAX'овий Календар-застосунок з JavaScript/HTML5/jQuery</p>
    </header>

    <div class="toolbar">
        <span class="demo-label"></span>
        <label>Show rooms:
            <select id="filterRooms">
                <option value="all" selected>All</option>
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
