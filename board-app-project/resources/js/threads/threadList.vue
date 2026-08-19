<script setup>
import { onMounted, reactive, ref } from "vue";
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

onMounted(() => {
    data.keyword = route.query.keyword ?? "";
    page.currentPage = route.query.page ?? 1;
    getThreads(page.currentPage);
});

//検索
const search = () => {
    getThreads();
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
    <h2>スレッド一覧</h2>
    <label for="serch">検索</label>
    <input type="text" id="serch" v-model="data.keyword" />
    <button @click="search">検索</button>
    <p>{{ errorMessage }}</p>
    <table>
        <thead>
            <tr>
                <th>タイトル</th>
                <th>閲覧数</th>
                <th>コメント数</th>
                <th>投稿者</th>
                <th>投稿日</th>
            </tr>
        </thead>

        <tbody>
            <tr v-if="data.threads.length === 0">
                <td colspan="5">検索条件に一致するスレッドがありません</td>
            </tr>
            <tr v-for="thread in data.threads" :key="thread.id">
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
                <td>{{ thread.view_count }}</td>
                <td>{{ thread.comments_count }}</td>
                <td>{{ thread.user.name }}</td>
                <td>{{ formatDate(thread.created_at) }}</td>
            </tr>
        </tbody>
    </table>

    <button @click="previousPage">前へ</button>
    <span>{{ page.currentPage }} / {{ page.totalPage }}</span>
    <button @click="nextPage">次へ</button>
</template>

<style></style>
