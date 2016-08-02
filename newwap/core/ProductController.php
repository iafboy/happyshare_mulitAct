<?php

/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:23
 */
require_once(ROOT_PATH . 'newwap/core/ActivityController.php');

class ProductController
{
    private $db;
    private $log;

    private $ALREADY_CHARGED = 1;
    private $LANGUAGE = 1;
    private $HIGH_PROFIT_NUMBER = 6;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
//        $this->activityController = $registry->get('ActivityController');
    }

    public function getProductList()

    {
        //查询mcc_product表，列出所有记录
        $sql = "select
                a.supplier_id as supplierId,
                b.supplier_name as supplierName,
                a.shipping as shipingType,
                a.product_id as productId,
                c.name as productName,
                a.market_price as orgPrice,
                a.storeprice as newPrice,
                a.credit_percent as credit,
                a.start_buynum as buyNum,
                a.start_sharenum as shareNum,
                a.comments as commentNum,
                a.start_collectnum as collectNum
                from " . getTable('product') . " a, " . getTable('supplier') . " b, " . getTable('product_description') . " c
                where a.supplier_id = b.supplier_id and
                a.product_id = c.product_id
                ";
        $res = $this->db->getAll($sql);
        $msg = new \leshare\json\message($res, 0, " success");
        return $msg;
    }

    public function search($key)
    {
        $domain = $this->getDomain();
        $sqlProduct = "SELECT  CONCAT('" . $domain . "/image/',a.img_3) AS topic, a.product_id as id ,CONCAT('product_id=',a.product_id) AS linktxt ,b.name AS title, a.storeprice  AS money ,a.price AS yuanjia ,a.market_price AS scj,
        c.supplier_id,c.supplier_name, 0 as jifen
        FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('supplier') . " c  where c.supplier_id=a.supplier_id and  a.product_id = b.product_id AND a.status=3 and  b.language_id = 1 AND b.name like '%" . $key . "%' limit 10";
        $res = $this->db->getAll($sqlProduct);
        if (count($res) > 0) {

            for ($i = 0; $i < count($res); $i++) {
                $jifen = $this->getProductCreditByDate($res[$i]['id'], date("Ymd"));
                $res[$i]['jifen'] = round($jifen);
            }
        }
        return $res;
    }

    public function  getShareCaseDetail($caseId){
        $domain = $this->getDomain();
        $sql = "select CONCAT('" . $domain . "/image/', image) as image from ".getTable('product')." where product_id = (select product_id from "
            .getTable('product_share')." where prdshare_id = ".to_db_int($caseId).")";
        $product = $this->db->querySingleRow($sql);
        if(!isset($product)){
            return [];
        }
        $sql = "select prdshare_id,title,memo,seq,product_id,"
            ." CONCAT('" . $domain . "/image/', imgurl1) as imgurl1,"
            ." CONCAT('" . $domain . "/image/', imgurl2) as imgurl2,"
            ." CONCAT('" . $domain . "/image/', imgurl3) as imgurl3,"
            ." CONCAT('" . $domain . "/image/', imgurl4) as imgurl4,"
            ." CONCAT('" . $domain . "/image/', imgurl5) as imgurl5,"
            ." CONCAT('" . $domain . "/image/', imgurl6) as imgurl6,"
            ." CONCAT('" . $domain . "/image/', imgurl7) as imgurl7,"
            ." CONCAT('" . $domain . "/image/', imgurl8) as imgurl8,"
            ." CONCAT('" . $domain . "/image/', imgurl9) as imgurl9 "
            ."  from ".getTable('product_share')." where prdshare_id = ".to_db_int($caseId);
        $case = $this->db->querySingleRow($sql);
        $product = array_merge($product,$case);
        return $product;
    }
    public function  getProductShareCasesInfo($productId)
    {
        $domain = $this->getDomain();
        $sql = "select CONCAT('" . $domain . "/image/', image) as image from ".getTable('product')." where product_id = ".to_db_int($productId);
        $product = $this->db->querySingleRow($sql);
        if(!isset($product)){
            return [];
        }
        $sql = "select prdshare_id,title,memo,seq,product_id,"
            ." CONCAT('" . $domain . "/image/', imgurl1) as imgurl1,"
            ." CONCAT('" . $domain . "/image/', imgurl2) as imgurl2,"
            ." CONCAT('" . $domain . "/image/', imgurl3) as imgurl3,"
            ." CONCAT('" . $domain . "/image/', imgurl4) as imgurl4,"
            ." CONCAT('" . $domain . "/image/', imgurl5) as imgurl5,"
            ." CONCAT('" . $domain . "/image/', imgurl6) as imgurl6,"
            ." CONCAT('" . $domain . "/image/', imgurl7) as imgurl7,"
            ." CONCAT('" . $domain . "/image/', imgurl8) as imgurl8,"
            ." CONCAT('" . $domain . "/image/', imgurl9) as imgurl9 "
            ."  from ".getTable('product_share')." where product_id = ".to_db_int($productId)." and audit = 1 order by seq asc";
        $cases = $this->db->queryRows($sql);
        $product['cases'] = $cases;
        return $product;
    }

    public
    function  getProductListBySupplier($supplierId)
    {
        if ($supplierId == null) {
            throw new exception("供货商id不能为空");
        }
        $domain = $this->getDomain();
        $sqlProduct = "SELECT 0 as jifen, CONCAT('" . $domain . "/image/',a.img_3) AS topic, a.product_id as id ,CONCAT('product_id=',a.product_id) AS linktxt ,b.name AS title, a.storeprice  AS money ,a.price AS yuanjia ,a.market_price AS scj,
        c.supplier_name as supplierName, 0 as xiaoliang
        FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('supplier') . "c  where c.supplier_id = a.supplier_id and a.product_id = b.product_id AND a.status=3 and  b.language_id = 1 AND a.supplier_id =" . $supplierId . " limit 10";
        $res = $this->db->getAll($sqlProduct);
        if (count($res) > 0) {
            foreach ($res as &$product) {
                $jifen = $this->getProductCreditByDate($product['id'], date("Ymd"));
                $product['jifen'] = round($jifen);
                $buyNum = $this->showBuyNums($product['id']);
                $product['xiaoliang'] = round($buyNum);
                $product = array_merge($product, getActInfo($product['id']));
            }
//            for ($i = 0; $i < count($res); $i++) {
//                $jifen = $this->getProductCreditByDate($res[$i]['id'], date("Ymd"));
//                $res[$i]['jifen'] = round($jifen);
//                $buyNum = $this->showBuyNums($res[$i]['id']);
//                $res[$i]['xiaoliang'] = round($buyNum);
//                $res[$i] = array_merge($res[$i],getActInfo($res[$i]['id']));
//            }
        }
        return $res;

    }


    public
    function getSupplierBanner($supplierId)
    {
        $domain = $this->getDomain();
        $sql = "select  CONCAT('" . $domain . "/image/', a.image) as image, link from " . getTable('brandbanner_image') . "a  where enable_status =1 and supplier_id =" . $supplierId . " order by sort_order";
        $res = $this->db->getAll($sql);
        return $res;
    }


    public
    function getProductDetail($request)
    {
        $domain = $this->getDomain();
        $productId = $request['productId'];
        //查询mcc_product表，列出所有记录
        $sql = "select
                 CONCAT('" . $domain . "/image/', a.img_3) as image,
                a.supplier_id as supplierId,
                b.supplier_name as supplierName,
                a.shipping as shipingType,
                a.product_id as productId,
                c.name as productName,
                a.market_price as orgPrice,
                a.storeprice as newPrice,
                a.credit_percent as credit,
                a.start_buynum as buyNum,
                a.start_sharenum as shareNum,
                a.comments as commentNum,
                a.start_collectnum as collectNum,
                a.express_template as templateId,
                a.memo as memo,a.status as status
                from mcc_product a, mcc_supplier b, mcc_product_description c
                where a.supplier_id = b.supplier_id and
                a.product_id = c.product_id and
                a.product_id = " . $productId;
        $res = $this->db->getAll($sql);

        if (count($res) == 0) {
            throw new exception("找不到产品信息,id=" . $productId);
        }
        $price = $this->getProductPriceByDate($productId, date("Ymd"));
        $res[0]['newPrice'] = $price;
        //$msg = new \leshare\json\message($res, 0, " success");
        return $res;
    }

