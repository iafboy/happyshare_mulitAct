<?php
class ModelScoreRule extends MyModel {

    public function queryCredit(){
        $sql = 'select * from '.getTable('config_credit').' limit 0,1';
        $credit = parent::querySingleRow($sql);
        $sql = 'select * from '.getTable('config_credit_rules').' order by seq asc';
        $rules = parent::queryRows($sql);
        $credit['rules'] = $rules;
        return $credit;
    }

    public function delCreditRule($seq){
        $sql = 'select count(1) as count from '.getTable('config_credit_rules').' where seq != '.to_db_int($seq);
        $count = parent::queryCount($sql);
        if($count == 0){
            return false;
        }
        $sql = 'delete from '.getTable('config_credit_rules').' where seq= '.to_db_int($seq);
        return parent::executeSql($sql);
    }

    public function addOrModify($data){
        $sql = 'update '.getTable('config_credit').' set id = id ';
        $sql.= ', earnCreditLv1 = '.to_db_int($data['earnCreditLv1']);
        $sql.= ', earnCreditLv2 = '.to_db_int($data['earnCreditLv2']);
        $sql.= ', earnCreditLv3 = '.to_db_int($data['earnCreditLv3']);
        $sql.= ', buyCreditLimitLastMonth = '.to_db_int($data['buyCreditLimitLastMonth']);
        $sql.= ', buyCreditLimitThisMonth = '.to_db_int($data['buyCreditLimitThisMonth']);
        $sql.= ', shareCreditLimitLastMonth = '.to_db_int($data['shareCreditLimitLastMonth']);
        $sql.= ', shareCreditLimitThisMonth = '.to_db_int($data['shareCreditLimitThisMonth']);
//        $sql.= ', bonusCreditLimitLastMonth = '.to_db_int($data['bonusCreditLimitLastMonth']);
//        $sql.= ', bonusCreditLimitThisMonth = '.to_db_int($data['bonusCreditLimitThisMonth']);
        parent::startTransaction();
        try{
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }

            $sql = 'delete from '.getTable('config_credit_rules');
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }

            $seqs = $data['seqs'];
            foreach($seqs as $k => $v){
                $sql = 'insert into '.getTable('config_credit_rules')
                    .' (`creditForBuyOnCreditEnabled`,
                `creditForBuyThresholdOnCredit`,
                `creditForBuyOnUserEnabled`,
                `creditForBuyThresholdOnUser`,
                `creditForWithdrawOnCreditEnabled`,
                `creditForWithdrawThresholdOnCredit`,
                `creditForWithdrawOnUserEnabled`,
                `creditForWithdrawThresholdOnUser`,
                `creditToManeyRate`,
                `creditRuleValidDateStart`,
                `creditRuleValidDateEnd`,`seq`) '
                    .' values ('
                    .to_db_int($data['is_r_'.$v.'_'.'creditForBuyThresholdOnCredit']).' , '
                    .to_db_int($data['r_'.$v.'_'.'creditForBuyThresholdOnCredit']).' , '
                    .to_db_int($data['is_r_'.$v.'_'.'creditForBuyThresholdOnUser']).' , '
                    .to_db_int($data['r_'.$v.'_'.'creditForBuyThresholdOnUser']).' , '
                    .to_db_int($data['is_r_'.$v.'_'.'creditForWithdrawThresholdOnCredit']).' , '
                    .to_db_int($data['r_'.$v.'_'.'creditForWithdrawThresholdOnCredit']).' , '
                    .to_db_int($data['is_r_'.$v.'_'.'creditForWithdrawThresholdOnUser']).' , '
                    .to_db_int($data['r_'.$v.'_'.'creditForWithdrawThresholdOnUser']).' , '
                    .to_db_int($data['r_'.$v.'_'.'creditToManeyRate']).' , '
                    .to_db_str($data['r_'.$v.'_'.'creditRuleValidDateStart']).' , '
                    .to_db_str($data['r_'.$v.'_'.'creditRuleValidDateEnd']).','.to_db_int($v)
                    .')';
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('');
                }
            }
        }catch (Exception $e){
            $success = false;
            parent::rollbackTransaction();
        }
        if(!$success){
            parent::rollbackTransaction();
        }else{
            parent::commitTransaction();
        }
        return $success;
    }

    public function queryAllRules(){
        $sql = 'select a.* from '.getTable('config_credit_rules').' a ';
        $rules = parent::queryRows($sql);
        return $rules;
    }

}