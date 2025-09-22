<script setup>
import axios from "axios";
import { onMounted, ref, computed } from "vue";
import Swal from "sweetalert2";
import { Bootstrap4Pagination } from "laravel-vue-pagination";

const selectedStatus = ref();
const appointmentStatus = ref([]);
const getAppointmentStatus = () => {
    axios.get(`/api/appointment-status`).then((response) => {
        appointmentStatus.value = response.data;
    });
    console.log(appointmentStatus.value);
};

const appointments = ref([]);
const getAppointments = (status, page = 1) => {
    selectedStatus.value = status;
    const params = {};
    if (status) {
        params.status = status;
    }
    axios
        .get(`/api/appointments?page=${page}`, {
            params: params,
        })
        .then((response) => {
            appointments.value = response.data;
        });
};

const appointmentsCount = computed(() => {
    return appointmentStatus.value
        .map((status) => status.count)
        .reduce((acc, value) => acc + value, 0);
});

const updateAppointmentSatusCount = (id) => {
    const deleteAppointmenStatus = appointments.value.data.find(
        (appointment) => appointments.id === id
    ).status.name;
    const statusToUpdate = appointmentStatus.value.find(
        (status) => status.name == deleteAppointmenStatus
    );
    statusToUpdate.count--;
};

const deleteAppointment = (id) => {
    Swal.fire({
        title: "¿Estás seguro de continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/appointments/${id}`).then((response) => {
                updateAppointmentSatusCount(id);
                appointments.value.data = appointments.value.data.filter(
                    (appointment) => appointment.id !== id
                );
                Swal.fire("¡Hecho!", response.data.message, "success");
            });
        }
    });
};

onMounted(() => {
    getAppointments();
    getAppointmentStatus();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Appointments</li>
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
                            <router-link to="/admin/appointments/create">
                                <button class="btn btn-primary">
                                    <i class="fa fa-plus-circle mr-1"></i> Add
                                    New Appointment
                                </button>
                            </router-link>
                        </div>
                        <div class="btn-group">
                            <button
                                @click="getAppointments()"
                                type="button"
                                class="btn"
                                :class="[
                                    typeof selectedStatus === 'undefined'
                                        ? 'btn-secondary'
                                        : 'btn-default',
                                ]"
                            >
                                <span class="mr-1">Todo</span>
                                <span class="badge badge-pill badge-info">{{
                                    appointmentsCount
                                }}</span>
                            </button>

                            <button
                                v-for="status in appointmentStatus"
                                @click="getAppointments(status.value)"
                                type="button"
                                class="btn"
                                :class="[
                                    selectedStatus === status.value
                                        ? 'btn-secondary'
                                        : 'btn-default',
                                ]"
                            >
                                <span class="mr-1">{{ status.name }}</span>
                                <span
                                    class="badge badge-pill"
                                    :class="`badge-${status.color}`"
                                    >{{ status.count }}</span
                                >
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Names</th>
                                        <th scope="col">Inicio</th>
                                        <th scope="col">Fin</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            appointment, index
                                        ) in appointments.data"
                                        :key="appointment.id"
                                    >
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ appointment.client.names }}</td>
                                        <td>{{ appointment.start_time }}</td>
                                        <td>{{ appointment.end_time }}</td>
                                        <td>
                                            <span
                                                class="badge"
                                                :class="`badge-${appointment.status.color}`"
                                                >{{
                                                    appointment.status.name
                                                }}</span
                                            >
                                        </td>
                                        <td>
                                            <router-link
                                                :to="`/admin/appointments/${appointment.id}/edit`"
                                            >
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>

                                            <a
                                                href=""
                                                @click.prevent="
                                                    ($event) =>
                                                        deleteAppointment(
                                                            appointment.id
                                                        )
                                                "
                                            >
                                                <i
                                                    class="fa fa-trash text-danger"
                                                ></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <Bootstrap4Pagination
                        :data="appointments"
                        @pagination-change-page="getAppointments"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
