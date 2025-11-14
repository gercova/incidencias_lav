<template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ title }}</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" @click="refreshData">
          <i class="fas fa-sync"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div v-if="loading" class="text-center">
        <i class="fas fa-spinner fa-spin"></i> Cargando...
      </div>
      <div v-else-if="error" class="alert alert-danger">
        {{ error }}
      </div>
      <div v-else>
        <Bar 
          v-if="type === 'bar'" 
          :data="chartData" 
          :options="chartOptions" 
        />
        <Pie 
          v-else-if="type === 'pie'" 
          :data="chartData" 
          :options="chartOptions" 
        />
        <Doughnut 
          v-else-if="type === 'doughnut'" 
          :data="chartData" 
          :options="chartOptions" 
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios'
import {
  Chart as ChartJS,
  BarElement,
  ArcElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  PointElement,
  LineElement
} from 'chart.js'
import { Bar, Pie, Doughnut } from 'vue-chartjs'

ChartJS.register(
  BarElement,
  ArcElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  PointElement,
  LineElement
)

const props = defineProps({
  title: String,
  type: String,
  endpoint: String,
  filters: Object
})

const chartData = ref({})
const chartOptions = reactive({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top'
    },
    title: {
      display: false
    }
  }
})

const loading = ref(false)
const error = ref(null)

// Configurar axios
const apiClient = axios.create({
  baseURL: '/api/reports',
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  }
})

const loadData = async () => {
  loading.value = true
  error.value = null
  
  try {
    const response = await apiClient.get(`/${props.endpoint}`, {
      params: props.filters
    })
    
    processChartData(response.data)
  } catch (err) {
    error.value = 'Error al cargar los datos: ' + (err.response?.data?.message || err.message)
  } finally {
    loading.value = false
  }
}

const processChartData = (data) => {
  if (Array.isArray(data) && data[0]?.data) {
    // Formato para gráfico de barras
    chartData.value = {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      datasets: data.map((dataset, index) => ({
        label: dataset.name,
        data: dataset.data,
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255, 99, 132, 1)',
          'rgba(255, 205, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }))
    }
  } else {
    // Formato para gráficos de pastel
    const labels = data.map(item => item.name)
    const values = data.map(item => item.y)
    
    chartData.value = {
      labels: labels,
      datasets: [{
        data: values,
        backgroundColor: generateColors(data.length),
        borderWidth: 1
      }]
    }
  }
}

const generateColors = (count) => {
  const colors = []
  for (let i = 0; i < count; i++) {
    const hue = (i * 360) / count
    colors.push(`hsl(${hue}, 70%, 60%)`)
  }
  return colors
}

const refreshData = () => {
  loadData()
}

watch(() => props.filters, () => {
  loadData()
}, { deep: true })

onMounted(() => {
  loadData()
})
</script>
<!--<template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ title }}</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" @click="refreshData">
          <i class="fas fa-sync"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div v-if="loading" class="text-center">
        <i class="fas fa-spinner fa-spin"></i> Cargando...
      </div>
      <div v-else-if="error" class="alert alert-danger">
        {{ error }}
      </div>
      <div v-else>
        <canvas :id="chartId" ref="chartCanvasRef" height="400"></canvas>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'
import axios from 'axios'
import {
  Chart as ChartJS,
  BarElement,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js'

// Registrar componentes necesarios
ChartJS.register(
  BarElement,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  ArcElement
)

const props = defineProps({
  title: String,
  type: {
    type: String,
    default: 'bar'
  },
  endpoint: String,
  filters: Object
})

const chartId = ref(`chart-${Math.random().toString(36).substr(2, 9)}`)
const chartCanvasRef = ref(null)
const chartInstance = ref(null)
const loading = ref(false)
const error = ref(null)

// Configurar axios con interceptores y headers
const apiClient = axios.create({
  baseURL: '/api/reports',
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  }
})

// Cargar datos y crear gráfico
const loadData = async () => {
  loading.value = true
  error.value = null
  
  try {
    const response = await fetchChartApi(props.endpoint, props.filters)
    
    // Esperar a que el DOM esté actualizado antes de crear el gráfico
    await nextTick()
    
    // Verificar que el canvas exista antes de crear el gráfico
    if (chartCanvasRef.value) {
      createChart(response.data)
    } else {
      console.warn('Canvas no disponible para crear el gráfico')
    }
  } catch (err) {
    console.error('Error al cargar datos:', err)
    error.value = 'Error al cargar los datos: ' + (err.response?.data?.message || err.message)
  } finally {
    loading.value = false
  }
}

