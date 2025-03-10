<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ コンビニ決済完了メール</title>
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
        <div class="header">コンビニ決済完了のご連絡</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['purchaser_nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <p>以下の注文のコンビニ支払いが完了しましたので、ご連絡します。</p>
            <br>
            <p>■ 注文内容</p>
            <ul>
                <li>商品名&nbsp;:&nbsp; {{ $data['item'] }}</li>
                <li>価格&nbsp;:&nbsp; <span>¥</span>{{number_format($data['price'])}}</li>
            </ul>
            <br>
            <p>出品者様より商品が発送されるまで、今しばらくお待ちください。</p>
            <p>またのご利用をお待ちしております。</p>
        </div>
    </div>
</body>