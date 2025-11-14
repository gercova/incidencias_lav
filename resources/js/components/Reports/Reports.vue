<template>
    <div class="col-12">
        <div class="row">
            <div class="col-3">
                <label for="category">Categoría: </label>
                <select class="form-control" v-model="filters.selectedCategory" id="category">
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
                <select class="form-control" v-model="filters.selectedType" id="type">
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
                <label for="start_date">Fecha inicio: </label>
                <input type="date" class="form-control" v-model="filters.start_date" id="star_date">
            </div>
            <div class="col-3">
                <label for="end_date">Fecha fin: </label>
                <input type="date" class="form-control" v-model="filters.end_date" id="end_date">
            </div>
        </div>
    </div>
    <hr>
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <month-reports ref="incidencebymonths"></month-reports>
            </div>
            <div class="col-lg-6">
                <level-reports ref="incidencebylevels"></level-reports>
            </div>
            <div class="col-lg-6">
                <category-reports ref="incidencebycategories"></category-reports>
            </div>
            <div class="col-lg-12">
                <user-drill-reports ref="incidencebyusers"></user-drill-reports>
            </div>
            <div class="col-lg-12">
                <area-drill-reports ref="incidencebyareas"></area-drill-reports>
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from "vue";
import axios from "axios";
import MonthReports from "./MonthReports.vue";
import LevelReports from "./LevelReports.vue";
import UserDrillReports from "./UserDrillReports.vue";
import CategoryReports from "./CategoryReports.vue";
import AreaDrillReports from "./AreaDrillReports.vue";

export default{
    components: {
        MonthReports,
        LevelReports,
        UserDrillReports,
        CategoryReports,
        AreaDrillReports,
    },
    data(){
        return{
            incidencebymonths: [],
            incidencebylevels: [],
            incidencebycategories: [],
            incidencebyusers: [],
            incidencebyareas: [],
            filters: {
                selectedCategory: 'TODO',
                selectedType: 'TODO',
                start_date: '',
                end_date: ''
            },

            categories: ref([]),
            types: ref([]),
            levels: ref([]),
        }
    },
    methods: {
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
        getIncidenceByMonth(){
            axios.get('/api/stats/months', {
                params: this.filters,
            })
            .then((response) => {
                this.$refs.incidencebymonths.createChart(response.data);
            });
        },
        getIncidenceByLevel(){
            axios.get('/api/stats/levels', {
                params: this.filters,
            }).then((response) => {
                this.$refs.incidencebylevels.createChart(response.data);
            });
        },
        getIncidenceByCategory(){
            axios.get('/api/stats/category', {
                params: this.filters,
            })
            .then((response) => {
                this.$refs.incidencebycategories.createChart(response.data);
            });
        },
        getIncidinceByUser(){
            axios.get('/api/stats/usersdrill', {
                params: this.filters
            })
            .then((response) => {
                this.$refs.incidencebyusers.createChart(response.data);
            });
        },
        getIncidenceByArea(){
            axios.get('/api/stats/areadrill', {
                params: this.filters
            })
            .then((response) => {
                this.$refs.incidencebyareas.createChart(response.data);
            })
        }
    },
    watch: {
        'filters': {
            handler(value){
                this.getIncidenceByMonth();
                this.getIncidenceByLevel();
                this.getIncidenceByCategory();
                this.getIncidinceByUser();
                this.getIncidenceByArea();
            },
            deep:true
        }
    },
    mounted() {
        this.getCategories();
        this.getTypes();
        this.getIncidenceByMonth();
        this.getIncidenceByLevel();
        this.getIncidenceByCategory();
        this.getIncidinceByUser();
        this.getIncidenceByArea();
    }
}
</script>