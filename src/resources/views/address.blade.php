@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-header">住所の変更</h2>
    <form class="form-group" action="/purchase/address/{{ $item_id }}" method="POST">
        @csrf
        <div class="form-group__item">
            <p class="form-group__item-label">郵便番号</p>
            <input class="form-group__item-input" type="text" name="post_cord" value="{{ old('post_cord') }}">
            <div class="error-message">
                @error('post_cord')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">住所</p>
            <input class="form-group__item-input" type="text" name="address" value="{{ old('address') }}">
            <div class="error-message">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group__item">
            <p class="form-group__item-label">建物名</p>
            <input class="form-group__item-input" type="text" name="building" value="{{ old('building') }}">
        </div>

        <button class="form-btn btn-margin" type="submit">
            更新する
        </button>
        <input type="hidden" name="nickname" value="{{ Auth::user()->nickname }}">
    </form>
</div>

@endsection
