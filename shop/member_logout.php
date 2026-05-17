<?php
session_start();
$_SESSION = array();

if(isset($_COOKIE[session_name()]) == true)
{
    setcookie(session_name(), '', time() - 42000, '/');
}

session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>

<style>
body {
    font-family: "Yu Gothic", "Meiryo", sans-serif;
    background-color: #f5f7f2;
    margin: 0;
    padding: 0;
    color: #333;
}

.header {
    background-color: #4caf50;
    color: white;
    padding: 20px 40px;
}

.header h1 {
    margin: 0;
    font-size: 28px;
}

.container {
    max-width: 600px;
    margin: 80px auto;
    background-color: white;
    padding: 40px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.message {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 15px;
}

.sub-message {
    color: #666;
    margin-bottom: 30px;
}

.button {
    display: inline-block;
    padding: 12px 28px;
    background-color: #4caf50;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}

.button:hover {
    background-color: #43a047;
}
</style>
</head>

<body>

<div class="header">
    <h1>ろくまる農園</h1>
</div>

<div class="container">
    <div class="message">ログアウトしました</div>
    <div class="sub-message">ご利用ありがとうございました。</div>

    <a class="button" href="shop_list.php">商品一覧へ戻る</a>
</div>

</body>
</html>