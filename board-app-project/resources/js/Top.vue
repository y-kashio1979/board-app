<script setup>
const features = [
    "ユーザー登録・ログイン",
    "スレッドの作成",
    "スレッド一覧の表示",
    "コメント機能",
    "バリデーション",
    "ページネーション",
];
</script>

<template>
    <div class="mx-auto max-w-2xl">
        <h2>トップ</h2>

        <div class="card">
            <div class="section-group">
                <h3 class="section-title">概要</h3>
                <p class="text-text">
                    本アプリはLaravelとVueの学習を目的として作成した、掲示板アプリです。
                </p>
            </div>

            <div class="section-group">
                <h3 class="section-title">主な機能</h3>
                <ul class="list-disc pl-6">
                    <li v-for="feature in features">
                        {{ feature }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>