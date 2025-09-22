<script setup>
import axios from "axios";
import { reactive, onMounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToastr } from "@/toastr";
import { Form } from "vee-validate";
import "select2/dist/css/select2.css";
import $ from "jquery";
import "select2";

const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const status = ref([]);
const instructiondegrees = ref([]);
const insurancecarriers = ref([]);
const bloodgroup = ref([]);
const form = reactive({
    numberHistory: "",
    dni: "",
    names: "",
    sex: "",
    birthday: "",
    telephone: "",
    email: "",
    s_id: "",
    bg_id: "",
    foreign_ubigeo: "",
    ubigeo_birth: "",
    ubigeo_residence: "",
    id_id: "",
    occupation_id: "",
    address: "",
    ic_id: "",
    surgical_history: "",
    pathological_history: "",
    physiological_history: "",
    family_history: "",
    rams: "",
    user_id: "",
});
const options = ref([]);
const editMode = ref(false);

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editAppointment(values, actions);
    } else {
        createAppointment(values, actions);
    }
};

const getStatus = () => {
    axios(`/api/status`).then((response) => {
        status.value = response.data;
    });
};

const getInstructionDegrees = () => {
    axios(`/api/instructional-degrees`).then((response) => {
        instructiondegrees.value = response.data;
    });
};

const getBloodGroup = () => {
    axios(`/api/blood-groups`).then((response) => {
        bloodgroup.value = response.data;
    });
};

const getInsuranceCarriers = () => {
    axios(`/api/insurance-carriers`).then((response) => {
        insurancecarriers.value = response.value;
    });
};
const createAppointment = (values, actions) => {
    axios
        .post("/api/appointments/create", form)
        .then((response) => {
            router.push("/admin/appointments");
            toastr.success(response.data.message);
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
};

const editAppointment = (values, actions) => {
    axios
        .put(`/api/appointments/${route.params.id}/edit`, form)
        .then((response) => {
            router.push("/admin/appointments");
            toastr.success(response.data.message);
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
};

const getAppointment = () => {
    axios.get(`/api/appointments/${route.params.id}/edit`).then(({ data }) => {
        form.title = data.title;
        form.client_id = data.client_id;
        form.start_time = data.formatted_start_time;
        form.end_time = data.formatted_end_time;
        form.description = data.description;
    });
};

onMounted(() => {
    if (route.name === "admin.appointments.edit") {
        editMode.value = true;
        getAppointment();
    }

    getStatus();
    getInstructionDegrees();
    getBloodGroup();
    getInsuranceCarriers();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Actualizar</span>
                        <span v-else>Nueva</span>
                        Historia
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard"
                                >Home</router-link
                            >
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/histories"
                                >Historias</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
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
                        <div class="card-header">
                            <h3 class="card-title">Filiación</h3>
                        </div>
                        <Form
                            @submit="handleSubmit()"
                            v-slot:default="{ errors }"
                        >
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>DNI</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                id="dni"
                                                v-model="form.dni"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombres</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="names"
                                                v-model="form.names"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Sexo</label>
                                            <select
                                                class="form-control"
                                                id="sexo"
                                                v-model="form.sex"
                                                required
                                            >
                                                <option value="M">
                                                    Masculino
                                                </option>
                                                <option value="F">
                                                    Femenino
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha Nacimiento</label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="birthday"
                                                v-model="form.birthday"
                                                onchange="getAge(this.value);"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Edad</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="edad"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Celular / Teléfono</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="celular"
                                                v-model="form.telephone"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>E-mail</label>
                                            <input
                                                type="email"
                                                class="form-control"
                                                v-model="form.email"
                                                id="email"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Estado civil</label>
                                            <select
                                                class="form-control"
                                                id="s_id"
                                                v-model="form.s_id"
                                                required
                                            >
                                                <option value="">
                                                    -- Seleccione --
                                                </option>
                                                <option
                                                    v-for="s in status"
                                                    :key="s.id"
                                                    :value="s.id"
                                                >
                                                    {{ s.description }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Grado instrucción</label>
                                            <select
                                                class="form-control"
                                                id="id_id"
                                                v-model="form.id_id"
                                                required
                                            >
                                                <option value="">
                                                    -- Seleccione --
                                                </option>
                                                <option
                                                    v-for="ids in instructiondegrees"
                                                    :key="ids.id"
                                                    :value="ids.id"
                                                >
                                                    {{ ids.description }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Grupo sanguíneo</label>
                                            <select
                                                class="form-control"
                                                id="bg_id"
                                                v-model="form.bg_id"
                                                required
                                            >
                                                <option value="">
                                                    -- Seleccione --
                                                </option>
                                                <option
                                                    v-for="bg in bloodgroup"
                                                    :key="bg.id"
                                                    :value="bg.id"
                                                >
                                                    {{ bg.description }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ocupación</label>
                                            <select
                                                class="form-control buscarOcupacion"
                                                id="ocupacion"
                                                v-model="form.occupation_id"
                                                required
                                            ></select>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <br />
                                            <button
                                                type="button"
                                                class="btn btn-warning extra"
                                                v-on:click=""
                                            >
                                                <i class="fa fa-globe"></i>
                                                Paciente extranjero
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-success pe d-none"
                                            >
                                                <i class="fa fa-globe"></i>
                                                Paciente nacional
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group nacional">
                                            <label>Lugar Nacimiento: </label>
                                            <Select2
                                                v-model="form.ubigeo_birth"
                                                :options="myOptions"
                                                :settings="{
                                                    settingOption: value,
                                                    settingOption: value,
                                                }"
                                                @change="myChangeEvent($event)"
                                                @select="mySelectEvent($event)"
                                            />
                                            <h4>Value: {{ myValue }}</h4>
                                        </div>
                                        <div class="form-group foreign d-none">
                                            <label>Ubigeo extranjero</label>
                                            <input
                                                class="form-control"
                                                id="foreign_ubigeo"
                                                v-model="form.foreign_ubigeo"
                                                placeholder="PAÍS, REGIÓN, CIUDAD"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Lugar Residencia: </label>

                                            <select
                                                class="form-control buscarUbigeoR"
                                                id="ubigeo_residence"
                                                v-model="form.ubigeo_residence"
                                                required
                                            ></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="direccion"
                                                name="direccion"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo de seguro</label>
                                            <select
                                                class="form-control"
                                                id="seguro"
                                                name="seguro"
                                                required
                                            >
                                                <option value="">
                                                    -- Seleccione --
                                                </option>
                                                <option
                                                    v-for="isc in insurancecarriers"
                                                    :key="isc.id"
                                                    :value="isc.id"
                                                >
                                                    {{ isc.description }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Número del Seguro</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="numseguro"
                                                name="numseguro"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                >Antecedentes quirúrgicos</label
                                            >
                                            <textarea
                                                class="form-control"
                                                id="aquirurgico"
                                                name="aquirurgico"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                >Antecedentes patológicos</label
                                            >
                                            <textarea
                                                class="form-control"
                                                id="apatologico"
                                                name="apatologico"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                >Antecedentes
                                                fisiológicos</label
                                            >
                                            <textarea
                                                class="form-control"
                                                id="afisioligico"
                                                name="afisioligico"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                >Antecedentes familiares</label
                                            >
                                            <textarea
                                                class="form-control"
                                                id="afamiliar"
                                                name="afamiliar"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>RAMS</label>
                                            <textarea
                                                class="form-control"
                                                id="rams"
                                                name="rams"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div
                                    class="d-grid gap-2 d-md-flex justify-content-md-end"
                                >
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        <i class="bi bi-floppy"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
