@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
@if (session('result'))
    <div class="flash_success-message">
        {{ session('result') }}
    </div>
@endif

<div class="purchase-container">
    <form  class="purchase-form flex" action="/purchase/{{ $item['id'] }}" method="POST">
        @csrf
        <div class="purchase-content__inner">
            <div class="purchase-content__img flex border">
                <div class="purchase-img__wrap">
                    <img class="purchase-img" src="{{ $item['image_url'] }}" alt="">
                </div>
                <div class="purchase-text">
                    <h2 class="purchase-ttl">{{ $item['name'] }}</h2>
                    <div class="purchase-price flex">
                        <span class="purchase-span">&yen;</span>
                        <input class="purchase-price__input" type="text" name="price" value="{{ number_format($item['price']) }}" readonly>
                    </div>
                </div>
            </div>

            <div class="purchase-content border">
                <p class="purchase-ttl">支払い方法</p>
                <div class="purchase-way__select-wrap">
                    <select class="purchase-way__select form-group__item-input" name="payment_id" id="select">
                        <option value="" selected disabled>選択してください</option>
                        @foreach($payments as $payment)
                        <option value="{{ $payment['id'] }}" {{ old('way') == $payment['id'] ? 'selected' : '' }}>{{ $payment['way'] }}</option>
                        @endforeach
                    </select>
                    <div class="error-message">
                        @error('way')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="purchase-content">
                <div class="purchase-ttl__inner flex">
                    <p class="purchase-ttl">配送先</p>
                    <a  class="purchase-address__btn" href="/purchase/address/{{ $item['id'] }}">変更する</a>
                </div>
                <div class="purchase-address__wrap">
                    <div class="purchase-address__item">
                        <span class="purchase-span">&#12306;</span><input class="purchase-cord" type="text" name="post_cord" value="{{ ($address) ? $address['post_cord'] : $user['post_cord'] }}" readonly>
                    </div>
                    <input class="purchase-address" type="text" name="address" value="{{ ($address) ? $address['address'] : $user['address'] }}" readonly>
                    <input class="purchase-address" type="text" name="building" value="{{ ($address) ? $address['building'] : $user['building'] }}" readonly>
                    <div class="error-message">
                        @if ($errors->has('post_cord'))
                        {{$errors->first('post_cord')}}
                        @elseif ($errors->has('address'))
                        {{$errors->first('address')}}
                        @else
                        {{$errors->first('building')}}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="purchase-confirm">
            <table class="purchase-confirm__table">
                <tr class="purchase-confirm__table-row">
                    <th class="purchase-confirm__table-heading border">商品代金</th>
                    <td class="purchase-confirm__table-item border"><span class="purchase-confirm__table-item--span">&yen;&nbsp;</span>{{ number_format($item['price']) }}</td>
                </tr>
                <tr class="purchase-confirm__table-row">
                    <th class="purchase-confirm__table-heading border">支払い方法</th>
                    <td class="purchase-confirm__table-item border" id="selectValue"></td>
                </tr>
            </table>
            <button class="form-btn purchase-btn" type="submit">購入する</button>
        </div>
    </form>
    <script src="{{ asset('js/select.js') }}"></script>
</div>
@endsection