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
    color: #333;
}

.header {
    background-color: #4caf50;
    color: white;
    padding: 20px 40px;
}

.header h1 {
    margin: 0;
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
    max-width: 1000px;
    margin: 40px auto;
    background: white;
    padding: 35px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.title {
    font-size: 24px;
    margin-bottom: 25px;
    border-left: 6px solid #4caf50;
    padding-left: 12px;
}

.empty-box {
    text-align: center;
    padding: 50px 20px;
}

.empty-box .icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.empty-box p {
    font-size: 20px;
    font-weight: bold;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
}

.cart-table th {
    background-color: #f0f7ef;
    padding: 12px;
    border-bottom: 2px solid #ddd;
}

.cart-table td {
    padding: 14px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

.product-name {
    font-weight: bold;
    text-align: left;
}

.product-img img {
    max-width: 90px;
    max-height: 90px;
    border-radius: 8px;
}

.no-image {
    color: #999;
    font-size: 13px;
}

.qty-input {
    width: 60px;
    padding: 6px;
    text-align: center;
}

.price {
    color: #e67e22;
    font-weight: bold;
}

.total-area {
    text-align: right;
    font-size: 22px;
    font-weight: bold;
    margin: 20px 0;
}

.total-price {
    color: #e67e22;
}

.button-area {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 25px;
}

.left-buttons,
.right-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.button {
    display: inline-block;
    padding: 12px 22px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    border: none;
    cursor: pointer;
}

.update-button {
    background-color: #4caf50;
    color: white;
}

.update-button:hover {
    background-color: #43a047;
}

.back-button {
    background-color: #ddd;
    color: #333;
}

.back-button:hover {
    background-color: #ccc;
}

.buy-button {
    background-color: #ff9800;
    color: white;
}

.buy-button:hover {
    background-color: #f57c00;
}

.member-button {
    background-color: #2196f3;
    color: white;
}

.member-button:hover {
    background-color: #1976d2;
}

@media (max-width: 700px) {
    .cart-table {
        font-size: 14px;
    }

    .product-img img {
        max-width: 60px;
    }

    .button-area {
        display: block;
    }

    .button {
        margin-bottom: 8px;
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
    if(isset($_SESSION['cart']) == true)
    {
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];
        $max = count($cart);
    }
    else
    {
        $max = 0;
    }

    if($max == 0)
    {
?>
        <div class="empty-box">
            <div class="icon">🛒</div>
            <p>カートに商品が入っていません。</p>
            <a class="button update-button" href="shop_list.php">商品一覧へ戻る</a>
        </div>
<?php
        exit();
    }

    require_once('../common/db.php');
    $dbh = get_dbh();

    foreach($cart as $key => $val)
    {
        $sql = 'SELECT code,name,price,gazou FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[0] = $val;
        $stmt->execute($data);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $pro_name[] = $rec['name'];
        $pro_price[] = $rec['price'];

        if($rec['gazou'] == '')
        {
            $pro_gazou[] = '';
        }
        else
        {
            $pro_gazou[] = $rec['gazou'];
        }
    }

    $dbh = null;
}
catch(Exception $e)
{
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

?>

    <div class="title">カートの中身</div>

    <form method="post" action="kazu_change.php">

        <table class="cart-table">
            <tr>
                <th>商品</th>
                <th>商品画像</th>
                <th>価格</th>
                <th>数量</th>
                <th>小計</th>
                <th>削除</th>
            </tr>

            <?php
            $total = 0;

            for($i = 0; $i < $max; $i++)
            {
                $subtotal = $pro_price[$i] * $kazu[$i];
                $total += $subtotal;
            ?>
            <tr>
                <td class="product-name">
                    <?php print htmlspecialchars($pro_name[$i], ENT_QUOTES, 'UTF-8'); ?>
                </td>

                <td class="product-img">
                    <?php if($pro_gazou[$i] == '') { ?>
                        <span class="no-image">画像なし</span>
                    <?php } else { ?>
                        <img src="../product/gazou/<?php print htmlspecialchars($pro_gazou[$i], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php } ?>
                </td>

                <td class="price">
                    <?php print htmlspecialchars($pro_price[$i], ENT_QUOTES, 'UTF-8'); ?>円
                </td>

                <td>
                    <input class="qty-input" type="text" name="kazu<?php print $i; ?>" value="<?php print htmlspecialchars($kazu[$i], ENT_QUOTES, 'UTF-8'); ?>">
                </td>

                <td class="price">
                    <?php print htmlspecialchars($subtotal, ENT_QUOTES, 'UTF-8'); ?>円
                </td>

                <td>
                    <input type="checkbox" name="sakujo<?php print $i; ?>">
                </td>
            </tr>
            <?php
            }
            ?>
        </table>

        <div class="total-area">
            合計：
            <span class="total-price"><?php print htmlspecialchars($total, ENT_QUOTES, 'UTF-8'); ?>円</span>
        </div>

        <input type="hidden" name="max" value="<?php print $max; ?>">

        <div class="button-area">
            <div class="left-buttons">
                <input class="button update-button" type="submit" value="数量変更">
                <input class="button back-button" type="button" onclick="history.back()" value="戻る">
            </div>

            <div class="right-buttons">
                <a class="button buy-button" href="shop_form.html">ご購入手続きへ進む</a>

                <?php
                if(isset($_SESSION['member_login']) == true)
                {
                    print '<a class="button member-button" href="shop_kantan_check.php">会員かんたん注文へ進む</a>';
                }
                ?>
            </div>
        </div>

    </form>

</div>

</body>
</html>