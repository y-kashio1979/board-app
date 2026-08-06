<script setup>
import { reactive } from "vue";
import axios from "axios";
import {useRouter} from "vue-router";


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
async function regist() {
    if (!validate()) {
        return;
    }
    try{
        await axios.post("/register", {
        name: data.name,
        email: data.email,
        password: data.password,
        password_confirmation: data.confirmPassword,
    });
    router.push({name:'Login'});
}catch(err){
    console.log(err);
    
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
    } else if (data.name.length > 20) {
        error.name = "ユーザー名は20文字以内で入力してください";
        havingError = true;
    }
    if (!data.email) {
        error.email = "メールアドレスは必須入力です";
        havingError = true;
    }

    if (!data.password) {
        error.password = "パスワードは必須入力です";
        havingError = true;
    } else if (data.password.length < 8) {
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
    <button @click="regist">登録</button>
</template>
