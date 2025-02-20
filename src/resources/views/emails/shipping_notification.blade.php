<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ 商品の発送をお願い致します</title>
</head>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        padding: 20px;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .header {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        padding-bottom: 10px;
        border-bottom: 2px solid rgb(59, 194, 52);
    }
    .content {
        margin-top: 20px;
        font-size: 16px;
        line-height: 1.5;
    }
</style>

<body>
    <div class="container">
        <div class="header">【重要】商品の発送をお願い致します</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['seller_nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <p>下記商品の決済が完了しましたので、商品の発送をお願い致します。</p>
            <br>
            <p>■ 購入者情報</p>
            <ul>
                <li>購入者&nbsp;:&nbsp; {{ $data['purchaser_nickname'] }}様</li>
                <li>商品名&nbsp;:&nbsp; {{ $data['item'] }}</li>
                <li>価格&nbsp;:&nbsp; <span>¥</span>{{number_format($data['price'])}}</li>
            </ul>
            <br>
            <p>■ 配送先</p>
            <ul>
                <li>郵便番号&nbsp;:&nbsp; &#12306;{{ $data['post_cord'] }}</li>
                <li>住所&nbsp;:&nbsp; {{ $data['address'] }}&nbsp;{{ $data['building'] }}</li>
            </ul>
            <br>
            <p>引き続きcoachtechフリマアプリをよろしくお願い致します。</p>
        </div>
    </div>
</body>