# ec-site

このリポジトリはシンプルなPHPベースのECサイト実装（学習用）です。

## 追加内容
以下をこのリポジトリ向けにまとめています：

- テーブル一覧（簡易ER図）
- 主要HTTPエンドポイント一覧（メソッド・主なパラメータ）
- ローカル起動手順
- 初期アカウント情報

---

## テーブル一覧（簡易ER図）
主要なテーブルと主なカラムを示します（詳細は `shop.sql` を参照）。

- `mst_product`
	- code (PK), name, price, gazou
- `mst_staff`
	- code (PK), name, password (MD5ハッシュ)
- `dat_member`
	- code (PK), date, password, name, email, postal1, postal2, address, tel, danjo, born
- `dat_sales`
	- code (PK), date, code_member, name, email, postal1, postal2, address, tel
- `dat_sales_product`
	- code (PK), code_sales (FK dat_sales.code), code_product (FK mst_product.code), price, quantity

（リレーション）
- `dat_sales` 1 --- * `dat_sales_product`
- `mst_product` 1 --- * `dat_sales_product`

---

## 主要HTTPエンドポイント一覧
（URLはローカル環境のルートを `http://localhost:8080/` と想定）

- 商品関連（管理者）
	- GET `/product/pro_list.php` — 商品一覧
	- GET `/product/pro_add.php` — 商品追加フォーム
	- POST `/product/pro_add_check.php` — 入力確認
	- POST `/product/pro_add_done.php` — DB登録（ファイルアップロードは `product/gazou/`）
	- GET/POST `/product/pro_edit.php`, `/product/pro_edit_check.php`, `/product/pro_edit_done.php` — 編集フロー
	- POST `/product/pro_delete.php`, `/product/pro_delete_done.php` — 削除

- ショップ（顧客向け）
	- GET `/shop/shop_list.php` — 商品一覧（公開）
	- GET `/shop/shop_product.php?procode=<code>` — 商品詳細
	- POST `/shop/shop_cartin.php` — カート追加（POST: procode, quantity）
	- GET `/shop/shop_cartlook.php` — カート表示
	- POST `/shop/shop_form_check.php` — 注文フォーム確認
	- POST `/shop/shop_form_done.php` — 注文確定 (INSERT dat_sales, dat_sales_product)
	- POST `/shop/member_login_check.php` — 会員ログイン (POST: email, password)

- スタッフ関連
	- GET `/staff_login/staff_login.html` — スタッフログインフォーム
	- POST `/staff_login/staff_login_check.php` — スタッフ認証 (POST: name, password)
	- GET `/staff_login/staff_top.php` — スタッフトップ（ログイン後）
	- GET `/staff/staff_list.php` — 管理者一覧表示
	- POST `/staff/*_add_check.php`, `/staff/*_add_done.php` — スタッフ追加など

- 注文ダウンロード
	- GET `/order/order_download.php` — CSV生成・ダウンロード
	- POST `/order/order_download_done.php` — ダウンロード完了処理

---

## ローカル起動手順（Docker）
1. Docker と docker-compose を用意します。
2. リポジトリルートで以下を実行（初回はDB初期化が走ります）：

```bash
docker-compose down
docker-compose up -d --build
```

3. ブラウザで `http://localhost:8080/` にアクセス。

注意点:
- `docker-compose.yml` の `web` サービスは `Dockerfile` を使って `pdo_mysql` をインストールするように設定済みです。
- DB初期データは `shop.sql` から自動でインポートされます（ボリュームにより既に初期化済みだと再投入されないことがあります）。

再初期化が必要な場合は DB ボリュームを削除して再構築してください（データ消失に注意）：

```bash
docker-compose down
docker volume rm ec-site_db_data
docker-compose up -d --build
```

---

## 初期アカウント（shop.sqlに含まれるもの）
- スタッフアカウント（`mst_staff`）:
	- 名称: `test` / パスワード: `password` (MD5: 5f4dcc3b5aa765d61d8327deb882cf99)
	- その他複数のサンプルスタッフが挿入されています。

