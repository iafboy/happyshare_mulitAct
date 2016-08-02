<?php

/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 9:59
 */
class ActivityController{

    private $db;

    private $log;

    private $productController;

    public function __construct($registry){
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
        $this->productController = $registry->get('ProductController');
    }


    public function getExtActivityList(){

        $sql = 'select distinct a.pid,a.subpromotionid,a.type,a.promotion_name from '.getTable('combine_promotion_view')
            .' a where 1=1 and status = 0 and type in (0) and now() >= startdate and now() <= enddate ';
        $result = $this->db->queryRows($sql);

        foreach($result as &$row){
            if($row['type']==0){
                $tbname = getTable('special_promotion');
                $sql = 'select imgurl,special_type from '.$tbname.' where promotion_id = '.to_db_int($row['subpromotionid']);
            }else if($row['type']==1){
                $tbname = getTable('freepromotion');
                $sql = 'select imgurl from '.$tbname.' where fp_id = '.to_db_int($row['subpromotionid']);
            }
            $sub = $this->db->querySingleRow($sql);
            $image = $sub['imgurl'];
            $row['image'] = DIR_IMAGE_URL. $image;
            $row['subType'] = $sub['special_type'];
        }
        return $result;
    }

    public function getActDetail($promotion_id){

        $sql = 'select a.* from '.getTable('promotions').' a where pid = '.to_db_int($promotion_id);
        $promotion = $this->db->querySingleRow($sql);
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
        $result = $this->db->querySingleRow($sql);
        $image = $result['imgurl'];
        $result['image'] = DIR_IMAGE_URL. $image;
        return $result;
    }

    public function getActProductList($promotion_id){
        if(!is_valid($promotion_id)){
            return [];
        }
        $sql = 'select a.* from '.getTable('promotions').' a where pid = '.to_db_int($promotion_id);
        $promotion = $this->db->querySingleRow($sql);
        if(isset($promotion)){
            switch ($promotion['type']){
                case 0:
                    $sql = 'select c.product_id,c.credit_percent,c.storeprice,c.market_price,d.name as product_name,b.act_price,b.act_credit_percent,b.act_credit,c.credit,concat('.to_db_str(DIR_IMAGE_URL).',c.img_3) as image from '.getTable('promotions').' a , '
                        .getTable('special_promotion_products')
                        .' b , '.getTable('product')
                        .' c , '.getTable('product_description')
                        .' d where a.subpromotionid = b.promotion_id and c.status = 3 '
                        .' and b.product_id = c.product_id '
                        .' and b.product_id = d.product_id '
                        .' and d.language_id = 1 '
                        .' and a.pid = '.to_db_int($promotion_id);
                    break;
                case 1:
                    $sql = 'select c.product_id,c.credit_percent,c.storeprice,c.market_price,'
                        .' d.name as product_name,b.act_price,b.credit,b.limitpeople,b.freedays,concat('.to_db_str(DIR_IMAGE_URL).',c.img_3) as image '
                        .' from '.getTable('promotions').' a , '
                        .getTable('fp_refound')
                        .' b , '.getTable('product')
                        .' c , '.getTable('product_description')
                        .' d where a.subpromotionid = b.fp_id and c.status = 3 '
                        .' and b.product_id = c.product_id '
                        .' and b.product_id = d.product_id '
                        .' and d.language_id = 1 '
                        .' and a.pid = '.to_db_int($promotion_id);
                    $sql .=' union all ';
                    $sql .= 'select c.product_id,c.credit_percent,c.storeprice,c.market_price,'
                        .' d.name as product_name,b.act_price,b.credit,b.limitpeople,b.freedays,concat('.to_db_str(DIR_IMAGE_URL).',c.img_3) as image '
                        .' from '.getTable('promotions').' a , '
                        .getTable('fp_norefound')
                        .' b , '.getTable('product')
                        .' c , '.getTable('product_description')
                        .' d where a.subpromotionid = b.fp_id and c.status = 3 '
                        .' and b.product_id = c.product_id '
                        .' and b.product_id = d.product_id '
                        .' and d.language_id = 1 '
                        .' and a.pid = '.to_db_int($promotion_id);
                    break;
                default:return [];
            }
        }else{
            return [];
        }
        $result = $this->db->queryRows($sql);
        foreach($result as &$row){
//            $row['jifen'] = $this->productController->getProductCreditByDate($row['product_id'],date('Ymd'));

            if($promotion['type']==0){
                $row['jifen'] = $row['act_credit'];
                $ori_credit = $row['credit'];
                $act_credit = $row['act_credit'];
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
                $row['zoom'] = $zoom;
            }
        }
        return $result;
    }

