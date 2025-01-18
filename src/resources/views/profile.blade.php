@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-ttl">プロフィール設定</h2>
    <form class="form-group" action="" method="POST">
        @csrf
        <div class="form-group__item form-img">
            <div class="user-img__wrap">
                <img class="user-img" src="" alt="">
            </div>
            <div class="user-img__btn-wrap">
                <label class="user-img__select" for="upload" >画像を選択する</label>
                <input class="user-img__hidden" type="file" id="upload" accept="">
            </div>
        </div>
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
            <p class="form-group__item-label">郵便番号</p>
            <input class="form-group__item-input" type="text" name="post_cord" value="{{ old('post_cord') }}">
            <div class="error-message">
                @error('post_cord')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">住所</p>
            <input class="form-group__item-input" type="text" name="address">
            <div class="error-message">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">建物名</p>
            <input class="form-group__item-input" type="text" name="building">
            <div class="error-message">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>

        <button class="form-btn btn-margin" type="submit">
            更新する
        </button>
    </form>
</div>
@endsection