<?php

header( 'Access-Control-Allow-Origin:*' );
session_start();
define('VERSION', '1.0.0.0');
define('LOCAL_IP','www.51hjfx.com');
define('ROOT_PATH','/opt/lampp/htdocs/leshare/');
define('HTTP_SERVER', 'http://'.LOCAL_IP.'/leshare/');
define('HTTPS_SERVER', 'https://'.LOCAL_IP.'/leshare/');
define('DIR_APPLICATION', ROOT_PATH.'catalog/');
define('DIR_SYSTEM',      ROOT_PATH.'system/');
define('DIR_WAP',         ROOT_PATH.'wap/');
define('DIR_LANGUAGE',    ROOT_PATH.'catalog/language/');
define('DIR_TEMPLATE',    ROOT_PATH.'catalog/view/theme/');
define('DIR_CONFIG',      ROOT_PATH.'system/config/');
define('DIR_IMAGE',       ROOT_PATH.'image/');
define('DIR_IMAGE_URL',   HTTP_SERVER.'image/');
define('DIR_CACHE',       ROOT_PATH.'system/cache/');
define('DIR_DOWNLOAD',    ROOT_PATH.'system/download/');
define('DIR_UPLOAD',      ROOT_PATH.'system/upload/');
define('DIR_MODIFICATION',ROOT_PATH.'system/modification/');
define('DIR_LOGS',        ROOT_PATH.'system/logs/');
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'lfx');
define('DB_PASSWORD', 'lfx,123');
define('DB_DATABASE', 'lfx');
define('DB_PREFIX', 'mcc_');
define('INDEX_PHP', 'index.php');
define('URL_SEPERATOR', '/');
define('PARAM_MARK', '?');
define('PARAM_SEPERATOR', '&');
define('PARAM_NAMEVALUE_SEPERATOR', '=');
define('CREDIT_EXCHANGE_PERCENT',10);

// helper functions
require_once('tools.php');

require_once(DIR_SYSTEM . 'startup.php');
require(ROOT_PATH.'system/library/db.php');
require(ROOT_PATH.'system/library/db/mpdo.php');
require(ROOT_PATH.'system/library/db/mssql.php');
require(ROOT_PATH.'system/library/db/mysql.php');
require(ROOT_PATH.'system/library/db/mysqli.php');
require(ROOT_PATH.'system/library/db/postgre.php');

//Startup
require_once(DIR_SYSTEM.'startup.php');

//exception handler
function error_handler($errorType, $errorMsg, $errorFile, $errorLine) {
    throw new ErrorException($errorMsg, 0, $errorType, $errorFile, $errorLine);
}
set_error_handler('error_handler');
date_default_timezone_set('PRC');

// Registry
$registry = new Registry();

require_once(ROOT_PATH.'newwap/db.php');
$db = new MYDB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// global configuration
require_once('property.php');

// global logger
require_once(ROOT_PATH.'newwap/log.php');
//require_once('log.php');
$log=new MYLOG();
$registry->set('log', $log);

// global error/exception handler
require_once('handler.php');

// return message definition
require_once('message.class.php');
require_once('common-db.php');
//注册Core里的常用类
//require(ROOT_PATH.'newwap/core/ShowProductInfo.php');
//$productInfo=new GetProductInfo($registry);
//$registry->set('ProductInfo', $productInfo);
require_once(ROOT_PATH.'newwap/core/BrandGroupController.php');
$brandGroupController =new BrandGroupController($registry);
$registry->set('BrandGroupController',$brandGroupController);

require_once(ROOT_PATH.'newwap/core/CollectionController.php');
$collectionController=new CollectionController($registry);
$registry->set('CollectionController',$collectionController);

require_once(ROOT_PATH.'newwap/core/ProductController.php');
$productController=new ProductController($registry);
$registry->set('ProductController',$productController);

require_once(ROOT_PATH.'newwap/core/CommentsController.php');
$commentsController=new CommentsController($registry);
$registry->set('CommentsController',$commentsController);

require_once(ROOT_PATH.'newwap/core/CommonController.php');
$commonController=new CommonController($registry);
$registry->set('CommonController',$commonController);

require_once(ROOT_PATH.'newwap/core/CreditController.php');
$creditController=new CreditController($registry);
$registry->set('CreditController',$creditController);

require_once(ROOT_PATH.'newwap/core/CreditManager.php');
$creditManager=new CreditManager($registry);
$registry->set('CreditManager',$creditManager);

