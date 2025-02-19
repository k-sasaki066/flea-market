<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ コンビニ決済失敗のお知らせ</title>
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
        border-bottom: 2px solid #e24a4a;
    }
    .content {
        margin-top: 20px;
        font-size: 16px;
        line-height: 1.5;
    }
</style>

<body>
    <div class="container">
        <div class="header">【重要】コンビニ決済が失敗しました</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['purchaser_nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>

            <p>ご注文のコンビニ決済処理が完了しませんでした。</p>
            <p>注文は自動でキャンセルされました。</p>
            <br>
            <p>■ 注文内容</p>
            <ul>
                <li>商品名&nbsp;:&nbsp; {{ $data['item'] }}</li>
                <li>価格&nbsp;:&nbsp; <span>¥</span>{{number_format($data['price'])}}</li>
                <li>支払い期限&nbsp;:&nbsp; {{ $data['expires_at'] }}</li>
            </ul>
            <br>
            <p>お手数ですが、再度お支払い手続きをお願いいたします。</p>
            <p>ご不明点がございましたら、お問い合わせください。よろしくお願い致します</p>
        </div>
    </div>
</body>