<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/15
 * Time: 13:49
 */
require_once('../../index.php');

    $sqldomain="select dvalue from ".getTable('dict')." where dkey='domainURL'";
    $res = $db->getAll($sqldomain);
    $domain=$res[0]['dvalue'];
    $productId = $_GET["product_id"];
//    SELECT a.image AS topic,b.name AS title, a.price AS money ,a.market_price AS yuanjia FROM mcc_product a, mcc_product_description b
//WHERE a.product_id = b.product_id AND b.language_id = 1 AND a.product_id = 28
    $sqlProduct = "SELECT  CONCAT('".$domain."/',a.image) AS topic,b.name AS title, a.storeprice  AS money ,a.price AS yuanjia ,a.market_price AS scj, a.storeprice*a.credit_percent/100 AS minge  FROM " . getTable('product') . " a, " . getTable('product_description') . " b where a.product_id = b.product_id AND b.language_id = 1 AND a.product_id =" . $productId . " limit 1";
    $shareNumSql = "SELECT collect as shareNum from " . getTable('sharehistory_view') . " where product_id =" . $productId . " limit 1";
    $commentNummSql = "SELECT commentnum as commentNum from " . getTable('commenthistory_view') . " where product_id =" . $productId . " limit 1";
    $collectNumSql = "SELECT sharenum as collectNum from " . getTable('collecthistory_view') . " where product_id =" . $productId . " limit 1";
    // 产品信息
    $product = $db->getAll($sqlProduct);
    if (count($product) == 0) {
        throw new exception("产品不存在,id=" . $productId);
    }
    return $product[0];
