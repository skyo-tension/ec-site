<?php
session_start();
session_regenerate_id(true);

require_once('../common/common.php');

$post = sanitize($_POST);

function show_error($message)
{
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
    max-width: 600px;
    margin: 80px auto;
    background: white;
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
    color: #c62828;
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
    <div class="icon">⚠️</div>
    <div class="message"><?php print htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <div class="sub-message">数量を確認して、もう一度入力してください。</div>
    <a class="button" href="shop_cartlook.php">カートに戻る</a>
</div>

</body>
</html>
<?php
    exit();
}

$max = $post['max'];

for($i = 0; $i < $max; $i++)
{
    if(preg_match("/\A[0-9]+\z/", $post['kazu'.$i]) == 0)
    {
        show_error('数量に誤りがあります。');
    }

    if($post['kazu'.$i] < 1 || 10 < $post['kazu'.$i])
    {
        show_error('数量は必ず1個以上、10個までです。');
    }

    $kazu[] = $post['kazu'.$i];
}

$cart = $_SESSION['cart'];

for($i = $max - 1; 0 <= $i; $i--)
{
    if(isset($_POST['sakujo'.$i]) == true)
    {
        array_splice($cart, $i, 1);
        array_splice($kazu, $i, 1);
    }
}

$_SESSION['cart'] = $cart;
$_SESSION['kazu'] = $kazu;

header('Location: shop_cartlook.php');
exit();
?>