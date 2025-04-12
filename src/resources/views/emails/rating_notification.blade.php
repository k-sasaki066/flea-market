<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ 取引が完了しました</title>
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
        <div class="header">取引が完了しました</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $transaction['seller']['nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <p>以下の取引が完了しましたので、お知らせいたします。</p>

            <br>
            <p><strong>■ 商品名&nbsp;:&nbsp;</strong>{{ $transaction['purchase']['item']['name'] }}</p>
            <p><strong>■ 購入者&nbsp;:&nbsp;</strong>{{ $transaction['buyer']['nickname'] }}様</p>
            <p><strong>■ 評価&nbsp;:&nbsp;</strong>{{ $rating['rating'] }}</p>

            <br>
            <p>{{ $transaction['buyer']['nickname'] }}様への評価を送りましょう！</p>
            <a href="{{ url('http://localhost/transaction/' .$transaction->id) }}">評価を送る</a>
            <br>
            <p>またのご利用をお待ちしております。</p>
        </div>
    </div>
</body>