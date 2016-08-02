<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/17
 * Time: 22:40
 */
require_once('../../index.php');
require_once('../../core/phpqrcode/phpqrcode.php');
$link = $_GET["link"];
$errorCorrectionLevel = 'L';//容错级别
$matrixPointSize = 6;//生成图片大小
//生成二维码图片
QRcode::png($link, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
echo '<img src="qrcode.png">';