// 获取产品在给定时间点时购买可以获得的积分
    public
    function getProductCreditByDate($productId, $date)
    {
        $this->log->debug("[ProductController][getProductCreditByDate] productId = $productId, date = $date");
        $credit = 0;
        // 获取该产品自身的积分值
        $sql = "SELECT a.credit as credit
                FROM mcc_product a
                WHERE a.product_id = $productId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $credit = $res[0]["credit"];
        }
        // 查询这个时间点该产品参加中的活动
        // 一个时间点一个产品只能参加一个活动 from @大冰同学
        // 特价活动 积分翻倍活动（mcc_special_promotion, mcc_special_promotion_product）
        $sql = "select act_credit,act_price
                from mcc_special_promotion a, mcc_special_promotion_products b, mcc_promotions c
                where a.promotion_id = b.promotion_id
				and a.promotion_id = c.subpromotionid
                and a.starttime<=DATE('$date')
                and a.endtime>=DATE('$date')
                and b.product_id=$productId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $credit = $res[0]["act_credit"];
        }
        // 免费体验活动（mcc_freepromotion, mcc_fp_refound, mcc_fp_norefound）
        $sql = "SELECT credit FROM mcc_freepromotion a, mcc_fp_refound b, mcc_promotions c
                WHERE a.fp_id = b.fp_id
				and a.fp_id = c.subpromotionid
				and c.status = 0
                and a.starttime<=DATE('$date')
                and a.endtime>=DATE('$date')
                and b.product_id=$productId
                union
                SELECT credit FROM mcc_freepromotion a, mcc_fp_norefound b, mcc_promotions c
                WHERE a.fp_id = b.fp_id
				and a.fp_id = c.subpromotionid
				and c.status = 0
                and a.starttime<=DATE('$date')
                and a.endtime>=DATE('$date')
                and b.product_id=$productId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $credit = $res[0]["credit"];
        }
        if ($credit == null) {
            $credit = 0;
        }
        $this->log->debug("[ProductController][getProductCreditByDate] credit = $credit");
        return (int)$credit;
    }

// 获取产品在给定时间点时购买的价格
    public
    function getProductPriceByDate($productId, $date)
    {
        $this->log->debug("[ProductController][getProductPriceByDate] productId = $productId, date = $date");
        $price = 0;
        // 获取该产品自身的平台价
        $sql = "SELECT a.storeprice as price
                FROM mcc_product a
                WHERE a.product_id = $productId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $price = $res[0]["price"];
            $this->log->debug("[ProductController][getProductPriceByDate] original price = $price");
        }
        // 查询这个时间点该产品参加中的活动
        // 一个时间点一个产品只能参加一个活动 from @大冰同学
        // 特价活动 积分翻倍活动（mcc_special_promotion, mcc_special_promotion_product）
        $sql = "select act_price, a.promotion_id
                from mcc_special_promotion a, mcc_special_promotion_products b, mcc_promotions c
                where a.promotion_id = b.promotion_id
				and a.promotion_id = c.subpromotionid
                and a.starttime<=DATE('$date')
                and a.endtime>=DATE('$date')
                and b.product_id=$productId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $price = $res[0]["act_price"];
            $promotion_id = $res[0]["promotion_id"];
            $this->log->debug("[ProductController][getProductPriceByDate] activity price = $price, activity id = $promotion_id");
        }
