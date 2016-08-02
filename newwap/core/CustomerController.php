<?php

/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 9:59
 */
class CustomerController
{
    private $db;
    private $log;
    private $registry;


    private $IMG_DIR = "/image/customer/qrcode/";
    private $FILE_PRE = "cus_reg_qrcode_";

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }

    /**
     * 注册新用户
     * @param $customer_name
     * @param $mobile
     * @param $password
     * @param $avatarId
     * @param $shareId 推荐人分享码，可为空
     * @return array  [userId,shareCode]
     */
    public function registerCustomer($customer_name, $mobile, $password, $avatarId, $fromShareCode, $shareId)
    {
        $customer_group_id = 1;
        $store_id = 0;
        $shareCode = $this->generateShareCode();


        $sql = "INSERT INTO " . getTable('customer') . " SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$store_id . "', fullname = '" .
            $this->db->escape($customer_name) . "', telephone = '" . $this->db->escape($mobile) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) .
            "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', ip = '" .
            $this->db->escape($_REQUEST->server['REMOTE_ADDR']) . "', status = '1',avatar_id= " . $avatarId . ", approved = '1', shareCode='" . $shareCode . "',date_added = NOW()";

        if ($fromShareCode != null) {
//            $fromCustomerId = $this->getCustomerIdByShareCode($fromShareCode);
            $sql = $sql . " ,referee='" . $this->db->escape($fromShareCode) . "'";

        }

        $this->db->query($sql);
        $customer_id = $this->db->getLastId();

