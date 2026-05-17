<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ShopController
{
    public static function listAction($smarty)
    {
        $products = ProductModel::fetchAll();
        $smarty->assign('products', $products);
        $smarty->display(__DIR__ . '/../views/templates/shop_list.tpl');
    }

    public static function productAction($smarty)
    {
        $procode = isset($_GET['procode']) ? $_GET['procode'] : null;
        if (!$procode) {
            header('HTTP/1.0 400 Bad Request');
            echo 'procode required';
            return;
        }

        $product = ProductModel::fetchByCode($procode);
        if (!$product) {
            header('HTTP/1.0 404 Not Found');
            echo 'Product not found';
            return;
        }

        $smarty->assign('product', $product);
        $smarty->display(__DIR__ . '/../views/templates/shop_product.tpl');
    }
}
