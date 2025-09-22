<template>
    <div v-if="isVisible">
        <div class="modal-content" role="document">
            <div class="modal-content">
                <div class="loader"></div>
                <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                <p>Cargando...</p>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            isVisible: {
                type: Boolean,
                required: true,
            },
        },
    };
</script>