<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    text: {
        type: String,
        required: true,
    },
    maxLength: {
        type: Number,
        required: true,
    },
});

const showAll = ref(false);

const getTextLength = () => {
    return props.text.replace(/\r?\n/g, "").length;
};

const getShortText = () => {
    let count = 0;
    let shortText = "";

    for (const char of props.text) {
        if (char !== "\n" && char !== "\r") {
            count++;
        }

        if (count > props.maxLength) {
            break;
        }

        shortText += char;
    }
    shortText += "..."; // 省略記号を追加
    return shortText;
};

// テキストを表示する算出プロパティ（全文表示か省略表示かを判定）
const showText = computed(() => {
    return showAll.value || getTextLength() <= props.maxLength
        ? props.text
        : getShortText();
});
</script>

<template>
    <div class="text-content">
        {{ showText }}
    </div>

    <!-- 省略表示の切り替え -->
    <span
        v-if="getTextLength() > props.maxLength"
        class="toggle-text"
        @click="showAll = !showAll"
    >
        {{ showAll ? "閉じる" : "全体を表示" }}
    </span>
</template>

<style scoped>
.text-content {
    white-space: pre-wrap;
}

.toggle-text {
    color: blue;
    cursor: pointer;
    text-decoration: underline;
}
</style>