- サンプル注文データ:
	- `dat_sales` に2件のテスト注文が含まれます（メール: `test@test.com`）

---

必要であれば、README に以下を追加できます：
- ER 図の画像（PlantUMLから生成）
- 各エンドポイントのリクエスト/レスポンスの詳細表
- テスト手順（E2Eスクリプト）

ご希望の追加項目を教えてください。

---

## `shop/` ディレクトリの処理フロー（ユーザ視点）
以下は典型的なユーザの操作パスと、関係するファイル・DBテーブルの流れです。実装やデバッグ時の把握に便利な順序で示します。

1) エントリ（商品閲覧）
	- ファイル: `shop/shop_list.php`
	- 動作: 全商品を `mst_product` から読み込み、商品名と価格を表示。
	- 次の操作: 商品詳細へ移動（`shop/shop_product.php?procode=<code>`）またはカートを見る。

2) 商品詳細確認
	- ファイル: `shop/shop_product.php`
	- 入力: GET `procode`
	- 動作: `mst_product` から該当商品（name, price, gazou）を読み込み、画像と価格を表示。カート追加リンク `shop_cartin.php?procode=` を表示。

3) カートに追加
	- ファイル: `shop/shop_cartin.php`
	- 入力: GET `procode`
	- 動作: PHPセッションの `$_SESSION['cart']` と `$_SESSION['kazu']` に商品コードと数量を保存（初回は配列を作成）。
	- レスポンス: カート追加完了メッセージ。ユーザは `shop/shop_cartlook.php` で中身確認。

4) カート確認・数量変更
	- ファイル: `shop/shop_cartlook.php`（編集は `shop/kazu_change.php` が担当）
	- 動作: セッション内のカートを参照し、各商品について `mst_product` から name/price/gazou を取得して表示。数量のPOSTで `kazu_change.php` に飛ばす。

5) 注文フォーム（顧客情報入力）
	- ファイル: `shop/shop_form.html` -> `shop/shop_form_check.php`
	- 動作: 顧客情報（名前、メール、住所、電話など）を入力。会員登録希望の場合はパスワードを入力する。
	- 次の操作: 確認画面から `shop/shop_form_done.php` へPOSTして注文確定。

6) 注文確定（DB書込）
	- ファイル: `shop/shop_form_done.php`
	- 動作:
	  - セッションのカートを読み出す（`$_SESSION['cart']`, `$_SESSION['kazu']`）
	  - 商品情報を `mst_product` から取得して明細を組み立てる
	  - 会員登録を選択している場合: `dat_member` に INSERT（パスワードは MD5 で保存）
	  - `dat_sales` に注文情報を INSERT（code_member には会員コードまたは0）
	  - `dat_sales_product` に明細（code_sales, code_product, price, quantity）をINSERT
	  - メール送信（注文確認）
	  - セッションのカートをクリア

7) 会員の簡単注文フロー
	- ファイル: `shop/shop_kantan_check.php` -> `shop/shop_kantan_done.php`
	- 動作: ログイン済み会員はセッション内の会員コードを参照して `dat_member` から住所等を取得し、フォームを省略して注文を確定できる。

8) 会員ログイン
	- ファイル: `shop/member_login.html` -> `shop/member_login_check.php`
	- 動作: POST の email と password を受け取り、`dat_member` を検索（パスワードはMD5化して比較）。成功時は `$_SESSION['member_login']=1` などをセットして `shop_list.php` へリダイレクト。

9) 注意点（デバッグ／開発）
	- 現行コードは各所で直接DB接続を行っていましたが、`common/db.php` の `get_dbh()` に統一しています。Docker環境では必須です。
	- パスワードは MD5 を使用しており安全ではありません。長期運用では `password_hash`/`password_verify` への移行を検討してください。
	- カートはセッション依存のため、クラスタ環境や永続化が必要なら別実装が必要です。

---

この追記で `shop/` の処理フローはカバーできるはずです。動線を図にしたい場合（mermaid/PlantUML）や、各ファイルの注釈入りリファクタ案も作成できます。どちらがよいですか？