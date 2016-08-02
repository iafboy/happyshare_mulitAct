<?php

class CollectionController
{
    private $db;
    private $log;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }

    //收藏
    public function publishCollection($productId, $customerId)
    {
        //检查是否已经收藏
        $sql = "select count(1) as num from " . getTable('collect_view') . " where customer_id=" . $customerId . " and product_id = " . $productId;
        $res = $this->db->getAll($sql);
        $num = $res[0]['num'];
        if ($num > 0) {
            throw new exception("已经添加收藏夹");
        }

        //检查数组picPaths,中数量，最多5个
        //将数据写入mcc_customer_ophistory，其中operation_type=3
        $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 3, customer_id='" . (int)$customerId . "', createTime =  NOW()";
        $this->db->query($sql);
    }

    public function  removeCollection($productId, $customerId)
    {
        $sql = "delete from " . getTable('customer_ophistory') . " where operation_type = 3 and product_id = " . (int)$productId . " and customer_id = " . (int)$customerId;
        $this->db->query($sql);
    }


    //查询Collection列表
    public function queryMyCollection($customerId,$productId)
    {
        //组合查询mcc_customer_ophistory表以及mcc_product表，其中operation_type=3,stastus=0
        $domainUrl = $this->getDomain();
        $sqlProduct = "SELECT  CONCAT('" . $domainUrl . "/image/',a.img_3) AS topic,b.name AS title, a.storeprice  AS money ,a.price AS yuanjia ,a.market_price AS scj,a.product_id as buylink,  CONCAT('product_id=',a.product_id) as linktext, a.storeprice*a.credit_percent/100 AS minge  FROM " .
            getTable('product') . " a, " . getTable('product_description') . " b," . getTable('customer_ophistory') . " c where a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.operation_type = 3 and c.status=0 and c.customer_id =" . $customerId ;
        if($productId != null){
            $sqlProduct = $sqlProduct." and a.product_id=".$productId;
        }
        $sqlProduct = $sqlProduct." order by c.createTime desc   limit 10";
        $res = $this->db->getAll($sqlProduct);
        return $res;
    }

    //查询收藏次产品的用户数(包含风格化显示)
    public function showCollectionNums($productId)
    {
        //根据productId查询mcc_product表中start_collectnum字段以及incr_collectnum字段订的值
        $sql = "select IFNULL(start_collectnum,0) ,IFNULL(incr_collectnum,0)  from " . getTable('product') . " where product_id =" . $productId . " limit 1";
        $res = $this->db->getAll($sql);
        $start_collectnum = $res[0]['start_collectnum'];
        $incr_collectnum = $res[0]['incr_collectnum'];

        //统计mcc_customer_ophistory，对应productid,operation_type=3的数量
        $collectNumSql = "SELECT  collectNum from " . getTable('collecthistory_view') . " where product_id =" . $productId . " limit 1";
        $res = $this->db->getAll($collectNumSql);

        if (count($res) == 0) {
            $collectionNum = 0;
        } else {
            $collectionNum = $res[0]['collectNum'];
        }
        //检查值是否大于start_collectnum，如果大于则 round(查询的值*（100+incr_collectnum)/100）否则按照正常返回
        if ($collectionNum > $start_collectnum) {
            return round($collectionNum * (100 + $incr_collectnum) / 100);
        } else {
            return $collectionNum;
        }
    }


    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }
}
