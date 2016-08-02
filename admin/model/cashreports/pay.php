<?php
class ModelCashreportsPay extends MyModel {


    public function queryCashReport($cash_report_id){

        $sql = 'select * from '
            .getTable('cash_report').' where cash_report_id = '
            .to_db_int($cash_report_id);
        return parent::querySingleRow($sql);
    }

    public function doPay($data = array() ){
        $sql = ' update '.getTable('cash_report').' set pay_bank_id = '.to_db_int($data['pay_bankid']).', '
            .' pay_bankcard = '.to_db_str($data['pay_bankcard']).','.'cash_pay_no =  '.to_db_str($data['transfer_no'])
            .', cash_pay_time = now() ,cash_pay_status = 1 '
            .' where cash_report_id = '.to_db_int($data['cashreport_id']);
        return parent::executeSql($sql);
    }

}