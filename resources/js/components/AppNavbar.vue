<script setup>
import { useSettingStore } from "../stores/SettingStore";

const settingStore = useSettingStore();
</script>
<template>
    <nav
        class="main-header navbar navbar-expand"
        :class="settingStore.theme === 'dark' ? 'navbar-dark' : 'navbar-light'"
    >
        <ul class="navbar-nav">
            <li class="nav-item" id="toggleMenuIcon">
                <a
                    class="nav-link"
                    data-widget="pushmenu"
                    href="#"
                    role="button"
                    ><i class="fas bi bi-list"></i
                ></a>
            </li>

            <li class="nav-item d-none d-sm-inline-block">
                <a 
                    @click.prevent="settingStore.changeTheme" 
                    href="#" 
                    class="nav-link"
                >
                    <i
                        class="far"
                        :class="
                            settingStore.theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill'
                        "
                    ></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far bi bi-bell-fill"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas bi bi-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm"
                            >3 mins</span
                        >
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas bi bi-people mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm"
                            >12 hours</span
                        >
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas bi bi-file-earmark mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm"
                            >2 days</span
                        >
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer"
                        >See All Notifications</a
                    >
                </div>
            </li>
            <!--<li class="nav-item">
                <a
                    class="nav-link"
                    data-widget="fullscreen"
                    href="#"
                    role="button"
                >
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>-->
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-widget="control-sidebar"
                    data-slide="true"
                    href="#"
                    role="button"
                >
                    <i class="fas bi bi-microsoft"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>
