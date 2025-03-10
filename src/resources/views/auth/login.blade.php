@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
@if (session('result'))
<div class="flash_success-message">
    {{ session('result') }}
</div>
@endif
@if (session('error'))
<div class="flash_error-message">
    {{ session('error') }}
</div>
@endif

<div class="form-container login-container">
    <h2 class="form-header">ログイン</h2>
    <form class="form-group" action="/login" method="POST">
        @csrf
        <div class="form-group__item">
            <p class="form-group__item-label bold">メールアドレス</p>
            <input class="form-group__item-input" type="text" name="email" value="{{ old('email') }}">
            <div class="error-message">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label bold">パスワード</p>
            <input class="form-group__item-input" type="password" name="password">
            <div class="error-message">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>

        <button class="form-btn btn-margin bold" type="submit">
            ログインする
        </button>
    </form>
    <a class="guid-text" href="/register">
        会員登録はこちら
    </a>
</div>
@endsection