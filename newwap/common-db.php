<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 2016/3/25
 * Time: 1:30
 */
function getActInfo($productId){
    global $db;
    $res = [];
    $promotonId = isProductInActivity($productId,date('Ymd'));
    if($promotonId != 0){
        $promotion = getActDetail($promotonId);
        $res = array_merge($res, ['promotion_id'=>$promotonId,'promotion_type'=>$promotion['type'],'sub_promotion_type'=>$promotion['special_type']]);
        switch ($promotion['type']){
            case 0:
                $sql = 'select a.*,b.credit as ori_credit from '.getTable('special_promotion_products').' a ,'.getTable('product').' b '
                    .' where a.product_id = b.product_id and a.promotion_id = '.to_db_int($promotion['promotion_id'])
                    .' and a.product_id = '.to_db_int($productId);
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            default:
        }
        if(is_valid($sql)){
            $act_product = $db->querySingleRow($sql);
            $ori_credit = $act_product['ori_credit'];
            $act_credit = $act_product['act_credit'];
            if($ori_credit == 0 && $act_credit != 0){
                // return -1 to show unlimited number
                $zoom = -1;
            }
            else if($ori_credit != 0 && $act_credit == 0){
                $zoom = 0;
            }
            else if($ori_credit != 0 && $act_credit != 0){
                $zoom = $act_credit/$ori_credit;
            }else if($ori_credit == 0 && $act_credit == 0){
                $zoom = 1;
            }
            $res = array_merge($res,['act_price'=>$act_product['act_price'],
                'act_credit_percent'=>$act_product['act_credit_percent'],
                'act_credit'=>$act_product['act_credit'],
                'zoom'=>$zoom]);
        }
    }
    return $res;
}

function getActDetail($promotion_id){
    global $db;
    $sql = 'select a.* from '.getTable('promotions').' a where pid = '.to_db_int($promotion_id);
    $promotion = $db->querySingleRow($sql);
    if(isset($promotion)){
        switch ($promotion['type']){
            case 0:
                $sql = 'select a.*,b.* from '.getTable('promotions').' a, '
                    .getPromitionSubTbName($promotion['type'])
                    .' b where a.subpromotionid = b.promotion_id and a.pid = '.to_db_int($promotion_id);
                break;
            case 1:
                $sql = 'select a.*,b.* from '.getTable('promotions').' a, '
                    .getPromitionSubTbName($promotion['type'])
                    .' b where a.subpromotionid = b.fp_id and a.pid = '.to_db_int($promotion_id);
                break;
            case 2:
                $sql = 'select a.*,b.* from '.getTable('promotions').' a, '
                    .getPromitionSubTbName($promotion['type'])
                    .' b where a.subpromotionid = b.cp_id and a.pid = '.to_db_int($promotion_id);
                break;
            case 3:
                $sql = 'select a.*,b.* from '.getTable('promotions').' a, '
                    .getPromitionSubTbName($promotion['type'])
                    .' b where a.subpromotionid = b.gp_id and a.pid = '.to_db_int($promotion_id);
                break;
            case 4:
                $sql = 'select a.*,b.* from '.getTable('promotions').' a, '
                    .getPromitionSubTbName($promotion['type'])
                    .' b where a.subpromotionid = b.tp_id and a.pid = '.to_db_int($promotion_id);
                break;
            default:return [];
        }
    }else{
        return [];
    }
    $result = $db->querySingleRow($sql);
    $image = $result['imgurl'];
    $result['image'] = DIR_IMAGE_URL. $image;
    return $result;
}

function isProductInActivity($productId, $date)
{
    global $db;
    // 查询这个时间点该产品参加中的活动
    // 一个时间点一个产品只能参加一个活动 from @大冰同学
    // 特价活动 积分翻倍活动（mcc_special_promotion, mcc_special_promotion_product）
    $sql = "select a.promotion_id, c.pid as promotionId
                from mcc_special_promotion a, mcc_special_promotion_products b, mcc_promotions c
                where a.promotion_id = b.promotion_id
				and a.promotion_id = c.subpromotionid
                and a.starttime<=DATE('$date')
                and a.endtime>=DATE('$date')
                and b.product_id=$productId";
    $res = $db->getAll($sql);
    if (count($res) != 0) {
        $flag = $res[0]['promotionId'];
    }else{
        $flag = 0;
    }
    return $flag;
}