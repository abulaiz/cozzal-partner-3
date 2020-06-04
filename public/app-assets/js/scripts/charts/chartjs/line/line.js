/*=========================================================================================
    File Name: line.js
    Description: Chartjs simple line chart
    ----------------------------------------------------------------------------------------
    Item Name: Robust - Responsive Admin Template
    Version: 2.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Line chart
// ------------------------------
$(window).on("load", function(){
    //Get the context of the Chart canvas element we want to select
    var ctx = $("#line-chart");
    // Chart Options
    var chartOptions = {
        responsive: true,
        elements: {
          line: {
            tension: 0.000001
          }
        },
        tooltips: {
          mode: 'index',
          intersect: false,
        },
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
        },
        hover: {
          mode: 'nearest',
          intersect: true
        },
        scales: {
          xAxes: [{
            display: true,
            scaleLabel: {
              display: true,
              labelString: 'Bulan'
            }
          }],
          yAxes: [{
            display: true,
            scaleLabel: {
              display: true,
              labelString: 'Hari'
            }
          }]
        },
        title: {
          display: true,
          text: 'Transaction'
        },
    };

    // Chart Data
    var chartData = {
        labels: ["January", "February", "March", "April", "May", "June", "July", 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: "My First dataset",
            data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56],
            fill: false,
            borderColor: window.chartColors.purple,
            pointBorderColor: window.chartColors.purple,
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 3,
        }, {
            label: "My Second dataset",
            data: [28, 48, 40, 19, 86, 66, 90, 48, 40, 19, 86, 66],
            fill: false,
            borderColor: window.chartColors.blue,
            pointBorderColor: window.chartColors.blue,
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 3,
        }, {
            label: "My Third dataset - No bezier",
            data: [45, 25, 16, 36, 67, 18, 76, 19, 86, 66, 90, 48],
            lineTension: 0,
            fill: false,
            borderColor: window.chartColors.yellow,
            pointBorderColor: window.chartColors.yellow,
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 3,
        }]
    };

    var config = {
        type: 'line',
        // Chart Options
        options : chartOptions,
        data : chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);
});
