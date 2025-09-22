<template>
    <div id="data-pie-levels"></div>
</template>
<script>
import { ref } from "vue";
import Highcharts from 'highcharts';
export default {
    data(){
        return {
            
        }
    },
    methods: {
        createChart(incidences){
            Highcharts.chart('data-pie-levels', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Incidencia por nivel'
                },
                tooltip: {
                    pointFormat: '{values.name} Cantidad: <b>{point.y}</b> <br>Porcentaje: <b>{point.percentage:.1f}%</b>'
                },
                series: [{
                    name: 'Niveles',
                    colorByPoint: true,
                    data: this.levels,
                    data: incidences
                }]
            });
        }
    },
}
</script>