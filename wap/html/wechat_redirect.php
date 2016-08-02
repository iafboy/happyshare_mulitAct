<?php

function error_handler($errno, $errstr, $errfile, $errline) {

    return true;
}

// Error Handler
set_error_handler('error_handler');
function is_valid($var){
    return isset($var) && !is_null($var) && strlen(trim((''.$var))) > 0;
}
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

 $appId = 'wx4ba8a02a5ef1d924';
 $secretId  = '4f4cc4d554de08b0d7628da3164ee92e';
 $code = $_GET['code'];
 $state = $_GET['state'];
if(!is_valid($code)){

}else{
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$secretId
        .'&code='.$code.'&grant_type=authorization_code';
    $json = file_get_contents($url);
    $xxx = $json;
    $json = json_decode($json);

    if(isset($json) && is_valid($json->openid) && is_valid($json->access_token)){

        $getInfoURL = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$json->access_token.'&openid='.$json->openid.'&lang=zh_CN';
        $user = file_get_contents($getInfoURL);
        $yyy = $user;
        $user = json_decode($user);
        if(isset($user) && is_valid($user->openid)){
            $success = 1;
        }else{
            $success = 0;
        }

    }else{
        $success = 0;
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta content="telephone=no" name="format-detection" />
    <title>好就分享</title>
    <link rel="stylesheet" href="../css/weui.css"/>
    <style>
        .container{
            display: none;
        }
        #wait-area{
            display: block;
        }
    </style>
    <script>
        var success = '<?php echo $success; ?>';
        var json = '<?php echo $yyy; ?>';
        var datas = '<?php echo $xxx; ?>';
    </script>
</head>
<body>
<!-- header S -->
<div class="tlCont">
    <!--<div class="bd" style="text-align: center;padding-top: 50px;">
        <span style="display: block" class="weui_icon_msg weui_icon_success"></span>
        <span style="display: block" class="weui_icon_msg weui_icon_warn"></span>
    </div>-->


    <div id="success-area" class="container js_container">
        <div class="page slideIn msg">
            <div class="weui_msg">
                <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
                <div class="weui_text_area">
                    <h2 class="weui_msg_title">登录成功</h2>
                    <p class="weui_msg_desc">跳转中...</p>
                </div>
                <!--<div class="weui_opr_area">
                    <p class="weui_btn_area">
                        <a href="javascript:;" class="weui_btn weui_btn_primary">确定</a>
                        <a href="javascript:;" class="weui_btn weui_btn_default">取消</a>
                    </p>
                </div>-->
                <!--<div class="weui_extra_area">
                    <a href="">查看详情</a>
                </div>-->
            </div>
        </div>
    </div>
    <div id="fail-area" class="container js_container">
        <div class="page slideIn msg">
            <div class="weui_msg">
                <div class="weui_icon_area"><i class="weui_icon_warn weui_icon_msg"></i></div>
                <div class="weui_text_area">
                    <h2 class="weui_msg_title">登录失败</h2>
                </div>
                <div class="weui_opr_area">
                    <p class="weui_btn_area">
                        <a href="login.html" class="weui_btn weui_btn_primary">返回登录</a>
                        <a href="index.html" class="weui_btn weui_btn_default">直接进入</a>
                    </p>
                </div>
                <!--<div class="weui_extra_area">
                    <a href="">查看详情</a>
                </div>-->
            </div>
        </div>
    </div>
    <div id="wait-area" class="container js_container">
        <div class="page slideIn msg">
            <div class="weui_msg">
                <div class="weui_icon_area"><i class="weui_icon_waiting weui_icon_msg"></i></div>
                <div class="weui_text_area">
                    <h2 class="weui_msg_title">登录中....</h2>
                    <p class="weui_msg_desc">请等待...</p>
                </div>
                <!--<div class="weui_opr_area">
                    <p class="weui_btn_area">
                        <a href="javascript:;" class="weui_btn weui_btn_primary">确定</a>
                        <a href="javascript:;" class="weui_btn weui_btn_default">取消</a>
                    </p>
                </div>
                <div class="weui_extra_area">
                    <a href="">查看详情</a>
                </div>-->
            </div>
        </div>
    </div>
</div>
<!-- login E -->
<!-- header E -->
<script src="../js/zepto.min.js"></script>
<script src="../js/iscroll.js"></script>
<script src="../js/slider.js"></script>
<script src="../js/common.js"></script>
<script src="../js/wechat_redirect.js"></script>
<!-- script E -->
</body>
</html>
