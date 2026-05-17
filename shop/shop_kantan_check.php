<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['member_login']) == false)
{
    print '<!DOCTYPE html>';
    print '<html><head><meta charset="UTF-8"><title>ろくまる農園</title></head><body>';
    print 'ログインされていません。<br />';
    print '<a href="shop_list.php">商品一覧へ</a>';
    print '</body></html>';
    exit();
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

.container {
    max-width: 760px;
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

.info-box {
    background-color: #f7faf6;
    padding: 22px;
    border-radius: 12px;
    margin-bottom: 25px;
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
    font-size: 18px;
    font-weight: bold;
}

.message {
    background-color: #fff8e1;
    border-left: 6px solid #ff9800;
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 25px;
    line-height: 1.7;
}

.button-area {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.button {
    padding: 12px 28px;
    border-radius: 8px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    font-size: 15px;
}

.back-button {
    background-color: #ddd;
    color: #333;
}

.back-button:hover {
    background-color: #ccc;
}

.submit-button {
    background-color: #4caf50;
    color: white;
}

.submit-button:hover {
    background-color: #43a047;
}
</style>
</head>

<body>

<div class="header">
    <h1>ろくまる農園</h1>
</div>

<div class="container">
    <div class="title">会員かんたん注文確認</div>

<?php

try
{
    $code = $_SESSION['member_code'];

    require_once('../common/db.php');
    $dbh = get_dbh();

    $sql = 'SELECT name,email,postal1,postal2,address,tel FROM dat_member WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    $dbh = null;

    $onamae = $rec['name'];
    $email = $rec['email'];
    $postal1 = $rec['postal1'];
    $postal2 = $rec['postal2'];
    $address = $rec['address'];
    $tel = $rec['tel'];
}
catch(Exception $e)
{
    print '<div class="message">ただいま障害により大変ご迷惑をお掛けしております。</div>';
    print '</div></body></html>';
    exit();
}

?>

    <div class="message">
        以下の会員情報で注文します。内容をご確認ください。
    </div>

    <div class="info-box">
        <div class="info-row">
            <div class="label">お名前</div>
            <div class="value"><?php print htmlspecialchars($onamae, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <div class="info-row">
            <div class="label">メールアドレス</div>
            <div class="value"><?php print htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <div class="info-row">
            <div class="label">郵便番号</div>
            <div class="value">
                <?php print htmlspecialchars($postal1, ENT_QUOTES, 'UTF-8'); ?>-<?php print htmlspecialchars($postal2, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>

        <div class="info-row">
            <div class="label">住所</div>
            <div class="value"><?php print htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <div class="info-row">
            <div class="label">電話番号</div>
            <div class="value"><?php print htmlspecialchars($tel, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    </div>

    <form method="post" action="shop_kantan_done.php">
        <input type="hidden" name="onamae" value="<?php print htmlspecialchars($onamae, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="email" value="<?php print htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="postal1" value="<?php print htmlspecialchars($postal1, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="postal2" value="<?php print htmlspecialchars($postal2, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="address" value="<?php print htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="tel" value="<?php print htmlspecialchars($tel, ENT_QUOTES, 'UTF-8'); ?>">

        <div class="button-area">
            <input class="button back-button" type="button" onclick="history.back()" value="戻る">
            <input class="button submit-button" type="submit" value="ＯＫ">
        </div>
    </form>
</div>

</body>
</html>