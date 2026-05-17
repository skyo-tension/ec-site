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

.container {
    max-width: 760px;
    margin: 50px auto;
    background: white;
    padding: 40px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.complete-icon {
    font-size: 48px;
    text-align: center;
    margin-bottom: 15px;
}

.title {
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 12px;
}

.message {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.8;
}

.info-box {
    background-color: #f7faf6;
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 25px;
}

.info-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    border-left: 5px solid #4caf50;
    padding-left: 10px;
}

.info-row {
    margin-bottom: 14px;
}

.label {
    color: #777;
    font-size: 14px;
}

.value {
    font-size: 17px;
    font-weight: bold;
}

.member-box {
    background-color: #fff8e1;
    border-left: 6px solid #ff9800;
    padding: 16px 18px;
    border-radius: 8px;
    margin-bottom: 25px;
    line-height: 1.7;
}

.button-area {
    text-align: center;
    margin-top: 30px;
}

.button {
    display: inline-block;
    padding: 13px 28px;
    background-color: #4caf50;
    color: white;
    border-radius: 8px;
    text-decoration: none;
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

<?php

try
{
    require_once('../common/common.php');

    $post = sanitize($_POST);

    $onamae = $post['onamae'];
    $email = $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];
    $chumon = $post['chumon'];
    $pass = $post['pass'];
    $danjo = $post['danjo'];
    $birth = $post['birth'];

    $honbun = '';
    $honbun .= $onamae . "様\n\nこのたびはご注文ありがとうございました。\n";
    $honbun .= "\n";
    $honbun .= "ご注文商品\n";
    $honbun .= "--------------------\n";

    $cart = $_SESSION['cart'];
    $kazu = $_SESSION['kazu'];
    $max = count($cart);

    require_once('../common/db.php');
    $dbh = get_dbh();

    for($i = 0; $i < $max; $i++)
    {
        $sql = 'SELECT name,price FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[0] = $cart[$i];
        $stmt->execute($data);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $name = $rec['name'];
        $price = $rec['price'];
        $kakaku[] = $price;
        $suryo = $kazu[$i];
        $shokei = $price * $suryo;

        $honbun .= $name . ' ';
        $honbun .= $price . '円 x ';
        $honbun .= $suryo . '個 = ';
        $honbun .= $shokei . "円\n";
    }

    $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE,dat_member WRITE';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $lastmembercode = 0;

    if($chumon == 'chumontouroku')
    {
        $sql = 'INSERT INTO dat_member (password,name,email,postal1,postal2,address,tel,danjo,born) VALUES (?,?,?,?,?,?,?,?,?)';
        $stmt = $dbh->prepare($sql);

        $data = array();
        $data[] = md5($pass);
        $data[] = $onamae;
        $data[] = $email;
        $data[] = $postal1;
        $data[] = $postal2;
        $data[] = $address;
        $data[] = $tel;
        $data[] = ($danjo == 'dan') ? 1 : 2;
        $data[] = $birth;

        $stmt->execute($data);

        $sql = 'SELECT LAST_INSERT_ID()';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastmembercode = $rec['LAST_INSERT_ID()'];
    }

    $sql = 'INSERT INTO dat_sales (code_member,name,email,postal1,postal2,address,tel) VALUES (?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);

    $data = array();
    $data[] = $lastmembercode;
    $data[] = $onamae;
    $data[] = $email;
    $data[] = $postal1;
    $data[] = $postal2;
    $data[] = $address;
    $data[] = $tel;

    $stmt->execute($data);

    $sql = 'SELECT LAST_INSERT_ID()';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastcode = $rec['LAST_INSERT_ID()'];

    for($i = 0; $i < $max; $i++)
    {
        $sql = 'INSERT INTO dat_sales_product (code_sales,code_product,price,quantity) VALUES (?,?,?,?)';
        $stmt = $dbh->prepare($sql);

        $data = array();
        $data[] = $lastcode;
        $data[] = $cart[$i];
        $data[] = $kakaku[$i];
        $data[] = $kazu[$i];

        $stmt->execute($data);
    }

    $sql = 'UNLOCK TABLES';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;

    $honbun .= "送料は無料です。\n";
    $honbun .= "--------------------\n";
    $honbun .= "\n";
    $honbun .= "代金は以下の口座にお振込ください。\n";
    $honbun .= "ろくまる銀行 やさい支店 普通口座 １２３４５６７\n";
    $honbun .= "入金確認が取れ次第、梱包、発送させていただきます。\n\n";

    if($chumon == 'chumontouroku')
    {
        $honbun .= "会員登録が完了いたしました。\n";
        $honbun .= "次回からメールアドレスとパスワードでログインしてください。\n";
        $honbun .= "ご注文が簡単にできるようになります。\n\n";
    }

    $honbun .= "□□□□□□□□□□□□□□\n";
    $honbun .= "　～安心野菜のろくまる農園～\n\n";
    $honbun .= "○○県六丸郡六丸村123-4\n";
    $honbun .= "電話 090-6060-xxxx\n";
    $honbun .= "メール info@rokumarunouen.co.jp\n";
    $honbun .= "□□□□□□□□□□□□□□\n";

    $title = 'ご注文ありがとうございます。';
    $header = 'From:info@rokumarunouen.co.jp';
    $mail_body = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');

    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail($email, $title, $mail_body, $header);

    $title = 'お客様からご注文がありました。';
    $header = 'From:' . $email;
    mb_send_mail('info@rokumarunouen.co.jp', $title, $mail_body, $header);
?>

<div class="container">
    <div class="complete-icon">✅</div>

    <div class="title">ご注文ありがとうございました</div>

    <div class="message">
        <?php print htmlspecialchars($onamae, ENT_QUOTES, 'UTF-8'); ?> 様<br>
        ご注文内容を <?php print htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?> に送信しました。
    </div>

    <div class="info-box">
        <div class="info-title">お届け先情報</div>

        <div class="info-row">
            <div class="label">郵便番号</div>
            <div class="value">
                <?php print htmlspecialchars($postal1, ENT_QUOTES, 'UTF-8'); ?>-<?php print htmlspecialchars($postal2, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>

        <div class="info-row">
            <div class="label">住所</div>
            <div class="value">
                <?php print htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>

        <div class="info-row">
            <div class="label">電話番号</div>
            <div class="value">
                <?php print htmlspecialchars($tel, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    </div>

    <?php if($chumon == 'chumontouroku') { ?>
        <div class="member-box">
            会員登録が完了いたしました。<br>
            次回からメールアドレスとパスワードでログインしてください。<br>
            ご注文が簡単にできるようになります。
        </div>
    <?php } ?>

    <div class="button-area">
        <a class="button" href="shop_list.php">商品画面へ戻る</a>
    </div>
</div>

<?php
}
catch (Exception $e)
{
    print '<div class="container">';
    print '<div class="title">エラー</div>';
    print '<div class="member-box">ただいま障害により大変ご迷惑をお掛けしております。</div>';
    print '<div class="button-area"><a class="button" href="shop_list.php">商品画面へ戻る</a></div>';
    print '</div>';
    exit();
}

?>

</body>
</html>