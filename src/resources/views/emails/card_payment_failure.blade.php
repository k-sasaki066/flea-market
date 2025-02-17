<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ カード決済失敗のお知らせ</title>
</head>

<body>
    <p>本メールはサーバーからの自動配信メールです。</p>
    <br>
    <p>{{ $data['name'] }} 様</p>
    <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>

    <p>ご注文のカード決済処理が失敗しました。</p>
    <br>
    <p>■ エラーメッセージ</p>
    <p>【{{ $data['message'] }}】</p>

    <p>お手数ですが、以下のいずれかの方法で再決済をお願いいたします。</p>
    <ul>
        <li>カード情報を再確認し、もう一度決済を試す</li>
        <li>別のカードを使用する</li>
    </ul>
    <br>
    <p>ご不明点がございましたら、お問い合わせください。よろしくお願い致します</p>
</body>