<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../AuthStore";
import axios from "axios";

const loginForm = reactive({
    email: "",
    password: "",
});

const errors = reactive({
    email: "",
    password: "",
    login: "",
});

const authStore = useAuthStore();
const router = useRouter();

const login = async () => {
    validate();
    if (errors.email || errors.password) return;

    try {
        await axios.get("sanctum/csrf-cookie");

        const response = await axios.post("/api/login", loginForm);

        const data = response.data;

        authStore.login(data.user);
        
        router.push("/");
    } catch (error) {
        if (error.response?.status === 422) {
            errors.email = error.response.data.errors?.email?.[0];
            errors.password = error.response.data.errors?.password?.[0];
        } else if (error.response?.status === 401) {
            errors.login = error.response.data.message;
        } else {
            errors.login = "ログインエラーが発生しました";
        }
    }
};

const validate = () => {
    for (const key in errors) {
        errors[key] = "";
    }

    const trimmedEmail = loginForm.email.trim();
    const trimmedPassword = loginForm.password.trim();

    if (!trimmedEmail) {
        errors.email = "メールアドレスは必須です";
    }

    if (!trimmedPassword) {
        errors.password = "パスワードは必須です";
    }
};
</script>

<template>
    <h2>ログイン</h2>

    <label>メールアドレス</label>
    <input type="email" v-model="loginForm.email" />
    <p>{{ errors.email }}</p>

    <label>パスワード</label>
    <input type="password" v-model="loginForm.password" />
    <p>{{ errors.password }}</p>

    <p>{{ errors.login }}</p>
    <button @click="login">ログイン</button>

    <hr />
    <p>
        会員登録ををお済みでない方はこちら<router-link to="/register"
            >会員登録</router-link
        >
    </p>
</template>

<style scoped></style>
