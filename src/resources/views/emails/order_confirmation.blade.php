<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ ご注文確認メール</title>
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
        <div class="header">ご注文内容の確認</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['purchaser_nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <p>以下の内容にてご注文を承りました。</p>
            <br>
            <p>■ 注文内容</p>
            <ul>
                <li>商品名&nbsp;:&nbsp; {{ $data['item'] }}</li>
                <li>価格&nbsp;:&nbsp; <span>&yen;</span>{{number_format($data['price'])}}</li>
                <li>支払い方法&nbsp;:&nbsp; {{ $data['payment_method'] }}</li>
            </ul>
            <p>※支払い方法にコンビニ決済を選択した場合、別途支払い方法ご案内メールが送信されます。</p>
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