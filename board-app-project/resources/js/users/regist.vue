<script setup>
import { reactive, ref, computed, watch } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import LoadingModal from "../components/modal/LoadingModal.vue";
import InfoModal from "../components/modal/InfoModal.vue";

const MAX_NAME_LENGTH = 20;
const MIN_PASSWORD_LENGTH = 8;
//入力用
const data = reactive({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
});
//バリデーションエラー
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

//リアルタイムバリデーション
watch(
    () => data.name,
    () => validateName(),
);
watch(
    () => data.email,
    () => validateEmail(),
);
watch(
    () => data.password,
    () => validatePassword(),
);
watch(
    () => data.confirmPassword,
    () => validateConfirmPassword(),
);
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
    return;
    validateName() &&
        validateEmail() &&
        validatePassword() &&
        validateConfirmPassword();
}

const validateName = () => {
    error.name = "";
    if (!data.name) {
        error.name = "ユーザー名は必須入力です";
        return false;
    } else if (data.name.length > MAX_NAME_LENGTH) {
        error.name = "ユーザー名は20文字以内で入力してください";
        return false;
    }
    return true;
};

const validateEmail = () => {
    error.email = "";
    if (!data.email) {
        error.email = "メールアドレスは必須入力です";
        return false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
        error.email = "メールアドレスの形式が正しくありません";
        return false;
    }
    return true;
};

const validatePassword = () => {
    error.password = "";
    if (!data.password) {
        error.password = "パスワードは必須入力です";
        return false;
    } else if (data.password.length < MIN_PASSWORD_LENGTH) {
        error.password = "パスワードは8文字以上で入力してください";
        return false;
    }
    return true;
};

const validateConfirmPassword = () => {
    error.confirmPassword = "";
    if (!data.confirmPassword) {
        error.confirmPassword = "確認用パスワードは必須入力です";
        return false;
    } else if (data.confirmPassword !== data.password) {
        error.confirmPassword = "パスワードと一致しません";
        return false;
    }
    return true;
};
//ユーザー名入力文字数数え上げ
const userNameLength = computed(() => {
    return data.name.length;
});
</script>
<template>
    <div class="max-w-2xl mx-auto">
        <h2>会員登録</h2>
        <div v-if="errorMessage" class="error-box">
            <p class="error-text">{{ errorMessage }}</p>
        </div>
        <div class="card">
            <div class="max-w-md mx-auto">
                <label for="name" class="form-label">ユーザー名</label>
                <input
                    type="text"
                    id="name"
                    v-model="data.name"
                    class="form-input w-full"
                />
                <p>{{ userNameLength }} / {{ MAX_NAME_LENGTH }}</p>
                <p class="error-text">{{ error.name }}</p>
                <label for="email" class="form-label">メールアドレス</label>
                <input
                    type="text"
                    id="email"
                    v-model="data.email"
                    class="form-input w-full"
                />
                <p class="error-text">{{ error.email }}</p>
                <label for="password" class="form-label">パスワード</label>
                <input
                    type="password"
                    id="password"
                    v-model="data.password"
                    class="form-input w-full"
                />
                <p class="error-text">{{ error.password }}</p>
                <label for="confirmPassword" class="form-label"
                    >確認用パスワード</label
                >
                <input
                    type="password"
                    id="confirmPassword"
                    v-model="data.confirmPassword"
                    class="form-input w-full"
                />
                <p class="error-text">{{ error.confirmPassword }}</p>
                <button @click="regist" :disabled="loading" class="btn-primary">
                    登録
                </button>
            </div>
        </div>
    </div>
    <LoadingModal v-if="loading">
        <p>登録中...</p>
    </LoadingModal>
    <InfoModal v-if="isSuccess">
        <p>{{ infoMsg }}</p>
        <p>ログイン画面へ遷移します</p>
    </InfoModal>
</template>
