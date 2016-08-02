<?php
class ModelReportsPay extends MyModel {

    public function queryPaySupplier($supplier_id){

        $sql = "select a.supplier_id,a.bankid,a.bankcard from "
            .getTable('supplier')
            .' a where 1=1 and a.supplier_id = '.to_db_int($supplier_id);
        return parent::querySingleRow($sql);
    }

    public function dopay($data=array()){
        $supplier_id = $data['supplier_id'];
        $order_ids = $data['order_ids'];
        $order_ids = explode(",",$order_ids);
        foreach($order_ids as $order_id){
            $sql ='select count(1) as count from '.getTable('repay').
                ' where order_id = '.to_db_int($order_id).' and supplier_id = '.to_db_int($supplier_id);
            $count = parent::queryCount($sql);
            if($count > 0){
                return ['success'=>false,'errMsg'=>'部分订单已填写结算单据，请重新选择！'];
            }
        }
        parent::startTransaction();
        $success = true;
        try{
            foreach($order_ids as $order_id){
                $amount = $this->querySupplierPriceSum($order_id,$supplier_id);
                $sql = 'insert into '.getTable('repay')
                    ." (`from_bank_id`,`from_bankcard`,`to_bank_id`,`to_bankcard`,`transfer_no`,`transfer_amount`,`order_id`,`supplier_id` ) "
                    ." values ("
                    .to_db_str($data['pay_bankid'])." , "
                    .to_db_str($data['pay_bankcard'])." , "
                    .to_db_str($data['supplier_bankid'])." , "
                    .to_db_str($data['supplier_bankcard'])." , "
                    .to_db_str($data['transfer_no'])." , "
                    .to_db_int($amount)." , "
                    .to_db_int($order_id)." , "
                    .to_db_int($supplier_id)."  "
                    .")";
                $success = parent::executeSql($sql);
                //echo "1--".$sql;
                if(!$success){
                    throw new Exception('');
                }
                $sql = 'update '.getTable('order_product').' set repay_status = 2 where order_id = '.to_db_int($order_id)
                    .' and supplier_id = '.to_db_int($supplier_id);
                $success = parent::executeSql($sql);
                //echo "2--".$sql;
                if(!$success){
                    throw new Exception('');
                }
                $sql ='select count(1) as count from '.getTable('order_product').' where order_id = '.to_db_int($order_id)
                    .' and repay_status != 2 ';
                $count = parent::queryCount($sql);
                if($count == 0){
                    // all suppliers have been paid
                    $sql = 'update '.getTable('order').' set repay_status = 1 where order_id = '.to_db_int($order_id);
                }else if($count > 0){
                    // partial suppliers have been paid
                    $sql = 'update '.getTable('order').' set repay_status = 2 where order_id = '.to_db_int($order_id);
                }
                $success = parent::executeSql($sql);
                //echo "3--".$sql;
                if(!$success){
                    throw new Exception('');
                }
            }
        }catch (Exception $e){
            $success = false;
            parent::rollbackTransaction();
        }
        if($success){
            parent::commitTransaction();
        }else{
            parent::rollbackTransaction();
        }

        if($success){
            return ['success'=>true,'errMsg'=>'',
                'location'=>html_entity_decode('index.php?route=reports/list&token='.$this->session->data['token'])];
        }else{
            return ['success'=>false,'errMsg'=>'支付失败！'];
        }
    }

    public function querySupplierPriceSum($order_ids,$supplier_id){
//        $sql = 'select sum(price*quantity) as total from '.getTable('order_product').' where 1=1 and supplier_id = '.to_db_int($supplier_id);
//     $sql = 'select e.pay_money+e.pay_score*0.1-sum((1-a.hasRefound)*b.interest_price*a.quantity)-a.hasRefound*sum(ifnull(f.shippment_cost,0))-sum(a.hasRefound*(a.price*a.quantity-ifnull(f.shippment_cost,0)))-e.allReturn*if(sum(a.pdSent)>0,0,1)*e.express_price as total from '.getTable('order').'e ,'.getTable('order_product').'a '.' left join '.getTable('refound_history').' f on (f.product_id=a.product_id and a.order_id=f.order_id) ,'.getTable('product').'b,'.getTable('order_product_express').' c ' .' where 1=1 and a.product_id=b.product_id and e.order_id=a.order_id and c.op_express_id=a.op_express_id and a.supplier_id = '.to_db_int($supplier_id);
       $sql = 'select e.pay_money+e.pay_score*0.1-sum(b.interest_price*a.quantity-b.interest_price*ifnull(f.return_num,0))-t.returntotal as total,a.order_id as order_id from '
            .getTable('order').'e ,'
            .getTable('order_product').'a '.' left join '.getTable('refound_history').' f on (f.product_id=a.product_id and a.order_id=f.order_id) ,'
            .getTable('product').'b,'
            .getTable('order_product_express').' c ,'
            .'(select ifnull(sum(ifnull(h.shippment_cost,0)),0) as returntotal,if(sum(ifnull(h.shippment_cost,0))-g.pay_money>0,1,0) as flag ,g.order_id from mcc_order g left join mcc_refound_history h on(g.order_id=h.order_id) group by g.order_id) t'
            .' where 1=1 and a.product_id=b.product_id and e.order_id=a.order_id  and a.order_id=t.order_id and c.op_express_id=a.op_express_id and a.supplier_id = '.to_db_int($supplier_id);

      $sql .= ' and a.order_id = '.to_db_str($order_ids);
        $sql .=' group by a.order_id';
        $total=0;
        $rows=parent::queryRows($sql);
        foreach($rows as $rs){
            $total=$total+$rs['total'];
        }
        return $total;
        //return parent::querySingleRow($sql)['total'];
    }

}
