<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ ご希望商品が売り切れました</title>
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
        <div class="header">ご希望商品が売り切れました</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['purchaser_nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <p>ご希望の商品「<strong>{{ $data['item_name'] }}</strong>」は、すでに他の方が購入されたため、購入できませんでした。</p>

            <br>
            <p>またのご利用をお待ちしております。</p>
        </div>
    </div>
</body>