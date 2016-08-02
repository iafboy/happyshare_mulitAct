<?php
class ModelActivityFree extends MyModel {

    public function queryNextFreeId(){
        return parent::queryNextId('freepromotion','fp_id');
    }
    function queryFreeById($subId){
        $sql = 'select a.*,b.regduration,b.amountsetting,b.buyamount,b.buysetting,b.buynumber,b.sharenumber,b.refoundsharesetting,b.rfsharenumber,b.memo as no_auth_memo from '.getTable('freepromotion').' a left join '
            .getTable('freepromotion_setting').' b on a.fp_id = b.fp_id where a.fp_id = '.to_db_int($subId);
        $free = parent::querySingleRow($sql);
        $sql = 'select a.* from '.getTable('fp_imgs').' a where fp_id = '.to_db_int($subId).' order by sort_order asc';
        $subimages = parent::queryRows($sql);
        $free['sub_images'] = $subimages;
        $sql = 'select distinct a.*,b.storeprice,c.name as product_name from '.getTable('fp_refound')
            .' a left join '.getTable('product').' b on a.product_id = b.product_id '
            .' left join '.getTable('product_description')
            .' c on a.product_id = c.product_id where a.fp_id='.to_db_int($subId);
        $refounds = parent::queryRows($sql);
        $free['refounds'] = $refounds;
        $sql = 'select distinct a.*,b.storeprice,c.name as product_name from '.getTable('fp_norefound')
            .' a left join '.getTable('product').' b on a.product_id = b.product_id '
            .' left join '.getTable('product_description')
            .' c on a.product_id = c.product_id where a.fp_id='.to_db_int($subId);
        $norefounds = parent::queryRows($sql);
        $free['norefounds'] = $norefounds;
        return $free;
    }

