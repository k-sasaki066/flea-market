# coachtechフリマアプリ
ログイン後、それぞれの商品へのいいね登録&解除、コメント送信、商品の出品や購入ができます。
<br>
購入の際はクレジットカード払いかコンビニ払いを選択して決済ができ、それぞれの決済に合わせて購入者と出品者にメールが送信されます。
<br>
購入後は商品ごとに購入者と出品者でメッセージのやり取りができ、双方で評価を送信することで取引が完了します。
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
|商品詳細情報が表示されます。いいね登録&解除、商品購入、コメント送信ができます。（ログインユーザーのみ）|購入済み商品の詳細情報画面では、Sold表示され購入ボタンは非表示になります。いいね登録&解除とコメント送信ができます。(ログインユーザーのみ）|

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
|<img width="1450" alt="c-purchase" src="https://github.com/user-attachments/assets/079e607b-041d-4c4c-8b11-083c5095904b" />|<img width="1450" alt="c-address" src="https://github.com/user-attachments/assets/d6d91cd1-0045-44ab-8a58-cbf00ca3f927" />|
|支払い方法と配送先住所を設定できます。購入ボタンをクリックすると決済画面に遷移します。配送先住所欄の「変更する」ボタンをクリックすると配送先設定画面に遷移します。|入力し変更ボタンをクリックすると購入画面の配送先が設定した住所に反映されます。この住所は購入した商品の配送先として紐づけられて登録されます。|

|決済画面|決済キャンセル画面|
| --- | --- |
|<img width="1460" alt="c-stripe" src="https://github.com/user-attachments/assets/7e6151a9-0dbe-4c40-8be6-272fdb54973c" />|<img width="1459" alt="c-cancel" src="https://github.com/user-attachments/assets/8753efc2-abae-4c91-97e1-2096b22f1a13" />|
|メールアドレスとそれぞれの支払い方法での必要項目を入力して決済します。|決済画面にて戻るボタンをクリックするとキャンセル画面に遷移します。|

|カード決済成功画面|コンビニ支払い手順画面|
| --- | --- |
|<img width="1459" alt="f-success" src="https://github.com/user-attachments/assets/0df1048a-6750-4fbe-b469-b1beea0be38d" />|<img width="1370" alt="支払い手順画面に遷移" src="https://github.com/user-attachments/assets/4a8cb099-5710-4ca0-bd01-cd3bb5e4c3b8" />|
|カード払いを選択し、決済が成功すると成功画面に遷移します。「マイページに戻る」ボタンをクリックすると購入商品一覧画面に遷移します。|コンビニ払いを選択し、決済が成功するとコンビニ支払い手順画面に遷移します。支払い可能なそれぞれのコンビニでの支払い手順が確認できます。|

|出品商品一覧画面|購入商品一覧画面|
| --- | --- |
|<img width="1516" alt="出品商品一覧(追加実装)" src="https://github.com/user-attachments/assets/fdb18193-0026-4cf7-85ae-1ef975bcae60" />|<img width="1516" alt="購入商品一覧(追加実装)" src="https://github.com/user-attachments/assets/2c1be232-4066-4298-ab49-c8443c56682c" />|
|マイページの「出品した商品」をクリックすると、出品した商品が表示されます。|マイページの「購入した商品」をクリックすると、購入した商品が表示されます。|

|取引中商品一覧画面|取引画面|
| --- | --- |
|<img width="1515" alt="取引中の商品２" src="https://github.com/user-attachments/assets/4b7ef4fb-6000-431a-95c2-771bfda2d06f" />|<img width="1515" alt="取引画面2" src="https://github.com/user-attachments/assets/39aaa0a7-9a6e-4422-966e-8fea52008fd0" />|
|マイページの「取引中の商品」をクリックすると、現在取引中の商品が表示されます。新着メッセージの総件数、各商品には新着メッセージ件数が表示され、表示商品は新着メッセージが来た順に並び替えられます。<br>相手から受け取った評価がある場合は平均値がユーザー名の下に表示されます。|「取引中の商品」に表示された商品画像をクリックすると各商品の取引画面に遷移します。<br>サイドバーから別の商品の取引ページに遷移できます。<br>購入者の取引画面には右上に取引完了ボタンが表示されます。|

|取引チャット機能|取引チャット例|
| --- | --- |
|<img width="1516" alt="メッセージ編集" src="https://github.com/user-attachments/assets/8d9d1165-9391-4986-b661-56fa966de296" />|<img width="1515" alt="取引画面（画像表示例）" src="https://github.com/user-attachments/assets/92520bfe-4c48-481f-bf6a-0b5dfff29395" />|
|投稿済みのメッセージの編集や削除ができます。<br>1つのメッセージに1枚の画像(任意)を送信できます。<br>メッセージの下にある「編集」をクリックすると編集フォームに切り替わります。|メッセージの下にある「削除」をクリックするとメッセージが削除され、『このメッセージは削除されました』と表示されます。|

|購入者評価画面|出品者評価画面|
| --- | --- |
|<img width="1517" alt="購入者評価画面" src="https://github.com/user-attachments/assets/7d4b648c-229e-4561-936a-b4df3f5ed309" />|<img width="1519" alt="出品者評価画面" src="https://github.com/user-attachments/assets/37a3d605-01ae-4190-8e77-dfaa70949a9d" />|
|「取引を完了する」ボタンをクリックすると評価モーダルが表示され、出品者への評価を送信できます。評価を送信すると取引が完了し、取引中の商品一覧ページから削除されます。<br>購入者の取引が完了すると、出品者へ取引完了通知がメールで送信されます。|購入者の取引完了後、該当商品の取引画面を開くと評価モーダルが表示され、購入者へ評価を送信できます。評価を送信すると取引が完了し、取引中の商品一覧ページから削除されます。|

|出品画面|ログアウト|
| --- | --- |
|<img width="800" alt="c-sell" src="https://github.com/user-attachments/assets/1197ed1e-a135-4590-b6cb-152030cdc420" />||
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

|注文キャンセルメール|購入者取引完了通知メール|
| --- | --- |
|<img width="1099" alt="キャンセルメール" src="https://github.com/user-attachments/assets/bee268ee-52d7-4595-91ca-a9a94ec7e5b8" />|<img width="1191" alt="出品者通知メール" src="https://github.com/user-attachments/assets/652a6512-993c-4dbf-9ca1-0ed05fd5f77f" />|
|コンビニ支払い期限内に支払いが完了しなかった場合に出品者に送信されます。|購入者の取引完了後に出品者に送信されます。メール内のリンクから取引画面に遷移できます。|

## 実行環境
Docker 27.5.1
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
<img width="1110" alt="テーブル仕様書追加ver" src="https://github.com/user-attachments/assets/c40a4fd4-d65f-4b61-bbaf-42e101021c64" />


## ER図
![ER図](src/flea_market.drawio.png)

## 環境構築
<br>
▫️gitクローン

```
git clone https://github.com/k-sasaki066/flea-market.git
```
<br>
▫️docker composeのバージョンによって一部記載が異なるため、はじめにバージョンを確認します。
<br>


```
docker compose version(docker-compose version)
```
<br>
-v1の場合
<br>
docker-compose.ymlファイルのコメントアウトを外してください

```
version: '3.8'　(コメントアウト解除)
```
<br>
-v2の場合
<br>
変更点なし
<br>
<br>
▫️.env.example ファイルをコピーして.env ファイルを作成し、環境変数を変更する
  
  ```
  cp .env.example .env
  ```
  <br>
  ① APP_NAME設定
  
  ```
  APP_NAME=coachtechフリマ
  ```
 
  <br>
  
  ② mysqlの設定(docker-compose.ymlを参照)
  
  ```
  DB_CONNECTION=mysql
  DB_HOST=mysql(変更)
  DB_PORT=3306
  DB_DATABASE=laravel_db(変更)
  DB_USERNAME=laravel_user(変更)
  DB_PASSWORD=laravel_pass(変更)
  ```
  <br>
  ③ mailhogの設定

  ```
  MAIL_MAILER=smtp
  MAIL_HOST=mail
  MAIL_PORT=1025
  MAIL_FROM_ADDRESS="送信元アドレス（例：flea-market@test.com）"
  ```
  <br>
  <img width="800" alt="スクリーンショット 2025-03-11 10 03 52" src="https://github.com/user-attachments/assets/6d876e0f-c13f-44d8-ad19-b28474c62c68" />

  <br>
  <br>
  ④ stripeのアカウント設定
　<br>
  stripe公式ページ(https://stripe.com/jp)
  <br>
  <img width="800" alt="stripe設定１" src="https://github.com/user-attachments/assets/9428feb4-add4-4f49-9594-ee25e4269bcd" />
  <br>
  <img width="800" alt="stripe設定３" src="https://github.com/user-attachments/assets/ea6ed36b-85b4-44eb-82a0-80f48ed6868c" />
  <br>
  <img width="800" alt="stripe設定４" src="https://github.com/user-attachments/assets/d61fb882-7316-4233-9605-a3222b8ffeda" />

  
  ```
  STRIPE_KEY=公開可能キーを貼り付け
  STRIPE_SECRET=シークレットキーを貼り付け
  ```
　<br>
  <br>
  ⑤ ngrokの設定
  <br>
  ngrok公式ページ(https://dashboard.ngrok.com/)
　<br>
  <img width="800" alt="ngrok設定" src="https://github.com/user-attachments/assets/5d27c22d-c7e9-4601-8605-93f441df54bb" />
  <br>
  
  ```
  NGROK_AUTHTOKEN=your_ngrok_auth_token
  ```
  <br>

▫️dockerビルド
```
docker compose up -d --build(docker-compose up -d --build)
```
<br>

> _Mac の M1・M2 チップの PC で設定しています。エラーが発生する場合は、platform: linux/x86_64をコメントアウトしてください。_
> docker-compose.yml ファイルの「mysql」、「phpMyAdmin」, 「mail」, 「ngrok」の4箇所に記載があります。_

```bash
mysql:
    platform: linux/x86_64(この文をコメントアウト)
    image: mysql:8.0.26
    environment:
```

<br>

#### Laravel環境構築
  1. PHPコンテナへ入る
  
  ```
  docker compose exec php bash(docker-compose exec php bash)
  ```
  <br>

  2. composer をインストール
  
  ```
  composer install
  ```

  <br>

  3. アプリケーションキーを取得
  
  ```
  php artisan key:generate
  ```
　<br>
 
  4. テーブル作成
  
  ```
  php artisan migrate
  ```

  <br>
  
  5. ダミーデータ作成
  
  ```
  php artisan db:seed
  ```
  

  <br>
  
  6. シンボリックリンク作成
  
  ```
  php artisan storage:link
  ```
<br>
<br>
  ▫️stripe webhookの設定(stripeのアカウントが作成されている上で設定してください)
　<br>
  <br>
  ▫️Stripe CLI をインストールする(composerではインストールできません)
  <br>
  MacOS（Homebrew）
  
  ```
  brew install stripe/stripe-cli/stripe
  ```
  <br>
  Linux（Curl)
  
  ```
  curl -fsSL https://stripe-cli.github.io/install.sh | bash
  ```
  
  <br>
  Windows（Scoop)

  ```
  scoop install stripe
  ```
  <br>
  <br>
  ▫️Stripe CLI の動作確認
  <br>
  
  ```
  stripe --version
  ```
  →例：stripe version 1.24.0のようにバージョンが表示されればインストール成功
  
  <br>
  <br>
  ▫️ログインして Stripe に接続
  <br>

  ```
  stripe login
  ```
  →ターミナルにYour pairing code is: ************と表示される
  <br>
  →enterを押下するとstripeのブラウザが開くので、ターミナルに表示された『Your pairing code』と同じことを確認してアクセス許可を押下
  <br>
  <img width="500" alt="stripe login認証" src="https://github.com/user-attachments/assets/77b2449c-0e7f-4b7c-995c-dd20a7dbd296" />


  <br>
  <br>
  ▫️ngrokのURLを確認する
  <br>
  ブラウザで ngrok の Web インターフェースにアクセスする (http://localhost:4040)
  <br>
  
  →`https://random-name.ngrok-free.app`のようにngrokのURLが表示されている（random-nameにはランダムに生成された値が入ります）
  <br>
  ※ngrokのコンテナを再起動させるたびにURLが変わるため、その都度Webhookを更新します
  <br>
  <img width="500" alt="4040アクセス" src="https://github.com/user-attachments/assets/776b34d9-d693-4968-ae45-22abc4ba53dc" />


  <br>
  <br>
  ▫️stripeのWebhookを設定する
  <br>
  <br>
  stripeダッシュボードの開発者→Webhook→送信先を追加するを押下
  <br>
  <img width="500" alt="stripe webhook作成" src="https://github.com/user-attachments/assets/18efb72a-b3e2-452d-bb91-a5dda24fae42" />

  <br>
  <br>
  お客様のアカウントを選択
  <br>
  以下の9イベントを送信するイベントとして選択する（保存後に『送信先を編集』にて編集可）
  <br>

  - charge.failed
  - charge.succeeded
  - checkout.session.async_payment_failed
  - checkout.session.async_payment_succeeded
  - checkout.session.completed
  - payment_intent.created
  - payment_intent.payment_failed
  - payment_intent.requires_action
  - payment_intent.succeeded

  <br>
  <img width="500" alt="webhook イベント選択" src="https://github.com/user-attachments/assets/0b3980d4-6f4f-4123-8325-bce5d554be53" />

  <br>
  <br>
  イベント送信先にWebhookエンドポイントを選択
  <br>
  <img width="500" alt="webhoon イベント送信先" src="https://github.com/user-attachments/assets/56240946-64b4-40da-8657-d995faaad603" />

  <br>
  <br>
  エンドポイントURLを設定
  <br>
  ngrok(http://localhost:4040)で確認したURLを以下のように編集し、設定する（保存後に『送信先を編集』にて編集可）
  <br>
  random-nameにはランダムに生成された値が入ります
  <br>
  <br>
  
  `https://random-name.ngrok-free.app` → **`https://random-name.ngrok-free.app/webhook/stripe`**
  <br>
  <img width="500" alt="webhook送信先設定" src="https://github.com/user-attachments/assets/6c4c2984-277b-42e7-9a24-68bd9d9c335a" />
  
  <br>
  <br>
  送信先を作成するを押下すると以下のような画面になります
  <br>
  右にある著名シークレットをコピーし、envファイルに記述する（Webhook の署名検証に必要）
  <br>
  <img width="500" alt="webhook著名シークレット" src="https://github.com/user-attachments/assets/d0025a34-4da1-469d-8375-95a717d03c47" />
  
  ```
  STRIPE_WEBHOOK_SECRET=whsec_***********************
  ```
  <br>
  <br>
  変更後に .env の設定を反映
  
  ```
  php artisan config:clear
  ```

  <br>
  <br>
  ▫️イベント送信をテストする
  <br>
  Webhook画面の右側にあるテストイベントを送信ボタンを押下
  <br>
  実行コマンドが表示されるので、ターミナルで実行する
  <br>
  <img width="500" alt="テストイベント送信" src="https://github.com/user-attachments/assets/3a4fb2f7-01e5-4817-9cfc-99252e7b2954" />
  
  ```
  stripe trigger payment_intent.succeeded
  ```
  <br>
  laravel.logにて✅ Webhook 受信が確認できればOK
  <br>
  <img width="500" alt="webhook テストlaravel" src="https://github.com/user-attachments/assets/5f3ff52a-f594-4a50-aea6-87efd6a49613" />
  <br>
  <br>
  ▫️作成したWebhookを編集する場合
  <br>
  開発者→Webhook→現在使用しているWebhookを選択する
  <br>
  開いた画面の右側にある『送信先を編集』を押下
  <br>
  <img width="500" alt="webhook編集１" src="https://github.com/user-attachments/assets/33a6678b-7c31-4ea7-8555-a883cc3b7aa9" />
  <br>
  <br>
  エンドポイントのURLやイベントを編集できるので、適宜編集して『送信先を保存』を押下
  <br>
  <img width="500" alt="webhook編集２" src="https://github.com/user-attachments/assets/c3410bb9-dbb1-4c77-9565-520db8e0ea47" />
  <br>

## URL

- 開発環境
  - ログインページ <http://localhost/login>
- MailHog <http://localhost:8025>
- phpMyAdmin <http://localhost:8080>
- ngrok <http://localhost:4040>
- stripeドキュメント [テストカード](https://docs.stripe.com/testing?locale=ja-JP&testing-method=card-numbers)

<br>

## ダミーデータ

- User1(item1~item5の出品者)
  - nickname : 出品者1
  - email : `seller1@example.com`
  - password : password1
<br>

- User2(item6~item10の出品者)
  - nickname : 出品者2
  - email : `seller2@example.com`
  - password : password2
<br>

- User3
  - nickname : 未出品ユーザー
  - email : `noitems@example.com`
  - password : password3
 
