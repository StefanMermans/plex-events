import { createRouter, createWebHistory } from "vue-router";

import TestView from './views/TestView.vue'
import HomeView from './views/HomeView.vue'
import RegisterView from './views/RegisterView.vue'

const routes = [
    {
        path: "/",
        name: "home",
        component: HomeView
    },
    {
        path: "/test",
        name: "test",
        component: TestView
    },
    {
        path: "/register",
        name: "register",
        component: RegisterView
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
});