<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['member_login']) == false)
{
    $member_message = 'ようこそゲスト様　<a href="member_login.html">会員ログイン</a>';
}
else
{
    $member_name = htmlspecialchars($_SESSION['member_name'], ENT_QUOTES, 'UTF-8');
    $member_message = 'ようこそ ' . $member_name . ' 様　<a href="member_logout.php">ログアウト</a>';
}
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
    background-color: #fff;
    padding: 15px 40px;
    border-bottom: 1px solid #ddd;
}

.user-area a {
    color: #2e7d32;
    font-weight: bold;
    text-decoration: none;
}

.container {
    max-width: 850px;
    margin: 40px auto;
    background-color: white;
    padding: 35px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.title {
    font-size: 24px;
    margin-bottom: 30px;
    border-left: 6px solid #4caf50;
    padding-left: 12px;
}

.product-detail {
    display: flex;
    gap: 35px;
    align-items: flex-start;
}

.product-image {
    width: 300px;
    min-height: 220px;
    background-color: #f0f0f0;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image img {
    max-width: 100%;
    height: auto;
}

.no-image {
    color: #999;
}

.product-info {
    flex: 1;
}

.info-row {
    margin-bottom: 18px;
}

.label {
    color: #777;
    font-size: 14px;
    margin-bottom: 5px;
}

.value {
    font-size: 20px;
    font-weight: bold;
}

.price {
    color: #e67e22;
    font-size: 26px;
}

.button-area {
    margin-top: 30px;
}

.cart-button,
.back-button {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    margin-right: 10px;
}

.cart-button {
    background-color: #ff9800;
    color: white;
}

.cart-button:hover {
    background-color: #f57c00;
}

.back-button {
    background-color: #ddd;
    color: #333;
    border: none;
    cursor: pointer;
}

.back-button:hover {
    background-color: #ccc;
}

@media (max-width: 700px) {
    .product-detail {
        flex-direction: column;
    }

    .product-image {
        width: 100%;
    }
}
</style>
</head>

<body>

<div class="header">
    <h1>ろくまる農園</h1>
</div>

<div class="user-area">
    <?php print $member_message; ?>
</div>

<div class="container">

<?php

try
{
    $pro_code = $_GET['procode'];

    require_once('../common/db.php');
    $dbh = get_dbh();

    $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_gazou_name = $rec['gazou'];

    $dbh = null;
}
catch(Exception $e)
{
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

?>

    <div class="title">商品情報</div>

    <div class="product-detail">

        <div class="product-image">
            <?php if($pro_gazou_name == '') { ?>
                <span class="no-image">画像なし</span>
            <?php } else { ?>
                <img src="../product/gazou/<?php print htmlspecialchars($pro_gazou_name, ENT_QUOTES, 'UTF-8'); ?>">
            <?php } ?>
        </div>

        <div class="product-info">

            <div class="info-row">
                <div class="label">商品コード</div>
                <div class="value">
                    <?php print htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>

            <div class="info-row">
                <div class="label">商品名</div>
                <div class="value">
                    <?php print htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>

            <div class="info-row">
                <div class="label">価格</div>
                <div class="value price">
                    <?php print htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8'); ?>円
                </div>
            </div>

            <div class="button-area">
                <a class="cart-button" href="shop_cartin.php?procode=<?php print htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8'); ?>">
                    カートに入れる
                </a>

                <button class="back-button" onclick="history.back()">戻る</button>
            </div>

        </div>

    </div>

</div>

</body>
</html>