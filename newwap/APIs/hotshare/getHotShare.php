<?php
/**
 *
 * 获取热门商品
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/15
 * Time: 10:55
 */
require_once('../../index.php');
//TODO
//Deprecated
try {
    $ztg_code = $_GET["ztg"];
    $num =  $_GET["num"];

    if($num == null){
        $num = 10;
    }

    $sqldomain="select dvalue from ".getTable('dict')." where dkey='domainURL'";
    $res = $db->getAll($sqldomain);
    $domain=$res[0]['dvalue'];

    $sqlProductUrl = "select dvalue from ".getTable('dict')." where dkey='getProductURL'";
    $res = $db->getAll($sqlProductUrl);
    $productUrl=$res[0]['dvalue'];
    $sql = null;
    if($ztg_code != null){
        $sql = " SELECT  c.name AS title ,CONCAT('".$domain."/',b.image) AS src , b.storeprice*b.credit_percent/100  AS jifen, CONCAT('".$productUrl."/',b.product_id) AS buylink , b.storeprice AS money,  b.price AS yuanjia ,b.market_price AS scj from ".getTable('sharehistory_view')." a,".getTable('product')
            ." b,".getTable('product_description')." c,".getTable('product_groups')." d WHERE d.product_id = b.product_id and d.pg_type = 0 and a.product_id = b.product_id AND c.product_id = b.product_id AND c.language_id = 1 and d.pg_name='".$ztg_code."' order by a.collectnum desc LIMIT ".$num;

    }else{
//        SELECT  c.name AS title ,  b.image AS src ,b.points AS jifen, b.product_id AS buylink , b.storeprice AS money,  b.price AS yuanjia ,b.market_price AS scj
//FROM  `mcc_sharehistory_view` a, `mcc_product` b , `mcc_product_description` c WHERE a.product_id = b.product_id AND c.product_id = b.product_id AND c.language_id = 1 LIMIT 10;
        $sql = " SELECT  c.name AS title ,CONCAT('".$domain."/',b.image) AS src , b.storeprice*b.credit_percent/100  AS jifen, CONCAT('".$productUrl."/',b.product_id) AS buylink , b.storeprice AS money,  b.price AS yuanjia ,b.market_price AS scj from ".getTable('sharehistory_view')." a,".getTable('product')
            ." b,".getTable('product_description')." c WHERE a.product_id = b.product_id AND c.product_id = b.product_id AND c.language_id = 1  order by a.collectnum desc LIMIT ".$num;
    }
    $res = $db->getAll($sql);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}

