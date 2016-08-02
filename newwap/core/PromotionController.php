<?php

class PromotionController
{
    private $db;
    private $log;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }

    //特价/积分翻倍活动
    public function getCreditsPromotionList($num)
    {

        //取表mcc_promotion_products中产品列表，获取特价/积分翻倍活动的产品信息列表，需要结合mcc_product表组合查询
        $sql1 ="select CONCAT('" . $this->getWebRoot() . "/',b.image) as src,a.product_id as id,CONCAT('product_id=',a.product_id) as buylink, c.name as title,  b.storeprice  AS money ,b.price AS yuanjia ,b.market_price AS scj, round(b.storeprice*b.credit_percent/100) AS jifen from ".getTable('special_promotion_products')." a,".getTable('product')." b,".getTable('product_description')." c where a.product_id=c.product_id and b.product_id=c.product_id  and a.product_id=c.product_id and c.language_id=1 order by a.sort_order";
      if($num != null){
          $sql1 = $sql1." limit ".$num;
      }
       $res = $this->db->getAll($sql1);

        return $res;
    }

    public function getFreeProductFPromotionList()
    {
        //取表mcc_fp_refound中产品列表，获取需要回退的免费体验活动的产品信息列表，需要结合mcc_product表组合查询
        $sql2 = "select CONCAT('" . $this->getWebRoot() . "/',b.image) as src,a.product_id as id,CONCAT('product_id=',a.product_id) as buylink, c.name as title,  b.storeprice  AS money ,b.price AS yuanjia ,b.market_price AS scj, round(b.storeprice*b.credit_percent/100) AS jifen from  " . getTable('fp_refound') . " a," .getTable('product')." b,".getTable('product_description')." c where a.product_id=c.product_id and b.product_id=c.product_id  and a.product_id=c.product_id and c.language_id=1 order by a.sort_order";
        $res = $this->db->getAll($sql2);
        return $res;
    }

    public function getFreeProductNFPromotionList()
    {
        //取表mcc_fp_norefound中产品列表，获取不需要回退的免费体验活动的信息产品列表，需要结合mcc_product表组合查询
        $sql3 = "select CONCAT('" . $this->getWebRoot() . "/',b.image) as src,a.product_id as id,CONCAT('product_id=',a.product_id) as buylink, c.name as title,  b.storeprice  AS money ,b.price AS yuanjia ,b.market_price AS scj, round(b.storeprice*b.credit_percent/100) AS jifen from " . getTable('fp_norefound') . " a," .getTable('product')." b,".getTable('product_description')." c where a.product_id=c.product_id and b.product_id=c.product_id  and a.product_id=c.product_id and c.language_id=1 order by a.sort_order";
        $res = $this->db->getAll($sql3);
        return $res;
    }
    private function getWebRoot(){
        $sqldomain = "select dvalue from " . getTable('dict') . " where dkey='domainURL'";
        $resd = $this->db->getAll($sqldomain);
        $dom = $resd[0]['dvalue'];
        return $dom;
    }
    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='getProductURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }

}