<script setup>
import { ref } from "vue";
import { useToastr } from "../../toastr.js";
import axios from "axios";

const toastr = useToastr();

const props = defineProps({
    user: Object,
    index: Number,
    selectAll: Boolean,
});

const emit = defineEmits(["userDeleted", "editUser", "confirmUserDeletion"]);

const roles = ref([
    {
        name: "ADMIN",
        value: 1,
    },
    {
        name: "USER",
        value: 2,
    },
]);
const changeRole = (user, role) => {
    axios.patch(`/api/users/${user.id}/change-role`, {
        role: role,
    }).then(() => {
        toastr.success("Rol cambiado exitosamente");
    });
};

const toggleSelection = () => {
    emit("toggleSelection", props.user);
};
</script>
<template>
    <tr>
        <td>
            <input
                type="checkbox"
                :checked="selectAll"
                @change="toggleSelection"
            />
        </td>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.formatted_created_at }}</td>
        <td>
            <select
                class="form-control-sm"
                @change="changeRole(user, $event.target.value)"
            >
                <option
                    v-for="rol in roles"
                    :value="rol.value"
                    :selected="user.role === rol.name"
                >
                    {{ rol.name }}
                </option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="$emit('editUser', user)"
                ><i class="bi bi-pencil-square"></i
            ></a>
            <a href="#" @click.prevent="$emit('confirmUserDeletion', user.id)"
                ><i class="bi bi-trash text-danger ml-2"></i
            ></a>
        </td>
    </tr>
</template>
