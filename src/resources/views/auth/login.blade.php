@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
<div class="form-container login-container">
    <h2 class="form-ttl">ログイン</h2>
    <form class="form-group" action="/login" method="POST">
        @csrf
        <div class="form-group__item">
            <label class="form-group__item-label">ユーザー名 / メールアドレス
                <input class="form-group__item-input" type="text" name="email" value="{{ old('email') }}">
            </label>
            <div class="error-message">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <label class="form-group__item-label">パスワード
                <input class="form-group__item-input" type="password" name="password">
            </label>
            <div class="error-message">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>

        <button class="form-btn btn-margin" type="submit">
            ログインする
        </button>
    </form>
    <a class="guid-text" href="/register">
        会員登録はこちらから
    </a>
</div>
@endsection