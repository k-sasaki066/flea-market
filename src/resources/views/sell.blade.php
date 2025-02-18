@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="sell-container">
    <h2 class="form-header">商品の出品</h2>
    <form class="sell-form flex" action="/sell" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="sell-img__group">
            <p class="sell-text flex">商品画像<span class="sell-text__required">必須</span></p>
            <div class="sell-img__wrap">
                <div class="sell-img__inner" id="sellFigure">
                    <img class="sell-img" id="figureImage" src="" alt="">
                </div>
                <label class="sell-img__select" for="upload">画像を選択する</label>
                <input class="sell-img__hidden" type="file" id="upload"  name="image_url" accept="image/png, image/jpeg">
            </div>
            <div class="error-message">
                @error('image_url')
                {{ $message }}
                @enderror
            </div>
        </div>
        
        <div class="sell-detail__group flex">
            <h3 class="sell-ttl border">商品の詳細</h3>
            <div class="sell-category__group">
                <p class="sell-text flex">カテゴリー<span class="sell-text__required">必須</span></p>
                <div class="sell-category__wrap flex">
                    @foreach($categories as $category)
                    <input class="sell-category__hidden" type="checkbox" id="{{ $category['name'] }}" name="category[]" value="{{ $category['id'] }}" {{ (is_array(old('category')) && in_array($category['id'], old('category'))) ? 'checked' : '' }}>
                    <label class="sell-category__select" for="{{ $category['name'] }}">{{ $category['name'] }}</label>
                    @endforeach
                </div>
            </div>
            <div class="error-message">
                @error('category')
                {{ $message }}
                @enderror
            </div>

            <div class="sell-condition_group">
                <p class="sell-text flex">商品の状態<span class="sell-text__required">必須</span></p>
                <div class="sell-condition__select-wrap">
                    <select class="sell-condition__select form-group__item-input" name="condition_id" id="">
                        <option value="" selected disabled>選択してください</option>
                        @foreach($conditions as $condition)
                        <option value="{{ $condition['id'] }}" {{ old('condition_id') == $condition['id'] ? 'selected' : '' }}>{{ $condition['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="error-message">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell-content__group flex">
            <h3 class="sell-ttl border">商品名と説明</h3>
            <div class="sell-content__inner">
                <p class="sell-text flex">商品名<span class="sell-text__required">必須</span></p>
                <input class="form-group__item-input" type="text" name="name" value="{{ old('name') }}">
                <div class="error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="sell-content__inner">
                <p class="sell-text">ブランド名</p>
                <input class="form-group__item-input" type="text" name="brand_name" value="{{ old('brand_name') }}">
            </div>
            <div class="sell-content__inner">
                <p class="sell-text flex">商品の説明<span class="sell-text__required">必須</span></p>
                <textarea class="sell-description__textarea form-group__item-input" name="description" rows="5">{{ old('description') }}</textarea>
                <div class="error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="sell-content__inner">
                <p class="sell-text flex">販売価格<span class="sell-text__required">必須</span></p>
                <input class="form-group__item-input" type="text" name="price" placeholder="&yen;" value="{{ old('price') }}">
                <div class="error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <button class="form-btn sell-btn" type="submit" onclick="return confirm('商品を出品しますか？');">出品する</button>
    </form>
    <script src="{{ asset('js/preview.js') }}"></script>
</div>
@endsection