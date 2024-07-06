

/****************************  destinations-stats  *******************************/
// let destinationsStatsInput = document.getElementById('destinations-stats');
// let lang = document.getElementById('lang').value;
// let destinationsStats = JSON.parse(destinationsStatsInput.value);
// let labels = [];
// let series = [];
// destinationsStats.forEach(destination => {
//     if (lang == 'ar') {
//         labels.push(destination.name_ar)
//     } else {
//         labels.push(destination.name)
//     }
//     series.push(destination.qty)
// });
function getChartColorsArray(e) {
    if (null !== document.getElementById(e)) {
        var t = document.getElementById(e).getAttribute("data-colors");
        if (t) return (t = JSON.parse(t)).map(function (e) {
            var t = e.replace(" ", "");
            if (-1 === t.indexOf(",")) {
                var o = getComputedStyle(document.documentElement).getPropertyValue(t);
                return o || t
            }
            e = e.split(",");
            return 2 != e.length ? t : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")"
        });
        console.warn("data-colors atributes not found on", e)
    }
}
// var options, chart, chartDonutBasicColors = getChartColorsArray("destinations-stats-chart");
// chartDonutBasicColors && (options = {
//     series: series,
//     labels: labels,
//     chart: {
//         height: 333,
//         type: "donut"
//     },
//     legend: {
//         position: "bottom"
//     },
//     stroke: {
//         show: !1
//     },
//     dataLabels: {
//         dropShadow: {
//             enabled: !1
//         }
//     },
//     colors: chartDonutBasicColors
// }, (chart = new ApexCharts(document.querySelector("#destinations-stats-chart"), options)).render());

/****************************  destinations-stats  *******************************/





/****************************  Monthly Shipments Entries  *******************************/


let monthlyShipmentsEntriesInput = document.getElementById('monthly-projects-entries');
let monthlyShipmentsEntriesStats = JSON.parse(monthlyShipmentsEntriesInput.value);
let categories = [];
let mseries = [];

monthlyShipmentsEntriesStats.forEach(destination => {
    categories.push(destination.month)
    mseries.push(destination.qty)
});

let lang = 'ar';
var linechartcustomerColors = getChartColorsArray("monthly-projects-entries-charts");
linechartcustomerColors && (options = {
    series: [{
        name: lang == 'ar' ? "مشروع" : "Project",
        type: "bar",
        data: mseries
    }],
    chart: {
        height: 370,
        type: "line",
        toolbar: {
            show: !1
        }
    },
    stroke: {
        curve: "straight",
        dashArray: [0, 0, 8],
        width: [2, 0, 2.2]
    },
    fill: {

    },
    markers: {
        size: [0, 0, 0],
        strokeWidth: 2,
        hover: {
            size: 4
        }
    },
    xaxis: {
        categories,
        axisTicks: {
            show: !1
        },
        axisBorder: {
            show: !1
        }
    },
    grid: {
        show: !0,
        xaxis: {
            lines: {
                show: !0
            }
        },
        yaxis: {
            lines: {
                show: !1
            }
        },
        padding: {
            top: 0,
            right: -2,
            bottom: 15,
            left: 10
        }
    },
    legend: {
        show: !0,
        horizontalAlign: "center",
        offsetX: 0,
        offsetY: -5,
        markers: {
            width: 9,
            height: 9,
            radius: 6
        },
        itemMargin: {
            horizontal: 10,
            vertical: 0
        }
    },
    plotOptions: {
        bar: {
            columnWidth: "50%",
            barHeight: "50%"
        }
    },
    colors: linechartcustomerColors,

}, (chart = new ApexCharts(document.querySelector("#monthly-projects-entries-charts"), options)).render());

/****************************  Monthly Shipments Entries  *******************************/




/****************************  destinations-stats  *******************************/
let shipmentsByStatusInput = document.getElementById('projects-by-status');
let shipmentsByStatus = JSON.parse(shipmentsByStatusInput.value);
let m2labels = [];
let m2series = [];
shipmentsByStatus.forEach(destination => {
    m2labels.push(destination.name)
    m2series.push(destination.qty)
});
function getChartColorsArray(e) {
    if (null !== document.getElementById(e)) {
        var t = document.getElementById(e).getAttribute("data-colors");
        if (t) return (t = JSON.parse(t)).map(function (e) {
            var t = e.replace(" ", "");
            if (-1 === t.indexOf(",")) {
                var o = getComputedStyle(document.documentElement).getPropertyValue(t);
                return o || t
            }
            e = e.split(",");
            return 2 != e.length ? t : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")"
        });
        console.warn("data-colors atributes not found on", e)
    }
}


var options, chart, chartDonutBasicColors = getChartColorsArray("projects-by-status-chart");
chartDonutBasicColors && (options = {
    series: m2series,
    labels: m2labels,
    chart: {
        height: 333,
        type: "donut"
    },
    legend: {
        position: "bottom"
    },
    stroke: {
        show: !1
    },
    dataLabels: {
        dropShadow: {
            enabled: !1
        }
    },
    colors: chartDonutBasicColors
}, (chart = new ApexCharts(document.querySelector("#projects-by-status-chart"), options)).render());

/****************************  destinations-stats  *******************************/