//        // 免费体验活动（mcc_freepromotion, mcc_fp_refound, mcc_fp_norefound）
//        $sql = "SELECT credit FROM mcc_freepromotion a, mcc_fp_refound b, mcc_promotions c
//                WHERE a.fp_id = b.fp_id
//				and a.fp_id = c.subpromotionid
//				and c.status = 0
//                and a.starttime<=DATE('$date')
//                and a.endtime>=DATE('$date')
//                and b.product_id=$productId
//                union
//                SELECT credit FROM mcc_freepromotion a, mcc_fp_norefound b, mcc_promotions c
//                WHERE a.fp_id = b.fp_id
//				and a.fp_id = c.subpromotionid
//				and c.status = 0
//                and a.starttime<=DATE('$date')
//                and a.endtime>=DATE('$date')
//                and b.product_id=$productId";
//        $res = $this->db->getAll($sql);
//        if (count($res) != 0) {
//            $credit = $res[0]["credit"];
//        }
        if ($price == null) {
            $price = 0;
        }
        $this->log->debug("[ProductController][getProductPriceByDate] price = $price");
        return $price;
    }

// 获取产品在给定活动中购买可以获得的积分
    public
    function getProductCredit($request)
    {
        $productId = $request['productId'];
        $date = $request['date'];
        $orderId = $request['orderId'];
        if ($productId != null) {
            $res = $this->getProductCreditByDate($productId, $date);
        } elseif ($orderId != null) {
            $res = $this->getProductCreditByOrder($orderId);
        }
        $msg = new \leshare\json\message($res, 0, " success");
        return $msg;
    }

// 获取产品在给定订单时购买可以获得的积分
    public
    function getProductCreditByOrder($orderId)
    {
        $sql = "SELECT date_added
                FROM mcc_order
                WHERE order_id = $orderId";
        $order_date = $this->db->getAll($sql)[0]['date_added'];
        $sql = "SELECT b.product_id, b.quantity
                FROM mcc_order a, mcc_order_product b
                WHERE a.order_id = b.order_id
                AND a.order_id = $orderId";
        $products = $this->db->getAll($sql);
        if (count($products) != 0) {
            $total_credit = 0;
            for ($i = 0; $i < count($products); $i++) {
                $current_credit = $this->getProductCreditByDate($products[$i]['product_id'], $order_date) * $products[$i]['quantity'];
                $total_credit += $current_credit;
            }
        }
        return $total_credit;
    }

// 获取产品在给定订单时购买可以获得的积分,同时返回消费积分和活动积分
    public
    function getProductCreditByOrderAndType($orderId)
    {
        $sql = "SELECT date_added
                FROM mcc_order
                WHERE order_id = $orderId";
        $order_date = $this->db->getAll($sql)[0]['date_added'];
        $sql = "SELECT b.product_id, b.quantity
                FROM mcc_order a, mcc_order_product b
                WHERE a.order_id = b.order_id
                AND a.order_id = $orderId";
        $products = $this->db->getAll($sql);
        if (count($products) != 0) {
            $total_buy_credit = 0;
            $total_activity_credit_list = array();
            $total_normal_credit_list = array();
            $j=0;
            for ($i = 0; $i < count($products); $i++) {
                $current_credit = $this->getProductCreditByDate($products[$i]['product_id'], $order_date) * $products[$i]['quantity'];
                $single_credit = $this->getProductCreditByDate($products[$i]['product_id'], $order_date);
                $flag = $this->isProductInActivity($products[$i]['product_id'], $order_date);
                if ($flag == 0) {
                    $total_buy_credit += $current_credit;
//                    $total_normal_credit_list[$i]['ref_id'] = $orderId;
//                    $total_normal_credit_list[$i]['credit'] = $current_credit;
//                    $total_normal_credit_list[$i]['product_id']=$products[$i]['product_id'];
//                    $total_normal_credit_list[$i]['s_credit'] = $single_credit;
                } else {
                    $total_activity_credit_list[$j]['ref_id'] = $flag;
                    $total_activity_credit_list[$j]['credit'] = $current_credit;
                    $total_activity_credit_list[$j]['product_id']=$products[$i]['product_id'];
                    $total_activity_credit_list[$j]['s_credit'] = $single_credit;
                    $j++;
                    //$total_buy_credit += $current_credit;
                }

            }
        }
        $res = array();
        $res['total_buy_credit'] = $total_buy_credit;
        $res['total_normal_credit_list'] = $total_normal_credit_list;
        $res['total_activity_credit_list'] = $total_activity_credit_list;
        return $res;
    }

    public
    function isProductInActivity($productId, $date)
    {
        $this->log->debug("[ProductController][isProductInActivity] productId = $productId, date = $date");
        $flag = 0;
        // 查询这个时间点该产品参加中的活动
        // 一个时间点一个产品只能参加一个活动 from @大冰同学
        // 特价活动 积分翻倍活动（mcc_special_promotion, mcc_special_promotion_product）
        $sql = "select a.promotion_id, c.pid as promotionId
                from mcc_special_promotion a, mcc_special_promotion_products b, mcc_promotions c
                where a.promotion_id = b.promotion_id
				and a.promotion_id = c.subpromotionid
                and a.starttime<=DATE('$date')
                and a.endtime>=DATE('$date')
                and b.product_id=$productId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $flag = $res[0]['promotionId'];
        } else {
            $flag = 0;
        }
        $this->log->debug("[ProductController][isProductInActivity] flag = $flag");
        return $flag;
    }

    public function getProductInfo($productId)
    {
        if ($productId == null) {
            throw new exception("产品id不能为空");
        }

        $domain = $this->getDomain();

        $sqlProduct = "SELECT   CONCAT('" . $domain . "/image/', a.image) as image,  b.name AS title,c.supplier_name as ghs,a.storeprice  AS money, a.market_price AS scj,b.description as content,
        IFNULL(a.return_limit,0) as refoundtype,IFNULL(a.return_limit,0) as refoundlimit,a.supplier_id as supplier_id,a.quantity as quantity
           FROM " . getTable('product') . " a, " . getTable('product_description') . " b, " . getTable('supplier') . " c where a.supplier_id = c.supplier_id and a.product_id = b.product_id AND b.language_id = 1 AND a.product_id =" . $productId . " limit 1";
        // 产品信息
        $res = $this->db->getAll($sqlProduct);
        if (count($res) == 0) {
            throw new exception("产品不存在,id=" . $productId);
        }
        $supplierId = $res[0]['supplier_id'];
        $resSupplierExpress = $this->getSupplierExpress($supplierId);
        if (count($resSupplierExpress) > 0) {
            $freePrice = $resSupplierExpress[0]['free_shipping'];
        } else {
            $freePrice = 0;
        }
        $res[0] = array_merge($res[0], array("freeShipPrice" => $freePrice));
        return $res;
    }

    public
    function getSupplierExpress($supplierId)
    {
        $sql = "select expressname,startweight,startprice,free_shipping,free_tax_min,free_tax_max,order_charge from " . getTable('express_setting') . " where supplier_id=" . $supplierId;
        $res = $this->db->getAll($sql);
        return $res;
    }

    public
    function  getProductImgs($productId)
    {
        $domain = $this->getDomain();
        $sqlProductImg = "select CONCAT('" . $domain . "/image/', image) as img from " . getTable('product_image') . " where product_id= " . $productId;
        $productImg = $this->db->getAll($sqlProductImg);
        return $productImg;
    }

    private
    function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }

