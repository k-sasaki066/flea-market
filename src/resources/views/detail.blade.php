@extends('layouts/app')

@section('css')
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

<div class="item-container">
    <div class="item-img__wrap">
        <img class="item-img" src="{{ $item['image_url'] }}" alt="item">
    </div>
    <div class="item-content">
        <div class="item-top">
            <div class="item-ttl__group">
                <h2 class="item-ttl">{{ $item['name'] }}</h2>
                @if($item['brand'] !== null)
                <p class="item-brand">{{ $item['brand']['name'] }}</p>
                @endif
            </div>
            <div class="item-price">
                <span class="item-price__span">¥</span><p class="item-price__text">{{ number_format($item['price']) }}</p><span class="item-price__span">(税込)</span>
            </div>
            <div class="item-count__wrap">
                <div class="favorite-count__group">
                    @if(Auth::check())
                        @if($favorite == null)
                        <form class="create-favorite__form" action="/like/:{{ $item['id'] }}" method="POST">
                            @csrf
                            <input class="favorite-count__img" type="image" src="{{ asset('images/star.svg') }}" alt="いいね" width="22px">
                        </form>
                        @else
                        <form class="delete-favorite__form" action="/unlike/:{{ $item['id'] }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input class="favorite-count__img" type="image" src="{{ asset('images/star-yellow.svg') }}" alt="いいね" width="22px">
                        </form>
                        @endif
                    @else
                    <a class="favorite-count__login" href="#favorite"><img class="favorite-count__img" src="{{ asset('images/star.svg') }}" alt="" width="22px"></a>
                    @endif
                    <span class="favorite-count__text">{{ $item['favorites_count'] }}</span>
                </div>
                <div class="comment-count__group">
                    <img class="comment-count__img" src="{{ asset('images/comment.svg') }}" alt="コメント" width="22px">
                    <span class="comment-count__text">2</span>
                </div>
            </div>
            @if(Auth::check())
            <a class="item-purchase__btn form-btn @if($item['status'] == 2) purchased @endif" href="/purchase/:{{ $item['id'] }}">購入手続きへ</a>
            @else
            <a class="item-purchase__btn form-btn @if($item['status'] == 2) purchased @endif" href="#modal">購入手続きへ</a>
            @endif
        </div>

        <div class="item-description">
            <h3 class="item-description__ttl">商品説明</h3>
            <p class="item-description__text">{!! nl2br(e($item['description'])) !!}</p>
        </div>

        <div class="item-info">
            <h3 class="item-info__ttl">商品の情報</h3>
            <div class="item-category__group">
                <p class="item-category__ttl">カテゴリー</p>
                <div class="item-category__name">
                    @foreach($category as $value)
                    <span class="item-category__text">{{ $value }}</span>
                    @endforeach
                </div>
            </div>
            <div class="item-condition__group">
                <p class="item-condition__ttl">商品の状態</p>
                <span class="item-condition__text">{{ $item['condition']['name'] }}</span>
            </div>
        </div>

        <div class="item-comment__group">
            <h3 class="item-comment__ttl">コメント ({{ $item['comments_count'] }})</h3>
            <div class="item-comment__inner">
                @foreach($item->comments as $user)
                <div class="item-comment__wrap">
                    <div class="item-comment__user">
                        <div class="item-comment__img-wrap">
                            <img class="item-comment__img" src="{{ $user['image_url'] }}" alt="">
                        </div>
                        <p class="item-comment__name">{{ $user['nickname'] }}</p>
                    </div>
                    <p class="item-comment__text">{{ $user->pivot->comment }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <form class="item-comment__form" action="/comment/:{{ $item['id'] }}" method="POST">
            @csrf
            <h3 class="item-comment__form-ttl">商品へのコメント</h3>
            <textarea class="item-comment__form-text" name="comment" rows="10">{{ old('comment') }}</textarea>
            <div class="error-message">
                @error('comment')
                {{ $message }}
                @enderror
            </div>
            @if(Auth::check())
            <button class="item-comment__form-btn form-btn" type="submit">コメントを送信する</button>
            @else
            <a class="item-purchase__btn form-btn" href="#comment">コメントを送信する</a>
            @endif
        </form>
    </div>

    <div class="modal__group" id="modal">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text">商品を購入するには、ログインが必要です。</p>
        </div>
    </div>

    <div class="modal__group" id="favorite">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text">いいね登録するには、ログインが必要です。</p>
        </div>
    </div>

    <div class="modal__group" id="comment">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text">コメントを送信するには、ログインが必要です。</p>
        </div>
    </div>
</div>
@endsection