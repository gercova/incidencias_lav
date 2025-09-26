import './bootstrap';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';
//import Login from './pages/auth/Login.vue';
import App from './App.vue';
import { useAuthUserStore } from './stores/AuthUserStore';
import { useSettingStore } from './stores/SettingStore';
import { Bootstrap4Pagination } from "laravel-vue-pagination";
//import Select2 from "vue3-select2-component";
//import MultiSelect from "@vueform/multiselect";
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import Swal from 'sweetalert2';

const pinia = createPinia();
const app = createApp(App);

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

router.beforeEach(async (to, from) => {
    const authUserStore = useAuthUserStore();
    if (authUserStore.user.name === '' && to.name !== 'admin.login') {
        const settingStore = useSettingStore();
        await Promise.all([
            authUserStore.getAuthUser(),
            settingStore.getSetting(),
        ]);
    }
});

app.use(pinia);
app.use(router);
app.component('Bootstrap4Pagination', Bootstrap4Pagination);
//app.component('Select2', Select2);
//app.component('MultiSelect', MultiSelect);
app.component('AgGridVue', AgGridVue);
app.component('Swal', Swal);
app.mount('#app');
