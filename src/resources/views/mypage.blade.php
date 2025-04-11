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

@if (session('error'))
<div class="flash_error-message">
    {{ session('error') }}
</div>
@endif

<div class="mypage-container">
    <div class="user__inner grid">
        <div class="user-img__wrap">
            @if($user['image_url'])
            <img class="user-img" src="{{ $user['image_url'] }}" alt="">
            @endif
        </div>
        <div class="user-name__wrap flex">
            <h2 class="user-name">{{ (!$user['nickname']) ? 'ユーザー' : $user['nickname'] }}</h2>
            <span class="star-rating" data-rate="">★ ★ ★ ★ ★</span>
        </div>
        <a class="profile-link bold" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="list__inner">
        <div class="list-menu__group border">
            <form class="list-menu__form" action="/mypage" method="GET">
                <input type="hidden" name="page" value="sell">
                <button class="list-menu__text bold {{ ($parameter == 'sell') ? 'selected' : ''}} " type="submit">出品した商品</button>
            </form>
            <form class="list-menu__form" action="/mypage" method="GET">
                <input type="hidden" name="page" value="buy">
                <button class="list-menu__text bold {{ ($parameter == 'buy') ? 'selected' : '' }}" type="submit">購入した商品</button>
            </form>
            <form class="list-menu__form" action="/mypage" method="GET">
                <input type="hidden" name="page" value="transaction">
                <button class="list-menu__text bold {{ ($parameter == 'transaction') ? 'selected' : '' }}" type="submit">取引中の商品
                    @if($unreadCount > 0)
                    <span class="unread-count__span">{{ $unreadCount }}</span>
                    @endif
                </button>
            </form>
        </div>

        <div class="list-card__group flex">
            @if($parameter == 'sell' || $parameter == 'buy')
            @foreach($items as $item)
            <div class="list-card__item">
                <div class="list-card__wrap">
                    @if($item['status'] == 2)
                    <p class="sold-out bold">Sold</p>
                    @endif
                    <a class="list-card__link" href="/item/{{ $item['id'] }}">
                        <img class="list-card__img" src="{{ $item['image_url'] }}" alt="item">
                    </a>
                </div>
                <p class="list-card__ttl">{{ $item['name'] }}</p>
            </div>
            @endforeach
            @elseif($parameter == 'transaction')
            @foreach($items as $item)
            <div class="list-card__item">
                <div class="list-card__wrap">
                    @if($item['unread_count'] > 0)
                    <p class="new-message__count flex">{{ $item['unread_count'] }}</p>
                    @endif
                    <a class="list-card__link" href="/transaction/{{ $item['id'] }}">
                        <img class="list-card__img" src="{{ $item['purchase']['item']['image_url'] }}" alt="item">
                    </a>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

@endsection