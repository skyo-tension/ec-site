<?php
session_start();
session_regenerate_id(true);
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

.user-area {
    background-color: #ffffff;
    padding: 15px 40px;
    border-bottom: 1px solid #ddd;
}

.user-area a {
    color: #2e7d32;
    text-decoration: none;
    font-weight: bold;
}

.container {
    max-width: 800px;
    margin: 30px auto;
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.product-list {
    margin-top: 20px;
}

.product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}

.product-item a {
    text-decoration: none;
    color: #333;
    font-size: 18px;
}

.product-item a:hover {
    color: #4caf50;
}

.price {
    color: #e67e22;
    font-weight: bold;
}

.cart-button {
    display: inline-block;
    margin-top: 30px;
    padding: 12px 24px;
    background-color: #ff9800;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}

.cart-button:hover {
    background-color: #f57c00;
}
</style>
</head>

<body>

<div class="header">
    <h1>ろくまる農園</h1>
</div>

<div class="user-area">
<?php
if(isset($_SESSION['member_login']) == false)
{
    print 'ようこそゲスト様　';
    print '<a href="member_login.html">会員ログイン</a>';
}
else
{
    print 'ようこそ ';
    print htmlspecialchars($_SESSION['member_name'], ENT_QUOTES, 'UTF-8');
    print ' 様　';
    print '<a href="member_logout.php">ログアウト</a>';
}
?>
</div>

<div class="container">
    <h2>商品一覧</h2>

    <div class="product-list">
<?php

try
{
    require_once('../common/db.php');
    $dbh = get_dbh();

    $sql = 'SELECT code,name,price FROM mst_product WHERE 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;

    while(true)
    {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if($rec == false)
        {
            break;
        }

        print '<div class="product-item">';
        print '<a href="shop_product.php?procode=' . htmlspecialchars($rec['code'], ENT_QUOTES, 'UTF-8') . '">';
        print htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8');
        print '</a>';
        print '<span class="price">';
        print htmlspecialchars($rec['price'], ENT_QUOTES, 'UTF-8') . '円';
        print '</span>';
        print '</div>';
    }
}
catch (Exception $e)
{
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

?>
    </div>

    <a class="cart-button" href="shop_cartlook.php">カートを見る</a>
</div>

</body>
</html>