@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email__container">
    <div class="verify-email__header">
        <h2 class="verify-email__header-text">
            {{ __('ご登録いただいたメールアドレスに、確認用のリンクをお送りしました。') }}
        </h2>
    </div>
    <div class="verify-email__content">
        <p class="verify-email__text">
            {{ __('もし確認用メールが送信されていない場合は、下記をクリックしてください。') }}
        </p>
        <form class="verify-email__form" method="post" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="verify-email__form-button">
                {{ __('確認メールを再送信する') }}
            </button>
        </form>

        <form class="verify-email__back-form" method="post" action="/logout">
            @csrf
            <button class="verify-email__back">
                {{ __('ログアウト') }}
            </button>
        </form>
    </div>
</div>
@endsection