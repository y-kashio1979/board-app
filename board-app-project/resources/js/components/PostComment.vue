<script setup>
import { computed, ref } from "vue";
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

//現在文字数　コメント本文
const bodyLength = computed(() => {
    return commentBody.value.length;
});
</script>

<template>
    <h3 class="mb-4 text-xl font-bold text-text">コメント投稿</h3>

    <div id="comment-form" class="card">
        <p v-if="apiError" class="error-box">{{ apiError }}</p>

        <div class="flex gap-item">
            <textarea
                id="comment-body"
                v-model="commentBody"
                rows="2"
                class="form-input"
                placeholder="コメントを入力して下さい"
            ></textarea>

            <button
                @click="createComment"
                class="btn-primary self-end w-24 h-10"
            >
                投稿
            </button>
        </div>

        <p class="text-text-muted">
            {{ bodyLength }} / {{ COMMENT_BODY_CHAR_LIMIT }}
        </p>

        <p v-if="error" class="error-text">{{ error }}</p>
    </div>
</template>

<style scoped></style>
