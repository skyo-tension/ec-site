(# ec-site — 小規模ECサンプル (MVC PoC)

簡潔な説明とセットアップ手順をまとめています。

## 機能概要
- 商品一覧表示、商品詳細表示
- 会員データ用のダンプ（旧仕様）を含むSQLダンプ（`shop.sql`）

## 環境構築手順（簡潔）
1. リポジトリをクローンする。
2. Docker (docker-compose) を利用する場合は `docker-compose up -d --build` を実行して Web と DB を起動する。Web コンテナには PHP (pdo_mysql) と Composer が入っています。
3. 依存をインストールする（コンテナ内で）:

```bash
# コンテナ内で実行例
composer install
```

4. データベースにスキーマを適用する（`shop.sql` をインポート）。
5. ブラウザで `http://localhost:8080/` を開き、トップの「商品注文」から動作確認する。

## アーキテクチャ構成（現在の状態）
- Front controller: `shop/index.php`（エントリポイント、action パラメータでルーティング）
- Controllers: `app/controllers/`（例: `ShopController`）
- Models: `app/models/`（例: `ProductModel`） — DB へのアクセスを担当
- Views / Templates: `app/views/templates/`（Smarty テンプレート）
- 共通ユーティリティ: `common/common.php`
- Composer autoload: `vendor/autoload.php`（PSR-4: `App\` → `app/`）
)