//        $res = $this->db->getAll("select customer_id as userId, shareCode from " . getTable('customer') . " where customer_id=" . $customer_id . " limit 1");

        //祖凯要求增加一条记录
        $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET  operation_type = 5, customer_id=" . (int)$customer_id . ", createTime =  NOW(),sharePic1='customer/564415169065896333.png' ";
        $this->db->query($sql);

        //生成qr
        $imgDir = DIR_IMAGE . 'customer/qrcode/';
        $this->getCustomerQR($customer_id, $imgDir, $shareCode);

        //增加积分
        if ($shareId != null) {
            $sharingController = $this->registry->get('SharingController');
            $sharingController->activeShareRecord($shareId);
        }


        $res = array("userId" => $customer_id, "shareCode" => $shareCode);
        return $res;
    }


    /**
     * 第三方用户登录
     * @param $thirdparty_id
     * @param $thirdparty_type
     * @param $nick_name
     */
    public function thirdAuthorization($thirdparty_id, $thirdparty_type, $avatarId, $nick_name, $fromShareCode)
    {

        $sql = "select b.customer_id  as userId, b.shareCode  from " . getTable('customer_thirdparty') . " a," . getTable('customer') . " b where a.customer_id=b.customer_id and a.thirdparty_id='" . $this->db->escape($thirdparty_id) . "' and a.thirdparty_type='" . $this->db->escape($thirdparty_type) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            $mobile = "";
            $password = "";
            $res = $this->registerCustomer($nick_name, $mobile, $password, $avatarId, $fromShareCode);

            $cusomerId = $res['userId'];

            $sql = "INSERT INTO " . getTable('customer_thirdparty') . " (                      `customer_id`,                      `thirdparty_id`,                      `thirdparty_type`,                      `relate_time`,                      `nick_name`,                      `desc_msg`                    )
                    VALUES                      (
                        " . $cusomerId . ",
                        '" . $this->db->escape($thirdparty_id) . "',
                        '" . $this->db->escape($thirdparty_type) . "',  NOW(),
                        '" . $this->db->escape($nick_name) . "',null
                      ) ";
            $this->db->query($sql);
        } else {
            $cusomerId = $res[0]['userId'];
            $sql = "update " . getTable('customer') . " set fullname='" . $this->db->escape($nick_name) . "' where customer_id=" . $cusomerId;
            $this->db->query($sql);
            $res = $res[0];
        }
        return $res;
    }


    /**
     * 根据客户分享码生成分享链接url
     * @param $customerId
     * @param $shareCode
     * @return string
     * @throws exception
     */
    public function getRegisterUrl($customerId, $shareCode)
    {
        if ($shareCode == null) {
            $sql = "select shareCode from " . getTable('customer') . " where customer_id=" . $customerId;
            $res = $this->db->getAll($sql);
            $shareCode = $res[0]['shareCode'];
            if ($shareCode == null) {
                throw new exception('当前客户分享码为空');
            }
        }

        $sql = "select dvalue from " . getTable('dict') . " where dkey ='registerUrl'";
        $res = $this->db->getAll($sql);
        $registerUrl = $res[0]['dvalue'];
        if ($registerUrl == null) {
            throw new exception('缺少系统配置：registerUrl');
        }

        $link = $registerUrl . "?shareCode=" . $shareCode;
        return $link;
    }

    /**
     * 生成客户注册推广二维码
     * @param $customerId
     * @param $imgDir
     * @param $shareCode
     * @return string
     * @throws exception
     */
    public function getCustomerQR($customerId, $imgDir, $shareCode)
    {
        $domain = $this->getDomain();

        $imgFile = $imgDir . $this->FILE_PRE . $customerId . ".png";
        if (file_exists($imgFile)) {
        } else {
            $link = $this->getRegisterUrl($customerId, $shareCode);
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 6;//生成图片大小
            QRcode::png($link, $imgFile, $errorCorrectionLevel, $matrixPointSize, 2);
        }

        return $domain . $this->IMG_DIR . $this->FILE_PRE . $customerId . ".png";


    }

    public function getSercieNumber($supplierId){
        $sql = "select dvalue as admin from ".getTable('dict')." where dkey ='platform_service_number'";
        $res1 = $this->db->getAll($sql);

        $sql = "select company_contacter_phone as supplier from ".getTable('supplier')." where supplier_id=".$supplierId;
        $res2 = $this->db->getAll($sql);

        return array_merge($res1[0],$res2[0]);
    }

    public function getCity($provinceCode)
    {
        $sql = "select region_code as cityCode ,name from " . getTable('addressbook_china_city') . " where parent_code='" . $this->db->escape($provinceCode) . "'";
        $res = $this->db->getAll($sql);
        return $res;
    }

    public function getDistrict($cityCode)
    {
        $sql = "select  region_code districtCode,name from " . getTable('addressbook_china_district') . " where parent_code='" . $this->db->escape($cityCode) . "'";
        $res = $this->db->getAll($sql);
        return $res;
    }

    public function getProvince()
    {
        $sql = "select  region_code  as provinceCode,name from " . getTable('addressbook_china_province');
        $res = $this->db->getAll($sql);
        return $res;
    }

    public function shoppingProduct($productIds, $productNum, $customerId, $addressId)
    {
        if ($productIds == null) {
            throw new exception("productIds不能为空");
        }
        if ($addressId == null) {
            throw new exception("请选择收货地址");
        }
        $addressRes = $this->queryCustomerAddress($addressId);

        $receiveAddressId = $addressRes[0]['china_city_id'];
        $idArray = explode(",", $productIds);
        $numArray = explode(",", $productNum);

        $shoppingCart = new ShoppingCart($customerId, $receiveAddressId);
        $productController = $this->registry->get('ProductController');

        for ($index = 0; $index < count($idArray); $index++) {
            $productId = $idArray[$index];
            //校验产品是否存在
            $request = array("productId" => $productId);
            $productController->getProductDetail($request);

            //添加到购物车
            $shoppingCart->addProdcut($customerId, $productId, $numArray[$index]);
            $isChk = 'on';
            $shoppingCart->checkProduct($isChk, $productId);

        }

        //获取购物车
        $productArray = $shoppingCart->getProductArray();
        $ghsArray = array();
        $productDetailArray = array();

        $hasOfflineProduct = 0;
        $totalAmount = 0;
        $totalExpressAmount = 0;
        $jifenAmount = 0;
        foreach ($productArray as $productInfo) {
            $productDetail = $productController->getProductDetail($productInfo);

            if ($productDetail[0]['status'] == 3) {

                $productCredit = $productController->getProductCreditByDate($productInfo['productId'], date("Ymd"));
                $productDetail = array_merge($productDetail, array("jifen" => round($productCredit)));

                $totalAmount += ($productDetail[0]['newPrice'] * $productInfo['amt']);
                $jifenAmount += ($productCredit * $productInfo['amt']);
                $ghsId = $productDetail[0]['supplierId'];
                $resSupplierExpress = $productController->getSupplierExpress($ghsId);
				
				$tips1 = "供货商包邮包税标准：满" . $resSupplierExpress[0]['free_shipping'] . "元";
                $tips2 = $resSupplierExpress[0]['free_shipping'];
                $address = "";
                $isChk = "";
                $ghsInfo = $shoppingCart->newGhs($ghsId, $productDetail[0]['supplierName'], $tips1, $tips2, $address, $isChk);
				
//              array_push($ghsArray, array("ghsId" => $productDetail[0]['supplierId'],
//            "ghsName" => $productDetail[0]['supplierName'],"tips1"=>"已包邮（供货商包邮包税标准：满200元）",
//            "tips2"=>"","address"=>"",
//            "isChk" => ""));
//              array_push($ghsArray, $ghsInfo);

                $shoppingCart->addGhs($ghsInfo);

                $src = $productDetail[0]['image'];
                $title = $productDetail[0]['productName'];
                $money = $productDetail[0]['newPrice'];
                $jifen = $productDetail[0]['jifen'];
                $num = $productInfo['amt'];
                $baoyou = "直邮 现货包邮";
                $productId = $productInfo['productId'];
                $isChk = $productInfo['isChk'];
                $productDetail2Array = $shoppingCart->newProductDetail($ghsId, $src, $title, $money, $productCredit, $num, $baoyou, $productId, $isChk);
//        array_push($productDetailArray, array("ghsId" => $productDetail[0]['supplierId'], "src" => $productDetail[0]['image'],
//            "title" => $productDetail[0]['productName'], "money" => $productDetail[0]['newPrice'],
//            "jifen" => $productDetail[0]['jifen'], "num" => $productInfo['amt'],
//            "id" => $productInfo['productId'],
//            "baoyou" => "直邮 现货包邮",
//            "buylink" => "productId=" . $productInfo['productId'], "isChk" => ""
//        ));
                array_push($productDetailArray, $productDetail2Array);
            } else {
                $hasOfflineProduct = 1;//:1,

            }

            //print_r($productDetailArray);
        }

        //$shoppingCart->setGhsArray($ghsArray);
        $shoppingCart->setHasOfflineProduct($hasOfflineProduct);
        $shoppingCart->setTotalAmount($totalAmount);
        $shoppingCart->setTotalExpressAmount($totalExpressAmount);
        $shoppingCart->setJifenAmount($jifenAmount);
        $shoppingCart->setProductDetailArray($productDetailArray);
        return $shoppingCart;
    }

    private function placeOrderMsg($orderId, $supplierIds, $request)
    {

        if ($supplierIds == null) {
            return;
        }
        $idArray = explode(",", $supplierIds);

        for ($index = 0; $index < count($idArray); $index++) {
            $supplierId = $idArray[$index];
            $msgIndex = "supplier_msg_" . $supplierId;

            $supplierMsg = $request[$msgIndex];
            if (isset($supplierId) && isset($supplierMsg)) {
                $sql = "INSERT INTO " . getTable('order_to_msg') . " (  `order_id`,  `supplier_id`,  `msg`,  `add_date`) VALUES  (
                " . $orderId . ",  " . $supplierId . ",    '" . $this->db->escape($supplierMsg) . "',  now()  )";
                $this->db->query($sql);

            }
        }

    }

    public function placeOrder($productIds, $productNum, $customerId, $addressId, $orderMsg, $supplierIds, $request, $shareId, $shareProductId, $referenceCode)
    {
        $this->log->debug('[lesharePayment][placeOrder] productIds = '.$productIds.
            ' productNum = '. $productNum.
            ' customerId = '. $customerId.
            ' addressId = '. $addressId.
            ' orderMsg = '. $orderMsg.
            ' supplierIds = '. $supplierIds.
            ' request = '. $request.
            ' shareId = '. $shareId.
            ' shareProductId = '. $shareProductId.
            ' referenceCode = '. $referenceCode);

        if(!is_valid($supplierIds)){
            throw new exception("供货商信息错误");
        }
        if(!is_valid($productIds)){
            throw new exception("产品信息错误");
        }
        if(!is_valid($productNum)){
            throw new exception("产品数量错误");
        }
        if(!is_valid($customerId)){
            throw new exception("客户id错误");
        }

        if(!is_valid($addressId)){
            throw new exception("收货地址错误");
        }
        //$addressRes = $this->queryCustomerAddress($addressId);
        //$addressId = $addressRes[0]['china_city_id'];

	$orderNoArrayStr = '';
        $orderIdArrayStr = '';
        $orderIdArray = [];
        $supplierIdArray = explode(',',$supplierIds);
        $productIdArray = explode(',',$productIds);
        $productNumArray = explode(',',$productNum);
        foreach($supplierIdArray as $supplierId){
            $productIdsStr = '';
            $productNumStr = '';
            $templats = [];
            $productController = $this->registry->get('ProductController');
            $index = 0;
            foreach($productIdArray as $productId){
                $product = $productController->getProductDetail(['productId'=>$productId])[0];
                if($product['supplierId']==$supplierId){
                    $productIdsStr .= $productId . ',';
                    $productNumStr .= $productNumArray[$index].',';
                    $templateId = $product['templateId'];
                    if(!isset($templats[$templateId])){
                        $templats[$templateId] = [];
                        $templats[$templateId][] = ['id'=>$productId,'num'=>$productNumArray[$index]];
                    }else{
                        $templats[$templateId][] = ['id'=>$productId,'num'=>$productNumArray[$index]];
                    }
                }
                $index++;
            }
            if(is_valid($productIdsStr)){
                $productIdsStr = substr($productIdsStr,0,strlen($productIdsStr)-1);
            }
            if(is_valid($productNumStr)){
                $productNumStr = substr($productNumStr,0,strlen($productNumStr)-1);
            }

            $shoppingCart = $this->shoppingProduct($productIdsStr, $productNumStr, $customerId, $addressId);
            if (count($shoppingCart->getProductDetailArray()) == 0) {
                $this->log->debug('[lesharePayment][placeOrder] illegal product! ');
                throw new exception("无有效商品");
            }
            $ghsProductArray = $shoppingCart->showCartDetail($productController);

            $totalShipMoney = $shoppingCart->getTotalExpressAmount();
            $totalPrice = $shoppingCart->getTotalAmount();
            if ($orderMsg == null) {
                $orderMsg = "";
            }
            $this->checkPlaceOrderRule($shoppingCart);

            $addressRes = $this->queryCustomerAddress($addressId);
            $t_addressId = $addressRes[0]['china_city_id'];
            $this->log->debug('[lesharePayment][placeOrder] customercontroller_addressId '.$t_addressId);

            $orderNo = $this->generatOrderNo();
            $this->log->debug('[lesharePayment][placeOrder] orderNo = '.$orderNo);
            $orderId = $this->insertOrder($customerId, $addressId, $orderMsg, $totalPrice, $orderNo, $totalShipMoney, $referenceCode);
            foreach ($shoppingCart->getProductDetailArray() as $productDetail) {
                //获取当前供货商的运费
                $ghsId = $productDetail['ghsId'];
                $ghs = $shoppingCart->getGhsByGhsId($ghsId);
                $shipMoney = $ghs['shipMoney'];
                $this->insertOrderPruduct($orderId, $productDetail, $shipMoney);
            }

            foreach($templats as $key=>$value){
                $template_id = $key;
                $products = $value;
                $express_price=0;
                if($shipMoney>0)
                    $express_price = $productController->getFreight($supplierId, $products, $t_addressId, $orderId, $ghsProductArray['total']);
                $express_price=$shipMoney;
                $this->log->debug('[lesharePayment][placeOrder] express ghs money = [ '.$ghsProductArray['total'].']');
                $sql = 'insert into '.getTable('order_product_express').' set '
                .' express_price= '.to_db_int($express_price).','
                .' express_no= '.to_db_str('').','
                .' template_id= '.to_db_int($template_id).','
                .' supplier_id= '.to_db_int($supplierId).','
                .' order_id= '.to_db_int($orderId)
                ;
                $this->log->debug('[lesharePayment][placeOrder] express insert sql = [ '.$sql.']');
                $this->db->executeSql($sql);

                $opExpressId = $this->db->getLastId();
                $sql = 'update '.getTable('order_product').' set op_express_id = '.to_db_int($opExpressId)
                    .', express_price = '.to_db_int($express_price)
                    .' where order_id = '.to_db_int($orderId).' and supplier_id = '.to_db_int($supplierId)
                    .' and express_template = '.to_db_int($template_id);
                $this->log->debug('[lesharePayment][placeOrder] express update sql = [ '.$sql.']');
                $this->db->executeSql($sql);
            }


            $orderNoArrayStr .= $orderNo . ',';
            $orderIdArrayStr .= $orderId .',';
            $orderIdArray[] = $orderId;
        }

        if(is_valid($orderNoArrayStr)){
            $orderNoArrayStr = substr($orderNoArrayStr,0,strlen($orderNoArrayStr)-1);
        }
        if(is_valid($orderIdArrayStr)){
            $orderIdArrayStr = substr($orderIdArrayStr,0,strlen($orderIdArrayStr)-1);
        }
        $orderGroupNo = $this->generatOrderGroupNo();
        $sql = 'insert into '.getTable('order_group').' (order_group_no,order_ids_str) values('.to_db_str($orderGroupNo)
            .','.to_db_str($orderIdArrayStr).')';
        $this->db->query($sql);
        $orderGroupId = $this->db->getLastId();
        foreach($orderIdArray as $ORDER_ID){
            $sql = 'update '.getTable('order').' set order_group_id = '.to_db_int($orderGroupId).' where order_id = '.to_db_int($ORDER_ID);
            $this->db->query($sql);
        }
        $domain = $this->getDomain();
        $res = array("orderGroupNo"=>$orderGroupNo,"orderNoStr" => $orderNoArrayStr, "backUrl" => $domain . "/newwap/APIs/shoppingCart/confirmOrder.php");
        return $res;
    }

    private function checkPlaceOrderRule($shoppingCart)
    {
        foreach ($shoppingCart->getProductDetailArray() as $productDetail) {
            $pid = $productDetail['buylink'];
            $num = $productDetail['num'];

            $sql = "select a.quantity as quantity,b.name  as name FROM " . getTable('product') . " a ," . getTable('product_description') . " b
        WHERE a.product_id = b.product_id
            AND b.language_id = 1
            AND a.product_id = " . $pid;
            $res = $this->db->getAll($sql);
            $pname=$res[0]['name'];
            if ($res[0]['quantity'] < $num) {
                throw new exception($pname."库存不足");
            }
        }
    }

    public function placeOrderV2($productIds, $productNum, $customerId, $addressId, $orderMsg, $supplierIds, $request)
    {
        //20160215      祖凯提出，不同供货商要分单，既一张订单只能是一个供货商，所以改成这种情况

        $resOrderNoArray = array();
        $shoppingCart = $this->shoppingProduct($productIds, $productNum, $customerId, $addressId);
        $productController = $this->registry->get('ProductController');
        $ghsProductArray = $shoppingCart->showCartDetail($productController);
        foreach ($ghsProductArray as $ghsProduct) {
            $ghsId = $ghsProduct['ghsId'];

            $totalPrice = $ghsProduct['total'];
            $orderNo = $this->generatOrderNo();
            if ($orderMsg == null) {
                $orderMsg = "";
            }
            $orderId = $this->insertOrder($customerId, $addressId, $orderMsg, $totalPrice, $orderNo);

            foreach ($shoppingCart->getProductDetailArray() as $productDetail) {
                if ($productDetail['ghsId'] == $ghsId) {
                    $this->insertOrderPruduct($orderId, $productDetail);
                }
            }
            $this->placeOrderMsg($orderId, $supplierIds, $request);
            //返回订单号
            array_push($resOrderNoArray, $orderNo);

//            $domain = $this->getDomain();
//            $res = array("orderNo" => $orderNo, "backUrl" => $domain . "/newwap/APIs/shoppingCart/confirmOrder.php");
        }

        return $resOrderNoArray;
    }

    private function insertOrder($customerId, $addressId, $orderMsg, $totalPrice, $orderNo, $totalShipMoney, $referenceCode)
    {
        $sql = "select order_no from " . getTable('order') . " where order_status <>1 and customer_id=" . $customerId;
        $res = $this->db->getAll($sql);
        //1首单0非首单
        if (count($res) == 0) {
            $firstOrder = 1;
        } else {
            $firstOrder = 0;
        }


        $orderTotal = round($totalPrice + $totalShipMoney, 2);
        // Table 'mcc_order'
        $sql = "INSERT INTO " . getTable('order') . "(
            `invoice_no`,  `invoice_prefix`,  `store_id`,  `store_name`,
          `store_url`,
          `customer_id`,
          `customer_group_id`,
          `fullname`,
          `email`,
          `telephone`,
          `fax`,
          `custom_field`,  `comment`,
          `total`,
          `order_status`,  `order_status_id`,
          `affiliate_id`,
          `commission`,
          `marketing_id`,
          `tracking`,
          `language_id`,  `currency_id`,  `currency_code`,  `currency_value`,
          `ip`,  `forwarded_ip`,
          `shippingid`,
          `user_agent`,
          `firstOrder`,
          `accept_language`,
          `date_added`,
          `date_modified`,
          `repay_status`,
          `order_no`,
          `supplier_price`,
          `finish_time`,
          `receiver_fullname`,
          `receiver_phone`,
          `order_type_id`,
          `supplier_id`,
          `receiver_address`,
          `referenceCode`,  `pay_status`,  `order_payment_id`,  `repay_id`,
          `address_prov_id`,  `address_city_id`,  `address_dist_id`,`express_price`)
        SELECT  0 AS invoice_no,'' AS invoice_prefix , a.store_id AS store_id, '' AS store_name, '' AS store_url,
            a.customer_id, a.customer_group_id, a.fullname,a.email,a.telephone,a.fax,
            a.custom_field,
            '" . $this->db->escape($orderMsg) . "' AS COMMENT,
            " . $orderTotal . " AS total,
             0 AS order_status,0 AS order_status_id,
             0 AS affiliate_id,
             0 AS commission,
             0 AS marketing_id ,
             '' AS tracking,
             1 AS language_id,	 c.currency_id ,c.code,c.value AS currency_value,
             '' AS ip, '' AS forwarded_ip,
             NULL AS shippingid,
             '' AS user_agent,
             " . $firstOrder . " AS firstOrder,
             '' AS accept_language,
             NOW() AS date_added,
             NOW() AS date_modified,
             0 AS repay_status,
             '" . $this->db->escape($orderNo) . "' AS order_no,
             " . $totalPrice . " AS supplier_price,
             NULL AS finish_time,
             b. fullname AS receiver_fullname,
             b. phone AS receiver_phone,
             0 AS order_type_id,
             0 AS supplier_id,
             b.address AS receiver_address,
             NULL AS referenceCode, 0 AS pay_status, NULL AS order_payment_id, NULL AS repay_id,
             b.province_id ,b.city_id, b.district_id,".to_db_int(round($totalShipMoney,2))."
         FROM " . getTable('customer') . " a, " . getTable('address') . " b, " . getTable('currency') . " c
         WHERE  c.code = 'CNY' AND a.customer_id = b.customer_id AND  a.customer_id = " . $customerId . " AND b.address_id = " . $addressId;

        $this->log->debug('[lesharePayment][placeOrder] sql = '.$sql);
        $this->db->query($sql);
        $orderId = $this->db->getLastId();
        $this->log->debug('[lesharePayment][placeOrder] orderId = '.$orderId);
        return $orderId;
    }

    private function  insertOrderPruduct($orderId, $productDetail, $shipMoney)
    {

//    case '0':return '未发货';
//    case '1':return '已发货，未收货';
//    case '2':return '已收货';

        //根据当前功供货商的运费确定是否包邮
        if ($shipMoney == 0) {
            $shipment_type = 0;
        } else {
            $shipment_type = 1;
        }
        $pid = $productDetail['buylink'];
        $jifen = $productDetail['jifen'];
        $num = $productDetail['num'];
        if ($num > 0) {
            $money = $productDetail['money'];
            $totalMoney = $money * $num;
            $totalJifen = $jifen * $num;
            $sqlProduct = "INSERT INTO " . getTable('order_product')
            . " (
              `order_id`,
              `product_id`,
              `supplier_id`,
              `name`,
              `model`,
              `quantity`,
              `price`,
              `total`,
              `tax`,
              `reward`,
              `shipment_type`,
              `unit_score`,
              `total_score`,
              `product_sale_type`,
              `main_image`,
              `product_sale_text`,
              `product_status`,
              `supplier_name`, `express_company_id`, `express_no`,  `order_product_status` ,
              `repay_status`,
              `market_price`,
              `supplier_price`,
              `charge_type`,
              `express_template`,
              `credit`,
              `refoundlimit`
          )
        SELECT  " . $orderId . " AS order_id ,
        a.product_id,a.supplier_id,b.name,a.model,
        " . $num . " AS quantity,
        " . $money . " AS price,
        " . $totalMoney . " AS total,
        0 AS tax,
        0 AS reward,
        " . $shipment_type . " AS shipment_type,
        " . $jifen . " AS unit_score,
        " . $totalJifen . " AS total_score,
        1 AS product_sale_type,
        a.image AS main_image,
        NULL AS product_sale_text,
        a.status AS product_status,
        c.supplier_name, 0 as express_company_id,'' as express_no,0 as order_product_status,
        1 as repay_status,
        a.market_price as market_price,
        a.price as supplier_price,
        a.charge_type,
        a.express_template,
        a.credit,
        a.return_limit
         FROM " . getTable('product') . " a ," . getTable('product_description') . " b ," . getTable('supplier') . " c
        WHERE a.product_id = b.product_id AND  c.supplier_id = a.supplier_id
            AND b.language_id = 1
            AND a.product_id = " . $pid;
            $this->db->query($sqlProduct);
            //商品库存要变更
            $sql = "update " . getTable('product') . " set quantity=quantity-" . $num . " where product_id=" . $pid;
            $this->db->query($sql);
            /*$sql = 'insert into '.getTable('order_product_express').' set '
                .' express_price= '.to_db_int($shipMoney).','
                .' express_no= '.to_db_str('').','
                .' template_id= (select express_template) '.to_db_int($shipMoney).','
                .' express_price= '.to_db_int($shipMoney).','
            ;*/
        }
    }

    //生成验证码
    private function generatOrderNo()
    {
        //modify 20160216
        //改为字母DD+14位数字，采用时间戳避免重复
        $length = 4;
        $pattern = '1234567890';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)};    //生成php随机数
        }

        $my_t = gettimeofday();

        return "DD" . $my_t['sec'] . $key;
    }



    private function generatOrderGroupNo()
    {
        //modify 20160216
        //改为字母DD+14位数字，采用时间戳避免重复
        $length = 4;
        $pattern = '1234567890';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)};    //生成php随机数
        }

        $my_t = gettimeofday();

        return "OG" . $my_t['sec'] . $key;
    }


    //Deprecated 作废，客户增加积分操作，统一调用api实现，不能操作客户表
    private function addShareCredit($shareCode, $targetCustomerId)
    {
        if ($shareCode != null) {
            try {
                $customerId = $this->getCustomerIdByShareCode($shareCode);
                $addCredit = 100;
                $sql = "UPDATE " . getTable('customer') . "  SET credit = credit+" . $addCredit . ",regCredit = regCredit+" . $addCredit . " WHERE customer_id =" . $customerId;
                $this->db->query($sql);

                //5 积分转增收入
                $sql = "INSERT INTO " . getTable('credithistory') . " (  `type`,  `ref_id`,  `adddate`,  `credit`,  `customerid`) VALUES
           (    6,  " . $customerId . ",   NOW(),   " . $addCredit . ",    " . $targetCustomerId . " ) ";
                $this->db->query($sql);

            } catch (Exception $e) {
            } finally {
            }
        }
    }

    public function clickShare($shareCode, $productId)
    {
        $customerId = $this->getCustomerIdByShareCode($shareCode);
        if ($productId == null) {
            //通过注册链接访问,
            $sql = "UPDATE " . getTable('customer') . "  SET clickShareCode = clickShareCode+1 WHERE customer_id =" . $customerId;
            $this->db->query($sql);
        } else {
            //通过产品分享访问
            $sql = "UPDATE " . getTable('customer') . "  SET clickShareCode = clickShareCode+1 WHERE customer_id =" . $customerId;
            $this->db->query($sql);
            //TODO
        }

    }

    //通过分享码获取客户
    public function getCustomerIdByShareCode($shareCode)
    {
        $sql = "select customer_id from " . getTable('customer') . " where shareCode = " . to_db_str($shareCode);
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new Exception("分享码不正确，找不到对应用户");
        }
        $customerId = $res[0]['customer_id'];
        return $customerId;
    }


    public function modifyCustomerMobile($customerId, $mobile,$curPwd)
    {
        $sql = "select customer_id from " . getTable('customer') . " where customer_id = " . $this->db->escape($customerId);
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new Exception("找不到对应用户");
        }
        $this->verifyPassword($customerId,$curPwd);
        //验证手机号是否已经存在
        $sql = "select customer_id from " . getTable('customer') . " where telephone = '"  . $this->db->escape($mobile) ."'";
        $res = $this->db->getAll($sql);
        if (count($res)> 0) {
            throw new Exception("手机号".$mobile."已存在");
        }


        $sql = "update " . getTable('customer') . " set telephone='" . $this->db->escape($mobile) . "' where  customer_id = " . $this->db->escape($customerId);
        $this->db->query($sql);
    }

    public function queryCustomerAvatar()
    {
        $domain = $this->getDomain();
        $sql = "select avatar_id, CONCAT('" . $domain . "/image/',imgurl) as img,seq from " . getTable('customer_avatar');
        $res = $this->db->getAll($sql);
        return $res;
    }

    public function setDefaultAddress($customerId, $addressId)
    {
        if ($addressId == null) {
            throw new exception("用户地址id不能为空");
        }
        if ($customerId == null) {
            throw new exception("用户id不能为空");
        }
        $sql = "select address_id,seq,customer_id from " . getTable('address') . " where address_id = " . $addressId;
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            $sql = "update " . getTable('customer') . " set address_id = " . $addressId . " where customer_id = " . $customerId;
            $this->db->query($sql);
        } else {
            throw new exception("用户地址记录不存在");

        }
    }

    public function deleteCustomerAddress($addressId)
    {

        if ($addressId == null) {
            throw new exception("用户地址id不能为空");
        }
        $sql = "select address_id,seq,customer_id from " . getTable('address') . " where address_id = " . $addressId;
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            $seq = $res[0]['seq'];
            $customerId = $res[0]['customer_id'];

            $sql = "delete from " . getTable('address') . " where address_id = " . $addressId;
            $this->db->query($sql);

            //修改数据seq
            $sql = "update " . getTable('address') . " set seq = seq -1 where customer_id=" . $customerId . " and seq>" . $seq;
            $this->db->query($sql);

            $sql = "update " . getTable('customer') . " set address_id = null where address_id = " . $addressId;
            $this->db->query($sql);


        }


    }

    /**
     * @param $addressId 可为空
     * @param $customerId
     * @param $name
     * @param $mobile
     * @param $province
     * @param $district
     * @param $city
     * @param $address
     * @throws exception
     */
    public function modifyCustomerAddress($addressId, $customerId, $name, $mobile, $province, $district, $city, $address, $isDefault)
    {
        if ($addressId == null && $customerId == null) {
            throw new exception("用户id不能为空");
        }
        if ($name == null) {
            throw new exception("收货人姓名不能为空");
        }
        if ($mobile == null) {
            throw new exception("收货人手机号");
        }
        if ($province == null) {
            throw new exception("省份称");
        }
        if ($district == null) {
            throw new exception("行政区不能为空");
        }
        if ($city == null) {
            throw new exception("地市不能为空");
        }
        if ($address == null) {
            throw new exception("地址不能为空");
        }


        $sql = "select id,name  from " . getTable('addressbook_china_province') . " where region_code ='" . $this->db->escape($province) . "'";

        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("省份编码" . $province . "无效");
        }
        $provinceId = $res[0]['id'];
        $provinceName = $res[0]['name'];

        $sql = "select id ,region_code as postCode,name from " . getTable('addressbook_china_city') . " where region_code = '" . $this->db->escape($city) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("地市编码" . $city . "无效");
        }
        $cityId = $res[0]['id'];
        $cityName = $res[0]['name'];
        $postCode = $res[0]['postCode'];

        $sql = "select id  from " . getTable('addressbook_china_district') . " where region_code ='" . $this->db->escape($district) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("行政区编码" . $district . "无效");
        }
        $districtId = $res[0]['id'];

        $sql = "select zone_id as id  from " . getTable('zone') . " where name ='" . $this->db->escape($provinceName) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("获取zoneid，" . $province . "无效");
        }
        $zoneId = $res[0]['id'];

        if ($addressId == null) {
            $sql = "select IFNULL(MAX(seq),0) AS seq from " . getTable('address') . " where customer_id=" . $customerId;
            $res = $this->db->getAll($sql);
            $maxSeq = $res[0]['seq'] + 1;


            $sql = "insert into " . getTable('address') . " set customer_id=" . $customerId . ",fullname='" . $this->db->escape($name) . "',company='',address='"
                . $this->db->escape($address) . "',city= '" . $this->db->escape($cityName) . "',postcode='" . $this->db->escape($postCode)
                . "',phone='" . $this->db->escape($mobile)
                . "',custom_field=''," . "country_id= 44, seq=" . $maxSeq . ",zone_id=" . $zoneId . ",province_id=" . $provinceId . ",city_id=" . $cityId . ",district_id=" . $districtId;
            $this->db->query($sql);
            $addressId = $this->db->getLastId();

            //设置为默认地址
        } else {
            //修改

            $sql = "update " . getTable('address') . " set customer_id=" . $customerId . ",fullname='" . $this->db->escape($name) . "',company='',address='"
                . $this->db->escape($address) . "',city= '" . $this->db->escape($cityName) . "',postcode='" . $this->db->escape($postCode)
                . "',phone='" . $this->db->escape($mobile)
                . "',custom_field=''," . "country_id= 44, seq=1,zone_id=" . $zoneId . ",province_id=" . $provinceId . ",city_id=" . $cityId . ",district_id=" . $districtId
                . " where address_id = " . $addressId;
            $this->db->query($sql);
        }
        if ($isDefault == '1') {
            $sql = "update " . getTable('customer') . " set address_id = " . $addressId . " where customer_id = " . $customerId;
            $this->db->query($sql);
        }


    }

    public function queryCustomerAddress($addressId)
    {
        $resArray = array();
        $sql = "select a.address_id as addressId,a.fullname as name,a.phone as mobile, a.address as address ,c.name as city ,e.region_code as cityId,e.id as china_city_id, c.region_code as provinceId, c.name as provinceName,
        d.region_code as  districtId ,d.name as districtName, a.seq as seq,a.customer_id as customerId
              from " . getTable('address') . " a ," . getTable('addressbook_china_province') .
            " c ," . getTable('addressbook_china_district') . " d, " . getTable('addressbook_china_city') . "e where d.id=a.district_id and e.id=a.city_id  and c.id = a.province_id and  a.address_id = " . $addressId . " order by a.seq";
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            $defaultAddressId = $this->queryCustomerDefalutAddressId($res[0]['customerId']);
            foreach ($res as $address) {
                if ($defaultAddressId == $address['addressId']) {
                    $address = array_merge($address, array("isDefalut" => "1"));
                } else {
                    $address = array_merge($address, array("isDefalut" => "0"));
                }
                array_push($resArray, $address);

            }
        }



        return $resArray;
    }

    public function queryCustomerAddressList($customerId)
    {
        $resArray = array();
        $sql = "select a.address_id as addressId,a.fullname as name,a.phone as mobile, a.address as address ,c.name as city ,a.city_id as cityId, a.province_id as provinceId, c.name as provinceName,
        a.district_id as  districtId ,d.name as districtName, a.seq as seq
              from " . getTable('address') . " a ," . getTable('addressbook_china_province') .
            " c ," . getTable('addressbook_china_district') . " d, " . getTable('addressbook_china_city') . "e where d.id=a.district_id and e.id=a.city_id  and c.id = a.province_id and  a.customer_id = " . $customerId . " order by a.seq";
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            $defaultAddressId = $this->queryCustomerDefalutAddressId($customerId);
            foreach ($res as $address) {
                if ($defaultAddressId == $address['addressId']) {
                    $address = array_merge($address, array("isDefalut" => "1"));
                } else {
                    $address = array_merge($address, array("isDefalut" => "0"));
                }
                array_push($resArray, $address);

            }
        }

        return $resArray;
    }

    public function queryCustomerDefalutAddressId($customerId)
    {
        $sql = "select address_id from  " . getTable('customer') . " where customer_id = " . $customerId;
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            return null;
        }
        return $res[0]['address_id'];
    }

    //作废 积分转赠调用jp提供的api，要有记录和入账时间，不是直接入账
    public function creditTrans($customerId, $targetCustomerId, $credit)
    {
        $currentCredit = $this->queryCustomerCredit($customerId);
        if ($currentCredit == null) {
            $currentCredit = 0;
        }
        if ($credit > $currentCredit) {
            throw new exception("客户积分不足");
        }

        $sql = "update " . getTable('customer') . " set credit= credit-" . $credit . " where  customer_id = " . $customerId;
        $this->db->query($sql);
        //4 积分转增支出
        $sql = "INSERT INTO " . getTable('credithistory') . " (  `type`,  `ref_id`,  `adddate`,  `credit`,  `customerid`) VALUES
           (     4,  " . $targetCustomerId . ",   NOW(),   " . $credit . ",    " . $customerId . " ) ";
        $this->db->query($sql);

        $sql = "update " . getTable('customer') . " set credit= credit+" . $credit . " where  customer_id = " . $targetCustomerId;
        //5 积分转增收入
        $this->db->query($sql);
        $sql = "INSERT INTO " . getTable('credithistory') . " (  `type`,  `ref_id`,  `adddate`,  `credit`,  `customerid`) VALUES
           (    5,  " . $customerId . ",   NOW(),   " . $credit . ",    " . $targetCustomerId . " ) ";
        $this->db->query($sql);

    }

    public function queryCustomerIdByMobile($mobile)
    {
        //根据手机号找到mcc_customer表中的记录
        $sql = "select customer_id from " . getTable('customer') . " where telephone = '" . $this->db->escape($mobile) . "'";

        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("找不到用户记录mobile=" . $mobile);
        }
        $customer_id = $res[0]['customer_id'];
        return $customer_id;
    }

    public function  queryCustomerCredit($customerId)
    {
        $sql = "select IFNULL(credit,0) as credit from " . getTable('customer') . " where customer_id = " . $customerId;

        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("找不到用户记录customerId=" . $customerId);
        }
        $credit = $res[0]['credit'];
        return $credit;
    }


    public function setPassword($customerId, $password, $salt)
    {
        if ($salt == null) {
            $ressalt = $this->db->getAll("select salt from " . getTable('customer') . " where customer_id=" . $customerId);
            $salt = $ressalt[0]["salt"];
        }
        $pwValue = sha1($salt . sha1($salt . sha1($password)));
        $sql = "update " . getTable('customer') . " set salt = '" . $this->db->escape($salt) . "', password = '" . $this->db->escape($pwValue) . "' where customer_id=" . $customerId;
        $this->db->query($sql);
    }

    public function verifyPassword($customerId, $password)
    {
        $ressalt = $this->db->getAll("select salt from " . getTable('customer') . " where customer_id=" . $customerId);
        $salt = $ressalt[0]["salt"];
        $sql = "select customer_id as userId  from " . getTable('customer') . " where customer_id=" . $customerId . " and password='" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' limit 1";
        $res = $this->db->getAll($sql);
        if ($res[0]['userId'] == null) {
            throw new exception("密码不正确");
        }
    }

    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }

    public function getLinks($customerId)
    {
        $finalRes = array();
        $sql = "SELECT d.name as productName, c.shareCode, b.totalNum, b.sucNum, b.credit, a.product_id
FROM mcc_customer_ophistory a, mcc_share_statistic b, mcc_customer c, mcc_product_description d
WHERE a.coh_id = b.cohId
AND a.customer_id = c.customer_id
AND a.product_id = d.product_id
AND d.language_id = 1
AND a.operation_type = 3
AND a.customer_id = $customerId
";
        $res = $this->db->getAll($sql);
        for ($i = 0; $i < count($res); $i++) {
            $finalRes['share'][$i]['name'] = $res[$i]['productName'];
            $finalRes['share'][$i]['totalNum'] = $res[$i]['totalNum'];
            $finalRes['share'][$i]['hitNum'] = $res[$i]['sucNum'];
            $finalRes['share'][$i]['totalCredit'] = $res[$i]['credit'];
            $finalRes['share'][$i]['url'] = $this->getURL($res[$i]['product_id'], $res[$i]['shareCode']);
        }
        $sql = "SELECT c.shareCode, b.totalNum, b.sucNum, b.credit, a.product_id
FROM mcc_customer_ophistory a, mcc_share_statistic b, mcc_customer c
WHERE a.coh_id = b.cohId
AND a.customer_id = c.customer_id
AND a.operation_type = 5
AND a.customer_id = $customerId
";
        $res = $this->db->getAll($sql);
        $finalRes['regist']['name'] = '';
        $finalRes['regist']['totalNum'] = $res[0]['totalNum'];
        $finalRes['regist']['hitNum'] = $res[0]['sucNum'];
        $finalRes['regist']['totalCredit'] = $res[0]['credit'];
        $finalRes['regist']['url'] = $this->getURL('', $res[0]['shareCode']);
        return $finalRes;
    }

    private function getURL($productId, $refCode)
    {
        // not implemented
        return "link$productId/$refCode";
    }


    //生成分享码（8位数字+字母组合）
    private function generateShareCode()
    {
        $length = 6;
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)};    //生成php随机数
        }
        return $key;
    }


    private function getLinkCredit($type, $hitNum)
    {
        // not implemented
        return $hitNum * 2;
    }
}
