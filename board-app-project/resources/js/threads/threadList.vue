<script></script>

<template>
<h2>スレッド一覧</h2>
<label for="serch">検索</label>
<input type="text" id="serch">
<button @click="serch">検索</button>
<table>
    <tr>
        <th>タイトル</th>
        <th>閲覧数</th>
        <th>コメント数</th>
        <th>投稿者</th>
        <th>投稿日</th>
    </tr>
    <tr @click="goDtail">
        <!--TODO:一覧表示-->
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<!--TODO:ページネーション作成-->
<button>前へ</button>
<span></span>
<button>次へ</button>
</template>

<style></style>