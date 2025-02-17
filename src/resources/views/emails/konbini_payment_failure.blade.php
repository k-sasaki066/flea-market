<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ コンビニ決済失敗のお知らせ</title>
</head>

<body>
    <p>本メールはサーバーからの自動配信メールです。</p>
    <br>
    <p>{{ $data['name'] }} 様</p>
    <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>

    <p>ご注文のコンビニ決済処理が完了しませんでした。</p>
    <p>注文は自動でキャンセルされました。</p>
    <br>
    <p>■ 注文内容</p>
    <ul>
        <li>商品名: {{ $data['item'] }}</li>
        <li>価格: <span>¥</span>{{number_format($data['price'])}}</li>
        <li>支払い期限: {{ $data['expires_at'] }}</li>
    </ul>

    <p>お手数ですが、再度お支払い手続きをお願いいたします。</p>
    <p>ご不明点がございましたら、お問い合わせください。よろしくお願い致します</p>
</body>