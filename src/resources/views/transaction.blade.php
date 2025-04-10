@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/transaction.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
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

<div class="transaction-container grid">
    <div class="transaction-list__wrap">
        <h3 class="transaction-list__text">その他の取引</h3>
        <div class="transaction-list__item flex">
            <a class="transaction-list__item-link" href="">商品名</a>
            <a class="transaction-list__item-link" href="">商品名</a>
            <a class="transaction-list__item-link" href="">商品名</a>
        </div>
    </div>

    <div class="transaction-wrap">
        <div class="transaction-title grid">
            <div class="transaction-user__img-wrap">
                <img class="transaction-user__img" src="" alt="">
            </div>
            <h2 class="transaction-name">「ユーザー名」さんとの取引画面</h2>
            <a class="transaction-complete__btn" href="">取引を完了する</a>
        </div>

        <div class="transaction-item__wrap flex">
            <div class="transaction-item__img-wrap">
                <img class="transaction-item__img" src="" alt="">
            </div>
            <div class="transaction-item__content flex">
                <h1 class="transaction-item__name">商品名</h1>
                <p class="transaction-item__price">商品価格</p>
            </div>
        </div>

        <div class="transaction-message__container">
            <div class="transaction-message__other-wrap">
                <div class="transaction-message__content flex">
                    <div class="transaction-message__img-wrap">
                        <img class="transaction-message__img" src="" alt="">
                    </div>
                    <p class="transaction-message__name bold">ユーザー名</p>
                </div>
                <div class="error-message">
                    message
                </div>
                <p class="transaction-message__text">メッセージメッセージ</p>
            </div>

            <div class="transaction-message__self-wrap">
                <div class="transaction-message__content flex message-self">
                    <p class="transaction-message__name bold">ユーザー名</p>
                    <div class="transaction-message__img-wrap">
                        <img class="transaction-message__img" src="" alt="">
                    </div>
                </div>
                <div class="error-message">
                    message
                </div>
                <p class="transaction-message__text">メッセージ</p>
                <div class="transaction-message__form-group flex">
                    <a class="transaction-message__update-btn" href="">編集</a>
                    <form class="transaction-message__delete-form" action="" method="">
                        @csrf
                        <button class="transaction-message__delete-btn">削除</button>
                    </form>
                </div>
            </div>
        </div>

        <form class="transaction-form grid" method="" action="">
            @csrf
            <textarea class="transaction-form__message-input" type="text" name="" rows="1" placeholder="取引メッセージを入力してください"></textarea>
            <label class="transaction-form__img-select bold" for="upload">画像を追加</label>
            <input class="transaction-form__img-hidden" type="file" id="upload" name="" accept="image/png, image/jpeg">
            <button class="transaction-form__btn" type="submit"><img class="transaction-form__btn-img" src="{{ asset('images/sendbutton.svg') }}" alt="コメント" width="54px"></button>
        </form>
    </div>
</div>

@endsection