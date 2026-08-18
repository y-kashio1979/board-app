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

onMounted(() => {
    fetchThread();
});

const reFetchThread = (isPosted) => {
    if (isPosted) {
        fetchThread();
    }
};

const goBack = () => {
    router.push({ name: "ThreadList" });
};

// 日付を日本語形式でフォーマットする関数
const formatDate = (date) => {
    return new Date(date).toLocaleString("ja-JP");
};
</script>

<template>
    <h2>スレッド詳細</h2>

    <button @click="goBack">一覧に戻る</button>

    <p v-if="errorMessage" class="error">
        {{ errorMessage }}
    </p>

    <div v-else-if="thread">
        <div id="thread-info">
            <h3>{{ thread.title }}</h3>
            <!-- TODO : スレッド投稿者と投稿者が同じ場合にユーザー名を青色表示 -->
            <h4>投稿者: {{ thread.user.name }}</h4>
            <h4>
                投稿日:
                {{ formatDate(thread.created_at) }}
            </h4>
        </div>

        <div class="thread-content">{{ thread.body }}</div>

        <h3>コメント一覧</h3>
        <div id="comments-list">
            <div v-for="comment in comments" :key="comment.id">
                <p>{{ comment.user.name }}</p>
                <p>
                    {{ formatDate(comment.created_at) }}
                </p>
                <ReadMoreText
                    :text="comment.body"
                    :maxLength="maxpreviewLength"
                />
            </div>
        </div>

        <PostComment
            v-if="AuthSotre.isLoggedIn"
            :threadId="thread.id"
            @postedComment="reFetchThread"
        />
        <div v-else>
            <p>コメントを投稿するにはログインが必要です。</p>
            <router-link
                :to="{ name: 'Login', query: { redirect: route.fullPath } }"
                >ログイン</router-link
            >
        </div>
    </div>
</template>

<style scoped>
/* TODO : Tailwind CSSに変換する */
.thread-content {
    white-space: pre-wrap;
}

#comments-list {
    max-height: 400px;
    overflow-y: auto;
}
</style>
