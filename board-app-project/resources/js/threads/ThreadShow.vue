<script setup>
import { ref, onMounted, onUnmounted, nextTick } from "vue";
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
const commentScroll = ref(null);
const currentPage = ref(1);
const lastPage = ref(1);
// 無限スクロールの監視対象の要素を取得
const loadMoreTrigger = ref(null);

const fetchThread = async () => {
    try {
        const response = await axios.get(`/api/threads/${route.params.id}`);
        thread.value = response.data.thread;
        comments.value = response.data.comments.data;
        currentPage.value = response.data.comments.current_page;
        lastPage.value = response.data.comments.last_page;
    } catch (error) {
        errorMessage.value = "スレッドの取得に失敗しました。";
    }
};

const fetchComments = async (page) => {
    try {
        const response = await axios.get(
            `/api/threads/${route.params.id}/comments`,
            {
                params: { page },
            },
        );

        comments.value.push(...response.data.data);

        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (error) {
        errorMessage.value = "コメントの取得に失敗しました。";
    }
};

const loadMoreComments = async () => {
    if (currentPage.value >= lastPage.value) {
        return;
    }

    await fetchComments(currentPage.value + 1);
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

    await nextTick();
    createObserver();

    if (!errorMessage.value && thread.value) {
        addThreadView();
    }
});

const reFetchThread = async (isPosted) => {
    if (!isPosted) {
        return;
    }
    const loadedPage = currentPage.value;

    for (let page = 1; page <= loadedPage; page++) {
        fetchComments(page);
    }
};

const scrollComment = (target) => {
    const commentBox = commentScroll.value;

    if (!commentBox) return;

    commentBox.scrollTo({
        top: target,
        behavior: "smooth",
    });
};

const scrollToTop = () => {
    scrollComment(0);
};

const goBack = () => {
    router.push({ name: "ThreadList", query: route.query });
};

// 日付を日本語形式でフォーマットする関数
const formatDate = (date) => {
    return new Date(date).toLocaleString("ja-JP");
};

let observer = null;

const createObserver = () => {
    observer = new IntersectionObserver(
        async ([entry]) => {
            // 監視対象が見えているとき
            if (entry.isIntersecting && currentPage.value < lastPage.value) {
                await loadMoreComments();
            }
        },
        {
            // 監視対象が見えたときに発火する領域
            root: commentScroll.value,
            // 要素がどれくらい見えたら発火するか
            threshold: 0.1,
        },
    );

    // 監視の宣言
    if (loadMoreTrigger.value) {
        observer.observe(loadMoreTrigger.value);
    }
};

onUnmounted(() => {
    observer?.disconnect();
});
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
                <h3 class="mb-2 text-xl font-bold pt-2 text-text">
                    {{ thread.title }}
                </h3>
                <h4 class="mb-2 text-text">投稿者: {{ thread.user.name }}</h4>
                <h4 class="text-text text-sm">
                    投稿日:
                    {{ formatDate(thread.created_at) }}
                </h4>

                <div class="mt-4 border-t border-secondary/20 pt-3">
                    <ReadMoreText
                        :text="thread.body"
                        :maxLength="maxpreviewLength"
                    />
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-text">
                    コメント一覧 ({{ comments.length }}件表示)
                </h3>

                <div class="flex gap-2">
                    <button class="btn-secondary text-sm" @click="scrollToTop">
                        ↑
                    </button>
                </div>
            </div>
            <div ref="commentScroll" class="card max-h-[300px] overflow-y-auto">
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
                <div ref="loadMoreTrigger" class="h-4"></div>
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

<style scoped></style>
