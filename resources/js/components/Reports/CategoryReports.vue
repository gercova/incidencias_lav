<template>
    <div id="data-category-report"></div>
</template>
<script>
import Highcharts from 'highcharts';
export default {
    data(){
        return {}
    },
    methods: {
        createChart(incidences){
            Highcharts.chart('data-category-report', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45
                    }
                },
                title: {
                    text: 'Incidencia por categoría',
                    align: 'center'
                },
                tooltip: {
                    pointFormat: '{values.name} Cantidad: <b>{point.y}</b> <br>Porcentaje: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45
                    }
                },
                series: [{
                    name: 'Cantidad',
                    data: incidences
                }]
            });
        }
    }
}
</script>