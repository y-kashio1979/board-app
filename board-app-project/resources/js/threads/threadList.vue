<script setup>
import { onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
const data = reactive({
    threads: [],
    keyword: "",
    searched: false
});
//ページ（現在のページ、総ページ数）
const page = reactive({
    currentPage: 1,
    totalPage: 1,
});
const errorMessage = ref("");
const router = useRouter();

//共通化
const getThreads = async (pageNumber = 1) => {
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
    getThreads();
});
//スレッド詳細遷移
function goDetail(threadId) {
    router.push({ name: "ThreadShow", params: { id: threadId } });
}

//検索
const search = () => {
    data.searched = true;
    getThreads(page.currentPage);
};
//タイトル字数超過時の省略
const shortenTitle = (title) => {
    return title.length > 20 ? title.substring(0, 20) + "..." : title;
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
</script>

<template>
    <h2>スレッド一覧</h2>
    <label for="serch">検索</label>
    <input type="text" id="serch" v-model="data.keyword" />
    <button @click="search">検索</button>
    <p v-if="data.searched && data.threads.length === 0">
        検索条件に一致するスレッドがありません
    </p>
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
            <tr
                @click="goDetail(thread.id)"
                v-for="thread in data.threads"
                :key="thread.id"
            >
                <td>{{ shortenTitle(thread.title) }}</td>
                <td>{{ thread.view_count }}</td>
                <td>{{ thread.comments_count }}</td>
                <td>{{ thread.user.name }}</td>
                <td>{{ thread.created_at }}</td>
            </tr>
        </tbody>
    </table>

    <button @click="previousPage">前へ</button>
    <span>{{ page.currentPage }} / {{ page.totalPage }}</span>
    <button @click="nextPage">次へ</button>
</template>

<style></style>
