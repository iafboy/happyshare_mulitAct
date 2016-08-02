<?php
class ModelCommonOrder extends MyModel {

    public function queryOrderTotalCount(){
        $sql = "select count(1) as count from "
            .getTable('order').' a , '
            .getTable('customer').' b , '
            .getTable('order_type').' d '
            .'where 1=1 and a.order_type_id = d.order_type_id and a.customer_id = b.customer_id ';
        return parent::queryCount($sql);
    }
}