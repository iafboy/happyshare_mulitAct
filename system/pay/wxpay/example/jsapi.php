<?php

function error_handler($errno, $errstr, $errfile, $errline) {
	return false;
}
include_once '../../Payment.Config.php';
// Error Handler
set_error_handler('error_handler');
ini_set('date.timezone','Asia/Shanghai');
error_reporting(0);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($log,$data)
{
	$log->INFO("=====================start to print request str ===================");
    foreach($data as $key=>$value){
		$log->INFO($key." ====>  "."$value ");
    }
	$log->INFO("=====================end to print request str ===================");
}
printf_info($log,$_POST);
// unit: 分
if(isset($_POST['ws'])){
	$money = $_POST['totalFee'];  // 订单总金额，单位分
	//$goods_tag = $_POST['productName'];  // 产品标签
	$order_no = $_POST['orderNo']; // 订单编号
	$body = $_POST['productDesc']; // 商品描述
	$frontURL = $_POST['frontUrl'];
	$baseUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
	$baseUrl = $baseUrl. '?totalFee='.$money;
//	$baseUrl = $baseUrl. '&productName='.$goods_tag;
	$baseUrl = $baseUrl. '&orderNo='.$order_no;
	$baseUrl = $baseUrl. '&productDesc='.$body;
	$baseUrl = $baseUrl. '&frontUrl='.$frontURL;
}else{
	$money = $_GET['totalFee'];  // 订单总金额，单位分
	//$goods_tag = $_GET['productName'];  // 产品标签
	$order_no = $_GET['orderNo']; // 订单编号
	$body = $_GET['productDesc']; // 商品描述
	$frontURL = $_GET['frontUrl']; // 商品描述
}



//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenIdWithRedirecturl($baseUrl);


// http://www.51hjfx.com/leshare/system/pay/wxpay/example/jsapi.php?totalFee=1&productName=ProductName&Ldse98723&productDesc=thisisadesc&attach=attachementoptions&





// optional params
//$attach = $_GET['attach'];   // 附加订单信息
//$productDetail = $_GET['productDetail']; // 商品描述
//$feeType = $_GET['fee_type']; // 货币类型 ，默认  CNY


$notifyUrl = PAY_BASE_URL.'wxpay/example/notify.php';

try{
//②、统一下单
	$input = new WxPayUnifiedOrder();
	$input->SetBody($body);
//$input->SetAttach($attach);
	$input->SetOut_trade_no($order_no);
	$input->SetTotal_fee($money);
	$input->SetTime_start(date("YmdHis"));
	$input->SetTime_expire(date("YmdHis", time() + 600));
//$input->SetGoods_tag("test");
	$input->SetNotify_url($notifyUrl);
	$input->SetTrade_type("JSAPI");
	$input->SetOpenid($openId);
	$order = WxPayApi::unifiedOrder($input);
//printf_info($order);
	$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
	/**
	 * 注意：
	 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
	 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
	 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
	 */
}catch(Exception $e){
	$e->getMessage();
}


?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付样例-支付</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
//				alert(res.err_code+res.err_desc+res.err_msg);
				if(res.err_msg == "get_brand_wcpay_request:ok" ) {
					window.location = '<?php echo $frontURL; ?>?wxResult=1&orderNo=<?php echo $order_no; ?>';
				}else{
					window.location = '<?php echo $frontURL; ?>?wxResult=0&orderNo=<?php echo $order_no; ?>';
				}
			}
		);
	}

	function callpay() {
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	/*function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;

				//alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}*/

	window.onload = function(){
		/*if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress);
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}*/
		 callpay();
	};

	</script>
</head>
<body style="padding: 0px;margin:0px;">
<div>
	<h2 style="display: inline-block;position: absolute;left:0px;top:0px;color:#fff;text-align: center;width: 100%;font-family: 'Microsoft Yahei';line-height: 10px;font-size: 30px;">
		支付跳转
	</h2>
	<img src="wechat.png" width="100%" height="auto" />
</div>

</body>