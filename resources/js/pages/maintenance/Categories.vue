<script setup>
import axios from "axios";
import { ref, onMounted, reactive, watch } from "vue";
import { Form, Field } from "vee-validate";
import * as yup from "yup";
import { useToastr } from "@/toastr";
import { debounce } from "lodash";

const toastr = useToastr();
const categories = ref({ data: [] });
const editing = ref(false);
const searchQuery = ref(null);
const formValues = ref();
const form = ref(null);
const categoryIdBeingDeleted = ref(null);

const formData = reactive({
    id: "",
    description: "",
    aka: "",
});

const getCategories = (page = 1) => {
    axios.get(`/api/categories?page=${page}`, {
        params: {
            query: searchQuery.value,
        },
    }).then((response) => {
        categories.value = response.data;
    });
};

const validateCategorySchema = yup.object({
    description: yup.string().required(),
});

const addCategory = () => {
    editing.value = false;
    form.value.resetForm();
    $("#categoryFormModal").modal("show");
};

const editCategory = (category) => {
    editing.value = true;
    formData.description = category.description;
    formData.aka = category.aka;
    formData.id = category.id;
    $("#categoryFormModal").modal("show");
    formValues.value = {
        id: category.id,
        description: category.description,
        aka: category.aka,
    };
};

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateCategory(values, actions);
    } else {
        createCategory(values, actions);
    }
};

const createCategory = (values, { resetForm, setErrors }) => {
    axios.post(`/api/categories`, values).then((response) => {
        categories.value.data.unshift(response.data.category);
        $("#categoryFormModal").modal("hide");
        resetForm();
        toastr.success("Categoría creada exitosamente");
    }).catch((error) => {
        if (error.response.data.errors) {
            setErrors(error.response.data.errors);
        }
    });
};

const updateCategory = (values, { setErrors }) => {
    axios.put(`/api/categories/` + formValues.value.id, values)
    .then((response) => {
        const index = categories.value.data.findIndex(
            (category) => category.id === response.data.category.id
        );
        categories.value.data[index] = response.data.category;
        $("#categoryFormModal").modal("hide");
        toastr.success("Categoría actualizada exitosamente");
    })
    .catch((error) => {
        console.log(error);
        setErrors(error.response.data.errors);
    });
};

const confirmCategoryDeletion = (id) => {
    categoryIdBeingDeleted.value = id;
    $("#deleteCategoryModal").modal("show");
};

const deleteCategory = () => {
    axios.delete(`/api/categories/${categoryIdBeingDeleted.value}`).then(() => {
        $("#deleteCategoryModal").modal("hide");
        toastr.success("Categoría eliminada exitosamente");
        categories.value.data = categories.value.data.filter(
            (category) => category.id !== categoryIdBeingDeleted.value
        );
    });
};

watch(
    searchQuery,
    debounce(() => {
        getCategories();
    }, 300)
);

onMounted(() => {
    getCategories();
});
</script>
<template>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <button
                @click="addCategory"
                type="button"
                class="mb-2 btn btn-success"
            >
                <i class="bi bi-plus-circle-fill mr-1"></i>
                Nueva Categoría
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
        <table
            class="table table-striped table-hover table-sm"
            id="historiaTable"
        >
            <thead>
                <tr>
                    <th scope="col" style="width: 5%">#</th>
                    <th scope="col" style="width: 45%">Descripción</th>
                    <th scope="col" style="width: 25%">Alias</th>
                    <th scope="col" style="width: 20%">Opciones</th>
                </tr>
            </thead>
            <tbody v-if="categories.data.length > 0">
                <tr
                    v-for="(category, index) in categories.data"
                    :key="category.id"
                >
                    <td>{{ index + 1 }}</td>
                    <td>{{ category.description }}</td>
                    <td>{{ category.aka }}</td>
                    <td>
                        <a
                            href="#"
                            class="bi bi-pencil-square mb-1 ml-1 text-primary"
                            @click.prevent="editCategory(category)"
                        ></a>
                        <a
                            href="#"
                            class="bi bi-trash mb-1 ml-1 text-danger"
                            @click.prevent="
                                confirmCategoryDeletion(category.id)
                            "
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
            :data="categories"
            @pagination-change-page="getCategories"
        />
    </div>
    <div
        class="modal fade"
        id="categoryFormModal"
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
                        <span v-if="editing">Actualizar Categoría</span>
                        <span v-else>Añadir nueva categoría</span>
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
                    :validation-schema="validateCategorySchema"
                    v-slot="{ errors }"
                    :initial-values="formValues"
                    autocomplete="off"
                >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <Field
                                name="description"
                                v-model="formData.description"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.description }"
                                id="description"
                                placeholder="Ingresa la descripción de la categoría"
                            />
                            <label for="aka">Alias</label>
                            <Field
                                name="aka"
                                v-model="formData.aka"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.aka }"
                                id="aka"
                                placeholder="Ingresa el alias de la categoría"
                            />
                            <span class="invalid-feedback">{{
                                errors.aka
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
                            <i class="bi bi-floppy"></i>
                            Guardar datos
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="deleteCategoryModal"
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
                        <span>Eliminar categoría</span>
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
                <div class="modal-body">
                    <h5>¿Estás seguro de borrar este ítem?</h5>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Cancelar
                    </button>
                    <button
                        @click.prevent="deleteCategory"
                        type="button"
                        class="btn btn-primary"
                    >
                        Borrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
