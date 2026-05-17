<?php

namespace App\Models;

require_once __DIR__ . '/../../common/db.php';

class ProductModel
{
    public static function fetchAll()
    {
        $dbh = get_dbh();
        $sql = 'SELECT code, name, price FROM mst_product WHERE 1';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $dbh = null;
        return $rows;
    }

    public static function fetchByCode($code)
    {
        $dbh = get_dbh();
        $sql = 'SELECT code, name, price, gazou FROM mst_product WHERE code = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$code]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $dbh = null;
        return $row;
    }
}
