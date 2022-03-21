import { RouteRecordRaw } from "vue-router";
import Teams from "../pages/Teams.vue";
import Projects from "../pages/Projects.vue";

export const routes: RouteRecordRaw[] = [
  {
    path: "/",
    redirect: "/teams",
  },
  {
    path: "/teams",
    component: Teams,
  },
  {
    path: "/projects",
    component: Projects,
  },
];

export default routes;
