<?php

/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 21:58
 */
class OrdcerController
{
    private $db;
    private $log;
    private $cache;

    public function __construct($registry, $cache)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
        $this->cache = $cache;
    }

    public function createOrder($data){

        // productIds []
        // nums []
        // addressId
        // customerId
        $productIdStr = $data['productIds'];
        $numStr = $data['nums'];
        $addressId = $data['addressId'];
        $customerId = $data['customerId'];
        if(is_valid($productIdStr) && is_valid($numStr) && is_valid($addressId) && is_valid($customerId)){

        }



        // Table 'mcc_order'
        // Table 'mcc_order_product'
        //

    }


}
