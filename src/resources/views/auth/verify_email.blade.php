@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email__container">
    <h2 class="verify-email__header-text">
        登録していただいたメールアドレスに認証メールを送付しました。
        <br>
        メール認証を完了してください。
    </h2>

    <a class="verify-email__btn" href="https://{{ Auth::user()->email }}" target="_blank" class="btn">認証はこちらから</a>

    <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="verify-email__form-button" type="submit">
            認証メールを再送する
        </button>
    </form>

</div>
@endsection