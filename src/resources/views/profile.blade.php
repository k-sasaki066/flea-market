@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-header">プロフィール設定</h2>
    <form class="form-group" action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group__item user-img__group flex">
            <div class="user-img__wrap" id="figure">
                <img class="user-img" id="figureImage" src="{{ $user['image_url'] }}" alt="">
            </div>
            <div class="user-img__btn-wrap">
                <label class="user-img__select" for="upload" >画像を選択する</label>
                <input class="user-img__hidden" type="file" id="upload"  name="image_url" accept="image/png, image/jpeg">
            </div>
        </div>
        <div class="error-message">
            @error('image_url')
            {{ $message }}
            @enderror
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">ユーザー名</p>
            <input class="form-group__item-input" type="text" name="nickname" value="{{ old('nickname') ? old('nickname') : $user['nickname'] }}">
            <div class="error-message">
                @error('nickname')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">郵便番号</p>
            <input class="form-group__item-input" type="text" name="post_cord" value="{{ old('post_cord') ? old('post_cord') : $user['post_cord'] }}">
            <div class="error-message">
                @error('post_cord')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">住所</p>
            <input class="form-group__item-input" type="text" name="address" value="{{ old('address') ? old('address') : $user['address'] }}">
            <div class="error-message">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">建物名</p>
            <input class="form-group__item-input" type="text" name="building" value="{{ old('building') ? old('building') : $user['building'] }}">
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
    <script src="{{ asset('js/preview.js') }}"></script>
</div>
@endsection