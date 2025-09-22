<script setup>
import axios from 'axios';
import { onMounted, ref, computed, reactive } from "vue";
import Swal from "sweetalert2";
import OptionsButton from './Components/OptionsButton.vue';


const columnDefs = reactive([
    { field: 'dni', headerName: 'DNI', flex: 1, filter: true, },
    { field: 'names', headerName: 'Nombres', flex: 2, filter: true, },
    { field: 'position_description', headerName: 'Posición y/o Cargo', flex: 2, },
    { field: 'start_date', headerName: 'FI', flex: 1, },
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
const fetchStaffData = async () => {
    const response = await fetch(`/api/staff`);
    const dataTable = await response.json();
    rowData.value = dataTable;
};

const incidenceId = ref(null);
const deleteIncidence = (id) => {
    incidenceId.value = id;
    Swal.fire({
        title: "¿Estás seguro de continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/incidences/${id}`).then((response) => {
                
                Swal.fire("¡Hecho!", response.data.message, "success");
            });
        }
    });
};

onMounted(() => {
    fetchStaffData();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Personal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Personal</li>
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
                            <router-link to="/admin/staff/create">
                                <button class="btn btn-primary">
                                    <i class="bi bi-plus-circle-fill mr-1"></i> Añadir Nuevo Personal
                                </button>
                            </router-link>
                        </div>
                        <div class="btn-group">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <AgGridVue
                                :rowData="rowData"
                                :columnDefs="columnDefs"
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
</template>