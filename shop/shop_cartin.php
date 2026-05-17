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

$message = '';
$is_duplicate = false;

try
{
    $pro_code = $_GET['procode'];

    if(isset($_SESSION['cart']) == true)
    {
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];

        if(in_array($pro_code, $cart) == true)
        {
            $message = 'その商品はすでにカートに入っています。';
            $is_duplicate = true;
        }
    }

    if($is_duplicate == false)
    {
        $cart[] = $pro_code;
        $kazu[] = 1;

        $_SESSION['cart'] = $cart;
        $_SESSION['kazu'] = $kazu;

        $message = 'カートに追加しました。';
    }
}
catch(Exception $e)
{
    $message = 'ただいま障害により大変ご迷惑をお掛けしております。';
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
    max-width: 600px;
    margin: 80px auto;
    background-color: white;
    padding: 40px;
    text-align: center;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.icon {
    font-size: 46px;
    margin-bottom: 15px;
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

.button-area {
    margin-top: 25px;
}

.button {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    margin: 5px;
}

.list-button {
    background-color: #4caf50;
    color: white;
}

.list-button:hover {
    background-color: #43a047;
}

.cart-button {
    background-color: #ff9800;
    color: white;
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
    <?php print $member_message; ?>
</div>

<div class="container">
    <div class="icon">
        <?php print $is_duplicate ? '⚠️' : '🛒'; ?>
    </div>

    <div class="message">
        <?php print htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    </div>

    <div class="sub-message">
        <?php if($is_duplicate == true) { ?>
            同じ商品は重複して追加できません。
        <?php } else { ?>
            商品がカートに入りました。
        <?php } ?>
    </div>

    <div class="button-area">
        <a class="button list-button" href="shop_list.php">商品一覧に戻る</a>
        <a class="button cart-button" href="shop_cartlook.php">カートを見る</a>
    </div>
</div>

</body>
</html>