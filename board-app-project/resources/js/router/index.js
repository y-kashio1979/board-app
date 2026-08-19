import { createRouter, createWebHistory } from 'vue-router'
import Regist from '../users/regist.vue'

import Login from '../users/Login.vue'
import ThreadShow from '../threads/ThreadShow.vue'
import CreateThread from '../threads/CreateThread.vue'
import ThreadList from '../threads/threadList.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path:'/user/create',
        name:'Regist',
        component: Regist
    },
    {
        path:'/threads/:id',
        name:'ThreadShow',
        component: ThreadShow
    },
    {
        path:'/threads/create',
        name:'CreateThread',
        component: CreateThread
    },
    {
        path:'/threads',
        name:'ThreadList',
        component: ThreadList
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes : routes,
})

export default router