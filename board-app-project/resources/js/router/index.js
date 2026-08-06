import { createRouter, createWebHistory } from 'vue-router'

import Login from '../users/Login.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes : routes,
})

export default router