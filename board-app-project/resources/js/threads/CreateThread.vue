<script setup>
import { ref } from "vue";
import axios from "axios";

//制限文字数
const THREAD_TITLE_CHAR_LIMIT = 200;
const THREAD_BODY_CHAR_LIMIT = 200;

// TODO: ユーザーIDを取得する処理の追加　今は仮で１としている
const userId = 1;
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
const makeThread = async () => {
    if (!validate()) return;

    const threadData = {
        user_id: userId,
        title: threadTitle.value,
        body: threadBody.value,
    };

    //テーブルへ追加する
    try {
        const res = await axios.post("/api/threads/create", threadData);
        resetInputs();
    } catch (e) {
        if (e.response?.status === 422) {
            const apiValidateErrors = e.response?.data?.errors;

            if (apiValidateErrors) {
                Object.keys(apiValidateErrors).forEach((key) => {
                    errors.value[key] = apiValidateErrors[key][0];
                });
            }
        } else {
            errors.value.title = "通信エラーのためスレッドを作成できませんでした";
        }
    }
};

//バリデーション
const validate = () => {
    resetErrors();

    validateTitle();
    validateBody();

    if (hasError()) return false;
    return true;
};

//スレッドタイトルバリデーション
const validateTitle = () => {
    const trimedTitle = threadTitle.value.trim();

    if (!trimedTitle) {
        errors.value.title = "タイトルは必須です";
        return false;
    }

    if (trimedTitle.length > THREAD_TITLE_CHAR_LIMIT) {
        errors.value.title = `タイトルは${THREAD_TITLE_CHAR_LIMIT}文字以内です`;
        return false;
    }

    return true;
};

//スレッド本文バリデーション
const validateBody = () => {
    const trimedBody = threadBody.value.trim();

    if (!trimedBody) {
        errors.value.body = "本文は必須です";
        return false;
    }

    if (trimedBody.length > THREAD_BODY_CHAR_LIMIT) {
        errors.value.body = `本文は${THREAD_BODY_CHAR_LIMIT}文字以内です`;
        return false;
    }

    return true;
};

//エラー初期化
const resetErrors = () => {
    errors.value = { ...initialErrors };
};

//エラーがあるか
const hasError = () => {
    return Object.values(errors.value).some(Boolean);
};

//入力内容初期化
const resetInputs = () => {
    threadTitle.value = "";
    threadBody.value = "";
};
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
