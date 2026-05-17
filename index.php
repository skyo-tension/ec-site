<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ec-site - ホーム</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
      color: #333;
    }

    .container {
      max-width: 900px;
      margin: 60px auto;
      padding: 40px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    h1 {
      margin-top: 0;
      font-size: 36px;
      color: #2c3e50;
      text-align: center;
    }

    .description {
      text-align: center;
      color: #666;
      margin-bottom: 40px;
    }

    .menu {
      list-style: none;
      padding: 0;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }

    .menu a {
      display: block;
      padding: 20px;
      text-decoration: none;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      color: #2c3e50;
      font-weight: bold;
      transition: 0.2s;
    }

    .menu a:hover {
      background: #3498db;
      color: #fff;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(52, 152, 219, 0.25);
    }

    .debug {
      margin-top: 40px;
      padding: 16px;
      background: #f1f5f9;
      border-left: 5px solid #3498db;
      border-radius: 8px;
      color: #555;
    }

    code {
      background: #e2e8f0;
      padding: 3px 6px;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>ec-site</h1>

    <p class="description">
      このリポジトリの入口ページです。以下から主要機能へ移動できます。
    </p>

    <ul class="menu">
      <li><a href="/shop/member_login.html">会員ログイン</a></li>
  <li><a href="/shop/index.php?action=list">商品注文</a></li>
    </ul>

    <p class="debug">
      開発・デバッグ用：Dockerで立ち上げたら
      <code>http://localhost:8080/</code>
      を開いてください。
    </p>
  </div>
</body>
</html>