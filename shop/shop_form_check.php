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

.confirm-box {
    background-color: #f7faf6;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
}

.item {
    margin-bottom: 18px;
}

.label {
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
}

.value {
    font-size: 18px;
    font-weight: bold;
}

.error-box {
    background-color: #fff3f3;
    border-left: 6px solid #e53935;
    padding: 15px 18px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.error-message {
    color: #c62828;
    margin-bottom: 10px;
    font-weight: bold;
}

.button-area {
    margin-top: 25px;
    display: flex;
    gap: 10px;
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
    <div class="title">お客様情報確認</div>

<?php

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
$pass2 = $post['pass2'];
$danjo = $post['danjo'];
$birth = $post['birth'];

$okflg = true;
$error_messages = array();

if($onamae == '')
{
    $error_messages[] = 'お名前が入力されていません。';
    $okflg = false;
}

if(preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $email) == 0)
{
    $error_messages[] = 'メールアドレスを正確に入力してください。';
    $okflg = false;
}

if(preg_match('/\A[0-9]+\z/', $postal1) == 0 || preg_match('/\A[0-9]+\z/', $postal2) == 0)
{
    $error_messages[] = '郵便番号は半角数字で入力してください。';
    $okflg = false;
}

if($address == '')
{
    $error_messages[] = '住所が入力されていません。';
    $okflg = false;
}

if(preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel) == 0)
{
    $error_messages[] = '電話番号を正確に入力してください。';
    $okflg = false;
}

if($chumon == 'chumontouroku')
{
    if($pass == '')
    {
        $error_messages[] = 'パスワードが入力されていません。';
        $okflg = false;
    }

    if($pass != $pass2)
    {
        $error_messages[] = 'パスワードが一致しません。';
        $okflg = false;
    }
}

if($okflg == false)
{
    print '<div class="error-box">';
    foreach($error_messages as $message)
    {
        print '<div class="error-message">・' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
    }
    print '</div>';

    print '<form>';
    print '<div class="button-area">';
    print '<input class="button back-button" type="button" onclick="history.back()" value="戻る">';
    print '</div>';
    print '</form>';
}
else
{
    print '<div class="confirm-box">';

    print '<div class="item">';
    print '<div class="label">お名前</div>';
    print '<div class="value">' . htmlspecialchars($onamae, ENT_QUOTES, 'UTF-8') . '</div>';
    print '</div>';

    print '<div class="item">';
    print '<div class="label">メールアドレス</div>';
    print '<div class="value">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</div>';
    print '</div>';

    print '<div class="item">';
    print '<div class="label">郵便番号</div>';
    print '<div class="value">' . htmlspecialchars($postal1, ENT_QUOTES, 'UTF-8') . '-' . htmlspecialchars($postal2, ENT_QUOTES, 'UTF-8') . '</div>';
    print '</div>';

    print '<div class="item">';
    print '<div class="label">住所</div>';
    print '<div class="value">' . htmlspecialchars($address, ENT_QUOTES, 'UTF-8') . '</div>';
    print '</div>';

    print '<div class="item">';
    print '<div class="label">電話番号</div>';
    print '<div class="value">' . htmlspecialchars($tel, ENT_QUOTES, 'UTF-8') . '</div>';
    print '</div>';

    if($chumon == 'chumontouroku')
    {
        print '<div class="item">';
        print '<div class="label">性別</div>';
        print '<div class="value">';
        print ($danjo == 'dan') ? '男性' : '女性';
        print '</div>';
        print '</div>';

        print '<div class="item">';
        print '<div class="label">生まれ年</div>';
        print '<div class="value">' . htmlspecialchars($birth, ENT_QUOTES, 'UTF-8') . '年代</div>';
        print '</div>';
    }

    print '</div>';

    print '<form method="post" action="shop_form_done.php">';
    print '<input type="hidden" name="onamae" value="' . htmlspecialchars($onamae, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="email" value="' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="postal1" value="' . htmlspecialchars($postal1, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="postal2" value="' . htmlspecialchars($postal2, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="address" value="' . htmlspecialchars($address, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="tel" value="' . htmlspecialchars($tel, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="chumon" value="' . htmlspecialchars($chumon, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="pass" value="' . htmlspecialchars($pass, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="danjo" value="' . htmlspecialchars($danjo, ENT_QUOTES, 'UTF-8') . '">';
    print '<input type="hidden" name="birth" value="' . htmlspecialchars($birth, ENT_QUOTES, 'UTF-8') . '">';

    print '<div class="button-area">';
    print '<input class="button back-button" type="button" onclick="history.back()" value="戻る">';
    print '<input class="button submit-button" type="submit" value="ＯＫ">';
    print '</div>';
    print '</form>';
}

?>

</div>

</body>
</html>