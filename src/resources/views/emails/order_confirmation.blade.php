<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ ご注文確認メール</title>
</head>
<body>
    <p>本メールはサーバーからの自動配信メールです。</p>
    <br>
    <p>{{ $data['name'] }} 様</p>
    <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
    <p>以下の内容にてご注文を承りました。</p>

    <p>■ 注文内容</p>
    <ul>
        <li>商品名: {{ $data['item'] }}</li>
        <li>価格: <span>¥</span>{{number_format($data['price'])}}</li>
        <li>支払い方法: {{ $data['payment_method'] }}</li>
    </ul>
    <p>※支払い方法にコンビニ決済を選択した場合、別途支払い方法ご案内メールが送信されます。</p>

    <p>■ 配送先</p>
    <ul>
        <li>郵便番号: {{ $data['post_cord'] }}</li>
        <li>住所: {{ $data['address'] }}&nbsp;{{ $data['building'] }}</li>
    </ul>

    <br>
    <p>引き続きcoachtechフリマアプリをよろしくお願い致します。</p>
</body>