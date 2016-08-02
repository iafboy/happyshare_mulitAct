<?php

/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/25
 * Time: 22:11
 */
class ShoppingCart
{
    private $customerId;
    private $productArray;
    private $productDetailArray;

    private $totalPrice;
    private $ghsArray;

    private $hasOfflineProduct;//:1,
    private $totalAmount;
    private $totalExpressAmount;
    private $jifenAmount;

    private $receiveAddressId;

    /**
     * ShoppingCart constructor.
     */
    public function __construct($customerId,$receiveAddressId)
    {
        $this->customerId = $customerId;
        $this->receiveAddressId = $receiveAddressId;

    }

    public function calShipPrice($productController)
    {
        //TODO

        $this->totalExpressAmount = 0;
    }

    public function checkGhs($checkType, $ghsId)
    {

        if ($checkType == "on") {
            $isChk = "on";
        } else {
            $isChk = "";
        }
        $ghsInfo = $this->getGhs($ghsId);
        $newGhs = $this->newGhs($ghsInfo['ghsId'], $ghsInfo['ghsName'], $ghsInfo['tips1'], $ghsInfo['tips2'], $ghsInfo['address'], $isChk);
        $this->replaceGhs($ghsInfo, $newGhs);

    }

    private function getGhs($ghsId)
    {
        $res = null;
        foreach ($this->ghsArray as $ghs) {
            if ($ghsId == $ghs['ghsId']) {
                $res = $ghs;
                break;
            }
        }
        return $res;
    }

    public function  newGhs($ghsId, $ghsName, $tips1, $tips2, $address, $isChk)
    {
        $res = array("ghsId" => $ghsId,
            "ghsName" => $ghsName, "tips1" => $tips1,
            "tips2" => $tips2, "address" => $address,
            "isChk" => $isChk);
        return $res;
    }

    private function  replaceGhs($ghs, $newGhs)
    {

        $res = array();
        if ($newGhs != null) {
            array_push($res, $newGhs);
        }

        foreach ($this->ghsArray as $ghsInfo) {
            if ($ghsInfo['ghsId'] != $ghs['ghsId']) {

                array_push($res, $ghsInfo);
            }
        }
        $this->ghsArray = $res;
    }

    public function  checkProduct($checkType, $productId)
    {
        if ($checkType == "on") {
            $isChk = "on";
        } else {
            $isChk = "";
        }

        $product = $this->getProduct($productId);

        $newProduct = array("productId" => $productId, "amt" => $product['amt'], "isChk" => $isChk);
        //array_push($this->productArray, $newProduct);
        $this->replaceProduct($product, $newProduct);

        $productDetail = $this->getProductDetail($productId);
        $newProductDetail = $this->newProductDetail($productDetail['ghsId'], $productDetail['src'],
            $productDetail['title'], $productDetail['money'],
            $productDetail['jifen'], $productDetail['num'],
            $productDetail['baoyou'],
            $productId, $isChk);

        $this - $this->replaceProductDetail($productDetail, $newProductDetail);

    }

    private function  getProduct($productId)
    {
        $res = null;
        foreach ($this->productArray as $productInfo) {
            if ($productId == $productInfo['productId']) {
                $res = $productInfo;
                break;
            }
        }
        return $res;

    }

    private function  replaceProduct($product, $newProduct)
    {
        $res = array();
        if ($newProduct != null) {
            array_push($res, $newProduct);
        }

        foreach ($this->productArray as $productInfo) {
            if ($product['productId'] != $productInfo['productId']) {

                array_push($res, $productInfo);
            }
        }
        $this->productArray = $res;
    }

    private function getProductDetail($productId)
    {
        $res = null;
        foreach ($this->productDetailArray as $productInfo) {
            if ($productId == $productInfo['buylink']) {
                $res = $productInfo;
                break;
            }
        }
        return $res;
    }

    public function  newProductDetail($ghsId, $src, $title, $money, $jifen, $num, $baoyou, $productId, $isChk)
    {
        $res = array("ghsId" => $ghsId, "src" => $src,
            "title" => $title, "money" => $money,
            "jifen" => $jifen, "num" => $num,
            "buylink" => $productId,
            "baoyou" => $baoyou,
            "linktext" => "product_id=" . $productId, "isChk" => $isChk
        );
        return $res;


    }

    private function  replaceProductDetail($productDetail, $newProductDetail)
    {

        if ($newProductDetail != null) {
            array_push($res, $newProductDetail);
        }
        foreach ($this->productDetailArray as $tmp) {
            if ($tmp['buylink'] != $newProductDetail['buylink']) {
                array_push($res, $tmp);
            }
        }

        $this->productDetailArray = $res;
    }

    public function addGhs($ghs)
    {

        if ($this->ghsArray == null) {
            $this->ghsArray = array();
        }
        $ghsInfo = $this->getGhs($ghs['ghsId']);
        if ($ghsInfo == null) {
            array_push($this->ghsArray, $ghs);
        }
    }

