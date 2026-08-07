<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import PostComment from "../components/PostComment.vue";

const route = useRoute();
const thread = ref(null);
const comments = ref([]);
const router = useRouter();
const errorMessage = ref(""); // エラーメッセージを格納する変数
const maxpreviewLength = 50; // コメントのプレビュー表示の最大文字数
// TODO : ログイン情報の呼び出し

const fetchThread = async () => {
    try {
        const response = await axios.get(`/api/threads/${route.params.id}`);
        thread.value = response.data;
        comments.value = response.data.comments.map((comment) => ({
            ...comment,
            showAll: false, // コメントの展開状態を管理するプロパティを追加
        }));
    } catch (error) {
        errorMessage.value = "スレッドの取得に失敗しました。";
    }
};

onMounted(() => {
    fetchThread();
});

const goBack = () => {
    router.push({ name: "ThreadList" });
};

// コメントの本文の長さ（改行を除く）取得する関数
const getCommentLength = (commentBody) => {
    return commentBody.replace(/\r?\n/g, "").length;
};

// 日付を日本語形式でフォーマットする関数
const formatDate = (date) => {
    return new Date(date).toLocaleString("ja-JP");
};

// コメントの本文をmaxpreviewLength文字までに短縮する関数
const getShortComment = (commentBody) => {
    let count = 0;
    let shortComment = "";

    for (const char of commentBody) {
        if (char !== "\n" && char !== "\r") {
            count++;
        }

        if (count > maxpreviewLength) {
            break;
        }

        shortComment += char;
    }
    shortComment += "..."; // 省略記号を追加
    return shortComment;
};

// コメントを表示する関数（全文表示か省略表示かを判定）
const showComment = (comment) => {
    return comment.showAll ||
        getCommentLength(comment.body) <= maxpreviewLength
        ? comment.body
        : getShortComment(comment.body);
};
</script>

<template>
    <h2>スレッド詳細</h2>

    <p v-if="errorMessage" class="error">
        {{ errorMessage }}
    </p>

    <button @click="goBack">一覧に戻る</button>

    <div v-if="thread">
        <div id="thread-info">
            <h3>{{ thread.title }}</h3>
            <!-- TODO : スレッド投稿者と投稿者が同じ場合にユーザー名を青色表示 -->
            <h4>投稿者: {{ thread.user.name }}</h4>
            <h4>投稿日: {{ formatDate(thread.created_at) }}</h4>
        </div>

        <div class="thread-content">{{ thread.body }}</div>
    </div>

    <h3>コメント一覧</h3>
    <div id="comments-list">
        <div v-for="comment in comments" :key="comment.id">
            <p>{{ comment.user.name }}</p>
            <p>{{ formatDate(comment.created_at) }}</p>
            <div class="comment-content">
                {{
                    // コメントの全文を表示するか、省略表示するかを判定
                    showComment(comment)
                }}
            </div>

            <!-- 省略表示の切り替え -->
            <span
                v-if="getCommentLength(comment.body) > maxpreviewLength"
                class="toggle-comment"
                @click="comment.showAll = !comment.showAll"
            >
                {{ comment.showAll ? "閉じる" : "全体を表示" }}
            </span>
        </div>
    </div>

    <PostComment v-if="isLoggedIn" :threadId="thread.id" :userId="currentUser.id" />
    <div v-else>
        <p>コメントを投稿するにはログインが必要です。</p>
        <router-link to="/login">ログイン</router-link>
    </div>
</template>

<style scoped>
/* TODO : Tailwind CSSに変換する */
.comment-content {
    white-space: pre-wrap;
}

.thread-content {
    white-space: pre-wrap;
}

#comments-list {
    max-height: 400px;
    overflow-y: auto;
}

.toggle-comment {
    color: blue;
    cursor: pointer;
    text-decoration: underline;
}
</style>
