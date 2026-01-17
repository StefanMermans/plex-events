import { createRouter, createWebHistory } from "vue-router";

import TestView from './views/TestView.vue'
import HomeView from './views/HomeView.vue'
import RegisterView from './views/RegisterView.vue'
import LoginView from './views/LoginView.vue'

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
    },
    {
        path: "/login",
        name: "login",
        component: LoginView
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
});