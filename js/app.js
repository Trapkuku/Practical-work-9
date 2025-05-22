$(function() {

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
        startDate: "2025-05-20",
        days: 30,
        timeHeaders: [
            { groupBy: "Month", format: "MMMM yyyy" },
            { groupBy: "Day", format: "d" }
        ],
        allowEventOverlap: false,
        timeRangeSelectedHandling: "Enabled",
        eventClickHandling: "Enabled",
        eventDeleteHandling: "Update",
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
            const barColors = {
                "Arrived": "#57b540",
                "Confirmed": "#2b7a2e",
                "New": "#e69138",
                "CheckedOut": "#0c539e",
                "Expired": "#cc0000",
                "confirmed": "#2b7a2e",
                "pending": "#e69138",
                "cancelled": "#cc0000"
            };
            args.data.barColor = barColors[args.data.status] || "#999";
            args.data.html = `${args.data.text}<br/><small>Paid: ${args.data.paid * 100}%</small>`;
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
        },

        onEventMoved: function(args) {
            $.post("backend_update.php", {
                id: args.e.data.id,
                start: args.newStart.toString(),
                end: args.newEnd.toString(),
                room: args.newResource,
                name: args.e.data.text,
                status: args.e.data.status,
                paid: args.e.data.paid
            }, loadEvents);
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
            $.post("backend_delete.php", { id: args.e.data.id }, loadEvents);
        }

    });

    dp.init();

    dp.rows.load("backend_rooms.php");

    loadEvents();

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

    $("#filterRooms").on("change", function() {
        const value = this.value;
        if (value === "all") {
            dp.rows.filter(null);
        } else {
            dp.rows.filter(function(row) {
                return String(row.data.capacity) === value;
            });
        }
    });

    $("#timerange").on("change", function() {
        const value = this.value;
        if (value === "day") {
            dp.days = 1;
        } else if (value === "week") {
            dp.days = 7;
        } else if (value === "month") {
            dp.days = DayPilot.Date.parse(dp.startDate).daysInMonth();
        }
        dp.update();
        loadEvents();
    });

    $("#autoWidth").on("change", function() {
        dp.cellWidthSpec = this.checked ? "Auto" : "Fixed";
        dp.update();
    });

});
