<script setup>
import { computed, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

//制限文字数
const THREAD_TITLE_CHAR_LIMIT = 200;
const THREAD_BODY_CHAR_LIMIT = 200;

const router = useRouter();

//送るデータ　スレッドタイトル、本文
const threadTitle = ref("");
const threadBody = ref("");

//初期化用エラーたち
const initialErrors = {
    title: "",
    body: "",
    api: "",
};

//エラーメッセージ保持
const errors = ref({ ...initialErrors });

//スレッド作成
const makeThread = async () => {
    if (!validate()) return;

    const threadData = {
        title: threadTitle.value,
        body: threadBody.value,
    };

    //テーブルへ追加する
    try {
        const res = await axios.post("/api/threads/create", threadData);
        resetInputs();
        moveThreadDetail(res.data["threadId"]);
    } catch (e) {
        if (e.response?.status === 422) {
            const apiValidateErrors = e.response?.data?.errors;

            if (apiValidateErrors) {
                Object.keys(apiValidateErrors).forEach((key) => {
                    errors.value[key] = apiValidateErrors[key][0];
                });
            }
        } else if (e.response?.status === 401) {
            errors.value.api = "スレッド作成するにはログインが必要です";
        } else {
            errors.value.api = "通信エラーのためスレッドを作成できませんでした";
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

//作成したスレッドに移動
const moveThreadDetail = (threadId) => {
    router.push({
        name: "ThreadShow",
        params: {
            id: threadId,
        },
    });
};

//現在文字数　タイトル
const titleLength = computed(() => {
    return threadTitle.value.length;
});

//現在文字数　本文
const bodyLength = computed(() => {
    return threadBody.value.length;
});
</script>

<template>
    <div class="mx-auto max-w-2xl p-6">
        <h2>スレッド作成</h2>

        <!-- エラーメッセージ　API -->
        <p v-if="errors.api" class="error-box">
            {{ errors.api }}
        </p>

        <div class="card">
            <div class="form-group">
                <label for="threadTitle" class="form-label"> タイトル </label>

                <input
                    id="threadTitle"
                    v-model="threadTitle"
                    type="text"
                    class="form-input"
                />
                <p class="text-text-muted">
                    {{ titleLength }} / {{ THREAD_TITLE_CHAR_LIMIT }}
                </p>

                <!-- エラーメッセージ　タイトル -->
                <p v-if="errors.title" class="error-text">
                    {{ errors.title }}
                </p>
            </div>

            <div class="form-group">
                <label for="threadBody" class="form-label"> 本文 </label>

                <textarea
                    id="threadBody"
                    v-model="threadBody"
                    rows="8"
                    class="form-input"
                ></textarea>
                <p class="text-text-muted">
                    {{ bodyLength }} / {{ THREAD_BODY_CHAR_LIMIT }}
                </p>

                <!-- エラーメッセージ　本文 -->
                <p v-if="errors.body" class="error-text">
                    {{ errors.body }}
                </p>
            </div>

            <div class="flex justify-end">
                <button @click="makeThread" class="btn-primary">投稿</button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
