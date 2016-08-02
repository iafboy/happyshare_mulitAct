<?php

require_once('../../index.php');

try {
    $sql = "select bank_id as bankId,bank_no as bankNo,bank_name as bankName from " . getTable('bank');
    $res = $db->getAll($sql);
    if (count($res) == 0) {
        throw new exception("暂无提现银行配置");
    }
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}