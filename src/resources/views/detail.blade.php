@extends('layouts/app')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
@if (session('result'))
<div class="flash_success-message">
    {{ session('result') }}
</div>
@endif

<div class="item-container grid">
    <div class="item-img__wrap">
        <img class="item-img" src="{{ $item['image_url'] }}" alt="item">
    </div>
    <div class="item-detail">
        <div class="item-detail__top flex">
            <div class="item-detail__ttl-group">
                <h1 class="item-detail__ttl">{{ $item['name'] }}</h1>
                @if($item['brand'] !== null)
                <p class="item-detail__brand">{{ $item['brand']['name'] }}</p>
                @endif
            </div>
            <div class="item-detail__price-group flex">
                <span class="item-detail__price-span">&yen;</span><p class="item-detail__price-text">{{ number_format($item['price']) }}</p><span class="item-detail__price-span">&nbsp;(税込)</span>
            </div>
            <div class="item-detail__count-group flex">
                <div class="favorite-count__group flex">
                    @auth
                    <button class="favorite-btn" data-item-id="{{ $item->id }}">
                        <img class="favorite-count__img" src="{{ asset($favorite ? 'images/star-yellow.svg' : 'images/star.svg') }}" alt="いいね" width="22px">
                    </button>
                    @else
                    <a class="favorite-count__login" href="#favorite"><img class="favorite-count__img" src="{{ asset('images/star.svg') }}" alt="" width="22px"></a>
                    @endauth
                    <span class="favorite-count__text">{{ $item['favorites_count'] }}</span>
                </div>
                <div class="comment-count__group flex">
                    <img class="comment-count__img" src="{{ asset('images/comment.svg') }}" alt="コメント" width="22px">
                    <span class="comment-count__text">{{ $item['comments_count'] }}</span>
                </div>
            </div>
            @if(Auth::check())
            <a class="item-purchase__btn form-btn bold @if($item['status'] == 2) purchased @endif" href="/purchase/{{ $item['id'] }}">購入手続きへ</a>
            @else
            <a class="item-purchase__btn form-btn bold @if($item['status'] == 2) purchased @endif" href="#modal">購入手続きへ</a>
            @endif
        </div>

        <div class="item-description__group flex">
            <h2 class="item-description__ttl">商品説明</h3>
            <p class="item-description__text">{!! nl2br(e($item['description'])) !!}</p>
        </div>

        <div class="item-info__group flex">
            <h2 class="item-info__ttl">商品の情報</h2>
            <div class="item-category__group flex">
                <p class="item-category__ttl">カテゴリー</p>
                <div class="item-category__name flex">
                    @foreach($category as $value)
                    <span class="item-category__text">{{ $value }}</span>
                    @endforeach
                </div>
            </div>
            <div class="item-condition__group flex">
                <p class="item-condition__ttl">商品の状態</p>
                <span class="item-condition__text">{{ $item['condition']['name'] }}</span>
            </div>
        </div>

        <div class="item-comment__group flex">
            <h2 class="item-comment__ttl">コメント ({{ $item['comments_count'] }})</h2>
            <div class="item-comment__inner">
                @foreach($item->comments as $comment)
                <div class="item-comment__wrap">
                    <div class="item-comment__user flex">
                        <div class="item-comment__img-wrap user-img__wrap">
                            @if($comment['user']['image_url'])
                            <img class="user-img" src="{{ $comment['user']['image_url'] }}" alt="">
                            @endif
                        </div>
                        <p class="item-comment__name">{{ $comment['user']['nickname'] }}</p>
                    </div>
                    <p class="item-comment__text">{{ $comment['comment'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <form class="item-comment__form flex" action="/comment/{{ $item['id'] }}" method="POST">
            @csrf
            <h3 class="item-comment__form-ttl">商品へのコメント</h3>
            <textarea class="item-comment__form-textarea" name="comment" rows="8">{{ old('comment') }}</textarea>
            <div class="error-message">
                @error('comment')
                {{ $message }}
                @enderror
            </div>
            @if(Auth::check())
            <button class="item-comment__form-btn form-btn bold" type="submit">コメントを送信する</button>
            @else
            <a class="item-comment__form-btn form-btn bold" href="#comment">コメントを送信する</a>
            @endif
        </form>
    </div>

    <div class="modal__group" id="modal">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text bold">商品を購入するには、ログインが必要です。</p>
        </div>
    </div>

    <div class="modal__group" id="favorite">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text bold">いいね登録するには、ログインが必要です。</p>
        </div>
    </div>

    <div class="modal__group" id="comment">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text bold">コメントを送信するには、ログインが必要です。</p>
        </div>
    </div>
    <script src="{{ asset('js/favorite.js') }}"></script>
</div>
@endsection