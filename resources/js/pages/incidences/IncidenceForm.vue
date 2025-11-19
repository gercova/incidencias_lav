<script setup>
import axios from "axios";
import { reactive, onMounted, ref, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToastr } from "@/toastr";
import { Form, Field } from "vee-validate";
//import Search from "./Components/Search.vue";
import StaffSearch from "./Components/MultiSelect.vue";
import { method } from "lodash";
//import VueMultiselect from '@vueform/multiselect';

//import MultiSelectApps from "./Components/MultiSelectApps.vue";
import Multiselect from "vue-multiselect";

const router    = useRouter();
const route     = useRoute();
const toastr    = useToastr();
const editMode  = ref(false);
const loadClass = ref('bi bi-floppy');
const form      = reactive({
    staffId: "",
    created_at: "",
    incidenceCategoryId: "",
    typeIncidenceId: "",
    description: "",
    solution: "",
    selectedApp: "",
    selectedItems: []
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editIncidence(values, actions);
    } else {
        createIncidence(values, actions);
    }
};

const createIncidence = async (values, actions) => {
    loadClass.value = 'spinner-border spinner-border-sm';
    try {
        await axios.post("/api/incidences/create", form).then((response) => {
            router.push("/admin/incidences");
            toastr.success(response.data.message);
        }).catch((error) => {
            actions.setErrors(error.response.data.errors);
            loadClass.value = 'bi bi-floppy'
        });
    } catch (error) {
        console.error(error);
        toastr.error('Error al guardar los datos');
    }
};

