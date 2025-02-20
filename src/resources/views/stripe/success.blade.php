@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
<link rel="stylesheet" href="{{ asset('css/result.css')}}">
@endsection

@section('content')
<div class="result-container">
    <h2 class="form-btn success-title">決済成功！</h2>
    <p class="result-message">お支払いありがとうございました。</p>
    <a class="home-btn" href="/mypage">マイページに戻る</a>
</div>

<script>
document.addEventListener("DOMContentLoaded", async function () {
    const urlParams = new URLSearchParams(window.location.search);
    const sessionId = urlParams.get("session_id");

    if (sessionId) {
        const response = await fetch(`/stripe/session-status?session_id=${sessionId}`);
        const data = await response.json();

        if (data.purchase_error === "already_sold") {
            alert("申し訳ありません。この商品は既に購入されています。");
            window.location.href = `/item/${data.item_id}`;
        }
    }
});
</script>
@endsection