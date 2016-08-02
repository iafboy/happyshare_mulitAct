<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$shareId=311;
$data=$registry->get("SharingController")->activeShareRecord($shareId);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();