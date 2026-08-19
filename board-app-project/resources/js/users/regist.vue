<script setup>
import { reactive, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import LoadingModal from "../components/modal/LoadingModal.vue";
import BaseModal from "../components/modal/BaseModal.vue";
import InfoModal from "../components/modal/InfoModal.vue";

const MAX_NAME_LENGTH = 20;
const MIN_PASSWORD_LENGTH = 8;
const data = reactive({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
});

const error = reactive({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
});
const router = useRouter();
const errorMessage = ref("");
const loading = ref(false);
const isSuccess = ref(false);
const infoMsg = ref("");
//登録
async function regist() {
    if (!validate()) {
        return;
    }
    errorMessage.value = "";
    loading.value = true;
    try {
        const response = await axios.post("/api/user/create", {
            name: data.name,
            email: data.email,
            password: data.password,
            password_confirmation: data.confirmPassword,
        });
        infoMsg.value = response.data.message;
        isSuccess.value = true;
        setTimeout(() => {
            router.push({ name: "Login" });
        }, 2000);
    } catch (err) {
        console.log(err);
        if (err.response?.status === 422) {
            const errors = err.response.data.errors;
            error.name = errors.name?.[0] ?? "";
            error.email = errors.email?.[0] ?? "";
            error.password = errors.password?.[0] ?? "";
        }
        errorMessage.value = "ユーザー登録に失敗しました";
    } finally {
        loading.value = false;
    }
}

function validate() {
    error.name = "";
    error.email = "";
    error.password = "";
    error.confirmPassword = "";
    let havingError = false;
    if (!data.name) {
        error.name = "ユーザー名は必須入力です";
        havingError = true;
    } else if (data.name.length > MAX_NAME_LENGTH) {
        error.name = "ユーザー名は20文字以内で入力してください";
        havingError = true;
    }
    if (!data.email) {
        error.email = "メールアドレスは必須入力です";
        havingError = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
        error.email = "メールアドレスの形式が正しくありません";
        havingError = true;
    }

    if (!data.password) {
        error.password = "パスワードは必須入力です";
        havingError = true;
    } else if (data.password.length < MIN_PASSWORD_LENGTH) {
        error.password = "パスワードは8文字以上で入力してください";
        havingError = true;
    }

    if (!data.confirmPassword) {
        error.confirmPassword = "確認用パスワードは必須入力です";
        havingError = true;
    } else if (data.confirmPassword !== data.password) {
        error.confirmPassword = "パスワードと一致しません";
        havingError = true;
    }

    return !havingError;
}
</script>
<template>
    <h2>会員登録</h2>
    <p>{{ errorMessage }}</p>
    <label for="name">ユーザー名</label>
    <input type="text" id="name" v-model="data.name" />
    <p class="error">{{ error.name }}</p>
    <label for="email">メールアドレス</label>
    <input type="text" id="email" v-model="data.email" />
    <p class="error">{{ error.email }}</p>
    <label for="password">パスワード</label>
    <input type="password" id="password" v-model="data.password" />
    <p class="error">{{ error.password }}</p>
    <label for="confirmPassword">確認用パスワード</label>
    <input
        type="password"
        id="confirmPassword"
        v-model="data.confirmPassword"
    />
    <p class="error">{{ error.confirmPassword }}</p>
    <button @click="regist" :disabled="loading">登録</button>
    <LoadingModal v-if="loading">
        <p>登録中...</p>
    </LoadingModal>
    <InfoModal v-if="isSuccess">
        {{ infoMsg }}
        <p>ログイン画面へ遷移します</p>
    </InfoModal>
</template>
