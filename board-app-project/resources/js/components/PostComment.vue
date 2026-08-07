<script setup>
import { ref } from "vue";

//制限文字数
const COMMENT_BODY_CHAR_LIMIT = 200;

const commentBody = ref("");
const error = ref("");

//バリデーション
const validate = () => {
    error.value = "";
    const trimedCommentBody = commentBody.value.trim();

    if (!trimedCommentBody) {
        error.value = "コメント本文は必須です";
    }

    if (trimedCommentBody.length > COMMENT_BODY_CHAR_LIMIT) {
        error.value = `コメント本文は${COMMENT_BODY_CHAR_LIMIT}文字以下です`;
    }
};

//コメント登録
const createComment = () => {
    validate();
    if (error.value) return;
    // TODO: api通信を実装
};

const isLogin = () => {
    // TODO : ログイン確認処理を書く
};
</script>

<template>
    <div id="comment-form">
        <label for="comment-body">コメント投稿</label>
        <textarea id="comment-body" v-model="commentBody"></textarea>
        <p v-if="error">{{ error }}</p>
        <button @click="createComment">投稿</button>
    </div>
</template>

<style scoped></style>
