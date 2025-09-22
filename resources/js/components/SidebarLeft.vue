<script setup>
import { useAuthUserStore } from "@/stores/AuthUserStore";
import { useRouter } from "vue-router";
import { useSettingStore } from "../stores/SettingStore";

const router = useRouter();
const authUserStore = useAuthUserStore();
const settingStore = useSettingStore();

const logout = () => {
    axios.post("/logout").then((response) => {
        authUserStore.user.name = "";
        router.push("/login");
    });
};
</script>

<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/admin/dashboard" class="brand-link">
            <img
                src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
                alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span class="brand-text font-weight-light">{{
                settingStore.setting.app_name
            }}</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="authUserStore.user.avatar"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{
                        authUserStore.user.name
                    }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <li class="nav-item">
                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/incidences" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-exclamation-triangle-fill"></i>
                            <p>Incidencias</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/users" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-people-fill"></i>
                            <p>Usuarios</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/staff" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-people"></i>
                            <p>Planilla</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/settings" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-gear-wide-connected"></i>
                            <p>Configuración</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/profile" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-person-gear"></i>
                            <p>Prefil</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/maintenance" active-class="active" class="nav-link">
                            <i class="nav-icon fa bi bi-house-gear-fill"></i>
                            <p>Mantenimiento</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <form class="nav-link">
                            <a href="#" @click.prevent="logout">
                                <i
                                    class="nav-icon fa bi bi-box-arrow-right"
                                ></i>
                                <p>Logout</p>
                            </a>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>