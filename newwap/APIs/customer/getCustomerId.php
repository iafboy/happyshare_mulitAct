<?php
require_once('../../index.php');

if(isset($_SESSION['customerId'])){
    $customerId =$_SESSION['customerId'];
}else{
}


$res = array("customerId"=>$customerId);
$msg = new \leshare\json\message($res, 0, " success");
$msg->writeJson();