<!-- resources/js/components/StaffSearch.vue -->

<template>
    <div>
        <Multiselect
            v-model="selectedValue"
            :options="options"
            :searchable="true"
            :min-chars="3"
            :delay="200"
            placeholder="Buscar un usuario"
            @search-change="fetchOptions"
            label="names"
            value-prop="id"
        />
    </div>
</template>

<script>
import { ref, watch } from 'vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

export default {
    name: 'StaffSearch',
    components: { Multiselect },
    props: {
        modelValue: {
            type: [String, Number],
            default: null
        }
    },
    
    emits: ['update:modelValue'],

    setup(props, { emit }) {
        const selectedValue = ref(props.modelValue);
        const options = ref([]);
        const fetchOptions = async (query) => {
            if (!query || query.length < 3) return;
            try {
                const response = await fetch(`/api/staff/search?query=${encodeURIComponent(query)}`);
                const data = await response.json();
                options.value = data;
            } catch (error) {
                console.error('Error:', error);
                options.value = [];
            }
        };

        watch(selectedValue, (newValue) => {
            emit('update:modelValue', newValue);
        });

        watch(() => props.modelValue, (newValue) => {
            selectedValue.value = newValue;
        });

        return { selectedValue, options, fetchOptions };
    }
};
</script>
<!--<template>
    <div>
        <Multiselect
            v-model="selectedValue"
            :options="options"
            :searchable="true"
            :min-chars="3"
            :delay="200"
            placeholder="Buscar un usuario"
            @search-change="fetchOptions"
            label="names"
            value-prop="id"
        />
    </div>
</template>

<script>
import { ref, watch } from 'vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

export default {
    components: { Multiselect },
    props: {
        modelValue: [String, Number]
    },
    
    setup(props, { emit }) {
        const selectedValue = ref(props.modelValue);
        const options = ref([]);

        const fetchOptions = async (query) => {
            if (!query || query.length < 3) return;
            
            try {
                const response = await fetch(`/api/staff/search?query=${encodeURIComponent(query)}`);
                const data = await response.json();
                options.value = data;
            } catch (error) {
                console.error('Error:', error);
                options.value = [];
            }
        };

        watch(selectedValue, (newValue) => {
            emit('update:modelValue', newValue);
        });

        watch(() => props.modelValue, (newValue) => {
            selectedValue.value = newValue;
        });

        return { selectedValue, options, fetchOptions };
    }
};
</script>-->