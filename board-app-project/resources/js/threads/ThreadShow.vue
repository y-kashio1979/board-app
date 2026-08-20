<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import PostComment from "../components/PostComment.vue";
import ReadMoreText from "../components/ReadMoreText.vue";
import { useAuthStore } from "../AuthStore.js";

const route = useRoute();
const thread = ref(null);
const comments = ref([]);
const router = useRouter();
const errorMessage = ref(""); // エラーメッセージを格納する変数
const maxpreviewLength = 50; // コメントのプレビュー表示の最大文字数
const AuthSotre = useAuthStore();

const fetchThread = async () => {
    try {
        const response = await axios.get(`/api/threads/${route.params.id}`);
        thread.value = response.data;
        comments.value = response.data.comments;
    } catch (error) {
        errorMessage.value = "スレッドの取得に失敗しました。";
    }
};

const addThreadView = async () => {
    try {
        const response = await axios.put(
            `/api/threads/${route.params.id}/views`,
        );
    } catch (error) {
        console.log(error);
    }
};

onMounted(async () => {
    await fetchThread();

    if (!errorMessage.value && thread.value) {
        addThreadView();
    }
});

const reFetchThread = (isPosted) => {
    if (isPosted) {
        fetchThread();
    }
};

const goBack = () => {
    router.push({ name: "ThreadList", query: route.query });
};

// 日付を日本語形式でフォーマットする関数
const formatDate = (date) => {
    return new Date(date).toLocaleString("ja-JP");
};
</script>

<template>
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="mb-0 text-text">スレッド詳細</h2>

            <button @click="goBack" class="btn-secondary">一覧に戻る</button>
        </div>

        <p v-if="errorMessage" class="error-text">
            {{ errorMessage }}
        </p>

        <div v-else-if="thread">
            <div class="card mb-4 pt-0 pb-3">
                <h3 class="mb-2 text-xl font-bold pt-2 text-text">{{ thread.title }}</h3>
                <h4 class="mb-2 text-text">
                    投稿者: {{ thread.user.name }}
                </h4>
                <h4 class="text-text text-sm">
                    投稿日:
                    {{ formatDate(thread.created_at) }}
                </h4>

                <div
                    class="mt-4 whitespace-pre-wrap border-t border-secondary/20 pt-3 text-text"
                >
                    {{ thread.body }}
                </div>
            </div>

            <h3 class="mb-4 text-xl font-bold text-text">コメント一覧</h3>
            <div class="card max-h-[300px] overflow-y-auto">
                <div
                    v-for="comment in comments"
                    :key="comment.id"
                    class="border-b border-secondary/20 py-3 last:border-b-0"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p
                            :class="[
                                'font-medium',
                                comment.user_id === thread.user_id
                                    ? 'text-blue-600'
                                    : 'text-text',
                            ]"
                        >
                            {{ comment.user.name }}
                        </p>
                        <p class="text-sm text-text-muted">
                            {{ formatDate(comment.created_at) }}
                        </p>
                    </div>
                    <ReadMoreText
                        :text="comment.body"
                        :maxLength="maxpreviewLength"
                    />
                </div>
            </div>

            <div class="mt-6">
                <PostComment
                    v-if="AuthSotre.isLoggedIn"
                    :threadId="thread.id"
                    @postedComment="reFetchThread"
                />
                <div v-else class="card text-center">
                    <p class="mb-3">
                        コメントを投稿するにはログインが必要です。
                    </p>
                    <router-link
                        :to="{
                            name: 'Login',
                            query: { redirect: route.fullPath },
                        }"
                        class="text-primary font-medium hover:underline"
                        >ログイン</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
