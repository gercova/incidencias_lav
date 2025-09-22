<script setup>
import axios from 'axios';
import { onBeforeMount, ref, reactive, watch } from "vue";
import OptionsButton from "./Components/OptionsButton.vue";
import Swal from "sweetalert2";

const columnDefs = reactive([
    { field: 'names', headerName: 'Nombres', flex: 3, filter: true, },
    { field: 'category', headerName: 'Categoría', flex: 1, filter: true, },
    { field: 'type', headerName: 'Nivel', flex: 1, filter: true, },
    { field: 'created', headerName: 'Fecha', flex: 1, filter: true, },
    { 
        field: 'id', 
        headerName: 'Opciones',
        cellRenderer: OptionsButton,
        width: 100,
        flex: 1,
    }
]);

const gridOptions = ref({
    rowHeight: 35,
    pagination: true,
    sorting: true,
    filtering: true,
    search: true,
});

const rowData = ref([]);
/*const filters = {
    start_date: '',
    end_date: ''
};*/

let timerInterval;
const fetchIncidencesData = async () => {
    
    const response = await fetch(`/api/incidences`);
    const dataTable = await response.json();
    rowData.value = dataTable;
    Swal.fire({
        title: 'Cargando...',
        html: "Extrayendo toda la data del sistema... <b></b> milisegundos.",
        timer: 2000,
        timerProgressBar: true,  // Muestra la barra de progreso
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
            console.log('Alerta cerrada automáticamente después de 8 segundos.');
        }
    });
};

/*const fetchIncidencesData = () => {
    axios.get(`/api/incidences`, {
        params: {
            b_date: start_date.value,
            e_date: end_date.value,
        }
    })
    .then((response) => {
        rowData.value = response.data;
    });
}*/

const rowSelection = ref([]);
const onRemoveSelected = () => {
    Swal.fire({
        title: "¿Estás seguro de continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            const selectedData = gridApi.value.getSelectedRows();
            if(selectedData.length){
                axios.delete(`/api/incidences/${selectedData[0].id}`).then((response) => {
                    const res = gridApi.value.applyTransaction({ remove: selectedData });
                    printResult(res);
                    Swal.fire("¡Hecho!", response.data.message, "success");
                });
                console.log(selectedData[0]);
            }else{
                Swal.fire("¡Upsss!", 'Seleccione un item!!!', "error");
                console.log(0);
            }
        }
    });
};

const gridApi = ref();
const onGridReady = (params) => {
    gridApi.value = params.api;
};

window.printResult = function printResult(res) {
    if (res.remove) {
        res.remove.forEach((rowNode) => {
            console.log("Removed Row Node", rowNode);
        });
    }
};

onBeforeMount(() => {
    fetchIncidencesData();
    rowSelection.value = "single";
});

</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Incidencias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Incidencias</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <router-link to="/admin/incidences/create">
                                <button class="btn btn-primary">
                                    <i class="bi bi-plus-circle-fill mr-1"></i> Añadir Nueva Incidencia
                                </button>
                            </router-link>
                        </div>
                        <div>
                            <button
                                v-on:click="onRemoveSelected()"
                                type="button"
                                class="ml-2 mb-2 btn  btn-outline-danger"
                            >
                                <i class="bi bi-trash mr-1"></i>
                                Borrar ítem seleccionado <!--<p v-if="rowSelection.value">{{ selectedData[0].names }}</p>-->
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <!--<div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="form-group col-lg-6 row">
                                                    <label for="begin_date" class="col-sm-4 col-form-label">Fecha inicio:</label>
                                                    <div class="col-sm-4">
                                                        <input 
                                                            id="begin_date"
                                                            type="date" 
                                                            class="form-control" 
                                                            v-model="filters.start_date"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-6 row">
                                                    <label for="end_data" class="col-sm-4 col-form-label">Fecha fin:</label>
                                                    <div class="col-sm-4">
                                                        <input 
                                                            id="end_data"
                                                            type="date" 
                                                            class="form-control" 
                                                            v-model="filters.end_date"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    
                                    <AgGridVue
                                        :rowData="rowData"
                                        :columnDefs="columnDefs"
                                        @grid-ready="onGridReady"
                                        :rowSelection="rowSelection"
                                        style="height: 500px"
                                        class="ag-theme-quartz"
                                        pagination="true"
                                    ></AgGridVue>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>