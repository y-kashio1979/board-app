<script setup>
import { useAuthStore } from "../AuthStore";
import axios from "axios";

const authStore = useAuthStore();

const logout = async () => {
    try {
        const response = await axios.post("/api/logout");
        authStore.logout();
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <h1>掲示板アプリ</h1>

    <!-- TODO: リンクとボタンの見た目を揃える -->
    <router-link :to="{ name: 'ThreadList' }"> スレッド一覧 </router-link>
    <router-link :to="{ name: 'CreateThread' }"> スレッド作成 </router-link>

    <div v-if="authStore.isLoggedIn">
        <button @click="logout">ログアウト</button>
    </div>

    <div v-else>
        <router-link :to="{ name: 'Login' }"> ログイン </router-link>
        <router-link :to="{ name: 'Regist' }"> 会員登録 </router-link>
    </div>
</template>

<style scoped></style>
