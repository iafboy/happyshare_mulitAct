<?php
require_once('../index.php');
try {

    $code = $_GET['verifyCode'];

    $realCode = $_SESSION['verifyCode'];


    if(is_valid($code) && strtoupper($code)==strtoupper($realCode)){
        $msg = new \leshare\json\message($res, 0, "success");
    }else{
        $msg = new \leshare\json\message($res, 1, "验证码不正确");
    }

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}