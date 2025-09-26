<template>
    <VueMultiselect
        v-model="selectedItem"
        :options="apps"
        :searchable="true"
        placeholder="Buscar aplicación..."
        label="text"
        value-prop="id"
        @search-change="fetchApps"
        @select="handleSelect"
    />
</template>

<script setup>
import { ref } from 'vue';
import VueMultiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import axios from "axios";
import { useToastr } from "@/toastr.js";

const toastr = useToastr();
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
</script>