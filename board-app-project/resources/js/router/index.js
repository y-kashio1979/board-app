import { createRouter, createWebHistory } from 'vue-router'
import Regist from '../users/regist.vue'

import Login from '../users/Login.vue'
import ThreadShow from '../threads/ThreadShow.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path:'/register',
        name:'Regist',
        component: Regist
    },
    {
        path:'/threads/:id',
        name:'ThreadShow',
        component: ThreadShow
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes : routes,
})

export default router