<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ カード決済失敗のお知らせ</title>
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
        <div class="header">【重要】カード決済が失敗しました</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['name'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <br>
            <p>ご注文のカード決済処理が失敗しました。</p>
            <br>
            <p>■ エラーメッセージ</p>
            <p>【{{ $data['message'] }}】</p>
            <br>
            <p>お手数ですが、以下のいずれかの方法で再決済をお願いいたします。</p>
            <ul>
                <li>カード情報を再確認し、もう一度決済を試す</li>
                <li>別のカードを使用する</li>
            </ul>
            <br>
            <p>ご不明点がございましたら、お問い合わせください。よろしくお願い致します</p>
        </div>
    </div>
</body>