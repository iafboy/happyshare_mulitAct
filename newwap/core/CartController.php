<?php

/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 21:58
 */
class CartController
{
    private $db;
    private $log;
    private $cache;
    private $registry;

    public function __construct($registry, $cache)
    {
        $this->registry = $registry;
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
        $this->cache = $cache;
    }



    public function loadShoppingCart($customerId){
        $res =  $this->cache->get( $this->getCartCacheKey($customerId));
        if($res == null){
            $customerController = $this->registry->get('CustomerController');
            $addressId=$customerController->queryCustomerDefalutAddressId($customerId);
            $addressRes = $customerController->queryCustomerAddress($addressId);
            $receiveAddressId = $addressRes[0]['china_city_id'];

            $res = new ShoppingCart($customerId,$receiveAddressId);
            $this->cache->set(getCartCacheKey($customerId),$res);
        }
        $res;
    }
    public function clearShoppingCart($customerId){
        $this->cache->delete( $this->getCartCacheKey($customerId));
    }

    private function getCartCacheKey($customerId){
        return "shoppingcart".$customerId;
    }


}