const editIncidence = async (values, actions) => {
    loadClass.value = 'spinner-border spinner-border-sm';
    try {
        await axios.put(`/api/incidences/${route.params.id}/edit`, form)
        .then((response) => {
            router.push("/admin/incidences");
            toastr.success(response.data.message);
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
    } catch (error) {
        console.error(error);
        toastr.error('Error al actualizar los datos');
    }
};

const categories = ref([]);
const getCategories = () => {
    axios.get('/api/incidence-category').then((response) => {
        categories.value = response.data;
    });
};

const levels = ref([]);
const getLevels = () => {
    axios.get('/api/incidence-type').then((response) => {
        levels.value = response.data;
    });
};

/*-------------------------------------------*/
/*
const apps = ref();
const searchApp = ref([]);
const fetchApps = () => {
    axios.get('/api/applications/getApps', {
        params: {
            query: searchApp.value,
        }
    }).then((response) => {
        apps.value = response.data;
    })
};

const selectedItem = ref();
const selectItem = (item) => {
    const found = form.selectedItems.find((el)=>{
        return(el.id == item.id);
    });

    if(found != undefined){
        //alert('Se repite');
        toastr.error('El ítem se repite');
        return;
    }

    form.selectedItems.push({
        id: item.id,
        text: item.text
    });
}

const removeRow = (id) => {
    form.selectedItems.splice(id, 1);
}
*/
/*-------------------------------------------*/
const apps = ref([]);
const selectedItem = ref(null);

const fetchApps = async (query) => {
    if (!query || query.length < 2) return;
    
    try {
        const response = await axios.get('/api/applications/getApps', {
            params: { query }
        });
        apps.value = response.data;
    } catch (error) {
        console.error('Error:', error);
        apps.value = [];
    }
};

const handleSelect = (selectedOption) => {
    if (!selectedOption) return;
    
    // 👇 Verificar duplicados (tu lógica original)
    const found = form.selectedItems.find(el => el.id == selectedOption.id);
    if (found) {
        toastr.error('El ítem se repite');
        selectedItem.value = null; // Limpiar selección
        return;
    }
    
    // 👇 Agregar al array
    form.selectedItems.push({
        id: selectedOption.id,
        text: selectedOption.text
    });
    
    selectedItem.value = null; // Limpiar después de agregar
};

const appsInvolved = ref([]);
const names = ref([]);
const getIncidence = () => {
    axios.get(`/api/incidences/${route.params.id}/edit`).then(({ data }) => {
        console.log(data);
        form.staffId                = data.incidence.staffId;
        form.created_at             = data.incidence.created_at
        form.incidenceCategoryId    = data.incidence.incidenceCategoryId;
        form.typeIncidenceId        = data.incidence.typeIncidenceId;
        form.description            = data.incidence.description;
        form.solution               = data.incidence.solution;
        names.value                 = data.user[0].user;
        appsInvolved.value          = data.apps;
    });
};

const removeRow = (id) => {
    form.selectedItems.splice(id, 1);
}

onMounted(() => {
    if (route.name === "admin.incidences.edit") {
        editMode.value = true;
        getIncidence();
    }

    getCategories();
    getLevels();
    fetchApps();
    removeRow();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Actualizar</span>
                        <span v-else>Crear</span>
                        Incidencia
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
                                >Incidencias</router-link
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
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="form-group col-4">
                                                <label v-if="!editMode">Busque y seleccione un usuario:</label>
                                                <label v-else>Usuario:</label>
                                                
                                                <Field v-if="!editMode" name="staffId" v-slot="{ field, errorMessage }">
                                                    <StaffSearch
                                                        v-model="form.staffId"
                                                        :error="errorMessage"
                                                        v-bind="field"
                                                    />
                                                </Field>
                                                <input v-else class="form-control" v-model="names" readonly>
                                                <input type="hidden" v-model="form.staffId">
                                                <div class="invalid-feedback">{{errors.staffId}}</div>
                                            </div>
                                            <div class="form-group col-4">
                                                <label v-if="!editMode" for="created_at">Ingrese la fecha del incidente</label>
                                                <label v-else for="created_at">Fecha del incidente</label>
                                                <Field
                                                    v-if="!editMode" 
                                                    as="input"
                                                    type="date"
                                                    name="created_at"
                                                    v-model="form.created_at"
                                                    class="form-control"
                                                    :class="{'is-invalid': errors.created_at}"
                                                    id="created_at"
                                                    rows="3"
                                                    placeholder="Ingresa la fecha de la incidencia"
                                                ></Field>
                                                <input v-else type="text" class="form-control" v-model="form.created_at" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="category">Categoría Incidencia</label>
                                        <Field 
                                            as="select" 
                                            name="incidenceCategoryId"
                                            class="form-control" 
                                            v-model="form.incidenceCategoryId" 
                                            id="category"
                                            :class="{'is-invalid': errors.incidenceCategoryId}"
                                        >
                                            <option value="">-- Seleccione --</option>
                                            <option 
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.description }}
                                            </option>
                                        </Field>
                                        <div class="invalid-feedback">{{errors.incidenceCategoryId}}</div>
                                    </div>
                                
                                    <div class="from-group col-4">
                                        <label for="level">Nivel Incidencia</label>
                                        <Field
                                            name="typeIncidenceId"
                                            as="select"
                                            class="form-control" 
                                            v-model="form.typeIncidenceId" 
                                            id="level"
                                            :class="{ 'is-invalid': errors.typeIncidenceId }"
                                        >
                                            <option value="">-- Seleccione --</option>
                                            <option 
                                                v-for="level in levels"
                                                :key="level.id"
                                                :value="level.id"
                                            >
                                                {{ level.description }}
                                            </option>
                                        </Field>
                                        <div class="invalid-feedback">{{errors.typeIncidenceId}}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="description">Descripción</label>
                                        <Field
                                            as="textarea"
                                            name="description"
                                            v-model="form.description"
                                            class="form-control"
                                            :class="{'is-invalid': errors.description}"
                                            id="description"
                                            rows="3"
                                            placeholder="Ingresa la descripción de la incidencia"
                                            required
                                        ></Field>
                                        <div class="invalid-feedback">{{errors.description}}</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="solution">Solución</label>
                                        <Field
                                            as="textarea"
                                            name="solution"
                                            v-model="form.solution"
                                            class="form-control"
                                            :class="{'is-invalid': errors.solution}"
                                            id="solution"
                                            rows="3"
                                            placeholder="Ingresa la solución de la incidencia"
                                            required
                                        ></Field>
                                        <div class="invalid-feedback">{{errors.solution}}</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div v-if="!editMode" class="col-lg-6">
                                        <label for="apps">Buscar aplicaciones afectadas:</label>
                                        
                                        <Multiselect
                                            v-model="selectedItem"
                                            :options="apps"
                                            :searchable="true"
                                            placeholder="Buscar aplicación..."
                                            label="text"
                                            value-prop="id"
                                            @search-change="fetchApps"
                                            @select="handleSelect"
                                        />

                                        <hr>
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Aplicación implicada</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, i) in form.selectedItems" :key="i">
                                                    <td>{{ i + 1 }}</td>
                                                    <td>{{ item.text }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-xs" @click="removeRow(i)">
                                                            <i class="bi bi-trash"></i> Quitar
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="col-lg-6">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Aplicación implicada</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(app, i) in appsInvolved" :key="appsInvolved.id">
                                                    <td>{{ i + 1 }}</td>
                                                    <td>{{ app.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-1">
                                        <span :class="loadClass" aria-hidden="true"></span>
                                        Guardar datos
                                    </button>
                                    <button v-if="editMode" type="reset" class="btn btn-secondary">Resetear formulario</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>