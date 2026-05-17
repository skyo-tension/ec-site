<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園 - 商品一覧</title>

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
    padding: 24px 40px;
}

.header h1 {
    margin: 0;
    font-size: 28px;
}

.container {
    max-width: 900px;
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

.product-list {
    display: grid;
    gap: 16px;
}

.product-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f7faf6;
    padding: 18px 22px;
    border-radius: 12px;
    border: 1px solid #e0eadf;
}

.product-name {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}

.product-name:hover {
    color: #4caf50;
}

.price {
    font-size: 18px;
    font-weight: bold;
    color: #e67e22;
}

.empty-message {
    text-align: center;
    color: #777;
    padding: 40px 0;
}

@media (max-width: 600px) {
    .container {
        margin: 20px;
        padding: 25px;
    }

    .product-card {
        display: block;
    }

    .price {
        margin-top: 10px;
    }
}
</style>
</head>

<body>

<div class="header">
    <h1>ろくまる農園</h1>
</div>

<div class="container">
    <div class="title">商品一覧</div>

    <div class="product-list">
        {foreach $products as $p}
            <div class="product-card">
                <a class="product-name" href="/shop/index.php?action=product&procode={$p.code|escape}">
                    {$p.name|escape}
                </a>
                <div class="price">{$p.price|escape} 円</div>
            </div>
        {foreachelse}
            <div class="empty-message">商品が登録されていません。</div>
        {/foreach}
    </div>
</div>

</body>
</html>