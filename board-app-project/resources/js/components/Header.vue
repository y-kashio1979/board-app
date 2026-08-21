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
    <div class="mb-section border-b bg-surface shadow-sm">
        <div
            class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4"
        >
            <h1 class="text-2xl font-bold text-primary">掲示板アプリ</h1>

            <div class="flex items-center gap-6">
                <router-link :to="{ name: 'Top' }" class="nav-link nav-divider"
                    >トップ</router-link
                >
                <router-link
                    :to="{ name: 'ThreadList' }"
                    class="nav-link nav-divider"
                >
                    スレッド一覧
                </router-link>

                <div
                    v-if="authStore.isLoggedIn"
                    class="flex items-center gap-6"
                >
                    <router-link
                        :to="{ name: 'CreateThread' }"
                        class="nav-link nav-divider"
                    >
                        スレッド作成
                    </router-link>
                    <span class="text-sm text-text-muted nav-divider">
                        {{ authStore.name }} さん
                    </span>
                    <button @click="logout" class="nav-link">ログアウト</button>
                </div>

                <div v-else class="flex items-center gap-6">
                    <router-link
                        :to="{ name: 'Login' }"
                        class="nav-link nav-divider"
                    >
                        ログイン
                    </router-link>
                    <router-link :to="{ name: 'Regist' }" class="nav-link">
                        会員登録
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
