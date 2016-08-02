<?php
$DEV = 0;
if($DEV == 1){
    define('PAY_BASE_URL','http://123.57.152.218/leshare/system/pay/');
    define('PAY_BASE_URL_SECURE','https://123.57.152.218/leshare/system/pay/');
    define('COMMON_PAYMENT_URL','http://123.57.152.218/leshare/newwap/APIs/order/confirmOrder.php');
}else{
    define('PAY_BASE_URL','http://www.51hjfx.com/leshare/system/pay/');
    define('PAY_BASE_URL_SECURE','https://www.51hjfx.com/leshare/system/pay/');
    define('COMMON_PAYMENT_URL','http://www.51hjfx.com/leshare/newwap/APIs/order/confirmOrder.php');
}
