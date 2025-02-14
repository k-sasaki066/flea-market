<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ コンビニ決済完了メール</title>
</head>
<body>
    <p>本メールはサーバーからの自動配信メールです。</p>
    <br>
    <p>{{ $data['name'] }} 様</p>
    <p>この度は、coachtechフリマアプリをご利用いただき誠にありがとうございます。</p>
    <p>以下の注文のコンビニ支払いが完了しましたので、ご連絡します。</p>

    <p>■ 注文内容</p>
    <ul>
        <li>商品名: {{ $data['item'] }}</li>
        <li>価格: <span>¥</span>{{number_format($data['price'])}}</li>
    </ul>

    <br>
    <p>出品者様より商品が発送されるまで、今しばらくお待ちください。</p>
    <p>またのご利用をお待ちしております。</p>
</body>