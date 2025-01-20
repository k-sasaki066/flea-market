@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
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
                    <img class="favorite-count__img" src="{{ asset('images/star.svg') }}" alt="いいね" width="26px">
                    <span class="favorite-count__text">3</span>
                </div>
                <div class="comment-count__group">
                    <img class="comment-count__img" src="{{ asset('images/comment.svg') }}" alt="コメント" width="22px">
                    <span class="comment-count__text">2</span>
                </div>
            </div>
            @if(Auth::check())
            <a class="item-purchase__btn form-btn" href="">購入手続きへ</a>
            @else
            <a class="item-purchase__btn form-btn" href="#modal">購入手続きへ</a>
            @endif
        </div>

        <div class="item-description">
            <h3 class="item-description__ttl">商品説明</h3>
            <p class="item-description__text">{{ $item['description'] }}</p>
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
            <h3 class="item-comment__ttl">コメント（2）</h3>
            <div class="item-comment__wrap">
                <div class="item-comment__user">
                    <div class="item-comment__img-wrap">
                        <img class="item-comment__img" src="" alt="">
                    </div>
                    <p class="item-comment__name">name</p>
                </div>
                <p class="item-comment__text">コメントが入ります。</p>
            </div>
        </div>

        <form class="item-comment__form" action="" method="">
            <h3 class="item-comment__form-ttl">商品へのコメント</h3>
            <textarea class="item-comment__form-text" name="" id="" rows="10"></textarea>
            <button class="item-comment__form-btn form-btn" type="submit">コメントを送信する</button>
        </form>
    </div>

    <div class="modal__group" id="modal">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal__inner">
            <a class="close-detail__button" href="#">×</a>
            <p class="modal-text">商品を購入するには、ログインが必要です。</p>
        </div>
    </div>
</div>
@endsection