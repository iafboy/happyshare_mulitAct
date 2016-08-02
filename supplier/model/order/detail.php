<?php
class ModelOrderDetail extends MyModel {

    public function getOrder($order_id){
        $sql = 'select b.*,a.*,c.name as province_name,d.name as city_name, e.name as district_name from ((('.getTable('order').' a left join '.getTable('order_payment').' b on '.
            ' a.order_payment_id = b.order_payment_id) left join '.getTable('addressbook_china_province').' c on a.address_prov_id = c.id ) '
            .' left join '.getTable('addressbook_china_city').' d on a.address_city_id = d.id ) '
            .' left join '.getTable('addressbook_china_district').' e on a.address_dist_id = e.id '
            .' where a.order_id = '.to_db_int($order_id);
        return parent::querySingleRow($sql);
    }

    public function queryOrderSuppliers($order_id){
        $sql = 'select c.supplier_name,c.supplier_id,a.quantity from '.getTable('order_product').' a , '
            .getTable('supplier').' c where  a.supplier_id = c.supplier_id and a.order_id = '.to_db_str($order_id)
            .' and c.supplier_id = '.to_db_int($this->session->data['supplier_id'])
            .' group by a.supplier_id';
        return parent::queryRows($sql);
    }

    public function queryOrderProducts($order_id,$supplier_id){
        $sql = 'select a.*,b.product_no,b.img_3 as img_3 from '.getTable('order_product').' a '.' , '.getTable('product').' b where a.product_id = b.product_id '
            .' and a.order_id = '.to_db_str($order_id)
            .' and a.supplier_id = '.to_db_str($supplier_id);
        return parent::queryRows($sql);
    }