    public function createFree($data){
        parent::startTransaction();
        try{
            if(!is_valid($data['regduration'])){$data['regduration'] = 0;}
            if(!is_valid($data['amountsetting'])){$data['amountsetting'] = 0;}
            if(!is_valid($data['buyamount'])){$data['buyamount'] = 0;}
            if(!is_valid($data['buysetting'])){$data['buysetting'] = 0;}
            if(!is_valid($data['buynumber'])){$data['buynumber'] = 0;}
            if(!is_valid($data['sharenumber'])){$data['sharenumber'] = 0;}
            if(!is_valid($data['refoundsharesetting'])){$data['refoundsharesetting'] = 0;}
            if(!is_valid($data['rfsharenumber'])){$data['rfsharenumber'] = 0;}
            if(!is_valid($data['no_auth_memo'])){$data['no_auth_memo'] = '';}
            $sql = 'insert into '.getTable('freepromotion')
                .' (`imgurl`,`fp_name`,`starttime`,`endtime`,`memo`) values ('
                .to_db_str($data['imgurl']).','
                .to_db_str($data['act_name']).','
                .to_db_str($data['act_start_date']).','
                .to_db_str($data['act_end_date']).','
                .to_db_str($data['act_memo'])
                .')';
            parent::executeSql($sql);
            $sub_id = parent::getLastId();
            $sql2 = 'insert into '.getTable('freepromotion_setting').
                ' (`fp_id`,`regduration`,`amountsetting`,`buyamount`,`buysetting`,`buynumber`,`sharenumber`,`refoundsharesetting`,`rfsharenumber`,`memo`) '
                .' values ('
                .to_db_int($sub_id).' , '
                .to_db_int($data['regduration']).' , '
                .to_db_int($data['amountsetting']).' , '
                .to_db_int($data['buyamount']).' , '
                .to_db_int($data['buysetting']).' , '
                .to_db_int($data['buynumber']).' , '
                .to_db_int($data['sharenumber']).' , '
                .to_db_int($data['refoundsharesetting']).' , '
                .to_db_int($data['rfsharenumber']).' , '
                .to_db_str($data['no_auth_memo'])
                .')';
            parent::executeSql($sql2);
            $seq = 0;
            foreach($data['subimages'] as $subimage){
                $seq ++;
                $sql4 = 'insert into '.getTable('fp_imgs')
                    .' (`fp_id`,`imgurl`,`sort_order`) '
                    .' values ('
                    .to_db_int($sub_id).' , '
                    .to_db_str($subimage).' , '
                    .to_db_int($seq)
                    .')';
                parent::executeSql($sql4);
            }
            $seq = 0;
            foreach($data['refounds'] as $product_id){
                $seq ++;
                $credit = $data['rfp_'.$product_id.'_credit'] ;
                $act_price = $data['rfp_'.$product_id.'_act_price'] ;
                $limitpeople = $data['rfp_'.$product_id.'_limitpeople'] ;
                $freedays = $data['rfp_'.$product_id.'_freedays'] ;
                $sharenumber = $data['rfp_'.$product_id.'_sharenumber'] ;
                $sql5 = 'insert into '.getTable('fp_refound')
                    .' (`product_id`,`fp_id`,`act_price`,`credit`,`limitpeople`,`freedays`,`sharenumber`,`sort_order`) '
                    .' values ('
                    .to_db_int($product_id).' , '
                    .to_db_int($sub_id).' , '
                    .to_db_int($act_price).' , '
                    .to_db_int($credit).' , '
                    .to_db_int($limitpeople).' , '
                    .to_db_int($freedays).' , '
                    .to_db_int($sharenumber).' , '
                    .to_db_int($seq)
                    .')';
                parent::executeSql($sql5);
            }
            $seq = 0;
            foreach($data['norefounds'] as $product_id){
                $seq ++;
                $act_price = $data['nrfp_'.$product_id.'_act_price'] ;
                $credit = $data['nrfp_'.$product_id.'_credit'] ;
                $limitpeople = $data['nrfp_'.$product_id.'_limitpeople'] ;
                $freedays = $data['nrfp_'.$product_id.'_freedays'] ;
                $sharenumber = $data['nrfp_'.$product_id.'_sharenumber'] ;
                $wxshare = $data['nrfp_'.$product_id.'_wxshare'] ;
                $sql6 = 'insert into '.getTable('fp_norefound')
                    .' (`product_id`,`fp_id`,`act_price`,`credit`,`limitpeople`,`freedays`,`sharenumber`,`wxshare`,`sort_order`) '
                    .' values ('
                    .to_db_int($product_id).' , '
                    .to_db_int($sub_id).' , '
                    .to_db_int($act_price).' , '
                    .to_db_int($credit).' , '
                    .to_db_int($limitpeople).' , '
                    .to_db_int($freedays).' , '
                    .to_db_int($sharenumber).' , '
                    .to_db_int($wxshare).' , '
                    .to_db_int($seq)
                    .')';
                parent::executeSql($sql6);
            }
            $sql3 = 'insert into '.getTable('promotions')
                .' (`subpromotionid`,`type`,`status`,`startdate`,`enddate`) '
                .' values ('
                .to_db_int($sub_id).' , '
                .to_db_int(1).' , '
                .to_db_int($data['act_status']).' , '
                .to_db_str($data['act_start_date']).' , '
                .to_db_str($data['act_end_date'])
                .')';
            parent::executeSql($sql3);
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return true;
    }
    public function modifyFree($data){
        parent::startTransaction();
        try{
            if(!is_valid($data['regduration'])){$data['regduration'] = 0;}
            if(!is_valid($data['amountsetting'])){$data['amountsetting'] = 0;}
            if(!is_valid($data['buyamount'])){$data['buyamount'] = 0;}
            if(!is_valid($data['buysetting'])){$data['buysetting'] = 0;}
            if(!is_valid($data['buynumber'])){$data['buynumber'] = 0;}
            if(!is_valid($data['sharenumber'])){$data['sharenumber'] = 0;}
            if(!is_valid($data['refoundsharesetting'])){$data['refoundsharesetting'] = 0;}
            if(!is_valid($data['rfsharenumber'])){$data['rfsharenumber'] = 0;}
            if(!is_valid($data['no_auth_memo'])){$data['no_auth_memo'] = '';}
            $sql = 'update '.getTable('freepromotion').' set fp_id = fp_id ';
            if(is_valid($data['imgurl'])){
                $sql .= ' , imgurl = '.to_db_str($data['imgurl']);
            }
            $sql.= ', fp_name = '.to_db_str($data['act_name']);
            $sql.= ', starttime = '.to_db_str($data['act_start_date']);
            $sql.= ', endtime = '.to_db_str($data['act_end_date']);
            $sql.= ', memo = '.to_db_str($data['act_memo']);
            $sql .= ' where fp_id = '.to_db_int($data['act_id']);
            $success = parent::executeSql($sql);

            if(!$success){
                throw new Exception('');
            }
            // setting table
            $sql = 'update '.getTable('freepromotion_setting').' set fp_id = fp_id ';
            $sql.= ', regduration = '.to_db_int($data['regduration']);
            $sql.= ', amountsetting = '.to_db_int($data['amountsetting']);
            $sql.= ', buyamount = '.to_db_int($data['buyamount']);
            $sql.= ', buysetting = '.to_db_int($data['buysetting']);
            $sql.= ', buynumber = '.to_db_int($data['buynumber']);
            $sql.= ', sharenumber = '.to_db_int($data['sharenumber']);
            $sql.= ', refoundsharesetting = '.to_db_int($data['refoundsharesetting']);
            $sql.= ', rfsharenumber = '.to_db_int($data['rfsharenumber']);
            $sql.= ', memo = '.to_db_str($data['no_auth_memo']);
            $sql .= ' where fp_id = '.to_db_int($data['act_id']);
            $success = parent::executeSql($sql);
						
            if(!$success){
                throw new Exception('');
            }
            $act_id = $data['act_id'];
			$sub_id = $act_id;


            foreach($data['sub_image_indexes'] as $seq){
                $sql = 'select count(1) as count from '.getTable('fp_imgs').' where fp_id = '.to_db_int($sub_id).' and sort_order = '.$seq;
                $count = parent::queryCount($sql);
                if($count == 0){
                    $sql = 'insert into '.getTable('fp_imgs')
                        .' (`fp_id`,`imgurl`,`sort_order`) '
                        .' values ('
                        .to_db_int($sub_id).' , '
                        .to_db_str($data['sub_image_'.$seq]).' , '
                        .to_db_int($seq)
                        .')';
                }else{
                    $sql = 'update '.getTable('fp_imgs').' set imgurl = '.to_db_str($data['sub_image_'.$seq])
                        .' where fp_id = '.to_db_int($sub_id).' and sort_order = '.$seq;
                }
                parent::executeSql($sql);
            }

            $sql = 'delete from '.getTable('fp_refound').' where fp_id = '.to_db_int($sub_id);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }

            $seq = 0;
            foreach($data['refounds'] as $product_id){
                $seq ++;
                $act_price = $data['rfp_'.$product_id.'_act_price'] ;
                $credit = $data['rfp_'.$product_id.'_credit'] ;
                $limitpeople = $data['rfp_'.$product_id.'_limitpeople'] ;
                $freedays = $data['rfp_'.$product_id.'_freedays'] ;
                $sharenumber = $data['rfp_'.$product_id.'_sharenumber'] ;
                $sql5 = 'insert into '.getTable('fp_refound')
                    .' (`product_id`,`fp_id`,`act_price`,`credit`,`limitpeople`,`freedays`,`sharenumber`,`sort_order`) '
                    .' values ('
                    .to_db_int($product_id).' , '
                    .to_db_int($sub_id).' , '
                    .to_db_int($act_price).' , '
                    .to_db_int($credit).' , '
                    .to_db_int($limitpeople).' , '
                    .to_db_int($freedays).' , '
                    .to_db_int($sharenumber).' , '
                    .to_db_int($seq)
                    .')';
                parent::executeSql($sql5);
            }


            $sql = 'delete from '.getTable('fp_norefound').' where fp_id = '.to_db_int($sub_id);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }
            $seq = 0;
            foreach($data['norefounds'] as $product_id){
                $seq ++;
                $act_price = $data['nrfp_'.$product_id.'_act_price'] ;
                $credit = $data['nrfp_'.$product_id.'_credit'] ;
                $limitpeople = $data['nrfp_'.$product_id.'_limitpeople'] ;
                $freedays = $data['nrfp_'.$product_id.'_freedays'] ;
                $sharenumber = $data['nrfp_'.$product_id.'_sharenumber'] ;
                $wxshare = $data['nrfp_'.$product_id.'_wxshare'] ;
                $sql6 = 'insert into '.getTable('fp_norefound')
                    .' (`product_id`,`fp_id`,`act_price`,`credit`,`limitpeople`,`freedays`,`sharenumber`,`wxshare`,`sort_order`) '
                    .' values ('
                    .to_db_int($product_id).' , '
                    .to_db_int($sub_id).' , '
                    .to_db_int($act_price).' , '
                    .to_db_int($credit).' , '
                    .to_db_int($limitpeople).' , '
                    .to_db_int($freedays).' , '
                    .to_db_int($sharenumber).' , '
                    .to_db_int($wxshare).' , '
                    .to_db_int($seq)
                    .')';
                parent::executeSql($sql6);
            }

            $sub_id = $data['act_id'];
            $sql3 = 'update '.getTable('promotions').' set status = '
                .to_db_int($data['act_status'])
                .' , startdate = '.to_db_str($data['act_start_date'])
                .' , enddate = '.to_db_str($data['act_end_date'])
                .' where subpromotionid = '.to_db_int($sub_id).' and type = '.to_db_int(1);
            $success = parent::executeSql($sql3);
            if(!$success){
                throw new Exception('');
            }
        }catch (Exception $e){
            parent::rollbackTransaction();
            return false;
        }
        parent::commitTransaction();
        return true;
    }

}