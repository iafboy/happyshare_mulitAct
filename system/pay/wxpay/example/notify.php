<?php

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';
include_once '../../Payment.Config.php';
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//function error_handler($errno, $errstr, $errfile, $errline) {
//	global $log;
//
//	// error suppressed with @
//	if (error_reporting() === 0) {
//		return false;
//	}
//	$tf = false;
//	switch ($errno) {
//		case E_NOTICE:
//		case E_USER_NOTICE:
//			$error = 'Notice';
//			break;
//		case E_WARNING:
//		case E_USER_WARNING:
//			$error = 'Warning';
//			break;
//		case E_ERROR:
//		case E_USER_ERROR:
//			$error = 'Fatal Error';
//			break;
//		default:
//			$error = 'Unknown';
//			break;
//	}
//
//	$log->ERROR('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
//
//	return true;
//}
//set_error_handler('error_handler');

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL);



$post = $_POST;

$get = $_GET;

$log->INFO("====================start to notify pay result =======================");
$log->INFO('POST PART::');
foreach($post as $key=>$val){
	$log->INFO(''.$key.' =====> '.$val.'');
}

$log->INFO('GET PART::');
foreach($get as $key=>$val){
	$log->INFO(''.$key.' =====> '.$val.'');
}
$log->INFO("====================end to notify pay result =======================");



function DealPost($url, $post = null) {
	if (is_array($post)) {
		ksort($post);
		$content = http_build_query($post);
		$content_length = strlen($content);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' =>
					"Content-type: application/x-www-form-urlencoded\r\n" .
					"Content-length: $content_length\r\n",
				'content' => $content
			)
		);
		return file_get_contents($url, false, stream_context_create($options));
	}
}



class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		$result = true;
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			$result = false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			$result = false;
		}

		$queryId = $data['transaction_id'];
		$orderNo = $data['out_trade_no'];
		$respMsg = $data['return_msg'];

		if($result==true){
			//payment success
//			file_get_contents(COMMON_PAYMENT_URL.'?orderNo='.$orderNo
//				.'&respMsg='.$respMsg.'&queryId='.$queryId.'&isSuccess=1');
			LOG::INFO('Comfirm Order Params:: orderNo:'.$orderNo.' ,respMsg:'.$respMsg.',queryId:'.$queryId.',isSuccess: 1');
			$msg = DealPost(COMMON_PAYMENT_URL,['orderNo'=>$orderNo,'respMsg'=>$respMsg,'queryId'=>$queryId,'isSuccess'=>1]);
			LOG::INFO('Comfirm Order Results:: '.$msg);
		}else{
			//payment fail
//			file_get_contents(COMMON_PAYMENT_URL.'?orderNo='.$orderNo
//				.'&respMsg='.$respMsg.'&queryId='.$queryId.'&isSuccess=0');
			LOG::INFO('Comfirm Order Params:: orderNo:'.$orderNo.' ,respMsg:'.$respMsg.',queryId:'.$queryId.',isSuccess: 0');
			$msg = DealPost(COMMON_PAYMENT_URL,['orderNo'=>$orderNo,'respMsg'=>$respMsg,'queryId'=>$queryId,'isSuccess'=>0]);
			LOG::INFO('Comfirm Order Results:: '.$msg);
		}
		return $result;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);

//LOG::INFO('Comfirm Order Params:: orderNo:123 ,respMsg:sss,queryId:xxx,isSuccess: 1');
//$msg = DealPost(COMMON_PAYMENT_URL,['orderNo'=>123,'respMsg'=>'222','queryId'=>'xxx','isSuccess'=>1]);
//LOG::INFO('Comfirm Order Results:: '.$msg);
