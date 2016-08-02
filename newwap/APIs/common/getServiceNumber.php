<?php
require_once('../../index.php');
try {
    $supplierId = $_GET['supplierId'];
    $res = $customerController->getSercieNumber($supplierId);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}