// Crear instancia del gráfico
const createChart = (data) => {
  // Destruir instancia anterior si existe
  if (chartInstance.value) {
    chartInstance.value.destroy()
    chartInstance.value = null
  }

  // Verificar que el canvas exista
  if (!chartCanvasRef.value) {
    console.error('Canvas no encontrado')
    return
  }

  const ctx = chartCanvasRef.value.getContext('2d')
  
  if (!ctx) {
    console.error('No se pudo obtener el contexto del canvas')
    return
  }

  const config = getChartConfig(data)
  
  chartInstance.value = new ChartJS(ctx, config)
}

// Configuración según tipo de gráfico
const getChartConfig = (data) => {
  const baseConfig = {
    type: props.type,
    data: getDataStructure(data),
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: false
        },
        tooltip: {
          mode: 'index',
          intersect: false
        }
      },
      scales: props.type === 'bar' || props.type === 'line' ? {
        y: {
          beginAtZero: true
        }
      } : {}
    }
  }

  return baseConfig
}

// Estructura de datos según tipo
const getDataStructure = (data) => {
  switch (props.type) {
    case 'bar':
    case 'line':
      return getBarLineData(data)
    case 'pie':
    case 'doughnut':
      return getPieData(data)
    default:
      return getBarLineData(data)
  }
}

const getBarLineData = (data) => {
  if (Array.isArray(data) && data[0]?.data) {
    // Formato para gráfico de barras (como incidencesByMonth)
    return {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      datasets: data.map((dataset, index) => ({
        label: dataset.name,
        data: dataset.data,
        backgroundColor: getColor(index),
        borderColor: getBorderColor(index),
        borderWidth: 1
      }))
    }
  } else {
    // Formato para otros gráficos de barras
    const labels = data.map(item => item.name)
    const values = data.map(item => item.y || item.count)
    
    return {
      labels: labels,
      datasets: [{
        label: 'Total',
        data: values,
        backgroundColor: getColor(0),
        borderColor: getBorderColor(0),
        borderWidth: 1
      }]
    }
  }
}

const getPieData = (data) => {
  const labels = data.map(item => item.name)
  const values = data.map(item => item.y)
  
  return {
    labels: labels,
    datasets: [{
      data: values,
      backgroundColor: generateColors(data.length),
      borderWidth: 1
    }]
  }
}

// Colores para los gráficos
const getColor = (index) => {
  const colors = [
    'rgba(54, 162, 235, 0.2)',
    'rgba(255, 99, 132, 0.2)',
    'rgba(255, 205, 86, 0.2)',
    'rgba(75, 192, 192, 0.2)',
    'rgba(153, 102, 255, 0.2)',
    'rgba(255, 159, 64, 0.2)'
  ]
  return colors[index % colors.length]
}

const getBorderColor = (index) => {
  const colors = [
    'rgba(54, 162, 235, 1)',
    'rgba(255, 99, 132, 1)',
    'rgba(255, 205, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)'
  ]
  return colors[index % colors.length]
}

const generateColors = (count) => {
  const colors = []
  for (let i = 0; i < count; i++) {
    const hue = (i * 360) / count
    colors.push(`hsl(${hue}, 70%, 60%)`)
  }
  return colors
}

// API call con axios
const fetchChartApi = async (endpoint, filters = {}) => {
  try {
    const response = await apiClient.get(`/${endpoint}`, {
      params: filters
    })
    return response
  } catch (error) {
    console.error(`Error en la petición a ${endpoint}:`, error)
    throw error
  }
}

// Refrescar datos
const refreshData = () => {
  loadData()
}

// Observar cambios en filtros
watch(() => props.filters, () => {
  loadData()
}, { deep: true })

// Montar el componente
onMounted(async () => {
  await nextTick() // Asegurar que el DOM esté completamente montado
  loadData()
})

// Limpiar recursos al desmontar
onUnmounted(() => {
  if (chartInstance.value) {
    chartInstance.value.destroy()
    chartInstance.value = null
  }
})
</script>-->