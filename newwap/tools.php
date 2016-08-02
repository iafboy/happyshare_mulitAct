<?php
/**
 * Created by PhpStorm.
 * User: kzu
 * Date: 2016/1/22
 * Time: 14:08
 */

function is_valid($var){
    return isset($var) && !is_null() && strlen(trim((''.$var))) > 0;
}

function to_db_int($var){
    return " ".$var." ";
}

function to_db_str($var){
    $var = str_replace('"','\\"',$var);
    $var = str_replace("'","\\'",$var);
    return " '".$var."' ";
}

function getPromitionSubTbName($type){
    switch($type){
        case 0:return getTable('special_promotion');
        case 1:return getTable('freepromotion');
        case 2:return getTable('credit_promotion');
        case 3:return getTable('gift_promotion');
        case 4:return getTable('trial_promotion');
        default: return '';
    }
}
function getRandomStr($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];
    }
    return $str;
}

/**
 *
 * @param numStr
 * @param decimal opcity to keep
 * @param tf whether remove '0' after '.'
 * @returns {*}
 */
function parseFormatNum($numStr,$decimal,$tf){
    if(is_valid($numStr)){
        $numStr = $numStr . '';
        $dealStr =  getNumberWithDecimal($numStr,$decimal);
    }else {
        $dealStr = getNumberWithDecimal("0", $decimal);
    }
    if($tf !== false){
        if(strpos($dealStr,'.') != false){
            $pos = strpos($dealStr,'.');
            $bool= false;
            for($i = $pos+1;$i<strlen($dealStr);$i++){
                if($dealStr[$i] != '0'){
                    $bool = true;
                }
            }
            if(!$bool){
                $dealStr = substr($dealStr,0,$pos);
            }
        }
    }
    return $dealStr;
}
function getNumberWithDecimal($numStr,$decimal){
    if(!$decimal){
        $decimal = 0;
    }
    //8.34
    //6
    $index = strpos($numStr,'.'); // 1
    $length = strlen($numStr);  // 4
    $decimalLen = ($length-1) - $index;
    if($index== false){

        if($decimal >0){
            $numStr .= '.';
            for($i = 0;$i < $decimal;$i++){
                $numStr .= '0';
            }
        }

        return $numStr;
    }else{
        //99.0
        //888888888.301
        //9999999.80
        if($decimalLen > $decimal){
            $numStr = substr($numStr,0,$length-($decimalLen-$decimal)-1);
        }else if($decimalLen < $decimal){
            for($i = 0;$i < $decimal-$decimalLen;$i++){
                $numStr .= '0';
            }
        }else{
            return $numStr;
        }
    }
    return $numStr;
}

function getNumberOpacity($str){
    if(!is_valid($str)){
        return 0;
    }
    $str = trim($str . '');
    if(strpos($str,'.') == false){
        return 0;
    }
    $len = strlen($str);
    $pos = strpos($str,'.');
    return $len- 1 -$pos;
}
