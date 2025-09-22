<script setup>
import axios from "axios";
import { reactive, onMounted, ref, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToastr } from "@/toastr";
import { Form, Field } from "vee-validate";

const router    = useRouter();
const route     = useRoute();
const toastr    = useToastr();
const editMode = ref(false);
const form      = reactive({
    dni: "",
    names: "",
    society: "",
    area: "",
    position_description: "",
    direction: "",
    organizational_unit: "",
    start_date: "",
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editStaff(values, actions);
    } else {
        createStaff(values, actions);
    }
};

const createStaff = (values, actions) => {
    axios.post("/api/staff/create", form)
    .then((response) => {
        router.push("/admin/staff");
        toastr.success(response.data.message);
    })
    .catch((error) => {
        actions.setErrors(error.response.data.errors);
    });
};

const editStaff = (values, actions) => {
    axios.put(`/api/staff/${route.params.id}/edit`, form)
    .then((response) => {
        router.push("/admin/staff");
        toastr.success(response.data.message);
    })
    .catch((error) => {
        actions.setErrors(error.response.data.errors);
    });
};

const getStaff = () => {
    axios.get(`/api/staff/${route.params.id}/edit`).then(({ data }) => {
        form.dni = data.dni;
        form.names = data.names;
        form.society = data.society;
        form.area = data.area;
        form.direction = data.direction;
        form.position_description = data.position_description;
        form.organizational_unit = data.organizational_unit;
        form.start_date = data.start_date;
        //console.log(data);
    });
};

onMounted(() => {
    if (route.name === "admin.staff.edit") {
        editMode.value = true;
        getStaff();
    }

});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Actualizar Datos</span>
                        <span v-else>Crear</span>
                        Personal
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard"
                                >Inicio</router-link
                            >
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments"
                                >Personal</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Actualizar</span>
                            <span v-else>Crear</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot:default="{ errors }">
                                <div class="form-row">
                                    <div class="form-group col-4">
                                        <label for="dni">DNI</label>
                                        <Field
                                            id="dni"
                                            name="dni"
                                            class="form-control"
                                            v-model="form.dni"
                                            placeholder="Buscar DNI..."
                                            :class="{'is-invalid': errors.dni}"
                                        />
                                        <div class="invalid-feedback">{{errors.dni}}</div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="names">Nombres</label>
                                        <Field 
                                            id="names"
                                            name="names"
                                            class="form-control" 
                                            v-model="form.names"
                                            :class="{'is-invalid': errors.names}"
                                        />
                                        <div class="invalid-feedback">{{errors.names}}</div>
                                    </div>
                                
                                    <div class="from-group col-2">
                                        <label for="society">Sociedad</label>
                                        <Field
                                            name="society"
                                            class="form-control" 
                                            v-model="form.society" 
                                            id="society"
                                            :class="{ 'is-invalid': errors.society }"
                                        />
                                        <div class="invalid-feedback">{{errors.society}}</div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-row">
                                    <div class="form-group col-4">
                                        <label for="area">Tipo empleado</label>
                                        <Field
                                            id="area"
                                            name="area"
                                            class="form-control"
                                            v-model="form.area"
                                            :class="{'is-invalid': errors.area}" 
                                        />
                                        <div class="invalid-feedback">{{errors.area}}</div>
                                    </div>

                                    <div class="form-group col-4">
                                        <label for="direction">Dirección</label>
                                        <Field
                                            id="direction"
                                            name="direction"
                                            class="form-control"
                                            v-model="form.direction"
                                            :class="{'is-invalid': errors.direction}" 
                                        />
                                        <div class="invalid-feedback">{{errors.area}}</div>
                                    </div>

                                    <div class="form-group col-4">
                                        <label for="position">Posición</label>
                                        <Field
                                            id="position"
                                            name="position"
                                            class="form-control"
                                            v-model="form.position_description"
                                            :class="{'is-invalid': errors.position_description}" 
                                        />
                                        <div class="invalid-feedback">{{errors.position_description}}</div>
                                    </div>
                                
                                    <div class="from-group col-4">
                                        <label for="unit">Unidad Organizativa</label>
                                        <Field
                                            name="unit"
                                            class="form-control" 
                                            v-model="form.organizational_unit" 
                                            id="unit"
                                            :class="{ 'is-invalid': errors.organizational_unit }"
                                        />
                                        <div class="invalid-feedback">{{errors.organizational_unit}}</div>
                                    </div>

                                    <div class="form-group col-2">
                                        <label for="start">Fecha Inicio</label>
                                        <Field 
                                            id="start"
                                            name="start"
                                            class="form-control" 
                                            type="date"
                                            v-model="form.start_date"
                                            :class="{'is-invalid': errors.start_date}"
                                        />
                                        <div class="invalid-feedback">{{errors.start_date}}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-1">
                                        <i class="bi bi-floppy"></i>
                                        Guardar datos
                                    </button>
                                    <button type="reset" class="btn btn-secondary">Resetear formulario</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>