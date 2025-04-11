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
            <div class="transaction-message__self-wrap" data-message-id="{{ $message['id'] }}">
                <div class="transaction-message__content flex message-self">
                    <p class="transaction-message__name bold">{{ $message['sender']['nickname'] }}</p>
                    <div class="transaction-message__img-wrap">
                        @if($message['sender']['image_url'])
                        <img class="transaction-message__img" src="{{ $message['sender']['image_url'] }}" alt="">
                        @endif
                    </div>
                </div>

                @if ($message->deleted_at)
                <p class="transaction-delete-message__text">このメッセージは削除されました</p>
                @else
                <p class="transaction-message__text">{{ $message['message'] }}</p>
                @endif

                @if($message['image_url'] && !$message->deleted_at)
                <div class="transaction-message__chat-img-wrap">
                    <img class="transaction-message__chat-img" src="{{ $message['image_url'] }}" alt="">
                </div>
                @endif

                @if (!$message->deleted_at)
                <div class="transaction-message__form-group flex">
                    <a class="transaction-message__update-btn" href="#">編集</a>
                    <form class="transaction-message__delete-form" action="/message/{{ $message['id'] }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="transaction-message__delete-btn" onclick="return confirm('「{{ $message['message']}}」\nこのメッセージを削除します。よろしいですか？');">削除</button>
                    </form>
                </div>
                @endif

                <form class="transaction-edit-form" action="/message/{{ $message['id'] }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                    <div class="edit-error-message">
                        @error('message_send')
                        {{ $message }}
                        @enderror
                    </div>
                    <textarea class="transaction-edit-form__message-input" name="message_send" rows="1">{{ old('message') ? old('message') : $message['message'] }}</textarea>
                    <div class="transaction-edit-form__btn-group flex">
                        <button class="transaction-edit__btn" type="submit">更新</button>
                        <button class="transaction-cancel-edit__btn" type="button">キャンセル</button>
                    </div>
                </form>
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

                @if ($message->deleted_at)
                <p class="transaction-delete-message__text">このメッセージは削除されました</p>
                @else
                <p class="transaction-message__text">{{ $message['message'] }}</p>
                @endif

                @if($message['image_url'] && !$message->deleted_at)
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

@if(session('message_updated'))
    <div id="message-update-status" data-updated="true"></div>
@endif

<script src="{{ asset('js/message_edit.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const textarea = document.querySelector('.transaction-form__message-input');
        const form = document.querySelector('.transaction-form');

        const transactionId = "{{ $transaction['id'] }}";
        const storageKey = 'chat_draft_' + transactionId;

        if (localStorage.getItem(storageKey)) {
            textarea.value = localStorage.getItem(storageKey);
        }

        textarea.addEventListener('input', function () {
            localStorage.setItem(storageKey, textarea.value);
        });

        form.addEventListener('submit', function () {
            localStorage.removeItem(storageKey);
        });
    });
</script>
@endsection