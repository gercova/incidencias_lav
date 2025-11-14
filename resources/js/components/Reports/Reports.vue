<template>
    <div class="col-12">
        <div class="row">
            <div class="col-3">
                <label for="category">Categoría: </label>
                <select class="form-control" v-model="selectedCategory" id="category">
                    <option value="TODO">TODO</option>
                    <option
                        v-for="cat in categories.value"
                        :key="cat.id"
                        :value="cat.id"
                    >
                        {{ cat.description }}
                    </option>
                </select>
            </div>

            <div class="col-3">
                <label for="type">Nivel:</label>
                <select class="form-control" v-model="selectedType" id="type">
                    <option value="TODO">TODO</option>
                    <option
                        v-for="type in types.value"
                        :key="type.id"
                        :value="type.id"
                    >
                        {{ type.description }}
                    </option>
                </select>
            </div>
            <div class="col-3">
                <label for="sdate">Fecha inicio: </label>
                <input type="date" class="form-control" v-model="start_date" id="sdate">
            </div>
            <div class="col-3">
                <label for="edate">Fecha fin: </label>
                <input type="date" class="form-control" v-model="end_date" id="edate">
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-log-6">
                <month-reports ref="incidencebymonths"></month-reports>
            </div>
        </div>
    </div>

</template>

<script>
import { ref } from "vue";
import Highcharts from 'highcharts';
import axios from "axios";
import MonthReports from "./MonthReports.vue";

export default{
    components: {
        MonthReports,
    },
    data(){
        return{
            //color: '#fff',
            months: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
            incidencebymonths: [],
            meses: [],
            cantidad: [],
            quantity: '',
            numberMonth: '',
            selectedCategory: ref('TODO'),
            categories: ref([]),
            selectedType: ref('TODO'),
            types: ref([]),
            start_date: ref(),
            end_date: ref(),
        }
    },
    methods: {
        getIncidenceByMonth(){
            axios.get('/api/stats/months')
            .then((response) => {
                this.$refs.incidencebymonths.createChart(response.data)
            });
        },
        getCategories(){
            axios.get('/api/incidence-category')
            .then((response) => {
                this.categories.value = response.data;
            });
        },
        getTypes(){
            axios.get('/api/incidence-type')
            .then((response) => {
                this.types.value = response.data;
            });
        },
        createChart() {
            Highcharts.chart('container', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Fruit Consumption'
                },
                xAxis: {
                    categories: ['Apples', 'Bananas', 'Oranges']
                },
                yAxis: {
                    title: {
                        text: 'Fruit eaten'
                    }
                },
                series: [{
                    name: 'Jane',
                    data: [1, 0, 4]
                }, {
                    name: 'John',
                    data: [5, 7, 3]
                }]
            });
            Highcharts.chart('data-category', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Fruit Consumption'
                },
                xAxis: {
                    categories: ['Apples', 'Bananas', 'Oranges']
                },
                yAxis: {
                    title: {
                        text: 'Fruit eaten'
                    }
                },
                series: [{
                    name: 'Jane',
                    data: [1, 0, 4]
                }, {
                    name: 'John',
                    data: [5, 7, 3]
                }]
            });
        }
    },
    mounted() {
        this.getIncidenceByMonth();
        this.getCategories();
        this.getTypes();
        this.createChart();
    }
}
</script>