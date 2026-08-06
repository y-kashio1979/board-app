<script setup>
import { ref } from "vue";

//制限文字数
const THREAD_TITLE_CHAR_LIMIT = 200;
const THREAD_BODY_CHAR_LIMIT = 200;

const threadTitle = ref("");
const threadBody = ref("");

//初期化用エラーたち
const initialErrors = {
    title: "",
    body: "",
};

//エラーメッセージ保持
const errors = ref({ ...initialErrors });

//スレッド作成
const makeThread = () => {
    validate();
    if (hasError) return;
    // TODO:apiに追加する処理を書く
};

//バリデーション
const validate = () => {
    resetErrors();

    const trimedTitle = threadTitle.value.trim();
    const body = threadBody.value;

    if (!trimedTitle) {
        errors.value.title = "タイトルは必須です";
    }

    if (trimedTitle.length > THREAD_TITLE_CHAR_LIMIT) {
        errors.value.title = "タイトルは200文字以内です";
    }

    if (!body) {
        errors.value.body = "本文は必須です";
    }

    if (body.length > THREAD_BODY_CHAR_LIMIT) {
        errors.value.body = "本文は200文字以内です";
    }
};

const resetErrors = () => {
    errors.value = { ...initialErrors };
};

//エラーがあるか
const hasError = () => {
    return Object.values(errors.value).some(Boolean);
};

const isLogin = () => {
    // TODO:ログインしているか確認処理実装
}
</script>

<template>
    <h2>スレッド作成</h2>
    <div>
        <label for="threadTitle">タイトル</label>
        <input type="text" id="threadTitle" v-model="threadTitle" />

        <!-- エラーメッセージ -->
        <p v-if="errors.title">{{ errors.title }}</p>

        <label for="threadBody">本文</label>
        <textarea
            name="threadBody"
            id="threadBody"
            v-model="threadBody"
        ></textarea>

        <!-- エラーメッセージ -->
        <p v-if="errors.body">{{ errors.body }}</p>

        <button @click="makeThread">投稿</button>
    </div>
</template>

<style scoped></style>
