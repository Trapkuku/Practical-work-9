$(function () {
    const dp = new DayPilot.Scheduler("dp", {
        cellWidth: 100,
        cellWidthSpec: "Fixed",
        heightSpec: "Max",
        rowHeaderWidth: 160,
        rowHeaderColumns: [
            { title: "Room", display: "name", width: 80 },
            { title: "Capacity", display: "capacity", width: 80 },
            { title: "Status", display: "status", width: 80 }
        ],
        scale: "Day",
        startDate: DayPilot.Date.today().firstDayOfMonth(),
        days: DayPilot.Date.today().daysInMonth(),
        timeHeaders: [
            { groupBy: "Month", format: "MMMM yyyy" },
            { groupBy: "Day", format: "d" }
        ],
        allowEventOverlap: false,
        timeRangeSelectedHandling: "Enabled",
        eventClickHandling: "Enabled",
        eventDeleteHandling: "Update", // Обов'язково!
        eventMoveHandling: "Update",
        eventResizeHandling: "Update",

        onBeforeRowHeaderRender: function(args) {
            args.row.columns[1].html = `${args.row.data.capacity} bed${args.row.data.capacity > 1 ? "s" : ""}`;
            args.row.columns[2].html = args.row.data.status;

            args.row.cssClass = "";
            if (args.row.data.status === "Dirty") {
                args.row.cssClass = "status_dirty";
            } else if (args.row.data.status === "Cleanup") {
                args.row.cssClass = "status_cleanup";
            } else if (args.row.data.status === "Ready") {
                args.row.cssClass = "status_ready";
            }
        },

        onBeforeEventRender: function(args) {
            var start = new DayPilot.Date(args.data.start);
            var end = new DayPilot.Date(args.data.end);

            var today = DayPilot.Date.today();
            var now = new DayPilot.Date();

            args.data.html = args.data.text + " (" + start.toString("M/d/yyyy") + " - " + end.toString("M/d/yyyy") + ")";

            switch (args.data.status) {
                case "New":
                    var in2days = today.addDays(1);
                    if (start < in2days) {
                        args.data.barColor = 'red';
                        args.data.toolTip = 'Застаріле (не підтверджено вчасно)';
                    } else {
                        args.data.barColor = 'orange';
                        args.data.toolTip = 'Новий';
                    }
                    break;
                case "Confirmed":
                    var arrivalDeadline = today.addHours(18);
                    if (start < today || (start.getDatePart() === today.getDatePart() && now > arrivalDeadline)) {
                        args.data.barColor = "#f41616";  // red
                        args.data.toolTip = 'Пізнє прибуття';
                    } else {
                        args.data.barColor = "green";
                        args.data.toolTip = "Підтверджено";
                    }
                    break;
                case 'Arrived':
                    var checkoutDeadline = today.addHours(10);
                    if (end < today || (end.getDatePart() === today.getDatePart() && now > checkoutDeadline)) {
                        args.data.barColor = "#f41616";  // red
                        args.data.toolTip = "Пізній виїзд";
                    } else {
                        args.data.barColor = "#1691f4";  // blue
                        args.data.toolTip = "Прибув";
                    }
                    break;
                case 'CheckedOut':
                    args.data.barColor = "gray";
                    args.data.toolTip = "Перевірено";
                    break;
                default:
                    args.data.toolTip = "Невизначений стан";
                    break;
            }

            args.data.html = args.data.html + "<br /><span style='color:gray'>" + args.data.toolTip + "</span>";

            var paid = args.data.paid || 0;
            var paidColor = "#aaaaaa";
            args.data.areas = [
                { bottom: 10, right: 4, html: "<div style='color:" + paidColor + "; font-size: 8pt;'>Paid: " + paid + "%</div>", v: "Visible"},
                { left: 4, bottom: 8, right: 4, height: 2, html: "<div style='background-color:" + paidColor + "; height: 100%; width:" + paid + "%'></div>", v: "Visible" }
            ];
        },

        onEventMoved: function(args) {
            $.post("backend_move.php", {
                id: args.e.data.id,
                newStart: args.newStart.toString(),
                newEnd: args.newEnd.toString(),
                newResource: args.newResource
            }, function(data) {
                if (data.result === "OK") {
                    dp.message(data.message);
                    loadEvents();
                } else {
                    dp.message(data.message, {type: "error"});
                    loadEvents();
                }
            }, "json");
        },

        onEventResized: function(args) {
            $.post("backend_update.php", {
                id: args.e.data.id,
                start: args.newStart.toString(),
                end: args.newEnd.toString(),
                room: args.e.data.resource,
                name: args.e.data.text,
                status: args.e.data.status,
                paid: args.e.data.paid
            }, loadEvents);
        },

        onEventDeleted: function(args) {
            $.post("backend_delete.php",
                { id: args.e.data.id },
                function(data) {
                    if (data.result === "OK") {
                        dp.message(data.message);
                        loadEvents();
                    } else {
                        dp.message("Delete failed!", {type: "error"});
                        loadEvents();
                    }
                },
                "json"
            );
        },

        onTimeRangeSelected: function (args) {
            var modal = new DayPilot.Modal();
            modal.closed = function() {
                dp.clearSelection();
                var data = this.result;
                if (data && data.result === "OK") {
                    loadEvents();
                }
            };
            modal.showUrl("new.php?start=" + args.start + "&end=" + args.end + "&resource=" + args.resource);
        },

        onEventClick: function(args) {
            var id = args.e.data.id;
            var modal = new DayPilot.Modal();
            modal.showUrl("edit.php?id=" + id);
            modal.closed = function() {
                if (this.result) {
                    loadEvents();
                }
            };
        }
    });

    dp.init();

    function loadResources() {
        const capacity = $("#filterRooms").val();
        dp.rows.load("backend_rooms.php?capacity=" + encodeURIComponent(capacity), function() {
            loadEvents();
        });
    }

    function loadEvents() {
        var start = dp.visibleStart();
        var end = dp.visibleEnd();

        $.post("backend_events.php",
            {
                start: start ? start.toString() : "",
                end: end ? end.toString() : ""
            },
            function(data) {
                dp.events.list = data;
                dp.update();
            }
        );
    }

    $("#filterRooms").on("change", function () {
        loadResources();
    });

    $("#timerange").on("change", function() {
        const value = this.value;
        if (value === "day") {
            dp.days = 1;
        } else if (value === "week") {
            dp.days = 7;
        } else if (value === "month") {
            dp.startDate = DayPilot.Date.today().firstDayOfMonth();
            dp.days = DayPilot.Date.today().daysInMonth();
        }
        dp.update();
        loadEvents();
    });

    $("#autoWidth").on("change", function() {
        dp.cellWidthSpec = this.checked ? "Auto" : "Fixed";
        dp.update();
    });

    // Додаємо обробник для кнопки "Додати кімнату"
    $("#addRoomBtn").on("click", function() {
        var modal = new DayPilot.Modal();
        modal.closed = function() {
            if (this.result && this.result.result === "OK") {
                loadResources();
            }
        };
        modal.showUrl("room_new.php");
    });

    loadResources();
});
