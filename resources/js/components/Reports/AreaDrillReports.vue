<template>
    <div id="data-area-reports"></div>
</template>
<script>
import Highcharts from 'highcharts';
export default {
    data(){
        return {}
    },
    methods: {
        createChart(incidences){
            Highcharts.chart('data-area-reports', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Incidencias por Áreas'
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
        }
    },
}
</script>