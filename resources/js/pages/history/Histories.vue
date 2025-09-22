<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";

const histories = ref([]);
const getHistories = (page = 1) => {
    axios.get(`/api/histories?page=${page}`).then((response) => {
        histories.value = response.data;
    });
};

onMounted(() => {
    getHistories();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Historias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Historias</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a
                                href="/admin/histories/create"
                                class="btn btn-primary"
                                ><i class="bi bi-plus-circle"></i> Agregar nueva
                                historia</a
                            >
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-hover table-sm"
                                        id="historiaTable"
                                    >
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">
                                                    Nro. historia
                                                </th>
                                                <th scope="col">DNI</th>
                                                <th scope="col">Nombres</th>
                                                <th scope="col">Sexo</th>
                                                <th scope="col">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    history, index
                                                ) in histories.data"
                                                :key="history.id"
                                            >
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    {{ history.number_history }}
                                                </td>
                                                <td>{{ history.dni }}</td>
                                                <td>{{ history.names }}</td>
                                                <td>{{ history.sex }}</td>
                                                <td>
                                                    <a
                                                        href="#"
                                                        class="btn btn-info bi bi-plus-circle mb-1 ml-1"
                                                        @click.prevent=""
                                                    ></a>
                                                    <a
                                                        href="#"
                                                        class="btn btn-warning bi bi-pencil-square mb-1 ml-1"
                                                        @click.prevent=""
                                                    ></a>
                                                    <a
                                                        href="#"
                                                        class="btn btn-danger bi bi-trash mb-1 ml-1"
                                                        @click.prevent=""
                                                    ></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr />
                                    <Bootstrap4Pagination
                                        :data="histories"
                                        @pagination-change-page="getHistories"
                                    />
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</template>
