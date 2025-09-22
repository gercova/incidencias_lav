<template>
    <div id="data-incidence-months"></div>
</template>
<script>
import Highcharts from 'highcharts';

export default{
    data(){
        return{
            months: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
        }
    },
    methods: {
        createChart(incidences) {
            Highcharts.chart('data-incidence-months', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Incidencias por mes',
                    align: 'center'
                },
                xAxis: {
                    categories: this.months
                },
                yAxis: {
                    title: {
                        text: 'Cantidad'
                    }
                },
                series: incidences
            });
        }
    },
}
</script>
