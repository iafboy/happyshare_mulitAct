<?php
include_once './func/common.php';
include_once './func/log.class.php';
include_once './func/secureUtil.php';
include_once 'SDKConfig.php';
include_once '../Payment.Config.php';
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
function DoPost($url, $post = null) {
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

//    foreach ( $_POST as $key => $val ) {
//        echo isset($mpi_arr[$key]) ?$mpi_arr[$key] : $key ;
//        echo $val;
//    }
    if (isset ( $_POST ['signature'] )) {
        $orderId  = $_POST ['orderId']; //其他字段也可用类似方式获取
        $respCode = $_POST['respCode'];
        $respMsg  = $_POST['respMsg'];
        $queryId  = $_POST['queryId'];
        $isSuccess = verify ( $_POST );
        if($isSuccess){
            if($respCode == '00' || $respCode == 'A6'){
                $ok = 1;
            }else{
                $ok = 0;
            }
            $log->LogInfo('Comfirm Order Params:: orderNo:'.$orderId.' ,respMsg:'.$respMsg.',queryId:'.$queryId.',isSuccess: '.$ok);
            $msg = DoPost(COMMON_PAYMENT_URL,['orderNo'=>$orderId,'respMsg'=>$respMsg,'queryId'=>$queryId,'isSuccess'=>$ok]);
            $log->LogInfo('Comfirm Order Results:: '.$msg);
        }
    } else {
        //签名为空认为不是银联发送，属于攻击
//        echo '签名为空';
    }