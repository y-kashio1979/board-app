<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useRoute } from "vue-router";
const MAX_TITLE_LENGTH = 20;
const data = reactive({
    threads: [],
    keyword: "",
});
//ページ（現在のページ、総ページ数）
const page = reactive({
    currentPage: 1,
    totalPage: 1,
});
const errorMessage = ref("");
const route = useRoute();
//共通化
const getThreads = async (pageNumber = 1) => {
    errorMessage.value = "";
    try {
        const res = await axios.get(`/api/threads`, {
            params: { page: pageNumber, keyword: data.keyword },
        });
        data.threads = res.data.data;
        page.currentPage = res.data.current_page;
        page.totalPage = res.data.last_page;
    } catch (error) {
        console.log(error);
        errorMessage.value = "スレッドの取得に失敗しました。";
    }
};

let timer = null;
watch(
    () => data.keyword,
    () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            page.currentPage = 1;
            getThreads(page.currentPage);
        }, 500);
    },
);

onMounted(() => {
    data.keyword = route.query.keyword ?? "";
    page.currentPage = route.query.page ?? 1;
    getThreads(page.currentPage);
});

//検索ワードクリア
const wordClear = () => {
    data.keyword = "";
};
//タイトル字数超過時の省略
const shortenTitle = (title) => {
    return title.length > MAX_TITLE_LENGTH
        ? title.substring(0, MAX_TITLE_LENGTH) + "..."
        : title;
};
//次のページ
const nextPage = () => {
    if (page.currentPage >= page.totalPage) {
        return;
    }
    getThreads(page.currentPage + 1);
};
//前のページ
const previousPage = () => {
    if (page.currentPage <= 1) {
        return;
    }
    getThreads(page.currentPage - 1);
};
//日付日本語フォーマット
const formatDate = (date) => {
    return new Date(date).toLocaleString("ja-JP");
};
</script>

<template>
    <div class="mx-auto max-w-6xl p-6">
        <h2>スレッド一覧</h2>
        <div class="card">
            <div class="form-group mb-8">
                <div class="flex gap-2">
                    <input
                        type="text"
                        id="serch"
                        v-model="data.keyword"
                        class="form-input max-w-md"
                        placeholder="タイトルまたは投稿者名を入力"
                    />
                    <button class="btn-primary">検索</button>
                    <button @click="wordClear" class="btn-secondary">
                        クリア
                    </button>
                    <p class="error-text">{{ errorMessage }}</p>
                </div>
            </div>
            <table class="table">
                <thead class="table-header">
                    <tr>
                        <th class="w-[40%]">タイトル</th>
                        <th class="w-[10%] text-center">閲覧数</th>
                        <th class="w-[10%] text-center">コメント数</th>
                        <th class="w-[15%] text-center">投稿者</th>
                        <th class="w-[25%] text-center">投稿日</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="data.threads.length === 0" class="table-row">
                        <td colspan="5" class="min-w-60">
                            検索条件に一致するスレッドがありません
                        </td>
                    </tr>
                    <tr
                        v-for="thread in data.threads"
                        :key="thread.id"
                        class="table-row"
                    >
                        <td>
                            <router-link
                                :to="{
                                    name: 'ThreadShow',
                                    params: { id: thread.id },
                                    query: {
                                        keyword: data.keyword,
                                        page: page.currentPage,
                                    },
                                }"
                                >{{ shortenTitle(thread.title) }}</router-link
                            >
                        </td>
                        <td class="text-center">{{ thread.view_count }}</td>
                        <td class="text-center">{{ thread.comments_count }}</td>
                        <td class="text-center">{{ thread.user.name }}</td>
                        <td class="text-center whitespace-nowrap">
                            {{ formatDate(thread.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4 flex items-center justify-center gap-4">
                <button @click="previousPage" class="btn-secondary">
                    前へ
                </button>
                <span>{{ page.currentPage }} / {{ page.totalPage }}</span>
                <button @click="nextPage" class="btn-primary">次へ</button>
            </div>
        </div>
    </div>
</template>

<style></style>
