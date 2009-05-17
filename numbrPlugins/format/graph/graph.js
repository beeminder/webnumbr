var makeGraph = function (c, data) {
    $(document).ready(function () {
        var graph = [];
        if (!$.isArray(data)) 
            data = [[0, data]];
        var series = [];
        var offset = new Date().getTimezoneOffset();
        for (var i=0; i < data.length; i++) {
            var time = data[i][0] - offset * 60;
            var val = data[i][1];
            series.push([time * 1000, val]);
        }

        if (series.length === 0 || (data.length == 1 && data[0][1] === null)) {
            series = [
                [0, 0],
                [2, 0], [2, 1], [4, 0], [4, 1], [4, 0], // N
                [7, 0], [6, 0.15], [5, 0.5], [6, 0.85], [7, 1], [8, 0.85], [9, 0.5], [8, 0.15], [7, 0], // O
                [16, 0], [16, 1], [18, 0.8], [18, 0.2], [16, 0], // D
                [21, 0], [22, 1], [22.5, 0.5], [21.5, 0.5], [22.5, 0.5], [23, 0], // A
                [27, 0], [27, 1], [25, 1], [29, 1], [27, 1], [27, 0], // T
                [31, 0], [32, 1], [32.5, 0.5], [31.5, 0.5], [32.5, 0.5], [33, 0], // A
                [35, 0]
            ];
        }
        var a = $("<a/>")
        .text(c.name)
        .attr("href", c.name);
        a.attr("title", a.text());
        if (a.text().length > 30) {
            a.text(a.text().substring(0, 27) + "...");
        }
        graph.push({
            "label" : $("<div/>").append(a).html(), // htmlspecialchars
            "data" : series
        });
        
        var config = {
            xaxis: { mode : "time" },
            legend : { 
                position : "nw",
                show : data.length > 1,
                backgroundOpacity : 0.5
            },
            lines : { show : true }
        };

        var plotFunction = function(json) {
            $("#plot").height($(".content").innerHeight());
            $("#plot").width(($(".content").width() - 15));
            $("#plot").css("margin", -10);
            $.plot($('#plot'), graph, config);
        }

        $(window).resize(function() { 
            plotFunction();
        });
        $("#plot").ready(function() {
            plotFunction();
        });

    });
}