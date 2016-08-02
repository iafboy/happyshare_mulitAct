<?php
class ModelReportsAdd extends MyModel {



    public function queryOrders($data=array()){
        //$sql = "select a.order_id,b.supplier_id,a.order_no,a.order_status,a.repay_status, a.pay_money+a.pay_score*0.1-sum((1-b.hasRefound)*e.interest_price*b.quantity+b.hasRefound*ifnull(f.shippment_cost,0)*b.quantity)-a.pay_score*0.1 as supplier_price from "
        //$sql = "select a.order_id,b.supplier_id,a.order_no,a.order_status,a.repay_status, a.pay_money+a.pay_score*0.1-sum((1-b.hasRefound)*e.interest_price*b.quantity)-b.hasRefound*sum(ifnull(f.shippment_cost,0))-sum(b.hasRefound*(b.price*b.quantity-ifnull(f.shippment_cost,0)))-a.allReturn*if(sum(b.pdSent)>0,0,1)*a.express_price as supplier_price from "
         //$sql = "select a.order_id,b.supplier_id,a.order_no,a.order_status,a.repay_status, a.pay_money+a.pay_score*0.1-sum(e.interest_price*b.quantity-e.interest_price*f.return_num)-sum(if(sum(ifnull(f.shippment_cost,0))-a.pay_money>0,1,0)*ifnull(f.shippment_cost,0)/sum(ifnull(f.shippment_cost,0))*(sum(ifnull(f.shippment_cost,0))-a.pay_money))*0.1-sum(ifnull(f.shippment_cost,0)-if(sum(ifnull(f.shippment_cost,0))-a.pay_money>0,1,0)*ifnull(f.shippment_cost,0)/sum(ifnull(f.shippment_cost,0))*(sum(ifnull(f.shippment_cost,0))-a.pay_money)*0.1) as supplier_price from "
       /*
        select a.order_id,b.supplier_id,a.order_no,a.order_status,a.repay_status, a.pay_money+a.pay_score*0.1-sum(e.interest_price*b.quantity-e.interest_price*f.return_num)-round(sum(t.flag*ifnull(f.shippment_cost,0)/t.returntotal*(t.returntotal-a.pay_money))*0.1)-t.returntotal as supplier_price from mcc_order a ,mcc_order_product b left join mcc_refound_history f on (b.order_id=f.order_id and b.product_id=f.product_id ),mcc_order_product_express d,mcc_product e ,mcc_supplier c,(select sum(ifnull(h.shippment_cost,0)) as returntotal,if(sum(ifnull(h.shippment_cost,0))-g.pay_money>0,1,0) as flag ,g.order_id from mcc_order g left join mcc_refound_history h on(g.order_id=h.order_id) group by g.order_id) t where 1=1 and a.order_status in (10,11) and b.supplier_id = c.supplier_id and a.order_id = b.order_id and d.op_express_id=b.op_express_id and e.product_id=b.product_id and a.order_id=t.order_id and c.supplier_id = 143 group by b.supplier_id,a.order_id order by a.date_modified DESC
        */
        $sql="select a.order_id,b.supplier_id,a.order_no,a.order_status,a.repay_status, a.pay_money+a.pay_score*0.1-sum(e.interest_price*b.quantity-e.interest_price*ifnull(f.return_num,0))-t.returntotal as supplier_price from"
            .getTable('order').' a ,'
            .getTable('order_product').' b '
            .'left join'.getTable('refound_history').' f on (b.order_id=f.order_id and b.product_id=f.product_id ) ,'
	.getTable('order_product_express').' d,'
	.getTable('product').' e ,'
            .getTable('supplier').'c ,'
            .'(select ifnull(sum(ifnull(h.shippment_cost,0)),0) as returntotal,if(sum(ifnull(h.shippment_cost,0))-g.pay_money>0,1,0) as flag ,g.order_id from mcc_order g left join mcc_refound_history h on(g.order_id=h.order_id) group by g.order_id) t'
            .' where 1=1 and a.order_status in (10,11) and b.supplier_id = c.supplier_id and a.order_id = b.order_id '
	.' and d.op_express_id=b.op_express_id'
	.' and e.product_id=b.product_id and a.order_id=t.order_id ';
        $sql .= " and c.supplier_id = " . to_db_int($data['filter_supplier_company']);
        if(is_valid($data['filter_order_status']) && $data['filter_order_status'] != '*'){
            $sql .= " and a.order_status = '" .$data['filter_order_status'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_start'])){
            $sql .= " and a.date_modified >= '" .$data['filter_order_finishtime_start'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_end'])){
            $sql .= " and a.date_modified <= '" .$data['filter_order_finishtime_end'] . "' ";
        }
        if(is_valid($data['filter_repay_status'])  && $data['filter_repay_status'] != '*' ){
            $sql .= " and a.repay_status = '" .$data['filter_repay_status'] . "' ";
        }
        $sql .= ' group by b.supplier_id,a.order_id order by a.date_modified desc ';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
//     echo $sql;
        return parent::queryRows($sql);
    }
    public function  queryOrdersCount($data=array()){
        $sql = "select count(1) as count from "
            .getTable('order').' a ,'
            .getTable('order_product').' b ,'
            .getTable('supplier')
            .' c where 1=1 and b.supplier_id = c.supplier_id and a.order_id = b.order_id';

        $sql .= " and c.supplier_id = " . to_db_int($data['filter_supplier_company']);
        if(is_valid($data['filer_order_status']) && $data['filer_order_status'] != '*'){
            $sql .= " and a.order_status = '" .$data['filer_order_status'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_start'])){
            $sql .= " and a.finish_time >= '" .$data['filter_order_finishtime_start'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_end'])){
            $sql .= " and a.finish_time <= '" .$data['filter_order_finishtime_end'] . "' ";
        }
        if(is_valid($data['filter_repay_status'])  && $data['filter_repay_status'] != '*' ){
            $sql .= " and a.repay_status = '" .$data['filter_repay_status'] . "' ";
        }
//        $sql .= ' group by b.supplier_id,a.order_id order by a.order_no ';
        return parent::queryCount($sql);
    }

    public function querySupplierPriceSum($order_ids,$supplier_id){
//        $sql = 'select sum(price) as total from '.getTable('order_product').' where 1=1 and supplier_id = '.to_db_int($supplier_id);
  //    $sql = 'select e.pay_money+e.pay_score*0.1-sum((1-a.hasRefound)*b.interest_price*a.quantity)-a.hasRefound*sum(ifnull(f.shippment_cost,0))-sum(a.hasRefound*(a.price*a.quantity-ifnull(f.shippment_cost,0)))-e.allReturn*if(sum(a.pdSent)>0,0,1)*e.express_price as total from '.getTable('order').'e ,'.getTable('order_product').'a '.' left join '.getTable('refound_history').' f on (f.product_id=a.product_id and a.order_id=f.order_id) ,'.getTable('product').'b,'.getTable('order_product_express').' c ' .' where 1=1 and a.product_id=b.product_id and e.order_id=a.order_id and c.op_express_id=a.op_express_id and a.supplier_id = '.to_db_int($supplier_id);
        $sql = 'select e.pay_money+e.pay_score*0.1-sum(b.interest_price*a.quantity-b.interest_price*ifnull(f.return_num,0))-t.returntotal as total,a.order_id as order_id from '
            .getTable('order').'e ,'
            .getTable('order_product').'a '.' left join '.getTable('refound_history').' f on (f.product_id=a.product_id and a.order_id=f.order_id) ,'
            .getTable('product').'b,'
            .getTable('order_product_express').' c ,'
            .'(select ifnull(sum(ifnull(h.shippment_cost,0)),0) as returntotal,if(sum(ifnull(h.shippment_cost,0))-g.pay_money>0,1,0) as flag ,g.order_id from mcc_order g left join mcc_refound_history h on(g.order_id=h.order_id) group by g.order_id) t'
            .' where 1=1 and a.product_id=b.product_id and e.order_id=a.order_id  and a.order_id=t.order_id and c.op_express_id=a.op_express_id and a.supplier_id = '.to_db_int($supplier_id);

        if(isset($order_ids)){
            if(is_string($order_ids)){
                $sql .= ' and a.order_id = '.to_db_str($order_ids);
            }else if(is_array($order_ids) && count($order_ids) > 0){
                $sql .= ' and a.order_id in (';
                foreach($order_ids as $order_id){
                    $sql = $sql. to_db_str($order_id).',';
                }
                $sql = substr($sql,0,strlen($sql)-1);
                $sql .= ') group by a.order_id';
            }else{
                $sql .= ' and 1=2';
            }
        }else{
            $sql .= ' and 1=2';
        }
        $total=0;
        $rows=parent::queryRows($sql);
        foreach($rows as $rs){
            $total=$total+$rs['total'];
        }
        return $total;
    }
    public function queryCanRepay($order_ids,$supplier_id){

        $sql = 'select count(1) as count from '.getTable('order')
            .'  where 1=1 and order_status != 10 ';
        if(isset($order_ids)){
            if(is_string($order_ids)){
                $sql .= ' and order_id = '.to_db_str($order_ids);
            }else if(is_array($order_ids) && count($order_ids) > 0){
                $sql .= ' and order_id in (';
                foreach($order_ids as $order_id){
                    $sql = $sql. to_db_str($order_id).',';
                }
                $sql = substr($sql,0,strlen($sql)-1);
                $sql .= ')';
            }else{
                return false;
            }
        }else{
            return false;
        }
        $count = parent::queryCount($sql);
        return $count == 0;
    }

}
