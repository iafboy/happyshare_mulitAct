<?php

class BrandGroupController
{
    private $db;
    private $log;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }

    //取得品牌相关信息
    public function getBrandGroupInfo($num = 10)
    {
        //查询mcc_brandgroup表,取得品牌相关信息
        $domainUrl = $this->getDomain();
        $sql = "select  a.bg_id, a.bg_id as buylink,CONCAT('bg_id=',a.bg_id ) as linktxt  , a.bg_name as name,a.bg_intro as intro,CONCAT('" . $domainUrl . "/image/',a.img) as img,b.supplier_id as supplierId  from "
            . getTable('brandgroup') . "a,"
            .getTable('supplier_to_brand')." b, "
            .getTable('supplier')." c where b.brand_id=a.bg_id and b.supplier_id = c.supplier_id and a.status=1 and c.status = 1 and c.own_brand=1  LIMIT 0, ".$num;
        $res = $this->db->getAll($sql);
        return $res;
    }


    public function getBrandSupplier($bgId)
    {
        $domainUrl = $this->getDomain();
        $sql = "SELECT a.supplier_id AS supplier_id, IFNULL(b.supplier_company,'') AS supplier_desc, b.supplier_name AS supplier_name, CONCAT('" . $domainUrl . "/image/', c.imgurl)AS img FROM "
            . getTable('supplier_to_brand') . " a," . getTable('supplier') . " b," . getTable('supplier_imgs') . " c
                WHERE a.supplier_id = b.supplier_id AND c.supplier_id = b.supplier_id AND c.seq = (select min(seq) from ".getTable('supplier_imgs')." where supplier_id=b.supplier_id  ) and a.brand_id = " . $bgId;
        $res = $this->db->getAll($sql);
        return $res;
    }

    //取得品牌下相关产品列表
    public function getBrandGroupProductList($bg_id)
    {

        //根据bg_id,查询表中mcc_brandgroup_product表中product_id,组合mcc_product表查询产品相关信息
        $domainUrl = $this->getDomain();
        $sqlProduct = "SELECT  CONCAT('" . $domainUrl . "/image/',a.image) AS topic,b.name AS title,IFNULL( a.storeprice,0)  AS money ,IFNULL(a.price,0) AS yuanjia ,IFNULL(a.market_price,0)  AS scj FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('brandgroup_product') . " c where a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.bg_id =" . $bg_id . " limit 10";

        $res = $this->db->getAll($sqlProduct);

        return $res;
    }

    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }
}