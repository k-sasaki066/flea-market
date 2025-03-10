# coachtechフリマアプリ
ログイン後、それぞれの商品へのいいね登録&解除、コメント送信、商品の出品や購入ができます。
<br>
購入の際はクレジットカード払いかコンビニ払いを選択して決済ができ、それぞれの決済に合わせて購入者と出品者にメールが送信されます。
<br>

### TOPページ
<img width="1445" alt="c-top2" src="https://github.com/user-attachments/assets/46fff86e-2b74-40f5-9005-17b931b213e2" />

## 作成した目的
学習のアウトプットのため
<br>
企業のフリマアプリを開発
<br>
<br>

## 機能一覧
|会員登録画面|ログイン画面|
| --- | --- |
|<img width="1387" alt="c-会員登録" src="https://github.com/user-attachments/assets/bcb6f711-176d-4781-9faa-4c4f9f1963ba" />|<img width="1385" alt="　c-ログイン" src="https://github.com/user-attachments/assets/ce1d68f9-0c66-4967-9638-0a6a5cf8c46d" />|
|名前、メールアドレス、パスワード、確認パスワードを入力して登録できます。|メールアドレス、パスワードを入力するとログインできます。|

|トップ画面|
| --- |
|<img width="1445" alt="c-top2" src="https://github.com/user-attachments/assets/ce6ea5b9-b1b7-4562-b702-096e92d6e0ce" />|
|未ログインユーザーは全商品一覧が表示されます。ログインユーザーは出品商品以外の商品一覧が表示されます。<br>検索バーで商品を検索でき、購入済みの商品はSold表示されます。|

|商品詳細画面（未購入商品）|商品詳細画面（購入済み商品）|
| --- | --- |
|<img width="1463" alt="f-detail" src="https://github.com/user-attachments/assets/9c3666f5-eefa-4d4e-a55e-1f67ed395c21" />|<img width="1458" alt="c-detail" src="https://github.com/user-attachments/assets/4dd8c7ac-8651-4cd7-909a-3c5bf8542bd4" />|
|商品詳細情報が表示されます。⭐️マークをクリックすることでいいね登録&解除、商品購入、コメント送信ができます。（ログインユーザーのみ）|購入済み商品の詳細情報画面では、Sold表示され購入ボタンは非表示になります。いいね登録&解除とコメント送信ができます。|

|メール認証画面|認証メール|
| --- | --- |
|<img width="1387" alt="c-メール認証" src="https://github.com/user-attachments/assets/363ed56d-03a0-49d1-b393-3c92e20fbe4e" />|<img width="1381" alt="スクリーンショット 2025-03-05 9 45 11" src="https://github.com/user-attachments/assets/2a4d8905-0eff-4ece-94e2-345be70a3180" />|
|会員登録後、登録したメールアドレスに本人確認のための認証メールが送信されます。再送ボタンをクリックすると認証メールを再送信できます。|送信されたメールの認証ボタンをクリックすることでログインができます。|

|プロフィール設定画面（未設定状態）|プロフィール設定画面（設定済み状態）|
| --- | --- |
|<img width="1459" alt="c-profile" src="https://github.com/user-attachments/assets/4f4c87e6-930e-4105-9a7f-7976d1ee715f" />|<img width="1459" alt="スクリーンショット 2025-03-10 14 07 55" src="https://github.com/user-attachments/assets/652f8f79-79de-4cea-9016-23d75698e169" />|
|画像（任意選択）、ユーザー名（サイト上に表示されるニックネーム）、郵便番号、住所、建物名（任意）を入力し登録します。<br>初回ログイン時に設定画面に遷移しますが、プロフィールが未設定の場合は次回以降のログイン時にも設定画面に遷移します。プロフィールが未設定の状態でコメント送信、出品、購入はできません。|マイページのプロフィール編集ボタンよりプロフィールを編集できます。現在設定している情報が表示されます。|

|おすすめ商品画面|マイリスト商品画面|
| --- | --- |
|<img width="1457" alt="c-suggest" src="https://github.com/user-attachments/assets/5cf48901-b467-49e3-a62e-9beca38b1c16" />|<img width="1459" alt="c-like" src="https://github.com/user-attachments/assets/8133aa5e-e2fc-4f14-a316-7386d8371975" />|
|トップ画面の「おすすめ」をクリックするとおすすめ商品が表示されます。<br>ログインユーザーでいいね登録のある場合は、いいね登録している商品のカテゴリーを参考に表示されます。<br>未ログインユーザーやいいね登録していないログインユーザーは状態の良い商品が表示されます。|トップ画面の「マイリスト」をクリックするといいね登録している商品が表示されます。検索バーで商品を検索できます。<br>未ログインユーザーは「マイリスト」は表示されません。|

|購入画面|配送先設定画面|
| --- | --- |
|<img width="1550" alt="c-purchase" src="https://github.com/user-attachments/assets/079e607b-041d-4c4c-8b11-083c5095904b" />|<img width="1300" alt="c-address" src="https://github.com/user-attachments/assets/d6d91cd1-0045-44ab-8a58-cbf00ca3f927" />|
|支払い方法と配送先住所を設定できます。|購入画面の配送先住所欄の「変更する」ボタンをクリックすると配送先設定画面に遷移します。入力し変更ボタンをクリックすると購入画面の配送先が設定した住所に反映されます。この住所は購入した商品の配送先として紐づけられて登録されます。|

