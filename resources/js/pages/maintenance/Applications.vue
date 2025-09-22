<script setup>
import axios from "axios";
import * as yup from "yup";
import { Form, Field } from "vee-validate";
import { onMounted, reactive, ref, watch } from "vue";
import { useToastr } from "@/toastr.js";
import { debounce } from "lodash";
import Swal from "sweetalert2";

const toastr        = useToastr();
const editing       = ref(false);
const searchQuery   = ref(null);
const loadClass     = ref('bi bi-floppy');
const formValues    = ref();
const form          = ref();
const formData      = reactive({
    id: "",
    name: "",
    description: "",
});

const cleanForm = () => {
    formData.value = {
        id: "",
        name: "",
        description: "",
    };
}

const addApplication = () => {
    editing.value = false;
    $("#applicationFormModal").modal("show");
    //$('#formApplication')[0].reset();
    loadClass.value = 'bi bi-floppy';
    resetForm();
};

const validateApplicationschema = yup.object({
    name: yup.string().required('El nombre es requerido'),
    description: yup.string().required('La descripción es requerida'),
});

const handleSubmit = (values, actions) => {
    if (editing.value) updateApplication(values, actions);
    else createApplication(values, actions);
};

const createApplication = async (values, { resetForm, setErrors }) => {
    loadClass.value = 'spinner-border spinner-border-sm';
    try {
        await axios.post(`/api/applications`, values)
        .then((response) => {
            applications.value.data.unshift(response.data.item);
            $("#applicationFormModal").modal("hide");
            toastr.success(response.data.message);
            resetForm();
        }).catch((error) => {
            if (error.response.data.errors) {
                setErrors(error.response.data.errors);
            }
        });
    } catch (error) {
        console.error(error);
        toastr.error('Error al guardar los datos, recargue la página e intenta de nuevo');
        loadClass.value = 'bi bi-floppy';
    }
};

const editApplication = (id) => {
    editing.value = true;
    axios.get(`/api/applications/${id}/edit`).then((response) => {
        formData.name = response.data.name;
        formData.description = response.data.description;
        formData.id = response.data.id;
        $("#applicationFormModal").modal("show");
        formValues.value = {
            id: response.data.id,
            name: response.data.name,
            description: response.data.description,
        };
    });
};

const updateApplication = async (values, { resetForm, setErrors }) => {
    loadClass.value = 'spinner-border spinner-border-sm';
    try {
        await axios.put(`/api/applications/${formValues.value.id}`, values)
        .then((response) => {
            const index = applications.value.data.findIndex(
                (application) => application.id == response.data.item.id
            );
        
            applications.value.data[index] = response.data.item;
            $("#applicationFormModal").modal("hide");
            toastr.success(response.data.message);
            resetForm();
        }).catch((error) => {
            if (error.response.data.errors) {
                setErrors(error.response.data.errors);
            }
        });
    } catch (error) {
        console.error(error);
        toastr.error('Error al guardar los datos, recargue la página e intenta de nuevo');
        loadClass.value = 'bi bi-floppy';
    }
};

const deleteProduct = (id) => {
    Swal.fire({
        title: "¿Estás seguro de continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/applications/${id}`).then((response) => {
                applications.value.data = applications.value.data.filter(
                    (application) => application.id != id
                );
                Swal.fire('¡Hecho!', response.data.message, 'success');
            });
        }
    });
};

const applications = ref({data: []});
const getApplications = (page = 1) => {
    axios.get(`/api/applications?page=${page}`, {
        params: {
            query: searchQuery.value,
        },
    })
    .then((response) => {
        applications.value = response.data;
    });
};

watch(
    searchQuery,
    debounce(() => {
        getApplications();
    }, 3000)
);

onMounted(() => {
    getApplications();
});
</script>
<template>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <button
                @click="addApplication"
                type="button"
                class="mb-2 btn btn-success"
            >
                <i class="bi bi-plus-circle-fill mr-1"></i>
                Nueva Aplicación
            </button>
        </div>
        <div>
            <input
                type="text"
                v-model="searchQuery"
                class="form-control"
                placeholder="Buscar..."
            />
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th scope="col" style="width: 5%">#</th>
                    <th scope="col" style="width: 35%">Nombre</th>
                    <th scope="col" style="width: 35%">Descripción</th>
                    <th scope="col" style="width: 20%">Opciones</th>
                </tr>
            </thead>
            <tbody v-if="applications.data.length > 0">
                <tr v-for="(app, index) in applications.data" :key="app.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ app.name }}</td>
                    <td>{{ app.description }}</td>
                    <td>
                        <a
                            href="#"
                            class="bi bi-pencil-square mb-1 ml-1 text-primary"
                            @click.prevent="editApplication(app.id)"
                        ></a>
                        <a
                            href="#"
                            class="bi bi-trash mb-1 ml-1 text-danger"
                            @click.prevent="deleteProduct(app.id)"
                        ></a>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center">
                        Ningún resultado encontrado...
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <Bootstrap4Pagination
            :data="applications"
            @pagination-change-page="getApplications"
        ></Bootstrap4Pagination>
    </div>
    <div
        class="modal fade"
        id="applicationFormModal"
        style="display: none"
        aria-hidden="true"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="staticBackdropLabel"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span v-if="editing">Actualizar Datos de la Aplicación</span>
                        <span v-else>Añadir Nueva Aplicación</span>
                    </h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <Form
                    ref="form"
                    id="formApplication"
                    @submit="handleSubmit"
                    :validation-schema="validateApplicationschema"
                    v-slot:default="{ errors }"
                    :initial-values="formValues"
                    autocomplete="off"
                >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre: </label>
                            <Field
                                name="name"
                                v-model="formData.name"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.name }"
                                id="description"
                                aria-describedby="nameHelp"
                                placeholder="Ingresa el nombre"
                            />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción: </label>
                            <Field
                                name="description"
                                v-model="formData.description"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.description }"
                                id="description"
                                aria-describedby="nameHelp"
                                placeholder="Ingresa la descripción"
                            />
                            <span class="invalid-feedback">{{ errors.description }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary mr-1">
                            <span :class="loadClass" aria-hidden="true"></span>&nbsp;Guardar datos
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
