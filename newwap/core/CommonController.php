<?php

class CommonController
{
    //生成验证码
    public function generateActiveCode()
    {
        $length=5;
        $pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key='';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        return $key;
    }
    //生成注册ID号
    public function generateRegCode($mobile){

        $length=4;
        $pattern = '1234567890abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key='';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        //$key= $mobile.$key;
        return $key;
    }

    public function generatePassword(){
        $length=6;
        $pattern = '1234567890abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key='';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        return $key;
    }


}