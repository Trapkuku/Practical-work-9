$(function() {

    const dp = new DayPilot.Scheduler("dp", {

        cellWidth: 50,
        cellWidthSpec: "Fixed",
        heightSpec: "Max",
        rowHeaderWidth: 160,
        rowHeaderColumns: [
            { title: "Room", display: "name", width: 80 },
            { title: "Capacity", display: "capacity", width: 60 },
            { title: "Status", display: "status", width: 20 }
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
            const columns = args.row.columns;
            columns[1].html = `${args.row.data.capacity} bed${args.row.data.capacity > 1 ? "s" : ""}`;
            const statusColors = {
                "Ready": "#57b540",
                "Cleanup": "#f5c447",
                "Dirty": "#e43d27",
                "available": "#57b540",
                "occupied": "#e69138",
                "maintenance": "#fa3f3f"
            };
            columns[2].backColor = statusColors[args.row.data.status] || "#b0b0b0";
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

        onTimeRangeSelected: function(args) {
            var modal = new DayPilot.Modal();
            modal.showUrl("new.php?start=" + args.start + "&end_date=" + args.end + "&resource=" + args.resource);
            modal.closed = function() {
                if (this.result) {
                    loadEvents();
                }
            };
            dp.clearSelection();
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
                end_date: args.newEnd.toString(),
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
                end_date: args.newEnd.toString(),
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
        dp.events.load("backend_events.php");
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
    });

    $("#autoWidth").on("change", function() {
        dp.cellWidthSpec = this.checked ? "Auto" : "Fixed";
        dp.update();
    });

});
