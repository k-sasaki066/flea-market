@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
@if (session('result'))
    <div class="flash_success-message">
        {{ session('result') }}
    </div>
@endif

<div class="verify-email__container">
    <h2 class="verify-email__header-text">
        登録していただいたメールアドレスに認証メールを送付しました。
        <br>
        メール認証を完了してください。
    </h2>

    <a class="verify-email__btn" href="http://localhost:8025" target="_blank">認証はこちらから</a>

    <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}" id="resendForm">
        @csrf
        <button class="verify-email__form-button" type="submit" id="resendButton">
            認証メールを再送する
        </button>
    </form>

</div>
@endsection