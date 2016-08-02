<?php
require_once('../index.php');

$mobile = $_POST["mobile"];
$password = $_POST["password"];
$forget = $_POST['forget'];


try {

    if($mobile==null){
        throw new exception("请输入手机号");
    }
    if($password==null){
        throw new exception("请输入密码");
    }

    $sql = "select salt,customer_id from " . getTable('customer') . " where telephone='" . $db->escape($mobile) . "' or fullname = '".$db->escape($mobile)."' ";
    $ressalt = $db->getAll($sql);
    if (count($ressalt) == 0) {
        $msg = new \leshare\json\message($res, 2, "当前用户不存在");
    } else {
        $salt = $ressalt[0]["salt"];
        $customer_id = $ressalt[0]["customer_id"];

        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        $sql = "select customer_id as userId,fullname AS userName, CONCAT('" . $domain . "/', 'image/catalog/user.jpg') as userImg, shareCode from " . getTable('customer') . " where customer_id=" . $customer_id . " and password='" . $db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' limit 1";
        $res = $db->getAll($sql);

        if (count($res) == 0) {
            $msg = new \leshare\json\message(null, 1, "用户密码不正确");

        } else {
            //TODO
            if($forget=='1'){
                setcookie("customerName", $res["userName"], time() + 3600);
                setcookie("customerId", $res["userId"], time() + 3600);
            }
            $_SESSION['customerId'] = $res[0]['userId'];
            $msg = new \leshare\json\message($res, 0, " success");
        }
    }

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}