require_once(ROOT_PATH.'newwap/core/leSharePayment.php');
$leSharePayment=new leSharePayment($registry);
$registry->set('LeSharePayment',$leSharePayment);

require_once(ROOT_PATH.'newwap/core/PromotionController.php');
$promotionController=new PromotionController($registry);
$registry->set('PromotionController',$promotionController);

require_once(ROOT_PATH.'newwap/core/SharingController.php');
$sharingController=new SharingController($registry);
$registry->set('SharingController',$sharingController);

require_once(ROOT_PATH.'newwap/core/SMSController.php');
$smsController=new SMSController($registry);
$registry->set('SMSController',$smsController);

require_once(ROOT_PATH.'newwap/core/ShippmentController.php');
$shippmentController=new ShippmentController($registry);
$registry->set('ShippmentController',$shippmentController);

require_once(ROOT_PATH.'newwap/core/CustomerController.php');
$customerController=new CustomerController($registry);
$registry->set('CustomerController',$customerController);

require_once(ROOT_PATH.'newwap/core/ActivityController.php');
$activityController=new ActivityController($registry);
$registry->set('ActivityController',$activityController);

require_once(ROOT_PATH.'newwap/core/ApiController.php');
$apiController=new ApiController($registry);
$registry->set('ApiController',$apiController);

require_once(ROOT_PATH.'newwap/core/WxController.php');
$wxController=new WxController($registry);
$registry->set('WxController',$wxController);

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache);


header( 'Access-Control-Allow-Origin:*' ); 

// router
define("CONTROLLER_DIR", ROOT_PATH."newwap/core/");

$_RequestUri = $_SERVER['REQUEST_URI']; //==>/leshare/newwap/index.php/controller/method?arg1=xxx&arg2=xxx
$index=strpos($_RequestUri, INDEX_PHP);
if ($index)
{
    $_UrlPath = substr($_RequestUri, $index+strlen(INDEX_PHP));// /controller/method?arg1=xxx&arg2=xxx
}
if ($_UrlPath != '')
{
    $_UrlPathArr = explode(PARAM_MARK, $_UrlPath); // [/controller/method] [arg1=xxx&arg2=xxx]

    $_RouterUrl = $_UrlPathArr[0];
    $_ParamUrl = $_UrlPathArr[1];

    $arr_url = array(
        'controller' => 'index',
        'method' => 'index',
        'parms' => array()
    );
    $_RouterUrlArr = explode(URL_SEPERATOR, $_RouterUrl);

    $arr_url['controller'] = $_RouterUrlArr[1];
    $arr_url['method'] = $_RouterUrlArr[2];


    if (strpos($_ParamUrl, PARAM_SEPERATOR)) {
        $_ParamArr = explode(PARAM_SEPERATOR, $_ParamUrl);
        for ($i = 0; $i < count($_ParamArr); $i++) {
            $_ParamNameValueArr = explode(PARAM_NAMEVALUE_SEPERATOR, $_ParamArr[$i]);;
            $arr_temp_hash = array($_ParamNameValueArr[0] => $_ParamNameValueArr[1]);
            $arr_url['parms'] = array_merge($arr_url['parms'], $arr_temp_hash);
        }
    }else{
        $_ParamNameValueArr = explode(PARAM_NAMEVALUE_SEPERATOR, $_ParamUrl);
        $arr_temp_hash = array($_ParamNameValueArr[0] => $_ParamNameValueArr[1]);
        $arr_url['parms'] = array_merge($arr_url['parms'], $arr_temp_hash);
    }

    $module_name = $arr_url['controller'];
    $module_name = $module_name.'Controller';
    $module_name = ucfirst($module_name);

    $module_file = CONTROLLER_DIR.$module_name.'.php';
    $method_name = $arr_url['method'];
	
    if (file_exists($module_file)) {
        $obj_module = new $module_name($registry);
        if (!method_exists($obj_module, $method_name)) {
            die("要调用的方法不存在");
        } else {
            if (is_callable(array($obj_module, $method_name))) {
                $data=$obj_module -> $method_name($arr_url['parms']);
//                $data->writeJson();
            }
        }
    } else {
        die("定义的模块不存在");
    }
}




/**
 * Global Variables Support :
 *
 *  $db  : defined in db.php --> database access support
 *
 *  $logger : defined in log.php --> logger access support
 *
 *  $pros : defined in property.php --> configuration support in database / configure file
 *
 *
 *
 *
 */