    public function queryProductsByExpressTemplate($order_id){
        $sql = 'select distinct a.express_template,b.express_template_name from '
            .getTable('order_product').' a,'.getTable('express_template').' b '
            .' where a.express_template = b.express_template_id and a.order_id = '.to_db_int($order_id)
            .' and a.supplier_id = '.to_db_int($this->session->data['supplier_id']).' group by a.express_template ';
        $rows = parent::queryRows($sql);
        foreach($rows as &$row){
            $templateId = $row['express_template'];
            $sql = 'select name,quantity from '.getTable('order_product')
                .' where order_id = '.to_db_int($order_id)
                .' and supplier_id = '.to_db_int($this->session->data['supplier_id'])
                .' and express_template = '.to_db_int($templateId);
            $products =parent::queryRows($sql);
            $row['products'] = $products;
            $sql = 'select * from '.getTable('order_product_express').' where order_id = '.to_db_int($order_id)
                .' and supplier_id = '.to_db_int($this->session->data['supplier_id'])
                .' and template_id = '.to_db_int($row['express_template']);
            $express = parent::querySingleRow($sql);
            $row['express_price'] = $express['express_price'];
            $row['express_no'] = $express['express_no'];
            $row['express_name'] = $express['express_name'];
            $row['size'] = sizeof($products);
        }
        return $rows;
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


    public function queryExpressCompanies(){
        $sql = 'select expco_id as id,name from '.getTable('express_company');
        return parent::queryRows($sql);
    }

    public function queryExpressInfo($order_id){
        $data = [];
        $sql = 'select a.*,b.name,c.address_prov_id,c.address_dist_id,c.address_city_id from '.getTable('express_price').' a, '.getTable('express_company').' b ,'.getTable('order').' c '
            .' where a.expco = b.expco_id and a.supplier_id = '.to_db_int($this->session->data['supplier_id'])
            .' and a.place_dest_prov = c.address_prov_id and a.place_dest_city = c.address_city_id and c.order_id = '.to_db_int($order_id);
        $row = parent::querySingleRow($sql);
        $data['express_company'] = $row;
        $data['chargetypes'] = [];
        foreach([1,2,3] as $charge_type){
            $sql = 'select a.*,b.product_no,c.express_no,c.express_price from ( '.getTable('order_product').' a '.' left join '.getTable('product').' b on a.product_id = b.product_id) left join '
                .getTable('order_product_express')
                .' c on c.charge_type = a.charge_type and c.supplier_id = a.supplier_id and c.order_id = a.order_id where '
                .' a.order_id = '.to_db_str($order_id)
                .' and a.supplier_id = '.to_db_int($this->session->data['supplier_id']).' and a.charge_type = '.to_db_int($charge_type);
            $result = parent::querySingleRow($sql);
            if(is_array($result) && sizeof($result) > 0){
                $data['chargetypes'][] = $charge_type;
                $data['express_no_'.$charge_type] = $result['express_no'];
                if(!is_valid($result['express_price']) || parseFormatNum($result['express_price'],2) ==0){
                    $sql = 'select * from '.getTable('order_product').' a '
                        .' where a.supplier_id = '.to_db_int($this->session->data['supplier_id'])
                        .' and order_id = '.to_db_int($order_id)
                        .' and charge_type = '.to_db_int($charge_type);
                    $product_idstr = '';
                    $product_numstr = '';
                    $products = parent::queryRows($sql);
                    foreach($products as $p){
                        $product_idstr .= $p['product_id'].',';
                        $product_numstr .= $p['quantity'].',';
                    }
                    if(is_valid($product_idstr)){
                        $product_idstr = substr($product_idstr,0,strlen($product_idstr)-1);
                    }
                    if(is_valid($product_numstr)){
                        $product_numstr = substr($product_numstr,0,strlen($product_numstr)-1);
                    }
                    $params = ['supplierId'=>$this->session->data['supplier_id'],
                        'productIds'=>$product_idstr,'productNums'=>$product_numstr,'orderId'=>$order_id,'receiveAddressId'=>$row['address_city_id']];
                    $msg = DoPost(HTTP_CATALOG.'newwap/APIs/order/getFright.php',$params);
                    $msg = json_decode($msg);
                    //echo $msg->resultMsg;
                    if(!is_valid($msg->data)){
                        if($msg->resultCode == 1){
                            $data['express_price_'.$charge_type] = is_valid($msg->resultMsg)?$msg->resultMsg:'运费错误';
                        }
                    }else{
                        $ship_price = $msg->data;
                        $data['express_price_'.$charge_type] = parseFormatNum($ship_price,2);
                    }
                }else{
                    $data['express_price_'.$charge_type] = parseFormatNum($result['express_price'],2);
                }
                $sql = 'select a.*,b.product_no from  '.getTable('order_product').' a , '.getTable('product').' b where a.product_id = b.product_id  and '
                    .' a.order_id = '.to_db_str($order_id)
                    .' and a.supplier_id = '.to_db_int($this->session->data['supplier_id']).' and a.charge_type = '.to_db_int($charge_type);
                $rows = parent::queryRows($sql);
                if(is_array($rows) && sizeof($rows) > 0){
                    $data['products_'.$charge_type] = $rows;
                }
            }
        }
        return $data;
    }

    public function updateReturnGoods($order_product_id, $allow_returngoods,$shipment_cost,$return_num){
        parent::startTransaction();
        try {
            $sql = 'select * from '.getTable('refound_history').' where order_product_id = '.to_db_int($order_product_id);
            $row = parent::querySingleRow($sql);
            $refund_status=$row['refund_status'];
            $order_id=$row['order_id'];
            //get the actrol return pay amount and score

            if($refund_status== 1 || $refund_status == 2 ||$refund_status == 3 ){
                //check if bigger than return total
                $sql='select ifnull(sum(shippment_cost),0)+'.to_db_int($shipment_cost).' as willpay from mcc_refound_history where order_product_id ='.to_db_int($order_product_id);
                $row = parent::querySingleRow($sql);
                $willpay=$row['willpay'];
                $sql='select pay_score*0.1+pay_money as total from mcc_order where order_id ='.to_db_int($order_id);
                $row = parent::querySingleRow($sql);
                $totalpaid=$row['total'];
                if($totalpaid<$willpay){
                    throw new Exception('输入退货总金额'.$willpay.'大于订单金额'.$totalpaid);
                }
                if($allow_returngoods == 1){
                    $sql = 'update '.getTable('order_product').' set order_product_status = 4 , return_goods_status = 2 where order_product_id = '.to_db_int($order_product_id);
                }else{
                    $sql = 'update '.getTable('order_product').' set order_product_status = 5 , return_goods_status = 3 where order_product_id = '.to_db_int($order_product_id);
                }
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception(''.$sql);
                }

                if($allow_returngoods == 1){
                    $sql = 'update '.getTable('refound_history').' set refund_status = 2 , shippment_cost = '.to_db_int($shipment_cost).',return_num='.to_db_int($return_num).' where order_product_id = '.to_db_int($order_product_id);
                }else{
                    $sql = 'update '.getTable('refound_history').' set refund_status = 3 , shippment_cost = 0 where order_product_id = '.to_db_int($order_product_id);
                }
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('error: '.$sql);
                }
//                $sql= 'update '.getTable('refound_history').' set return_score=return_score+'.$flag.'*round('.$pay2.'*'.$shipment_cost.'/'.$returntotal.')*10,return_pay=return_pay+shippment_cost-'.$flag.'*round('.$pay2.'*shippment_cost/'.$returntotal.') where order_product_id = '.to_db_int($order_product_id);
//                $success = parent::executeSql($sql);
//                if(!$success){
//                    throw new Exception('error: '.$sql);
//                }
            }else{
                if($allow_returngoods == 1){
                    $sql = 'update '.getTable('refound_history').' set  shippment_cost = '.to_db_int($shipment_cost).',return_num='.to_db_int($return_num).' where order_product_id = '.to_db_int($order_product_id);
                }else{
                    $sql = 'update '.getTable('refound_history').' set  shippment_cost = 0 where order_product_id = '.to_db_int($order_product_id);
                }
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('error: '.$sql);
                }
//                $sql= 'update '.getTable('refound_history').' set return_score=return_score+'.$flag.'*round('.$pay2.'*'.$shipment_cost.'/'.$returntotal.')*10,return_pay=return_pay+shippment_cost-'.$flag.'*round('.$pay2.'*shippment_cost/'.$returntotal.') where order_product_id = '.to_db_int($order_product_id);
//                $success = parent::executeSql($sql);
//                if(!$success){
//                    throw new Exception('error: '.$sql);
//                }
            }

        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
            return ['success'=>$success,'errMsg'=> is_valid($e->getMessage())? $e->getMessage(): '操作失败'];
        }
        if($success){
            parent::commitTransaction();
        }else{
            parent::rollbackTransaction();
        }

        if($success){
            return ['success'=>true];
        }else{
            return ['success'=>false,'errMsg'=>'操作失败'];
        }

    }
    public function confirmReturnGoods($order_product_id, $refund_status){
        parent::startTransaction();
        try {
            //if($refund_status == 8 ) {
                $sql = 'update ' . getTable('order_product') . ' set order_product_status = 8 , return_goods_status = 5 '
                    .' where order_product_id = ' . to_db_int($order_product_id);
                $success = parent::executeSql($sql);
                if (!$success) {
                    throw new Exception('修改退货状态异常');
                }
                $sql = 'update ' . getTable('refound_history') . ' set refund_status = '
                    .to_db_int(5).' where order_product_id = ' . to_db_int($order_product_id);
                $success = parent::executeSql($sql);
                if (!$success) {
                    throw new Exception('记录退货历史异常');
                }
                $sql='select ifnull(shippment_cost,0) as shipment_cost,order_id from'.getTable('refound_history').' where order_product_id = '.to_db_int($order_product_id);
                $row = parent::querySingleRow($sql);
                $shipment_cost=$row['shipment_cost'];
                $order_id=$row['order_id'];

                $sql='select ifnull(sum(ifnull(h.shippment_cost,0)),0) as returntotal,if(sum(ifnull(h.shippment_cost,0))-g.pay_money>0,1,0) as flag,if(sum(ifnull(h.shippment_cost,0))-g.pay_money<0,0,sum(ifnull(h.shippment_cost,0))-g.pay_money) as pay2 from '.getTable('order').' g left join '.getTable('refound_history').' h on(g.order_id=h.order_id) and g.order_id='.$order_id;
                $row = parent::querySingleRow($sql);
                $returntotal=$row['returntotal'];
                $flag=$row['flag'];
                $pay2=$row['pay2'];

                $sql= 'update '.getTable('refound_history').' set return_score='.$flag.'*round('.$pay2.'*'.$shipment_cost.'/'.$returntotal.'*10),return_pay='.$shipment_cost.'-'.$flag.'*round(round('.$pay2.'*'.$shipment_cost.'/'.$returntotal.'*10)/10,2) where order_product_id = '.to_db_int($order_product_id);
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('error: '.$sql);
                }
            //check if total return is bigger than actrol payment
            $sql='select sum(return_pay) returntotalpay from '.getTable('refound_history').'  where order_id='.$order_id;
            $row = parent::querySingleRow($sql);
            $returntotalpay=$row['returntotalpay'];
            $sql='select pay_score,pay_money,'.$returntotalpay.'-pay_money as delta from '.getTable('order').'  where order_id='.$order_id;
            $row = parent::querySingleRow($sql);
            $pay_score=$row['pay_score'];
            $pay_money=$row['pay_money'];
            $delta=$row['delta'];
            if($delta>0){
                $sql= 'update '.getTable('refound_history').' set return_score=return_score+round('.$delta.'*10),return_pay=return_pay-'.$delta.' where order_product_id = '.to_db_int($order_product_id);
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('error: '.$sql);
                }
            }
                /*$sql = 'update ' . getTable('order') . ' set order_status = 10 , order_status_id =10 where order_id = (select distinct(order_id) from  ' . getTable('order_product') . ' where order_product_id = ' . to_db_int($order_product_id).')';
                $success = parent::executeSql($sql);

                if (!$success) {
                    throw new Exception('');
                }*/
           // }else{
            //    throw new Exception('--'.$refund_status.'退货状态异常');
            //}
        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
            return ['success'=>$success,'errMsg'=> is_valid($e->getMessage())? $e->getMessage(): '操作失败'];
        }
        if($success){

            parent::commitTransaction();
        }else{
            parent::rollbackTransaction();
        }

        if($success){
            return ['success'=>true];
        }else{
            return ['success'=>false,'errMsg'=>'操作失败'];
        }

    }

    public function queryRefound($order_product_id){
        $sql = 'select a.* from '.getTable('refound_history').'a, '.getTable('order_product').' b where a.order_product_id = b.order_product_id '
            .' and a.order_product_id = '.to_db_int($order_product_id);
        return parent::querySingleRow($sql);
    }

    public function  updateExpressInfo($data = array()){

        $order_id = $data['order_id'];

        $supplier_id = $this->session->data['supplier_id'];

        $templateIds = $data['template_ids'];



        parent::startTransaction();
        try{

            foreach($templateIds as $templateId){

                $express_no = $data['express_no_'.$templateId];
                $express_name=$data['express_name_'.$templateId];
//                $express_price = $data['express_price_'.$templateId];
//                $sql = 'select count(1) as count from '.getTable('order_product_express')
//                    .' where supplier_id = '.to_db_int($supplier_id)
//                    .' and order_id = '.to_db_int($order_id)
//                    .' and template_id = '.to_db_int($templateId);
//
//                $count = parent::queryCount($sql);
//                if($count == 0){
//                    $sql = 'insert into '.getTable('order_product_express')
//                        .' (`order_id`,`supplier_id`,`express_price`,`express_no`,`template_id`)'
//                        .' values ('
//                        .to_db_int($order_id).', '
//                        .to_db_int($supplier_id).', '
//                        .to_db_int($express_price).', '
//                        .to_db_str($express_no).', '
//                        .to_db_int($templateId)
//                        .') ';
//                    $success = parent::executeSql($sql);
//                    if(!$success){
//                        throw new Exception('');
//                    }
//                }else{
                    $sql = 'update '.getTable('order_product_express')
                        .' set '
                        .' express_no = '.to_db_str($express_no)
                        .' ,express_name = '.to_db_str($express_name)
                        .' where supplier_id = '.to_db_int($supplier_id)
                        .' and template_id = '.to_db_int($templateId)
                        .' and order_id = '.to_db_int($order_id);
                    $success = parent::executeSql($sql);
                    if(!$success){
                        throw new Exception('更新物流信息失败');
                    }
//                }
//				$opExpressId = parent::getLastId();
                $sql  =
                    ' update '.getTable('order_product')
                    .' set '
                    .' express_no = '.to_db_str($express_no)
                    .' ,pdSent=1 ';
                if(is_valid($express_no)){
                    $sql .= ' ,  order_product_status = 1 ';
                }
                $sql
                    .= ' where order_id = '.to_db_int($order_id)
                    .' and express_template = '.to_db_int($templateId)
                    .' and supplier_id = '.to_db_int($supplier_id)
                ;
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('更新子订单信息失败');
                }
            }
            $sql = ' select count(1) as count from '.getTable('order_product')
                .' where order_id = '.to_db_int($order_id).' and order_product_status = 0 ';
            $count = parent::queryCount($sql);
            if($count == 0){
                $sql = 'update '.getTable('order').' set order_status = 3 where order_id = '.to_db_int($order_id);
            }else{
                $sql = 'update '.getTable('order').' set order_status = 12 where order_id = '.to_db_int($order_id);
            }
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('更新子订单状态失败');
            }
        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
            return ['success'=>$success,'errMsg'=> is_valid($e->getMessage())? $e->getMessage(): '操作失败'];
        }
        if($success){
            parent::commitTransaction();
        }else{
            parent::rollbackTransaction();
        }

        if($success){
            return ['success'=>true];
        }else{
            return ['success'=>false,'errMsg'=>'更新物流信息失败'];
        }

    }

}
