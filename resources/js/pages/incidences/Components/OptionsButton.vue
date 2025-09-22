<script>
import Swal from 'sweetalert2'
export default {
  setup(props, context){
      
    let {value} = props.params;

    context.emit("deleteIncidence", value)
    
    function alertClick(){
      Swal.fire({
        title: "¿Estás seguro de continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/api/activities/${id}`).then((response) => {
            activities.value.data = activities.value.data.filter(
              (occupation) => occupation.id != id
            );
            Swal.fire("¡Hecho!", response.data.message, "success");
          });
        }
      });
    }
    return {value, alertClick }
  },
  methods: {
    deleItem(id){
      this.$emit('deleteIncidence', id);
    },
  }
}
</script>
<template>
  <div>
    <router-link
      class="btn btn-warning btn-xs"
      :to="`/admin/incidences/${value}/edit`"
    >
      <i class="bi bi-pencil-square mr-1"></i>Actualizar
    </router-link>
    &nbsp;
  </div>
</template>

<!--
  <template>
    <div>
      <Select2
        v-model="myValue"
        :options="myOptions"
        :settings="settings"
        @change="myChangeEvent"
        @select="mySelectEvent"
      />
      <h4>Value: {{ myValue }}</h4>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import Select2 from 'vue3-select2-component';
  
  export default {
    components: { Select2 },
    setup() {
      const myValue = ref('');
      const myOptions = ref([]);
      const settings = ref({
        placeholder: 'Select an option',
        // Otros settings para Select2
      });
  
      const fetchOptions = async () => {
        try {
          const response = await fetch('/api/staff');
          const data = await response.json();
          myOptions.value = data.map(item => ({
            id: item.id,
            text: item.text,
          }));
        } catch (error) {
          console.error('Error fetching options:', error);
        }
      };
  
      onMounted(() => {
        fetchOptions();
      });
  
      const myChangeEvent = (val) => {
        console.log(val);
      };
  
      const mySelectEvent = ({ id, text }) => {
        console.log({ id, text });
      };
  
      return {
        myValue,
        myOptions,
        settings,
        myChangeEvent,
        mySelectEvent,
      };
    },
  };
  </script>
  
  <style scoped>
  /* Puedes agregar estilos adicionales aquí si es necesario */
  </style>-->