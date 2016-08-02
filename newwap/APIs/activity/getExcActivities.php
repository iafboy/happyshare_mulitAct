<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 1/24/16
 * Time: 8:45 AM
 */

require_once ('../../index.php');

$result =  $activityController->getExtActivityList();

$msg = new \leshare\json\message($result, 0, "success");
$msg->writeJson();