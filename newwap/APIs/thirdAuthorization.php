<?php
require_once('../index.php');
require_once('../core/phpqrcode/phpqrcode.php');

try {
//    $thirdparty_id = $_GET['thirdParty'];
//    $thirdparty_type = $_GET['thirdType'];
//    $nick_name = $_GET['nickName'];
//    $fromShareCode = $_GET['shareCode'];

    $thirdparty_id = $_POST['thirdParty'];
    $thirdparty_type = $_POST['thirdType'];
    $nick_name = $_POST['nickName'];
    $fromShareCode = $_POST['shareCode'];

    if($thirdparty_id == null){
        throw new exception("第三方用户不能为空");
    }
    if($thirdparty_type == null){
        throw new exception("第三方用户类型不能为空");
    }
    if($nick_name == null){
        throw new exception("第三方用户昵称不能为空");
    }

    $avatarId = 1;

    $res = $customerController->thirdAuthorization($thirdparty_id, $thirdparty_type, $avatarId, $nick_name, $fromShareCode);
    $_SESSION['customerId'] = $res['userId'];
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
