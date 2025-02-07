@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
@if (session('result'))
    <div class="flash_success-message">
        {{ session('result') }}
    </div>
@endif

<div class="mypage-container">
    <div class="user__inner flex">
        <div class="user-img__group flex">
            <div class="user-img__wrap">
                <img class="user-img" src="{{ $user['image_url'] }}" alt="">
            </div>
            <h2 class="user-name">{{ ($user['nickname'] == null) ? 'ユーザー' : $user['nickname'] }}</h2>
        </div>
        <a class="profile-link" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="list__inner">
        <div class="list-menu__group border">
            <form class="list-menu__form" action="/mypage" method="GET">
                @csrf
                <input type="hidden" name="tab" value="sell">
                <button class="list-menu__text {{ ($parameter == 'sell') ? 'selected' : ''}} " type="submit">出品した商品</button>
            </form>
            <form class="list-menu__form" action="/mypage" method="GET">
                @csrf
                <input type="hidden" name="tab" value="buy">
                <button class="list-menu__text {{ ($parameter == 'buy') ? 'selected' : '' }}" type="submit">購入した商品</button>
            </form>
        </div>

        <div class="list-card__group flex">
            @if($parameter == 'sell' || $parameter == 'buy')
            @foreach($items as $item)
            <div class="list-card__item">
                @if($item['status'] == 2)
                <p class="sold-out">Sold</p>
                @endif
                <div class="list-card__wrap">
                    <a class="list-card__link" href="/item/:{{ $item['id'] }}">
                        <img class="list-card__img" src="{{ $item['image_url'] }}" alt="item">
                    </a>
                </div>
                <p class="list-card__ttl">{{ $item['name'] }}</p>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

@endsection