<template>
    <div id="data-incidence-months" style="height: 480px;"></div>
</template>
<script>
import { ref } from "vue";
import Highcharts from 'highcharts';
import axios from "axios";

export default{
    data(){
        return{
            months: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
            meses: [],
            cantidad: [],
            quantity: '',
            numberMonth: '',
            selectedCategory: ref('TODO'),
            categories: ref([]),
            selectedType: ref('TODO'),
            types: ref([]),
        }
    },
    methods: {
        createChart(incidences) {
            Highcharts.chart('data-incidence-months', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Incidencia por mes',
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
                // series: datas.resData.quantity,
                series: incidences
            });
        }
    },
}
</script>