<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/17
 * Time: 23:41
 */
require_once('../../index.php');
require_once('../../core/phpqrcode/phpqrcode.php');

 $my_t=gettimeofday();
//echo  "DD".$my_t['sec'].$my_t['usec'];
echo  $my_t['sec'];
echo date("Ymd");

