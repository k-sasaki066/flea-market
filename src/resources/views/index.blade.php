@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
@endsection

@section('content')
<div class="list-container">
    <div class="list-menu__group">
        <a class="list-menu__text" href="">おすすめ</a>
        <a class="list-menu__text" href="/mypage">マイリスト</a>
    </div>

    <div class="list-card__group">
        <div class="list-card__item">
            <div class="list-card__img-wrap">
                <img class="list-card__img" src="" alt="item">
            </div>
            <p class="list-card__ttl">商品名</p>
        </div>
        <div class="list-card__item">
            <div class="list-card__img-wrap">
                <img class="list-card__img" src="" alt="item">
            </div>
            <p class="list-card__ttl">商品名</p>
        </div>
        <div class="list-card__item">
            <div class="list-card__img-wrap">
                <img class="list-card__img" src="" alt="item">
            </div>
            <p class="list-card__ttl">商品名</p>
        </div>
        <div class="list-card__item">
            <div class="list-card__img-wrap">
                <img class="list-card__img" src="" alt="item">
            </div>
            <p class="list-card__ttl">商品名</p>
        </div>
    </div>
</div>

@endsection