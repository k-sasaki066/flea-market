@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-ttl">会員登録</h2>
    <form class="form-group" action="" method="">
        @csrf
        <div class="form-group__item">
            <label class="form-group__item-label">
                ユーザー名
                <input class="form-group__item-input" type="text" name="name">
            </label>
            <div class="error-message">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <label class="form-group__item-label">メールアドレス
                <input class="form-group__item-input" type="text" name="email">
            </label>
            <div class="error-message">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <label class="form-group__item-label">パスワード
                <input class="form-group__item-input" type="text" name="password">
            </label>
            <div class="error-message">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <label class="form-group__item-label">確認用パスワード
                <input class="form-group__item-input" type="text" name="password_confirmation">
            </label>
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
        ログインはこちらから
    </a>
</div>
@endsection