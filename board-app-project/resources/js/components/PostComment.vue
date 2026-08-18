<script setup>
import { ref } from "vue";
import axios from "axios";

const props = defineProps(["threadId"]);
const emit = defineEmits(["postedComment"]);

//制限文字数
const COMMENT_BODY_CHAR_LIMIT = 200;

//コメント本文
const commentBody = ref("");

//エラーメッセージ保持
const error = ref("");
const apiError = ref("");

//バリデーション
const validate = () => {
    resetErrors();
    const trimedCommentBody = commentBody.value.trim();

    if (!trimedCommentBody) {
        error.value = "コメント本文は必須です";
        return;
    }

    if (trimedCommentBody.length > COMMENT_BODY_CHAR_LIMIT) {
        error.value = `コメント本文は${COMMENT_BODY_CHAR_LIMIT}文字以下です`;
        return;
    }
};

//コメント登録
const createComment = async () => {
    validate();
    if (error.value) return;

    const commentData = {
        body: commentBody.value,
    };

    try {
        const res = await axios.post(
            `/api/threads/${props.threadId}/posts`,
            commentData,
        );
        resetInput();
        //投稿完了のイベントを渡す
        emit("postedComment", true);
    } catch (e) {
        if (e.response?.status === 422) {
            error.value = e.response?.data?.message;
        } else if (e.response?.status === 401) {
            apiError.value = "コメント投稿するにはログインが必要です";
        } else {
            apiError.value = "通信エラーのため投稿できませんでした";
        }
        //投稿できなかった場合のイベントを渡す
        emit("postedComment", false);
    }
};

//エラー内容リセット
const resetErrors = () => {
    error.value = "";
    apiError.value = "";
};

//入力内容リセット
const resetInput = () => {
    commentBody.value = "";
};
</script>

<template>
    <div id="comment-form">
        <p v-if="apiError">{{ apiError }}</p>

        <label for="comment-body">コメント投稿</label>
        <textarea id="comment-body" v-model="commentBody"></textarea>

        <p v-if="error">{{ error }}</p>
        <button @click="createComment">投稿</button>
    </div>
</template>

<style scoped></style>
