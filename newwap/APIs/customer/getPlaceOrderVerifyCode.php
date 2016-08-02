<?php

require_once('../../index.php');
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2016/2/18
 * Time: 19:48
 */


function vCode($num = 4, $size = 20, $width = 0, $height = 0) {
    !$width && $width = $num * $size * 4 / 5 + 5;
    !$height && $height = $size + 10;
    // ȥ���� 0 1 O l ��
    $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
    $code = '';
    for ($i = 0; $i < $num; $i++) {
        $code .= $str[mt_rand(0, strlen($str)-1)];
    }
    // ��ͼ��
    $im = imagecreatetruecolor($width, $height);
    // ����Ҫ�õ�����ɫ
    $back_color = imagecolorallocate($im, 235, 236, 237);
    $boer_color = imagecolorallocate($im, 118, 151, 199);
    $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
    // ������
    imagefilledrectangle($im, 0, 0, $width, $height, $back_color);
    // ���߿�
     imagerectangle($im, 0, 0, $width-1, $height-1, $boer_color);
    // ��������
    for($i = 0;$i < 5;$i++) {
        $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
    }
    // �����ŵ�
    for($i = 0;$i < 50;$i++) {
        $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
    }
    // ����֤��
    @imagefttext($im, $size , 0, 5, $size + 3, $text_color, 'simsunb.ttf', $code);
//    echo $code;
    ob_clean();
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    header("Content-type: image/png;charset=gb2312");
    imagepng($im);
    imagedestroy($im);

    $_SESSION['orderCode'] = $code;
}

vCode(5, 15,60,20); //4�����֣���ʾ��СΪ15
