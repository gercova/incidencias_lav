<template>
    <div>
        <select ref="select" id="item-search" style="width: 100%;"></select>
    </div>
</template>
<script>
import { onMounted, ref, watch } from 'vue';
import $ from 'jquery';
import 'select2/dist/css/select2.css';
import 'select2';

export default {
    props: {
        modelValue: {
            type: [String, Number],
            required: true
        }
    },
    
    setup(props, { emit }) {

        const select = ref(null);

        onMounted(() => {
            $('#item-search').select2({
                placeholder: 'Buscar un usuario',
                minimumInputLength: 3,
                ajax: {
                    url: '/api/staff/search',
                    dataType: 'json',
                    delay: 200,
                    data: function (params) {
                        return {
                            query: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true,
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    if (data.loading) {
                        return data.text;
                    }
                    var markup = "<option value='" + data.id + "'>" + data.names + "</option>";
                    return markup;
                },
                templateSelection: function(data) {
                    return data.names || data.id;
                },
            });

            $(select.value).on('change', function () {
                emit('update:modelValue', $(this).val());
            });

            $(select.value).val(props.modelValue).trigger('change');
        });

        watch(() => props.modelValue, (newValue) => {
            $(select.value).val(newValue).trigger('change');
        });

        return {
            select
        };
    },
};
</script>