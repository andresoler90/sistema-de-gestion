var total_time = par.total_minutes + pro.total_minutes + cli.total_minutes;

Highcharts.chart('chart_column', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Gestión de la solicitud'
    },
    subtitle: {
        text: 'Porcentaje de la gestión por cada parte'
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
        text: 'Participación porcentual total'
        },
        max: 100
    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
        borderWidth: 0,
        dataLabels: {
            enabled: true,
            format: '{point.y:.1f}%'
        }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> del total<br/> {point.subname}'
    },

    series: [
        {
        name: "Gestión",
        colorByPoint: true,
        data: [
            {
            name: `Par`,
            subname : `<b>Dias:</b> ${par.days} <br><b>Horas:</b> ${par.hours} <br><b>Minutos:</b> ${par.minutes}`,
            y: ((par.total_minutes*100) / total_time ),
            color: '#48BED1',
            },
            {
            name: "Cliente",
            subname : `<b>Dias:</b> ${cli.days} <br><b>Horas:</b> ${cli.hours} <br><b>Minutos:</b> ${cli.minutes}`,
            y: ((cli.total_minutes*100) / total_time ),
            color: '#5987DD',
            },
            {
            name: "Proveedor",
            subname : `<b>Dias:</b> ${pro.days} <br><b>Horas:</b> ${pro.hours} <br><b>Minutos:</b> ${pro.minutes}`,
            y: ((pro.total_minutes*100) / total_time ),
            color: '#4FD69F',
            }
        ]
        }
    ]
});
$('.highcharts-credits').css('display','none');