    /**
     * 购物车商品明细
     */
    public function showCartDetail($productController)
    {

        $res = array();

        for($index = 0;$index< count($this->ghsArray);$index++){
            $ghsInfo = $this->ghsArray[$index];
            $ghsMoney = 0;
            $ghsShipPrice = 0;

            $productInfoArray = $this->getProductInfoByGhs($ghsInfo['ghsId']);
            if (count($productInfoArray) > 0) {
                $tmp = array("lists" => $productInfoArray);
                $products = [];
                foreach ($productInfoArray as $productInfo) {
                    $products[] = ['num'=>$productInfo['num'],'id'=>$productInfo['buylink']];
                    $ghsMoney += ($productInfo['money']* $productInfo['num']);
                }
                try{

                    $shipPrice = ($productController->getFreight($ghsInfo['ghsId'], $products, $this->receiveAddressId, -1, $ghsMoney));
                    //供货商的运费
                    $ghsShipPrice  += $shipPrice;
                    $this->totalExpressAmount += $shipPrice;
                }catch (Exception $e) {
                }

                //供货商信息中增加运费信息
                $newGhsInfo = array_merge($ghsInfo,array("total"=>$ghsMoney,"shipMoney"=>$ghsShipPrice));

                //替换当前的供货商信息
                $this->replaceGhs($ghsInfo,$newGhsInfo);
                array_push($res, array_merge($newGhsInfo, $tmp));

            } else {
                //没有产品 删除供货商信息
                $this->replaceGhs($ghsInfo, null);
            }
        }

        return $res;

    }

    public function  getProductInfoByGhs($ghsId)
    {
        $res = array();
        foreach ($this->productDetailArray as $productInfo) {
            if ($ghsId == $productInfo['ghsId']) {
                array_push($res, $productInfo);
            }
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function getTotalExpressAmount()
    {
        return $this->totalExpressAmount;
    }

    /**
     * 向购物车添加商品
     * @param $customerId
     * @param $productId
     */
    public function addProdcut($customerId, $productId, $amt)
    {
        if ($amt == null) {
            $amt = 1;
        }
        $this->customerId = $customerId;
        if ($this->productArray == null) {
            $this->productArray = array();
        }
        $product = $this->getProduct($productId);

        if ($product == null) {
            array_push($this->productArray, array("productId" => $productId, "amt" => $amt, "isChk" => ""));
//            $product = new Product($productId, $amt);
//            array_push($this->productArray, $product);
        } else {
            if ($amt + $product['amt'] > 0) {
                $newProduct = array("productId" => $productId, "amt" => $amt + $product['amt'], "isChk" => $product['isChk']);
                //array_push($this->productArray, $newProduct);
                $this->replaceProduct($product, $newProduct);
            } else {
                //实际清除商品
                $this->removeProduct($productId);
            }

        }
//        print_r($this->productArray);

    }

    /**
     * 删除购物车中商品
     * @param $productId
     */
    public function removeProduct($productId)
    {
        $product = $this->getProduct($productId);
        if ($product != null) {
            $this->replaceProduct($product, null);
        }
//        print_r($this->productArray);
    }

    /**
     * 清空购物车
     */
    public function clearCart()
    {
        $this->productArray = array();
        $this->productDetailArray = array();
        $this->hasOfflineProduct = 0;
        $this->totalAmount = 0;
        $this->totalExpressAmount = 0;
        $this->jifenAmount = 0;

        $this->receiveAddressId = null;
    }

    /**
     * @return mixed
     */
    public
    function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     */
    public
    function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return mixed
     */
    public
    function getProductArray()
    {
        return $this->productArray;
    }

    /**
     * @param mixed $productArray
     */
    public
    function setProductArray($productArray)
    {
        $this->productArray = $productArray;
    }

    /**
     * @return mixed
     */
    public
    function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public
    function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return mixed
     */
    public
    function getGhsArray()
    {
        return $this->ghsArray;
    }

    /**
     * @param mixed $ghsArray
     */
    public
    function setGhsArray($ghsArray)
    {
        $this->ghsArray = $ghsArray;
    }

    /**
     * @return mixed
     */
    public
    function getProductDetailArray()
    {
        return $this->productDetailArray;
    }

    public function  getGhsByGhsId($ghsId){
        foreach($this->ghsArray as $ghs){
            if($ghs['ghsId'] == $ghsId){
                $res =  $ghs;
                break;
            }
        }
        return $res;
    }


    /**
     * @param mixed $productDetailArray
     */
    public
    function setProductDetailArray($productDetailArray)
    {
        $this->productDetailArray = $productDetailArray;
    }

    /**
     * @param mixed $hasOfflineProduct
     */
    public function setHasOfflineProduct($hasOfflineProduct)
    {
        $this->hasOfflineProduct = $hasOfflineProduct;
    }

    /**
     * @param mixed $totalAmount
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @param mixed $totalExpressAmount
     */
    public function setTotalExpressAmount($totalExpressAmount)
    {
        $this->totalExpressAmount = $totalExpressAmount;
    }

    /**
     * @param mixed $jifenAmount
     */
    public function setJifenAmount($jifenAmount)
    {
        $this->jifenAmount = $jifenAmount;
    }

    /**
     * @param mixed $receiveAddressId
     */
    public function setReceiveAddressId($receiveAddressId)
    {
        $this->receiveAddressId = $receiveAddressId;
    }

    /**
     * @return mixed
     */
    public function getHasOfflineProduct()
    {
        return $this->hasOfflineProduct;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return mixed
     */
    public function getJifenAmount()
    {
        return $this->jifenAmount;
    }

    /**
     * @return mixed
     */
    public function getReceiveAddressId()
    {
        return $this->receiveAddressId;
    }

}
