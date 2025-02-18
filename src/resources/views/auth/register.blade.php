@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-header">会員登録</h2>
    <form class="form-group" action="/register" method="POST">
        @csrf
        <div class="form-group__item">
            <p class="form-group__item-label">ユーザー名</p>
            <input class="form-group__item-input" type="text" name="name" value="{{ old('name') }}">
            <div class="error-message">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">メールアドレス</p>
            <input class="form-group__item-input" type="text" name="email" value="{{ old('email') }}">
            <div class="error-message">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">パスワード</p>
            <input class="form-group__item-input" type="password" name="password">
            <div class="error-message">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">確認用パスワード</p>
            <input class="form-group__item-input" type="password" name="password_confirmation">
            <div class="error-message">
                @error('password_confirmation')
                {{ $message }}
                @enderror
            </div>
        </div>

        <button class="form-btn btn-margin" type="submit">
            登録する
        </button>
    </form>
    <a class="guid-text" href="/login">
        ログインはこちら
    </a>
</div>
@endsection