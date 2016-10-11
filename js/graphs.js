(function() {
    // Categories Chart.
    $.getJSON("//burnsy.github.io/LincolnHack/js/chart_categories.json", function(data) {
        var chart = $("#categoriesChart");
        var categoriesChart = new Chart(chart, {
            type: 'doughnut',
            data:{ 
            labels: data.labels,
            datasets: [{
                data: data.datasets.data,
                backgroundColor: data.datasets.backgroundColor,
                hoverBackgroundColor: data.datasets.hoverBackgroundColor
            }]
        }})
    });

    // Activities Chart.
    $.getJSON("//burnsy.github.io/LincolnHack/js/chart_activities.json", function(data) {
        var chart = $("#activitiesChart");
        var categoriesChart = new Chart(chart, {
            type: 'bar',
            data:{ 
            labels: data.labels,
            datasets: [{
                label: data.datasets.label,
                data: data.datasets.data,
                backgroundColor: data.datasets.backgroundColor,
                borderColor: data.datasets.borderColor,
                borderWidth: data.datasets.borderWidth
            }]
        }})
    });

    // Most Contributing User Chart.
    $.getJSON("//burnsy.github.io/LincolnHack/js/chart_mcu.json", function(data) {
        var chart = $("#mauChart");
        var mauChart = new Chart(chart, {
            type: 'bar',
            data:{ 
            labels: [data.labels],
            datasets: [{
                label: data.datasets.label,
                data: [data.datasets.data],
                backgroundColor: data.datasets.backgroundColor,
                borderColor: data.datasets.borderColor,
                borderWidth: data.datasets.borderWidth
            }]
        }})
    });
})();