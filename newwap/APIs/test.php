<?php
//require_once('../index.php');
require_once('../index.php');
//$productId=28;
//$data=$productInfo->getProductDetail($productId);
//echo $data;
//$msg = new \leshare\json\message($data, 0, " success");
//$msg->writeJson();
$shareCode = "pxr4ko";
$shareId = $sharingController->newShareRecord($type,null,null,$shareCode);
echo $shareId;
echo '<br>';
 $sharingController->activeShareRecord($shareId);