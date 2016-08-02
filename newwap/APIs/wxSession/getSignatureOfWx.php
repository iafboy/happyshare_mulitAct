<?php
require_once('../../index.php');
/**
 * This is to signature for Wx Api Key
 * 
 */

try {
    $url = $_POST['url'];
    if(!is_valid($url)){
        $url = HTTP_SERVER.'mobile/';
    }
    $signature_arr = $wxController->signature($url);
    $msg = new \leshare\json\message($signature_arr,0,"success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}