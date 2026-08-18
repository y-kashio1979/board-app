import { createRouter, createWebHistory } from 'vue-router'
import Regist from '../users/regist.vue'
import { useAuthStore } from "../AuthStore.js";

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
        path:'/register',
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
        component: CreateThread,
        meta:{
            requiresAuth: true
        }
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

//各ルートのログイン必須確認
router.beforeEach((to) => {
    const authStore = useAuthStore();

    //ログインしていない場合、ログインページに飛ばす処理
    if (to.meta.requiresAuth && !authStore.isLoggedIn) {
        return{
            name: "Login",
            query: { redirect: to.fullPath },
        }
    }
})

export default router