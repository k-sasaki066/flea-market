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
            @if($otherItems)
            @foreach($otherItems as $otherItem)
            <a class="transaction-list__item-link" href="/transaction/{{ $otherItem['id'] }}">{{ $otherItem['purchase']['item']['name'] }}</a>
            @endforeach
            @endif
        </div>
    </div>

    <div class="transaction-wrap">
        <div class="transaction-title grid">
            <div class="transaction-user__img-wrap">
                @if($otherUser['image_url'])
                <img class="transaction-user__img" src="{{ $otherUser['image_url'] }}" alt="">
                @endif
            </div>
            <h2 class="transaction-name">{{ $otherUser['nickname'] }}さんとの取引画面</h2>
            @if($transaction['buyer_id'] == $user['id'])
            <a class="transaction-complete__btn" href="">取引を完了する</a>
            @endif
        </div>

        <div class="transaction-item__wrap flex">
            <div class="transaction-item__img-wrap">
                <img class="transaction-item__img" src="{{ $transaction['purchase']['item']['image_url'] }}" alt="">
            </div>
            <div class="transaction-item__content flex">
                <h1 class="transaction-item__name">{{ $transaction['purchase']['item']['name'] }}</h1>
                <p class="transaction-item__price"><span class="transaction-item__price-span">&yen;</span>{{ number_format($transaction['purchase']['item']['price']) }}</p>
            </div>
        </div>

        <div class="transaction-message__container">
            @foreach($messages as $message)
            @if($message->sender_id === $user->id)
            <div class="transaction-message__self-wrap">
                <div class="transaction-message__content flex message-self">
                    <p class="transaction-message__name bold">{{ $message['sender']['nickname'] }}</p>
                    <div class="transaction-message__img-wrap">
                        @if($message['sender']['image_url'])
                        <img class="transaction-message__img" src="{{ $message['sender']['image_url'] }}" alt="">
                        @endif
                    </div>
                </div>
                <p class="transaction-message__text">{{ $message['message'] }}</p>
                @if($message['image_url'])
                <div class="transaction-message__chat-img-wrap">
                    <img class="transaction-message__chat-img" src="{{ $message['image_url'] }}" alt="">
                </div>
                @endif
                <div class="transaction-message__form-group flex">
                    <a class="transaction-message__update-btn" href="">編集</a>
                    <form class="transaction-message__delete-form" action="" method="">
                        @csrf
                        <button class="transaction-message__delete-btn">削除</button>
                    </form>
                </div>
            </div>
            @else
            <div class="transaction-message__other-wrap">
                <div class="transaction-message__content flex">
                    <div class="transaction-message__img-wrap">
                        @if($message['sender']['image_url'])
                        <img class="transaction-message__img" src="{{ $message['sender']['image_url'] }}" alt="">
                        @endif
                    </div>
                    <p class="transaction-message__name bold">{{ $message['sender']['nickname'] }}</p>
                </div>
                <p class="transaction-message__text">{{ $message['message'] }}</p>
                @if($message['image_url'])
                <div class="transaction-message__chat-img-wrap">
                    <img class="transaction-message__chat-img" src="{{ $message['image_url'] }}" alt="">
                </div>
                @endif
            </div>
            @endif
            @endforeach
        </div>

        <form class="transaction-form grid" method="POST" action="/transaction/{{ $transaction['id'] }}" enctype="multipart/form-data">
            @csrf
            <div class="error-message">
                @error('message')
                {{ $message }}
                @enderror
            </div>
            <div class="error-message">
                @error('image_url')
                {{ $message }}
                @enderror
            </div>
            <div class="transaction-form__group grid">
                <textarea class="transaction-form__message-input" type="text" name="message" rows="1" placeholder="取引メッセージを入力してください">{{ old('message') }}</textarea>
                <label class="transaction-form__img-select bold" for="upload">画像を追加</label>
                <input class="transaction-form__img-hidden" type="file" id="upload" name="image_url" accept="image/png, image/jpeg">
                <button class="transaction-form__btn" type="submit"><img class="transaction-form__btn-img" src="{{ asset('images/sendbutton.svg') }}" alt="コメント" width="54px"></button>
            </div>
        </form>
    </div>
</div>
@endsection