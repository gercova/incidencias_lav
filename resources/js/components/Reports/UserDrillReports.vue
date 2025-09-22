<template>
    <div id="data-user-drill"></div>
</template>
<script>
import Highcharts from 'highcharts';
export default {
    data(){
        return {}
    },
    methods: {
        createChart(incidences){
            Highcharts.chart('data-user-drill', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Incidencias por Usuarios'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Total'
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    name: 'Cantidad',
                    data: incidences.data
                }],
                drilldown: {
                    series: incidences.drilldown
                }
            });
        },
    },
}
</script>