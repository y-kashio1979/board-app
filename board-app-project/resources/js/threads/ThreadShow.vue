<script setup></script>

<template>
    <h2>スレッド詳細</h2>

    <!-- TODO : 一覧に戻る処理作成 -->
    <button>一覧に戻る</button>

    <!-- TODO: スレッドのタイトル,投稿者,投稿日の表示 -->
    <div id="thread-info">
        <h3>タイトル</h3>
        <h4>投稿者:</h4>
        <h4>投稿日:</h4>
    </div>

    <!-- TODO: 本文の表示 -->
    <p>スレッド本文</p>

    <div id="comments-list">
        <h3>コメント一覧</h3>
        <!-- TODO: コメントの投稿者,投稿日,コメント本文の表示 -->
        <div v-for="comment in comments" :key="comment.id">
            <p>投稿者</p>
            <p>投稿日</p>
            <!-- TODO : コメント長文時の省略表示の実装 -->
            <div class="comment-content">コメント本文</div>
        </div>
    </div>

    <div id="comment-form">
        <label>コメント投稿</label>
        <textarea></textarea>
        <!-- TODO : コメントのエラーメッセージ表示 -->
        <p>エラーメッセージ</p>
        <!-- TODO : コメント投稿処理の実装 -->
        <button>投稿</button>
    </div>
</template>

<style scoped>
#comment-content {
    white-space: pre-wrap;
}
</style>