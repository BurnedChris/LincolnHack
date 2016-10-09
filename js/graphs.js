(function() {
    // Categories Chart.
    $.getJSON("//lcw.ngrok.io/api/stats?s=categories", function(data) {
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
    $.getJSON("//lcw.ngrok.io/api/stats?s=activities", function(data) {
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

    // Most Active User Chart.
    $.getJSON("//lcw.ngrok.io/api/stats?s=mau", function(data) {
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