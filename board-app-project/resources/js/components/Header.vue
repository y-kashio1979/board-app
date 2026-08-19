<script setup>
import { useAuthStore } from "../AuthStore";
import axios from "axios";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const logout = async () => {
    try {
        const response = await axios.post("/api/logout");
        authStore.logout();
        router.push("/");
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <h1>掲示板アプリ</h1>

    <!-- TODO: リンクとボタンの見た目を揃える -->
    <!-- TODO: トップのリンクをルート名に変更 -->
    <router-link :to="{ path: '/' }">トップ</router-link>
    <router-link :to="{ name: 'ThreadList' }"> スレッド一覧 </router-link>

    <div v-if="authStore.isLoggedIn">
        <router-link :to="{ name: 'CreateThread' }"> スレッド作成 </router-link>
        <button @click="logout">ログアウト</button>
    </div>

    <div v-else>
        <router-link :to="{ name: 'Login' }"> ログイン </router-link>
        <router-link :to="{ name: 'Regist' }"> 会員登録 </router-link>
    </div>
</template>

<style scoped></style>
