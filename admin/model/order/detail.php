<?php
class ModelOrderDetail extends MyModel {

    public function getOrder($order_id){
        $sql = 'select * from '.getTable('order').' a left join '.getTable('order_payment').' b on '.
            ' a.order_payment_id = b.order_payment_id where a.order_id = '.to_db_str($order_id);
        return parent::querySingleRow($sql);
    }

    public function getOrderDetail($order_id){
        $sql = 'select a.*,b.*,c.name as province_name,d.name as city_name, e.name as district_name from ((('.getTable('order').' a left join '.getTable('order_payment').' b on '.
            ' a.order_payment_id = b.order_payment_id) left join '.getTable('addressbook_china_province').' c on a.address_prov_id = c.id ) '
            .' left join '.getTable('addressbook_china_city').' d on a.address_city_id = d.id ) '
            .' left join '.getTable('addressbook_china_district').' e on a.address_dist_id = e.id '
            .' where a.order_id = '.to_db_int($order_id);
        return parent::querySingleRow($sql);
    }

    public function queryOrderSuppliers($order_id){
        $sql = 'select c.supplier_name,c.supplier_id,a.quantity from '.getTable('order_product').' a , '
            .getTable('supplier').' c where  a.supplier_id = c.supplier_id and a.order_id = '.to_db_str($order_id)
            .' group by a.supplier_id';
        return parent::queryRows($sql);
    }

    public function queryOrderProducts($order_id,$supplier_id){
        /*$sql = 'select a.* from '.getTable('order_product').' a '
            .' where a.order_id = '.to_db_str($order_id)
            .' and a.supplier_id = '.to_db_str($supplier_id);
        return parent::queryRows($sql);*/
        $sql = 'select a.*,b.product_no,b.img_3 as img_3 from '.getTable('order_product').' a '.' , '.getTable('product').' b where a.product_id = b.product_id '
            .' and a.order_id = '.to_db_str($order_id)
            .' and a.supplier_id = '.to_db_str($supplier_id);
        return parent::queryRows($sql);
    }

    public function queryProductShipment($product_id){
        $sql = 'select b.supplier_name,a.shipments_id from '.getTable('shipments') .' a, '
            .getTable('order_product').' b,'
            .getTable('shipments_orderproduct').' c '
            .' where 1=1 and b.product_id = '.to_db_str($product_id)
            .' and a.shipments_id = c.shipments_id and b.order_product_id = c.order_product_id';
        return parent::querySingleRow($sql);
    }
    public function getShipmentProcesses($shipments_id){
        $sql = 'select * from '.getTable('shipments').' a, '.getTable('shipments_process').' b '
            .' where 1=1 and a.shipments_id = '.to_db_str($shipments_id).' and a.shipments_id = b.shipments_id order by b.time desc ';
        return parent::queryRows($sql);
    }

    public function getRefundInfo($order_id, $product_id){
        $sql = 'select a.*, b.fullname, c.bank_name from '.getTable('refound_history').' a, '.getTable('customer').' b, '.getTable('bank').' c '
            .' where 1=1 and a.customer_id = b.customer_id and a.bank_id = c.bank_id and a.order_id = '.$order_id. ' and a.product_id = '.$product_id;
//        writeJson(['sql'=>$sql]);
        return parent::queryRows($sql);
    }

    public function getCredit($order_id){
        $sql = 'SELECT pay_score as credit,customer_id from '.getTable('order').' a where a.order_id = '.$order_id;
        return parent::queryRows($sql);
    }

    public function saveTransferNo($orderId,$productId,$transferNo,$cost,$credit,$customer_id){
        $sql='select count(1) from '.getTable('refound_history').' where ifnull(transferNo,-1)<>-1 and order_id = '.$orderId.' and  product_id = '.$productId;
        $count = parent::queryCount($sql);
        if($count >0){
            return;
        }
        $sql = 'UPDATE '.getTable('refound_history').' set transferNo = '.$transferNo
            .' , refund_status = 7 '
            .' where 1=1 and order_id = '.$orderId.' and  product_id = '.$productId;
        parent::executeSql($sql);
        // 更新order_product表
        $sql = 'UPDATE '.getTable('order_product').' set return_goods_status = 6 , hasRefound=1 '
            .' where 1=1 and order_id = '.$orderId.' and  product_id = '.$productId;
        parent::executeSql($sql);
        //get
        $sql='select ifnull(sum(ifnull(h.shippment_cost,0)),0) as returntotal,if(sum(ifnull(h.shippment_cost,0))-g.pay_money>0,1,0) as flag ,g.order_id from mcc_order g left join mcc_refound_history h on(g.order_id=h.order_id) and g.order_id='.$orderId;
        $rows = parent::querySingleRow($sql);
        $returntotal=$rows['returntotal'];
        $flag=$rows['flag'];

        //计算退货剩余资金应当返还的积分数
//        $sql='select '.$flag.'*round(('.$returntotal.'-a.pay_money)*h.shippment/'.$returntotal.')*10 as credit from '.getTable('order').' a, '.getTable('refound_history').' f where a.order_id=f.order_id and a.order_id='.$orderId.' and a.product_id='.$productId;
//        $rows = parent::querySingleRow($sql);
//    	$t_credit = $rows[0]['credit'];
//		if($t_credit>=0){
//			$credit=$t_credit;
//		}else{
//			$credit=0;
//		}
        $sql='select return_score,return_pay,return_num from '.getTable('refound_history').'where order_id='.$orderId.' and product_id='.$productId;;
        $rows=parent::querySingleRow($sql);
        $credit = $rows['return_score'];
        $returnpay = $rows['return_pay'];

        $returnnum = $rows['return_num'];
		// 更新order表
        $sql = 'UPDATE '.getTable('order').' set total = total - '.$returnpay.'-0.1*'.$credit
            .' where order_status <> 10 and order_id = '.$orderId;
        parent::executeSql($sql);
        // 更新customer表
        $sql = 'UPDATE '.getTable('customer').' set credit = credit + '.$credit
            .' where customer_id = '.$customer_id;
        parent::executeSql($sql);
        // 检查该订单下是否还有未退货的产品  如果没有未退货产品则将order状态置成10
        $sql = 'select * from '.getTable('order').' a, '.getTable('refound_history').' b '
            .' where 1=1 and a.order_id = b.order_id  and a.order_id = '.$orderId.' and refund_status != 7' ;
        $rows = parent::queryRows($sql);
        if($rows == null){
            $sql = 'UPDATE '.getTable('order').' set order_status = 10 '
                .' where 1=1 and order_id = '.$orderId;
            parent::executeSql($sql);
        }
        //remove the earned credits
        //$sql='update '.getTable('credithistory').' set credit='.''.' where type=0 and status=0 and productId='.$productId.' and ref_id='.$orderId;
        //parent::executeSql($sql);
        // if all product in an order will be refound
        $sql = 'select * from mcc_order_product where order_product_id not in(select order_product_id from mcc_refound_history where order_id= '.$orderId.') and order_id='.$orderId;
        $rows = parent::queryRows($sql);
        if($rows == null){
            $sql = 'UPDATE '.getTable('order').' set allReturn = 1 '
                .' where order_id = '.$orderId;
            parent::executeSql($sql);
        }
    }

}