    function getActProductDetail($productId,$promotionId){
        if(!is_valid($promotionId) || !is_valid($productId)){
            return [];
        }
        $sql = 'select a.* from '.getTable('promotions').' a where pid = '.to_db_int($promotionId);
        $promotion = $this->db->querySingleRow($sql);
        if(isset($promotion)){
            switch ($promotion['type']){
                case 0:
                    $sql = 'select c.credit_percent,c.credit,b.act_price,b.act_credit_percent,b.act_credit from '.getTable('promotions').' a , '
                        .getTable('special_promotion_products')
                        .' b , '.getTable('product')
                        .' c , '.getTable('product_description')
                        .' d where a.subpromotionid = b.promotion_id and c.status = 3 '
                        .' and b.product_id = c.product_id '
                        .' and b.product_id = d.product_id '
                        .' and d.language_id = 1 '
                        .' and c.product_id = '.to_db_int($productId)
                        .' and a.pid = '.to_db_int($promotionId);
                    break;
                case 1:
                    $sql = 'select c.*,concat('.to_db_str(DIR_IMAGE_URL).',c.image) as src,'
                        .' b.act_price,b.credit,b.limitpeople,b.freedays,b.sharenumber,0 as wxshare,0 as isnorefound '
                        .' from '.getTable('promotions').' a , '
                        .getTable('fp_refound')
                        .' b , '.getTable('product')
                        .' c , '.getTable('product_description')
                        .' d where a.subpromotionid = b.fp_id and c.status = 3 '
                        .' and b.product_id = c.product_id '
                        .' and b.product_id = d.product_id '
                        .' and d.language_id = 1 '
                        .' and c.product_id = '.to_db_int($productId)
                        .' and a.pid = '.to_db_int($promotionId);
                    $sql .=' union all ';
                    $sql .= 'select c.*,concat('.to_db_str(DIR_IMAGE_URL).',c.image) as src,'
                        .'  b.act_price,b.credit,b.limitpeople,b.freedays ,b.sharenumber, wxshare,1 as isnorefound'
                        .' from '.getTable('promotions').' a , '
                        .getTable('fp_norefound')
                        .' b , '.getTable('product')
                        .' c , '.getTable('product_description')
                        .' d where a.subpromotionid = b.fp_id '
                        .' and b.product_id = c.product_id '
                        .' and b.product_id = d.product_id '
                        .' and d.language_id = 1 '
                        .' and c.product_id = '.to_db_int($productId)
                        .' and a.pid = '.to_db_int($promotionId);
                    break;
                default:return [];
            }
        }else{
            return [];
        }

        $row = $this->db->querySingleRow($sql);
        if($promotion['type']==0){
            $row['jifen'] = $row['act_credit'];
            $ori_credit = $row['credit'];
            $act_credit = $row['act_credit'];
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
            $row['zoom'] = $zoom;
        }
        return $row;
    }

    public function getDateFromGivenPeriod($start, $end){
        $res = Array();
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        while ($dt_start<=$dt_end){
            array_push($res, date('Y-m-d',$dt_start)) ;
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $res;
    }

    // 获取无效活动时间
    public function getInvalidDate(){
        $result = Array();
        // 获取所有活动的起始和结束时间
        $sql = "SELECT `starttime`,`endtime` FROM `mcc_special_promotion`";
        $res = $this->db->getAll($sql);
        // 分别计算无效活动时间
        for($i = 0; $i < count($res); $i++){
            $result = array_merge($result, $this->getDateFromGivenPeriod($res[$i]['starttime'],$res[$i]['endtime'])) ;
        }
        // 返回
        return $result;
    }

}