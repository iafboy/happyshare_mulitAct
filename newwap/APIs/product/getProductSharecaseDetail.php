<?php
require_once('../../index.php');
require_once('../../tools.php');
try {

    $caseId = $_GET["case_id"];

    if($caseId == null){
        throw new exception("case id不能为空");
    }

    $res = $productController->getShareCaseDetail($caseId);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