// receiveAddressId 是目的城市
    public
    function getFreight($supplierId, $products, $receiveAddressId, $orderID = -1, $orderAmount)
    {
        $this->log->debug("[ProductController][getFreight] supplierId = $supplierId"
            . "  products = $products"
            . "  receiveAddressId = $receiveAddressId"
            . "  orderID = $orderID"
            . "  orderAmount = $orderAmount");

        $sql = "SELECT * FROM mcc_express_setting where supplier_id = $supplierId ";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $order_charge = $res[0]["order_charge"];
            $free_shipping = $res[0]["free_shipping"];
        } else {
            $this->log->debug("[ProductController][getFreight] 获取全局计费方式失败！");
            throw new exception("获取全局计费方式失败！");
        }

        // 把$receiveAddressId 转换成mcc_addressbook_china_city中对应的relate_code
        $sql = "SELECT region_code FROM `mcc_addressbook_china_city` WHERE id = $receiveAddressId";
        $res = $this->db->getAll($sql);
        if (count($res) != 0) {
            $region_code = $res[0]["region_code"];
        } else {
            $this->log->debug("[ProductController][getFreight] 获取全局计费方式失败！");
            throw new exception("获取全局计费方式失败！");
        }

//        // 获取订单总价
//        $sql = "SELECT * FROM mcc_order where order_id = $orderID ";
//        $res = $this->db->getAll($sql);
//        if (count($res) != 0) {
//            $orderAmount = $res[0]["total"];
//        } else {
//            $this->log->debug("[ProductController][getFreight] 获取全局计费方式失败！");
//            throw new exception("获取全局计费方式失败！");
//        }

        // 包邮
        if ($orderAmount != -1 && $orderAmount > $free_shipping) {
            $this->log->debug("[ProductController][getFreight] free freight! orderAmount = $orderAmount > free_shipping = $free_shipping .");
            return 0;
        }

        $this->log->debug("[ProductController][getFreight] order_charge = $order_charge");
        // 4-9更新，运费计算只看计费模板，原先的分单和合单概念取消
        if ($order_charge == -1) {//分单
            // 遍历所有的产品
            $productPriceArray = Array();
            for ($i = 0; $i < count($products); $i++) {
                // 获取当前产品
                $curProductID = $products[$i]['id'];
                $this->log->debug("[ProductController][getFreight] curProductID = $curProductID");
                $sql = "SELECT *
                FROM mcc_product a
                WHERE a.product_id = $curProductID
                limit 0,1";
                $res = $this->db->getAll($sql);
                if (count($res) != 0) {
                    $currentProduct = $res[0];
                } else {
                    $this->log->debug("[ProductController][getFreight] 获取产品(id:\" . $curProductID . \"失败！");
                    throw new exception("获取产品(id:\" . $curProductID . \"失败！");
                }


                //获取计费方式和产品信息
                if ($orderID == -1) {
                    $sql = "SELECT *
                FROM mcc_product a
                WHERE a.product_id = $curProductID
                limit 0,1";
                    $res = $this->db->getAll($sql);
                    if (count($res) != 0) {
                        $weight = $res[0]["weight"];
                        $volume = $res[0]["volume"];
                    } else {
                        $this->log->debug("[ProductController][getFreight] 获取产品(id:" . $curProductID . ")信息失败！orderID == -1");
                        throw new exception("获取产品(id:" . $curProductID . ")信息失败！orderID == -1");
                    }
                } else {
                    $sql = "SELECT *
                FROM mcc_order_product a
                WHERE a.product_id = $curProductID
                and a.order_id = $orderID
                limit 0,1";
                    $res = $this->db->getAll($sql);
                    if (count($res) != 0) {
                        $weight = $res[0]["weight"];
                        $volume = $res[0]["volume"];
                    } else {
                        $this->log->debug("[ProductController][getFreight] 获取产品(id:" . $curProductID . ")信息失败！");
                        throw new exception("获取产品(id:" . $curProductID . ")信息失败！");
                    }
                }
                $this->log->debug("[ProductController][getFreight] init weight".$weight);
                // 获取费率
                $express_template = $currentProduct['express_template'];
                // 查询mcc_express_template获取默认费率
                $sql = "SELECT express_mode as express_mode, default_unit as unit, default_price as price, default_add_unit as add_unit, default_add_price as add_price
                    FROM mcc_express_template a
                WHERE a.express_template_id = $express_template";
                $res = $this->db->getAll($sql);
                if (count($res) != 0) {
                    $defaultExpressPrice = $res[0];
                    $chargeType = $defaultExpressPrice['express_mode'];
                } else {
                    $this->log->debug("[ProductController][getFreight] sql = $sql");
                    throw new exception("获取产品(id:" . $curProductID . ")默认费率失败！");
                }
                // 查询mcc_express_rule和mcc_express_district获取特殊定义费率，如果没有则使用默认费率
                $sql = "SELECT *
                    FROM mcc_express_rule a, mcc_express_district b
                WHERE a.rule_id = b.rule_id
                AND a.template_id = $express_template
                AND b.relate_code = $region_code";
                $res = $this->db->getAll($sql);
                if (count($res) != 0) {
                    $expressPrice = $res[0];
                } else {
                    $expressPrice = $defaultExpressPrice;
                    $this->log->debug("[ProductController][getFreight] no additional freight for product id : " . $curProductID . ". use default template.");
                }

                $this->log->debug("[ProductController][getFreight] chargeType = $chargeType");
                $cur_price = 0;
                if ($chargeType == 1) {//按件计费
                    $cur_num = ($products[$i]['num'] == null) ? 1 : $products[$i]['num'];
                    $this->log->debug("[ProductController][getFreight] cur_num = $cur_num");
                    if ($cur_num >= $expressPrice['unit']) {
                        $cur_price = $expressPrice['price'] * $expressPrice['unit'] + (int)(($cur_num - $expressPrice['unit']) / $expressPrice['add_unit']) * $expressPrice['add_price'];
                    } else {
                        $cur_price = $expressPrice['price'] * $cur_num;
                    }
                    $this->log->debug("[ProductController][getFreight] piece_start_price = " . $expressPrice['price']);
                    $this->log->debug("[ProductController][getFreight] piece_add_price = " . $expressPrice['add_price']);
                } else if ($chargeType == 2) {//按重量
                    $cur_weight = $weight;
                    $cur_num = ($products[$i]['num'] == null) ? 1 : $products[$i]['num'];
                    $this->log->debug("[ProductController][getFreight] cur_num = $cur_num");
                    $cur_weight = $cur_weight * $cur_num;
                    if ($cur_weight <= $expressPrice['unit']) {
                        $cur_price = $expressPrice['price'];
                    } else {
                        // 如果是0.1 - 0.9 则按1算
                        $cur_price = $expressPrice['price'] + (int)(($cur_weight - $expressPrice['unit'] + 1) / $expressPrice['add_unit']) * $expressPrice['add_price'];
                    }
                } else if ($chargeType == 3) {//按体积
                    $cur_volume = $volume;
                    $cur_num = ($products[$i]['num'] == null) ? 1 : $products[$i]['num'];
                    $this->log->debug("[ProductController][getFreight] cur_num = $cur_num");
                    $cur_volume = $cur_volume * $cur_num;
                    if ($cur_volume <= $expressPrice['unit']) {
                        $cur_price = $expressPrice['price'];
                    } else {
                        // 如果是0.1 - 0.9 则按1算
                        $cur_price = $expressPrice['price'] + (int)(($cur_volume - $expressPrice['unit'] + 1) / $expressPrice['add_unit']) * $expressPrice['add_price'];
                    }
                }
                $expressPrice['curPrice'] = $cur_price;
                $productPriceArray[$products[$i]['id']] = $expressPrice;
            }
            // 分析数组，结合计费方式来计算总运费
            $total_price = 0;
            foreach ($productPriceArray as $key => $value) {
                $total_price = $total_price + $value['curPrice'];
            }
            $this->log->debug("[ProductController][getFreight] total_price = " . $total_price);
            return $total_price;
        } else {//合单,将相同模板的商品放到一起计算运费
            // 遍历所有的产品
            $productPriceArray = Array();
            for ($i = 0; $i < count($products); $i++) {
                // 获取当前产品
                $curProductID = $products[$i]['id'];
                $this->log->debug("[ProductController][getFreight] curProductID = $curProductID");
                $sql = "SELECT *
                FROM mcc_product a
                WHERE a.product_id = $curProductID
                limit 0,1";
                $res = $this->db->getAll($sql);
                if (count($res) != 0) {
                    $currentProduct = $res[0];
                } else {
                    $this->log->debug("[ProductController][getFreight] 获取产品(id:\" . $curProductID . \"失败！");
                    throw new exception("获取产品(id:\" . $curProductID . \"失败！");
                }


                //获取计费方式和产品信息
                if ($orderID == -1) {
                    $sql = "SELECT *
                FROM mcc_product a
                WHERE a.product_id = $curProductID
                limit 0,1";
                    $res = $this->db->getAll($sql);
                    if (count($res) != 0) {
                        $weight = $res[0]["weight"];
                        $volume = $res[0]["volume"];
                    } else {
                        $this->log->debug("[ProductController][getFreight] 获取产品(id:" . $curProductID . ")信息失败！orderID == -1");
                        throw new exception("获取产品(id:" . $curProductID . ")信息失败！orderID == -1");
                    }
                } else {
                    $sql = "SELECT *
                FROM mcc_order_product a
                WHERE a.product_id = $curProductID
                and a.order_id = $orderID
                limit 0,1";
                    $res = $this->db->getAll($sql);
                    if (count($res) != 0) {
                        $weight = $res[0]["weight"];
                        $volume = $res[0]["volume"];
                    } else {
                        $this->log->debug("[ProductController][getFreight] 获取产品(id:" . $curProductID . ")信息失败！");
                        throw new exception("获取产品(id:" . $curProductID . ")信息失败！");
                    }
                }
                $this->log->debug("[ProductController][getFreight] merge order init weight :".$weight);
                // 获取费率
                $express_template = $currentProduct['express_template'];
                $this->log->debug("[ProductController][getFreight] express_template : " . $express_template);
                // 加到全局数组中
                if ($productPriceArray[$express_template] == null || $productPriceArray[$express_template] == "") {
                    $productPriceArray[$express_template]["unit"] = 0;
                    $productPriceArray[$express_template]["price"] = 0;
                }

                // 查询mcc_express_template获取默认费率
                $sql = "SELECT express_mode as express_mode, default_unit as unit, default_price as price, default_add_unit as add_unit, default_add_price as add_price
                    FROM mcc_express_template a
                WHERE a.express_template_id = $express_template";
                $res = $this->db->getAll($sql);
                    if (count($res) != 0) {
                    $defaultExpressPrice = $res[0];
                    $chargeType = $defaultExpressPrice['express_mode'];
                } else {
                    $this->log->debug("[ProductController][getFreight] sql = $sql");
                    throw new exception("获取产品(id:" . $curProductID . ")默认费率失败！");
                }
                // 查询mcc_express_rule和mcc_express_district获取特殊定义费率，如果没有则使用默认费率
                $sql = "SELECT *
                    FROM mcc_express_rule a, mcc_express_district b
                WHERE a.rule_id = b.rule_id
                AND a.template_id = $express_template
                AND b.relate_code = $region_code";
                $res = $this->db->getAll($sql);
                if (count($res) != 0) {
                    $expressPrice = $res[0];
                } else {
                    $expressPrice = $defaultExpressPrice;
                    $this->log->debug("[ProductController][getFreight] no additional freight for product id : " . $curProductID . ". use default template.");
                }

                $this->log->debug("[ProductController][getFreight] chargeType = $chargeType");
                $cur_price = 0;
                if ($chargeType == 1) {//按件计费
                    $cur_num = ($products[$i]['num'] == null) ? 1 : $products[$i]['num'];
                    $cur_num = $cur_num + $productPriceArray[$express_template]["unit"];
                    $productPriceArray[$express_template]["unit"] = $cur_num;
                    $this->log->debug("[ProductController][getFreight]  type 1 cur_num = $cur_num");
                    if ($cur_num > $expressPrice['unit']) {
                        $cur_price = $expressPrice['price'] + (int)(($cur_num - $expressPrice['unit']) / $expressPrice['add_unit']) * $expressPrice['add_price'];
                    } else {
                        $cur_price = $expressPrice['price'];
                    }
                    $productPriceArray[$express_template]["price"] = $cur_price;
                    $this->log->debug("[ProductController][getFreight] piece_start_price = " . $expressPrice['price']);
                    $this->log->debug("[ProductController][getFreight] piece_add_price = " . $expressPrice['add_price']);
                } else if ($chargeType == 2) {//按重量

                    $this->log->debug("[ProductController][getFreight] type2 weight = $weight");
		$cur_weight = $weight;
		$this->log->debug("[ProductController][getFreight] type2 cur_weight0 = $cur_weight");
                    $cur_num = ($products[$i]['num'] == null) ? 1 : $products[$i]['num'];
                    $this->log->debug("[ProductController][getFreight] type2 cur_num = $cur_num");
                    $cur_weight = $cur_weight * $cur_num;
		$this->log->debug("[ProductController][getFreight] type2 cur_weight1 = $cur_weight");
                    $cur_weight = $cur_weight + $productPriceArray[$express_template]["unit"];
		$this->log->debug("[ProductController][getFreight] type2 cur_weight2 = $cur_weight");
                    $productPriceArray[$express_template]["unit"] = $cur_weight;
                    if ($cur_weight <= $expressPrice['unit']) {
                        $cur_price = $expressPrice['price'];
                    } else {
                        // 如果是0.1 - 0.9 则按1算
                        $cur_weight_int = (int)$cur_weight;
                        if($cur_weight_int < $cur_weight){
                            $cur_weight_int = $cur_weight_int + 1;
                        }
                        $cur_price = $expressPrice['price'] + ($cur_weight_int - $expressPrice['unit']) / $expressPrice['add_unit'] * $expressPrice['add_price'];
                    }
                    $productPriceArray[$express_template]["price"] = $cur_price;
                } else if ($chargeType == 3) {//按体积
                    $cur_volume = $volume;
                    $cur_num = ($products[$i]['num'] == null) ? 1 : $products[$i]['num'];
                    $this->log->debug("[ProductController][getFreight] type3 cur_num = $cur_num");
                    $cur_volume = $cur_volume * $cur_num;
                    $cur_volume = $cur_volume + $productPriceArray[$express_template]["unit"];
                    $productPriceArray[$express_template]["unit"] = $cur_volume;
                    if ($cur_volume <= $expressPrice['unit']) {
                        $cur_price = $expressPrice['price'];
                    } else {
                        // 如果是0.1 - 0.9 则按1算
                        $cur_volume_int = (int)$cur_volume;
                        if($cur_volume_int < $cur_volume){
                            $cur_volume_int = $cur_volume_int + 1;
                        }
                        $cur_price = $expressPrice['price'] + ($cur_volume_int - $expressPrice['unit']) / $expressPrice['add_unit'] * $expressPrice['add_price'];
                    }
                    $productPriceArray[$express_template]["price"] = $cur_price;
                }
            }
            // 分析数组，结合计费方式来计算总运费
            $total_price = 0;
            foreach ($productPriceArray as $productPrice) {
                $this->log->debug("[ProductController][getFreight] unit : " . $productPrice['unit'] . " price :" . $productPrice['price']);
                $total_price = $total_price + $productPrice['price'];
            }
            $this->log->debug("[ProductController][getFreight] total_price = " . $total_price);
            return $total_price;
        }

    }


    private
    function  getProduct4Hostshop($productId,$isRecommended,$change)
    {
        $domain = $this->getDomain();
        if($isRecommended ==1){
		if($change==1){
            $sql = " SELECT    b.product_id as id ,CONCAT('product_id=',b.product_id) AS linktxt  , CONCAT('" . $domain . "/image/',IFNULL(b.img_1,'')) AS src , c.name AS title , b.storeprice AS money,  b.market_price AS oldmoney,e.supplier_name AS ghs from "
                . getTable('product') . " b ,"
                . getTable('product_description') . " c ,"
                . getTable('supplier')
                . " e WHERE b.status = 3 and b.supplier_id = e.supplier_id AND c.product_id = b.product_id "
                . " and  b.product_id=" . $productId . " AND c.language_id = 1 LIMIT 1";
		}else{
			 $sql = " SELECT    b.product_id as id ,CONCAT('product_id=',b.product_id) AS linktxt  , CONCAT('" . $domain . "/image/',IFNULL(b.img_3,'')) AS src , c.name AS title , b.storeprice AS money,  b.market_price AS oldmoney,e.supplier_name AS ghs from "
                . getTable('product') . " b ,"
                . getTable('product_description') . " c ,"
                . getTable('supplier')
                . " e WHERE b.status = 3 and b.supplier_id = e.supplier_id AND c.product_id = b.product_id "
                . " and  b.product_id=" . $productId . " AND c.language_id = 1 LIMIT 1";
		}
        }else {
            $sql = " SELECT    b.product_id as id ,CONCAT('product_id=',b.product_id) AS linktxt  , CONCAT('" . $domain . "/image/',IFNULL(b.img_3,'')) AS src , c.name AS title , b.storeprice AS money,  b.market_price AS oldmoney,e.supplier_name AS ghs from "
                . getTable('product') . " b ,"
                . getTable('product_description') . " c ,"
                . getTable('supplier')
                . " e WHERE b.status = 3 and b.supplier_id = e.supplier_id AND c.product_id = b.product_id "
                . " and  b.product_id=" . $productId . " AND c.language_id = 1 LIMIT 1";
        }


        $res = $this->db->getAll($sql);
        $productCredit = $this->getProductCreditByDate($productId, date("Ymd"));
        $buyNum = $this->showBuyNums($productId);

        if (count($res) > 0) {
            $res = array_merge($res[0], array("jifen" => round($productCredit), "xiaoliang" => $buyNum));
            $res = array_merge($res, getActInfo($productId));
            return $res;
        } else {
            return null;
        }

    }

