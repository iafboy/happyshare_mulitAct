<?php
require_once('./ShoppingCart.php');
$customerId = $customerId = $_GET["customerId"];
if($customerId == null){
    throw new exception("客户id不能为空");
}

$shoppingCart = $cache->get("shoppingcart" . $customerId);
//echo "shoppingCart from cache:";
//echo '<br>';
//echo $shoppingCart;
if ($shoppingCart == null) {
    $addressId=$customerController->queryCustomerDefalutAddressId($customerId);
    $addressRes = $customerController->queryCustomerAddress($addressId);
    $receiveAddressId = $addressRes[0]['china_city_id'];
    $shoppingCart = new ShoppingCart($customerId,$receiveAddressId);
    $cache->set("shoppingcart" . $customerId, $shoppingCart);

//    echo "shoppingCart new:";
//    echo '<br>';
//    echo $shoppingCart;
}

