@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">
<link rel="stylesheet" href="{{ asset('css/result.css')}}">
@endsection

@section('content')
<div class="result-container">
    <h2 class="form-btn error-title">決済がキャンセルされました</h2>
    <p class="result-message">もう一度お試しください</p>
    <a class="home-btn" href="/">ホームに戻る</a>
</div>
@endsection