|決済画面|決済キャンセル画面|
| --- | --- |
|<img width="1460" alt="c-stripe" src="https://github.com/user-attachments/assets/7e6151a9-0dbe-4c40-8be6-272fdb54973c" />|<img width="1459" alt="c-cancel" src="https://github.com/user-attachments/assets/8753efc2-abae-4c91-97e1-2096b22f1a13" />|
|購入画面で購入ボタンをクリックすると決済画面に遷移します。メールアドレスとそれぞれの支払い方法での必要項目を入力して決済します。|決済画面にて戻るボタンをクリックするとキャンセル画面に遷移します。|

|カード決済成功画面|コンビニ支払い手順画面|
| --- | --- |
|<img width="1459" alt="f-success" src="https://github.com/user-attachments/assets/0df1048a-6750-4fbe-b469-b1beea0be38d" />|<img width="1370" alt="支払い手順画面に遷移" src="https://github.com/user-attachments/assets/4a8cb099-5710-4ca0-bd01-cd3bb5e4c3b8" />|
|カード払いを選択し、決済が成功すると成功画面に遷移します。「マイページに戻る」ボタンをクリックすると購入商品一覧画面に遷移します。|コンビニ払いを選択し、決済が成功するとコンビニ支払い手順画面に遷移します。支払い可能なそれぞれのコンビニでの支払い手順が確認できます。|

|出品商品一覧画面|購入商品一覧画面|
| --- | --- |
|<img width="1459" alt="c-mypage" src="https://github.com/user-attachments/assets/6d7c7bcf-0f42-44d6-8f97-eadfef1f5764" />|<img width="1458" alt="c-mypage2" src="https://github.com/user-attachments/assets/239988c5-02a7-4713-a4d1-896a96284bb7" />|
|マイページの「出品した商品」をクリックすると、出品した商品が表示されます。|マイページの「購入した商品」をクリックすると、購入した商品が表示されます。|

|出品画面|ログアウト|
| --- | --- |
|<img width="1300" alt="c-sell" src="https://github.com/user-attachments/assets/1197ed1e-a135-4590-b6cb-152030cdc420" />||
|商品画像、カテゴリー（複数選択可）、状態、商品名、ブランド（任意）、説明、価格を入力し商品を登録できます。|ログアウトボタンをクリックするとサイトからログアウトできます。|

### 購入者へのメール
|注文確認メール|コンビニ支払い手順メール|
| --- | --- |
|<img width="1086" alt="注文内容確認メール" src="https://github.com/user-attachments/assets/cb6c1cbb-3e39-4502-b19b-2b6c91a7d999" />|<img width="1082" alt="支払い手順メール" src="https://github.com/user-attachments/assets/d9995b6d-d8c3-456d-b4a6-c6b2a8ede10d" />|
|決済完了時に購入した商品の内容と配送先を確認するメール。カード決済、コンビニ決済共通で送信されます。|コンビニ決済を選択して支払いボタンをクリックした際に、登録したメールアドレスにも支払い期限と手順が送信されます。|

|コンビニ決済完了メール|カード決済失敗メール|
| --- | --- |
|<img width="1098" alt="決済完了" src="https://github.com/user-attachments/assets/0fd362f5-5349-4dd6-aff3-cd4aebd51950" />|<img width="1085" alt="カード失敗メール" src="https://github.com/user-attachments/assets/3adaef7e-65b2-4e97-b946-776a580d2859" />|
|支払い期限内に支払いが完了した際に送信されます。|カード決済時に残高不足などの理由で決済が失敗した際に送信されます。|

|コンビニ決済失敗メール|売り切れメール|
| --- | --- |
|<img width="1083" alt="失敗メール" src="https://github.com/user-attachments/assets/d3662568-e294-4393-8dda-4406f97a43b0" />|<img width="1102" alt="売り切れメール" src="https://github.com/user-attachments/assets/5b970686-2bea-4f13-a5b6-2396d0533978" />|
|支払い期限までに支払いが完了しなかった場合に送信されます。|既に他の購入者によって購入済みの商品を購入した場合に送信されます。|

### 出品者へのメール
|商品売上通知メール|発送準備通知メール|
| --- | --- |
|<img width="1099" alt="売れましたメール" src="https://github.com/user-attachments/assets/e480092b-13ca-469a-8fbe-f1619108317e" />|<img width="1081" alt="発送準備メール" src="https://github.com/user-attachments/assets/a43a0776-d8b0-46a0-8c50-dbaeb63b30c8" />|
|商品の購入完了時に出品者に送信されます。|カード決済とコンビニ決済それぞれで決済が完了した時点で出品者に送信されます。|

|注文キャンセルメール|
| --- |
|<img width="1099" alt="キャンセルメール" src="https://github.com/user-attachments/assets/bee268ee-52d7-4595-91ca-a9a94ec7e5b8" />|
|購入者がコンビニ支払い期限内に支払いが完了しなかった場合に出品者に送信されます。|

## 実行環境
Docker 27.4.0
<br>
nginx 1.21.1
<br>
php 8.3.8
<br>
mysql 8.0.26
<br>
phpMyAdmin 5.2.1
<br>
Mailhog
<br>
ngrok
<br>
stripe CLI 1.24.0

## 使用技術
Laravel Framework 8.83.8
<br>
Laravel Fortify
<br>
Stripe
<br>
HTML/CSS
<br>
Javascript
<br>
PHP
<br>

## テーブル設計
<br>
<img width="710" alt="テーブル仕様書" src="https://github.com/user-attachments/assets/91de8f90-f7f0-44b6-831f-4803f761ee7a" />

## ER図
![ER図](src/flea_market.drawio.png)
