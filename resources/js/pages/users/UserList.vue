<script setup>
import axios from "axios";
import { ref, onMounted, reactive, watch } from "vue";
import { Form, Field } from "vee-validate";
import * as yup from "yup";
import { useToastr } from "@/toastr.js";
import UserListItem from "./UserListItem.vue";
import { debounce } from "lodash";

const toastr = useToastr();
const users = ref({ data: [] });
const editing = ref(false);
const formValues = ref();
const form = ref(null);

const formData = reactive({
    id: "",
    name: "",
    password: "",
});

const searchQuery = ref(null);
const getUsers = (page = 1) => {
    axios.get(`/api/users?page=${page}`, {
        params: {
            query: searchQuery.value,
        },
    }).then((response) => {
        users.value = response.data;
        selectAllUsers.value = [];
        selectAll.value = false;
    });
};

/*const createUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(6),
});*/

/*const editeUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().when((password, schema) => {
        return password ? schema.required().min(8) : schema;
    }),
});*/

const createUser = (values, { resetForm, setErrors }) => {
    axios.post("/api/users", values).then((response) => {
        users.value.data.unshift(response.data);
        $("#userFormModal").modal("hide");
        resetForm();
        toastr.success(response.data.message);
    }).catch((error) => {
        if (error.response.data.errors) {
            setErrors(error.response.data.errors);
        }
    });
};

const editUser = (user) => {
    editing.value = true;
    formData.name = user.name;
    formData.email = user.email;
    formData.id = user.id;
    $("#userFormModal").modal("show");
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email,
    };
};

const addUser = () => {
    editing.value = false;
    form.value.resetForm();
    $("#userFormModal").modal("show");
};

const updateUser = (values, { setErrors }) => {
    axios.put("/api/users/" + formValues.value.id, values).then((response) => {
        const index = users.value.data.findIndex(
            (user) => user.id === response.data.id
        );
        users.value.data[index] = response.data;
        $("#userFormModal").modal("hide");
        toastr.success(response.data.message);
    }).catch((error) => {
        setErrors(error.response.data.errors);
        console.log(error);
    });
};

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateUser(values, actions);
    } else {
        createUser(values, actions);
    }
};

const selectedUsers = ref([]);
const toggleSelection = (user) => {
    const index = selectedUsers.value.indexOf(user.id);
    if (index === -1) {
        selectedUsers.value.push(user.id);
    } else {
        selectedUsers.value.splice(index, 1);
    }
};

const userIdBeingDeleted = ref(null);
const confirmUserDeletion = (id) => {
    userIdBeingDeleted.value = id;
    $("#deleteUserModal").modal("show");
};

const deleteUser = () => {
    axios.delete(`/api/users/${userIdBeingDeleted.value}`).then((response) => {
        $("#deleteUserModal").modal("hide");
        toastr.success(response.data.message);
        users.value.data = users.value.data.filter(
            (user) => user.id !== userIdBeingDeleted.value
        );
    });
};

const bulkDelete = () => {
    axios.delete("/api/users", {
        data: {
            ids: selectedUsers.value,
        },
    }).then((response) => {
        users.value.data = users.value.data.filter(
            (user) => !selectedUsers.value.includes(user.id)
        );
        selectedUsers.value = [];
        selectAll.value = false;
        toastr.success(response.data.message);
    });
};

const selectAll = ref(false);
const selectAllUsers = () => {
    if (selectAll.value) {
        selectedUsers.value = users.value.data.map((user) => user.id);
    } else {
        selectedUsers.value = [];
    }
};

watch(
    searchQuery,
    debounce(() => {
        getUsers();
    }, 300)
);

onMounted(() => {
    getUsers();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button
                        @click="addUser"
                        type="button"
                        class="mb-2 btn btn-primary"
                    >
                        <i class="fa bi bi-plus-circle-fill mr-1"></i>
                        Nuevo Usuario
                    </button>
                    <div v-if="selectedUsers.length > 0">
                        <button
                            v-if="selectedUsers.length > 0"
                            @click="bulkDelete"
                            type="button"
                            class="ml-2 mb-2 btn btn-danger"
                        >
                            <i class="fa fa-trash mr-1"></i>
                            Borrar usuarios seleccionados
                        </button>
                        <span class="ml-2"
                            >{{ selectedUsers.length }} usuarios
                            seleccionados</span
                        >
                    </div>
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
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>
                                    <input
                                        type="checkbox"
                                        v-model="selectAll"
                                        @change="selectAllUsers"
                                    />
                                </th>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="users.data.length > 0">
                            <UserListItem
                                v-for="(user, index) in users.data"
                                :key="user.id"
                                :user="user"
                                :index="index"
                                @edit-user="editUser"
                                @confirm-user-deletion="confirmUserDeletion"
                                @toggle-selection="toggleSelection"
                                :select-all="selectAll"
                            />
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">
                                    Ningún resultado encontrado...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Bootstrap4Pagination
                        :data="users"
                        @pagination-change-page="getUsers"
                    ></Bootstrap4Pagination>
                </div>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="userFormModal"
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
                        <span v-if="editing">Actualizar datos de usuario</span>
                        <span v-else>Añadir nuevo usuario</span>
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
                    @submit="handleSubmit"
                    :validation-schema="
                        editing ? editeUserSchema : createUserSchema
                    "
                    v-slot="{ errors }"
                    :initial-values="formValues"
                >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombres</label>
                            <Field
                                name="name"
                                v-model="formData.name"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.name }"
                                id="name"
                                placeholder="Ingresa los nombres completos"
                            />
                            <span class="invalid-feedback">{{
                                errors.name
                            }}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field
                                name="email"
                                v-model="formData.email"
                                type="email"
                                class="form-control"
                                :class="{ 'is-invalid': errors.email }"
                                id="email"
                                placeholder="Ingresa email"
                            />
                            <span class="invalid-feedback">{{
                                errors.email
                            }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <Field
                                name="password"
                                v-model="formData.password"
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': errors.password }"
                                id="password"
                                placeholder="Enter password"
                            />
                            <span class="invalid-feedback">{{
                                errors.password
                            }}</span>
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
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteUserModal" style="display: none" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span>Delete User</span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure to delete this user?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">
                        Borrar usuario
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
