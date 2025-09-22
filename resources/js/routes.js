import Dashboard from "./components/Dashboard.vue";
import IncidencesList from "./pages/incidences/IncidencesList.vue";
import IncidenceForm from "./pages/incidences/IncidenceForm.vue";
import StaffList from "./pages/staff/StaffList.vue";
import StaffForm from "./pages/staff/StaffForm.vue";
import UserList from "./pages/users/UserList.vue";
import UpdateSetting from "./pages/settings/UpdateSetting.vue";
import UpdateProfile from "./pages/profile/UpdateProfile.vue";
import Login from "./pages/auth/Login.vue";
import Maintenance from "./pages/maintenance/Maintenance.vue";
export default [
    {
        path: "/login",
        name: "admin.login",
        component: Login,
    },
    {
        path: "/",
        name: "admin.dashboard",
        component: Dashboard,
    },
    {
        path: "/admin/dashboard",
        name: "admin.dashboard",
        component: Dashboard,
    },
    {
        path: "/admin/staff",
        name: "admin.staff",
        component: StaffList,
    },
    {
        path: "/admin/staff/create",
        name: "admin.staff.create",
        component: StaffForm,
    },
    {
        path: "/admin/staff/:id/edit",
        name: "admin.staff.edit",
        component: StaffForm,
    },
    {
        path: "/admin/users",
        name: "admin.users",
        component: UserList,
    },
    {
        path: "/admin/settings",
        name: "admin.settings",
        component: UpdateSetting,
    },
    {
        path: "/admin/profile",
        name: "admin.profile",
        component: UpdateProfile,
    },
    {
        path: "/admin/maintenance",
        name: "admin.maintenance",
        component: Maintenance,
    },
    {
        path: "/admin/incidences",
        name: "admin.incidences",
        component: IncidencesList,
    },
    {
        path: "/admin/incidences/create",
        name: "admin.incidences.create",
        component: IncidenceForm,
    },
    {
        path: "/admin/incidences/:id/edit",
        name: "admin.incidences.edit",
        component: IncidenceForm,
    },
];
