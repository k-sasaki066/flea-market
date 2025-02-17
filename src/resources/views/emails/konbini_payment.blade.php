<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ コンビニ支払いのご案内</title>
</head>
<body>
    <p>本メールはサーバーからの自動配信メールです。</p>
    <br>
    <p>{{ $data['name'] }} 様</p>
    <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
    <p>いただきましたご注文の支払い方法をご案内します。</p>
    <p>ご希望店舗でのお支払い手順をご確認のうえ、期限までにお支払いください。</p>
    <p>各コンビニ毎でお支払い方法が異なりますので、ご注意ください。</p>
    <br>
    <ul>
        <li>購入商品: {{ $data['item'] }}</li>
        <li>合計金額: <span>¥</span>{{number_format($data['price'])}}</li>
        <li>支払い期限: {{ $data['expires_at'] }}</li>
        <li>ご利用可能店舗: ファミリーマート、&nbsp;ローソン、&nbsp;ミニストップ、&nbsp;セイコーマート</li>
        <li>支払い手順: <a href="{{ $data['voucher_url'] }}">{{ $data['voucher_url'] }}</a></li>
    </ul>
    <br>
    <p>お支払い完了後、確認のメールが届きます。</p>
    <p>※お支払いは現金のみです。クレジットカードはご利用いただけません。</p>
    <p>※お支払い期限にお手続きいただけない場合、注文は自動でキャンセルされます。</p>
</body>
</html>