<?php

class ShippmentController
{
    private $db;
    private $log;
    private $buyType = 0;
    private $refundType = 1;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }

    public function getShippmentOrderNo($orderNo){
        $sql = "select b.product_id as product_id,b.name as name,c.name as expcoName,b.express_no as expNo,b.order_product_status as order_product_status from ".
            getTable('order')." a, ".getTable('order_product')." b,".getTable('express_company').
            " c where c.expco_id=b.express_company_id and a.order_id=b.order_id and a.order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        return $res[0];
    }

    //获取购买产品的物流信息
    public function getShippmentInfo($orderId)
    {
        //查询mcc_shippment表与mcc_shipment_orderproduct,mcc_product表，以orderId为条件即可,$type=0，查出运货单中对应所有的产品信息
        //查询mcc_shippment_process表得出对应的物流信息
        $res = array();
        $productRes = $this->getProudctShipmentsInfo($orderId, $this->buyType);
        foreach ($productRes as $productShipInfo) {
            $shipmentId = $productShipInfo['shipments_id'];
            $shipmentProcess = $this->getShipmentProcess($shipmentId);
            //$tmp = array_merge($productShipInfo, $shipmentProcess);
            $tmp = array_merge($productShipInfo, array("shipmentProcess" => $shipmentProcess));
            // $res.array_push()
            // $res = array_merge($res,$tmp);
            array_push($res, $tmp);

        }
        return $res;
    }

    //获取退货产品的物流信息
    public function getRefoundProdShippmentInfo($orderId)
    {
        //查询mcc_shippment表与mcc_shipment_orderproduct,mcc_product表，以orderId为条件即可,$type=1，查出运货单中对应所有的产品信息
        //查询mcc_shippment_process表得出对应的物流信息
        $res = array();
        $productRes = $this->getProudctShipmentsInfo($orderId, $this->refundType);
        foreach ($productRes as $productShipInfo) {
            $shipmentId = $productShipInfo['shipments_id'];
            $shipmentProcess = $this->getShipmentProcess($shipmentId);
            //$tmp = array_merge($productShipInfo, $shipmentProcess);
            $tmp = array_merge($productShipInfo, array("shipmentProcess" => $shipmentProcess));
            // $res.array_push()
            // $res = array_merge($res,$tmp);
            array_push($res, $tmp);

        }
        return $res;
    }

    private function getProudctShipmentsInfo($orderId, $shipType)
    {
//        SELECT a.shipments_id AS shipments_id , c.name AS product_name, b.shipments_no AS shipments_no, d.supplier_name AS shipment_name , IF( b.status = 1 , "运送中" ,IF( b.status = 0,"未运送" , "送达"))   AS STATUS ,b.arrive_time AS arrive_time  FROM `mcc_shipments_orderproduct` a ,`mcc_shipments` b ,`mcc_product_description` c,`mcc_supplier` d
//WHERE d.supplier_id=b.supplier_id AND a.shipments_id = b.shipments_id AND c.product_id=a.order_product_id AND c.language_id =1 AND  a.order_id = 10;
        $sql = "SELECT a.shipments_id AS shipments_id , c.name AS product_name, b.shipments_no AS shipments_no, d.supplier_name AS shipment_name , IF( b.status = 1 , \"运送中\" ,IF( b.status = 0,\"未运送\" , \"送达\"))   AS STATUS ,b.arrive_time AS arrive_time  FROM "
            . getTable('shipments_orderproduct') . " a ," . getTable('shipments') . " b," . getTable('order_product') . " c," . getTable('supplier') . " d WHERE d.supplier_id=b.supplier_id AND a.shipments_id = b.shipments_id AND c.order_product_id=a.order_product_id AND b.shipment_type = " . $shipType . " and a.order_id = " . $orderId;
        $res = $this->db->getAll($sql);
        return $res;
    }

    private function  getShipmentProcess($shipmentId)
    {
        $sql = "SELECT shipments_process_id, TIME, place,current_shipments_site ,prev_shipments_site ,next_shipments_site FROM " . getTable('shipments_process') . " WHERE shipments_id = " . $shipmentId;
        $res = $this->db->getAll($sql);
        return $res;
    }


}