<?php
require_once('../../index.php');

if(isset($_SESSION['shareCode'])){
    $shareCode =$_SESSION['shareCode'];
}else{
}


$res = array("shareCode"=>$shareCode);
$msg = new \leshare\json\message($res, 0, " success");
$msg->writeJson();