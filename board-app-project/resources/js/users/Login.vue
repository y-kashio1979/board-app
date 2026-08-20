<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useRoute } from "vue-router";
import { useAuthStore } from "../AuthStore";
import axios from "axios";
import LoadingModal from "../components/modal/LoadingModal.vue";

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
const route = useRoute();

const redirectPath = route.query.redirect || "/";
const isLoading = ref(false);

const login = async () => {
    validate();
    if (errors.email || errors.password) return;

    isLoading.value = true;

    try {
        await axios.get("sanctum/csrf-cookie");

        const response = await axios.post("/api/login", loginForm);

        const data = response.data;

        authStore.login(data.user);

        router.push(redirectPath);
    } catch (error) {
        if (error.response?.status === 422) {
            errors.email = error.response.data.errors?.email?.[0];
            errors.password = error.response.data.errors?.password?.[0];
        } else if (error.response?.status === 401) {
            errors.login = error.response.data.message;
        } else {
            errors.login = "ログインエラーが発生しました";
        }
    } finally {
        isLoading.value = false;
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
    <div class="max-w-2xl mx-auto">
        <h2>ログイン</h2>
        <p v-if="errors.login" class="error-box">{{ errors.login }}</p>

        <div class="card">
            <div class="max-w-md mx-auto">
                <div class="form-group">
                    <label class="form-label">メールアドレス</label>
                    <input
                        type="email"
                        v-model="loginForm.email"
                        class="form-input"
                    />
                    <p class="error-text">{{ errors.email }}</p>
                </div>

                <div class="form-group">
                    <label class="form-label">パスワード</label>
                    <input
                        type="password"
                        v-model="loginForm.password"
                        class="form-input"
                    />
                    <p class="error-text">{{ errors.password }}</p>
                </div>

                <button
                    @click="login"
                    :disabled="isLoading"
                    class="btn-primary w-full mt-4"
                >
                    ログイン
                </button>

                <hr class="my-6 border-secondary/20" />
                <p class="text-center text-text-muted">
                    会員登録ををお済みでない方はこちら<router-link
                        :to="{ name: 'Regist' }"
                        class="text-primary font-medium ml-1"
                        >会員登録</router-link
                    >
                </p>
            </div>
        </div>
    </div>
    <loading-modal v-if="isLoading">
        <p>ログイン中...</p>
    </loading-modal>
</template>

<style scoped></style>