//查询购买产品的用户数(包含风格化显示)
    public
    function showBuyNums($productId)
    {
        //根据productId查询mcc_product表中start_buynum字段以及incr_buynum字段订的值
        $sql = "select start_buynum ,incr_buynum from " . getTable('product') . " where product_id =" . $productId . " limit 1";
        $res = $this->db->getAll($sql);
        $start_buynum = $res[0]['start_buynum'];
        $incr_buynum = $res[0]['incr_buynum'];

        //统计mcc_customer_ophistory，对应productid,operation_type=0的数量
        $sql = "SELECT count(1) as buyNum from " . getTable('customer_ophistory') . " where operation_type = 0 and product_id =" . $productId;
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            $buyNum = 0;
        } else {
            $buyNum = $res[0]['buyNum'];
        }

        //检查值是否大于start_buynum，如果大于则 round(查询的值*（100+incr_buynum)/100）否则按照正常返回
        if ($buyNum > $start_buynum) {
//            return $buyNum;
            return round($buyNum * (100 + $incr_buynum) / 100);
        } else {
            return $buyNum;
        }

    }

    public
    function getHotShopProductList($show, $ztg_code, $num)
    {
        if ($ztg_code != null) {
            $sql = "select dict_id as ztgId,dvalue from " . getTable('dict') . " where group_id =0 and dkey='" . $this->db->escape($ztg_code) . "'";
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("主题馆编码无效");
            }
            $ztgName = $res[0]['dvalue'];
            $sql = "select product_type_id from " . getTable('product_type') . " where status =1 and type_name='" . $this->db->escape($ztgName) . "'";
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("主题馆编码无效,无对应product_type");
            }
            $productTypeId = $res[0]['product_type_id'];
        }

        $sql = 'select b.product_id from ' . getTable('product') . ' b where b.status = 3 ';
        $change=0;
	if ($show == null) {
            $show = 'recommended';
		$change=1;
        }
        if ($show == 'recommended') {
            //shareLevel
//            if ($productTypeId != null) {
//                $sql .= " and b.product_type_id=" . $productTypeId . " order by b.shareLevel desc  LIMIT " . $num;
//            } else {
//                $sql .= " order by b.shareLevel desc  LIMIT " . $num;
//            }
            if ($ztg_code != null) {
                $sql = " select b.product_id from " . getTable('product') . " b where b.status = 3 and  b.product_type_id=" . $productTypeId . " order by b.shareLevel DESC,b.date_added DESC  LIMIT " . $num;
            } else {
                $sql = " SELECT  product_id from " . getTable('product') . " where status = 3 order by shareLevel DESC,date_added DESC LIMIT " . $num;
            }
        }

        if ($show == 'buyNum') {
            if ($productTypeId != null) {
                $sql = " select b.product_id from " . getTable('buyhistory_view') . " a right join " . getTable('product') . " b on a.product_id = b.product_id where b.status = 3 and b.product_type_id=" . $productTypeId . " order by a.saledProductNum desc LIMIT " . $num;
				//$sql = " select b.product_id from " . getTable('product') . " b where b.status = 3 and b.product_type_id=" . $productTypeId . "  order by b.sales desc LIMIT " . $num;

            } else {
                $sql = " select b.product_id from " . getTable('buyhistory_view') . " a right join " . getTable('product') . " b on a.product_id = b.product_id where b.status = 3 order by a.saledProductNum desc LIMIT " . $num;
				//$sql = " select b.product_id from " . getTable('product') . " b on a.product_id = b.product_id where b.status = 3 order by b.sales desc LIMIT " . $num;
			}

        }
        if ($show == 'newArrival') {
            if ($ztg_code != null) {
                $sql = " select b.product_id from " . getTable('product') . " b where b.status = 3 and  b.product_type_id=" . $productTypeId . " order by b.date_added desc  LIMIT " . $num;
            } else {
                $sql = " SELECT  product_id from " . getTable('product') . " where status = 3 order by date_added desc LIMIT " . $num;
            }
        }

        $isRecommended = 1;
        if ($show == null) {
            $sql = "select product_id from " . getTable('product') . " where status = 3 LIMIT " . $num;
            $isRecommended = 0;
        }
		//echo $sql;
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("暂无热门商品");
        }

        $resArray = array();
        foreach ($res as $tmp) {
            $productId = $tmp["product_id"];
            $productDetail = $this->getProduct4Hostshop($productId,$isRecommended,$change);
            if ($productDetail != null) {
                array_push($resArray, $productDetail);
            }
        }
        return $resArray;

    }

    /*
        获取高收益产品列表
        $type : 主题馆类型 ('健康馆')
    */
    /**
     * @param $type
     */
    public
    function getHighProfitProductList($type, $limit, $page)
    {
        $begin = $limit * ($page - 1);
        $end = $limit * $page;
        //高收益产品指的是获得分享积分收益最高的产品
        $sql = "SELECT d.name as name,e.image as image,SUM(c.credit) as value
FROM mcc_dict a, mcc_product_to_ztg b, mcc_credithistory c, mcc_product_description d, mcc_product e
WHERE a.dict_id = b.ztgId
AND b.productId = c.productId
AND c.productId = d.product_id
AND d.product_id = e.product_id
AND c.status = $this->ALREADY_CHARGED
AND c.group_id = 0
AND a.dkey = '$type'
AND d.language_id = $this->LANGUAGE
GROUP BY c.productId
ORDER BY value DESC
LIMIT " . $begin . "," . $end;
        $res = $this->db->getAll($sql);
        if (count($res) < $this->HIGH_PROFIT_NUMBER) {
            $missing_number = $this->HIGH_PROFIT_NUMBER - count($res);
        }
        // 如果高收益产品不足，则从产品列表中补足，补足规则为积分回馈最高的
        if ($missing_number > 0) {
            $sql = "SELECT a.name as name,b.image as image, b.credit_percent as value
FROM mcc_product_description a, mcc_product b
WHERE a.product_id = b.product_id
AND a.language_id = $this->LANGUAGE
ORDER BY value DESC
LIMIT $missing_number";
            $res2 = $this->db->getAll($sql);
        }
        return array_merge($res, $res2);
    }

    /*
     * 获取免费体验活动产品列表
     */
    public
    function getFreeTrialProductList()
    {
        $sql = "SELECT c.name as name, d.image as image, d.price as price, d.market_price as market_price, d.storeprice as storeprice, b.limitpeople as limitpeople, 0 as type
FROM mcc_fp_refound b, mcc_product_description c, mcc_product d
WHERE b.product_id = c.product_id
AND c.product_id = d.product_id
AND c.language_id = $this->LANGUAGE";
        $res1 = $this->db->getAll($sql);
        $sql = "SELECT c.name as name, d.image as image, d.price as price, d.market_price as market_price, d.storeprice as storeprice, b.limitpeople as limitpeople, 1 as type
FROM mcc_fp_norefound b, mcc_product_description c, mcc_product d
WHERE b.product_id = c.product_id
AND c.product_id = d.product_id
AND c.language_id = $this->LANGUAGE";
        $res2 = $this->db->getAll($sql);
        $res = array_merge($res1, $res2);
        return $res;
    }

    /*
     * 获取特价活动产品列表
     */
    public
    function getSpecialActProductList()
    {
        $sql = "SELECT c.name as name, d.image as image, d.price as price, d.market_price as market_price, d.storeprice as storeprice
FROM mcc_special_promotion a, mcc_special_promotion_products b, mcc_product_description c, mcc_product d
WHERE a.promotion_id = b.promotion_id
AND b.product_id = c.product_id
AND c.product_id = d.product_id
AND c.language_id = $this->LANGUAGE
AND a.special_type = 0";
        $res = $this->db->getAll($sql);
        return $res;
    }

    /*
     * 获取双倍积分活动产品列表
     */
    public
    function getDoubleCreditActProductList()
    {
        $sql = "SELECT c.name as name, d.image as image, d.price as price, d.market_price as market_price, d.storeprice as storeprice
FROM mcc_special_promotion a, mcc_special_promotion_products b, mcc_product_description c, mcc_product d
WHERE a.promotion_id = b.promotion_id
AND b.product_id = c.product_id
AND c.product_id = d.product_id
AND c.language_id = $this->LANGUAGE
AND a.special_type = 1";
        $res = $this->db->getAll($sql);
        return $res;
    }

    public
    function getProductShareCase($productId, $customerId)
    {
        if (!is_valid($productId)) {
            return [];
        }
        $sql = 'select 1 from ' . getTable('product_share') . ' where audit = 1 and product_id = ' . to_db_int($productId);
        $res = $this->db->getAll($sql);
        $domain = $this->getDomain();

        if (count($res) > 0) {
            $sql = 'select title,memo,concat (\'' . $domain . '/image/\' ,imgurl1) as image from ' . getTable('product_share') . ' where audit = 1 and product_id = ' . to_db_int($productId);
        } else {
            $sql = 'select b.name as title,b.name as memo,concat (\'' . $domain . '/image/\' ,image) as image from ' . getTable('product') . ' a , ' . getTable('product_description') . ' b where a.product_id = b.product_id '
                . ' and a.product_id = ' . to_db_int($productId) . ' and b.language_id = 1 ';
        }
        $res = $this->db->getAll($sql);

        $sql = "select shareCode from " . getTable('customer') . " where customer_id=" . $customerId;
        $custRes = $this->db->getAll($sql);
        return array_merge($res[0], $custRes[0]);
    }

}
