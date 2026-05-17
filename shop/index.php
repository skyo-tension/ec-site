<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../common/common.php';

use App\Controllers\ShopController;

// Initialize Smarty
$smarty = new \Smarty();
$smarty->setTemplateDir(__DIR__ . '/../app/views/templates');
$smarty->setCompileDir(__DIR__ . '/../app/views/templates_c');

// Simple routing by action param
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
switch ($action) {
    case 'list':
        ShopController::listAction($smarty);
        break;
    case 'product':
        ShopController::productAction($smarty);
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Not found';
}
