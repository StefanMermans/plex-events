import { createRouter, createWebHistory, type RouteRecordRaw } from "vue-router";

import TestView from './views/TestView.vue'
import HomeView from './views/HomeView.vue'
import RegisterView from './views/RegisterView.vue'
import LoginView from './views/LoginView.vue'
import PlexEventView from './views/PlexEventView.vue'

declare module 'vue-router' {
    interface RouteMeta {
        layout?: 'auth' | 'guest'
    }
}

const routes: readonly RouteRecordRaw[] = [
    {
        path: "/",
        name: "home",
        component: HomeView,
        meta: { layout: 'auth' }
    },
    {
        path: "/test",
        name: "test",
        component: TestView,
        meta: { layout: 'auth' }
    },
    {
        path: "/register",
        name: "register",
        component: RegisterView,
        meta: { layout: 'guest' }
    },
    {
        path: "/login",
        name: "login",
        component: LoginView,
        meta: { layout: 'guest' }
    },
    {
        path: "/plex-event",
        name: "plex-event",
        component: PlexEventView,
        meta: { layout: 'auth' }
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
});