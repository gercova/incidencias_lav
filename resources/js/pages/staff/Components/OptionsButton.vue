<script>
import { onMounted, inject } from 'vue'
import Swal from 'sweetalert2'
export default {

    setup(props){
        let {value} = props.params;
        return {value }
    },
}
</script>
<template>
    <div>
        <router-link
            class="btn btn-warning btn-xs"
            :to="`/admin/staff/${value}/edit`"
        >
            <i class="bi bi-pencil-square mr-1"></i>
            Editar
        </router-link>
        &nbsp;
    </div>
</template>