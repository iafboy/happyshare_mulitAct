<?php
require_once('../../index.php');
try {

    $res = $creditController->getCreditRules();
    $msg = $res;
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}