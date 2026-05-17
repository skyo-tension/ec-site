<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園 - 商品詳細</title>

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
    width: 320px;
    min-height: 240px;
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
    font-size: 28px;
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
    .container {
        margin: 20px;
        padding: 25px;
    }

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

<div class="container">
    <div class="title">商品情報</div>

    <div class="product-detail">

        <div class="product-image">
            {if $product.gazou != ''}
                <img src="../product/gazou/{$product.gazou|escape}" alt="{$product.name|escape}">
            {else}
                <span class="no-image">画像なし</span>
            {/if}
        </div>

        <div class="product-info">
            <div class="info-row">
                <div class="label">商品コード</div>
                <div class="value">{$product.code|escape}</div>
            </div>

            <div class="info-row">
                <div class="label">商品名</div>
                <div class="value">{$product.name|escape}</div>
            </div>

            <div class="info-row">
                <div class="label">価格</div>
                <div class="value price">{$product.price|escape} 円</div>
            </div>

            <div class="button-area">
                <a class="cart-button" href="/shop/shop_cartin.php?procode={$product.code|escape}">
                    カートに入れる
                </a>
                <button class="back-button" onclick="history.back()">戻る</button>
            </div>
        </div>

    </div>
</div>

</body>
</html>