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

<div class="list-container">
    <div class="list-menu__group border">
        <form class="list-menu__form" action="/" method="GET">
            @csrf
            <input type="hidden" name="tab" value="suggest">
            <button class="list-menu__text {{ ($parameter == 'suggest') ? 'selected' : '' }}" type="submit">おすすめ</button>
        </form>
        <form class="list-menu__form" action="/" method="GET">
            @csrf
            <input type="hidden" name="tab" value="mylist">
            <button class="list-menu__text {{ ($parameter == 'mylist') ? 'selected' : '' }}" type="submit">マイリスト</button>
        </form>
    </div>

    <div class="list-card__group flex">
        @foreach($items as $item)
        <div class="list-card__item">
            <div class="list-card__wrap">
                @if($item['status'] == 2)
                <p class="sold-out">Sold</p>
                @endif
                <a class="list-card__link" href="/item/{{ $item['id'] }}">
                    <img class="list-card__img" src="{{ $item['image_url'] }}" alt="item">
                </a>
            </div>
            <p class="list-card__ttl">{{ $item['name'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection