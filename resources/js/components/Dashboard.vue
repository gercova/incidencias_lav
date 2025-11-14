<!--<template>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Filtros de Reportes</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <label>Fecha Inicio</label>
            <input 
              v-model="filters.start_date" 
              type="date" 
              class="form-control"
            >
          </div>
          <div class="col-md-3">
            <label>Fecha Fin</label>
            <input 
              v-model="filters.end_date" 
              type="date" 
              class="form-control"
            >
          </div>
          <div class="col-md-3">
            <label>Categoría</label>
            <select v-model="filters.selectedCategory" class="form-control">
              <option value="TODO">Todas</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <label>Tipo</label>
            <select v-model="filters.selectedType" class="form-control">
              <option value="TODO">Todos</option>
              <option v-for="type in types" :key="type.id" :value="type.id">
                {{ type.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>

    
    <div class="row">
      <div class="col-md-6">
        <ChartComponent
          title="Incidencias por Mes"
          type="bar"
          endpoint="incidences-by-month"
          :filters="filters"
        />
      </div>
      <div class="col-md-6">
        <ChartComponent
          title="Incidencias por Niveles"
          type="pie"
          endpoint="incidences-by-levels"
          :filters="filters"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <ChartComponent
          title="Incidencias por Categorías"
          type="doughnut"
          endpoint="incidence-by-categories"
          :filters="filters"
        />
      </div>
      <div class="col-md-6">
        <ChartComponent
          title="Incidencias por Usuarios (Top 15)"
          type="horizontalBar"
          endpoint="incidences-by-users"
          :filters="filters"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <ChartComponent
          title="Incidencias por Áreas"
          type="bar"
          endpoint="incidence-by-areas"
          :filters="filters"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import ChartComponent from './Reports/ChartComponent.vue'

const filters = ref({
  start_date: '',
  end_date: '',
  selectedCategory: 'TODO',
  selectedType: 'TODO'
})

const categories = ref([])
const types = ref([])

// Configurar axios
const apiClient = axios.create({
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  }
})

// Cargar categorías y tipos
const loadOptions = async () => {
  try {
    // Cargar categorías
    const catResponse = await apiClient.get('/api/incidence-categories')
    categories.value = catResponse.data
    
    // Cargar tipos
    const typeResponse = await apiClient.get('/api/incidence-types')
    types.value = typeResponse.data
  } catch (error) {
    console.error('Error cargando opciones:', error)
  }
}

onMounted(async () => {
  await nextTick()
  loadOptions()
})
</script>

<style scoped>
.row {
  margin-bottom: 20px;
}
</style>-->
<script setup>
import axios from "axios";
import { onMounted, reactive, computed, ref } from "vue";
import Reports from "./Reports/Reports.vue";

const selectedDateRangeIncendeces = ref("today");
const totalIncidencesCount = ref(0);
const getIncidencesCount = () => {
    axios.get('/api/stats/incidences', {
        params: {
            date_range: selectedDateRangeIncendeces.value,
        }
    }).then((response) => {
        totalIncidencesCount.value = response.data.totalIncidencesCount;
    });
}

const selectedTypes = ref("todos");
const totalTypesCount = ref(0);
const getTypesCount = () => {
    axios.get('/api/stats/types', {
        params: {
            type: selectedTypes.value,
        }
    }).then((response) => {
        totalTypesCount.value = response.data.totalTypesCount;
    });
};

const selectedCategories = ref("todos");
const totalCategoriesCount = ref(0)
const getCategoriesCount = () => {
  axios.get('/api/stats/categories', {
    params: {
      category: selectedCategories.value,
    }
  })
  .then((response) => {
    totalCategoriesCount.value = response.data.totalCategoriesCount;
  });
};

const types = ref([]);
const getTypes = () => {
  axios.get('/api/incidence-type')
  .then((response) => {
    types.value = response.data;
  });
};

const categories = ref([]);
const getCategories = () => {
  axios.get('/api/incidence-category')
  .then((response) => {
    categories.value = response.data;
  });
};

onMounted(() => {
  getIncidencesCount();
  getTypesCount();
  getCategoriesCount();
  getTypes();
  getCategories();
});

</script>
<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <div class="d-flex justify-content-between">
                  <h3>{{ totalIncidencesCount }}</h3>
                  <select
                    v-model="selectedDateRangeIncendeces"
                    @change="getIncidencesCount()"
                    style="
                        height: 2rem;
                        outline: 2px solid transparent;
                    "
                    class="px-1 rounded border-0"
                  >
                      <option value="today">Hoy</option>
                      <option value="30_days">30 días</option>
                      <option value="60_days">60 días</option>
                      <option value="360_days">360 días</option>
                      <option value="month_to_date">
                          Mes hasta la fecha
                      </option>
                      <option value="year_to_date">
                          Año hasta la fecha
                      </option>
                  </select>
              </div>
              <p>Incidencias</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <router-link to="/admin/incidences" class="small-box-footer">
              Ver incidencias
              <i class="fas bi bi-arrow-right-circle-fill"></i>
            </router-link>
          </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <div class="d-flex justify-content-between">
                        <h3>{{ totalTypesCount }}</h3>
                        <select
                            v-model="selectedTypes"
                            @change="getTypesCount()"
                            style="
                                height: 2rem;
                                outline: 2px solid transparent;
                            "
                            class="px-1 rounded border-0"
                        >
                            <option value="todos">TODOS</option>
                            <option 
                                v-for="type in types" 
                                :key="type.id" 
                                :value="type.id"
                            >
                                {{ type.description }}
                            </option>
                        </select>
                    </div>
                    <p>Niveles</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <router-link to="/admin/incidences" class="small-box-footer">
                    Ver incidencias
                    <i class="fas bi bi-arrow-right-circle-fill"></i>
                </router-link>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <div class="d-flex justify-content-between">
                        <h3>{{ totalCategoriesCount }}</h3>
                        <select
                            v-model="selectedCategories"
                            @change="getCategoriesCount()"
                            style="
                                height: 2rem;
                                outline: 2px solid transparent;
                            "
                            class="px-1 rounded border-0"
                        >
                            <option value="todos">TODOS</option>
                            <option 
                                v-for="category in categories" 
                                :key="category.id" 
                                :value="category.id"
                            >
                                {{ category.aka }}
                            </option>
                        </select>
                    </div>
                    <p>Categorías</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <router-link to="/admin/incidences" class="small-box-footer">
                    Ver incidencias
                    <i class="fas bi bi-arrow-right-circle-fill"></i>
                </router-link>
            </div>
        </div>
      </div>
      <hr>
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <Reports/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
