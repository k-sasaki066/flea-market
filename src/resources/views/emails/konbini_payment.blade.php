<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ コンビニ支払いのご案内</title>
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

    a {
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: inline-block;
        max-width: 550px;
    }
</style>

<body>
    <div class="container">
        <div class="header">コンビニ支払い手続きのご案内</div>
        <div class="content">
            <p>本メールはサーバーからの自動配信メールです。</p>
            <br>
            <p>{{ $data['purchaser_nickname'] }}様</p>
            <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
            <p>いただきましたご注文の支払い方法をご案内します。</p>
            <p>ご希望店舗でのお支払い手順をご確認のうえ、期限までにお支払いください。</p>
            <p>各コンビニ毎でお支払い方法が異なりますので、ご注意ください。</p>
            <br>
            <ul>
                <li>購入商品&nbsp;:&nbsp; {{ $data['item'] }}</li>
                <li>合計金額&nbsp;:&nbsp; <span>¥</span>{{number_format($data['price'])}}</li>
                <li>支払い期限&nbsp;:&nbsp; {{ $data['expires_at'] }}</li>
                <li>ご利用可能店舗&nbsp;:&nbsp; ファミリーマート、&nbsp;ローソン、&nbsp;ミニストップ、&nbsp;セイコーマート</li>
                <li>支払い手順&nbsp;:&nbsp; <a href="{{ $data['voucher_url'] }}">{{ $data['voucher_url'] }}</a></li>
            </ul>
            <br>
            <p>お支払い完了後、確認のメールが届きます。</p>
            <p>※お支払いは現金のみです。クレジットカードはご利用いただけません。</p>
            <p>※お支払い期限にお手続きいただけない場合、注文は自動でキャンセルされます。</p>
        </div>
    </div>
</body>
</html>