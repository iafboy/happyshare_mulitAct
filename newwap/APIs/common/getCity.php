<?php
require_once('../../index.php');
try {
    $provinceCode = $_GET["provinceCode"];

    $res = $customerController->getCity($provinceCode);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}