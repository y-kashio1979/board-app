<script setup>
</script>

<template>
    <h2>
        ログイン
    </h2>

    <label>メールアドレス</label>
    <input type="text" v-model="email" />
    <!-- TODO : メールアドレスのエラーメッセージの表示 -->
     <p></p>

    <label>パスワード</label>
    <input type="password" v-model="password" />
    <!-- TODO : パスワードのエラーメッセージの表示 -->
     <p></p>

    <!-- TODO : ログインエラーメッセージの表示 -->
    <p></p>
    <button @click="login">ログイン</button>

    <hr>
    <!-- TODO : 会員登録リンクの表示 -->
    <p>会員登録ををお済みでない方はこちら<a href="">会員登録</a></p>
</template>

<style scoped>
</style>