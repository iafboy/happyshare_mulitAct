<?php


class leSharePayment
{
    private $db;
    private $log;
    private $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }


    /**
     * 测试接口，发货
     * @param $orderNo
     * @throws exception
     */
    public function fahuo($orderNo)
    {
        $sql = "select order_id, customer_id ,order_status,pay_status,order_payment_id,total   FROM " . getTable('order') . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("订单不存在:" . $orderNo);
        }
        $orderId = $res[0]['order_id'];

        $sql = "update " . getTable('order') . " set  order_status=3,order_status_id=3,date_modified=now() WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $this->db->query($sql);

        $sql = "update " . getTable('order_product') . " set order_product_status=1  where order_id=" . $orderId;
        $this->db->query($sql);
    }

    /**
     * 自动收货、自动取消未支付的订单
     */
    public function processOrder()
    {
        $this->autoCancelOrder();
        $this->autoReciptOrder();
    }

    private function autoCancelOrder()
    {
        //取配置
        $sqldomain = "select dvalue from mcc_dict where dkey = 'unpaid_order_close_hour'";
        $res = $this->db->getAll($sqldomain);
        $autoCancelHour = $res[0]['dvalue'];
        //SELECT order_no FROM `mcc_order` WHERE order_status=0 AND date_added > NOW() -10*24*3600 ;
        $sql = "select order_no,order_id from " . getTable('order') . " WHERE order_status=0 AND date_modified < NOW() -3600*" . $autoCancelHour;
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            foreach ($res as $order) {
                $order_no = $order['order_no'];
                $this->cancelOrder($order_no);
            }
        }
    }

    private function autoReciptOrder()
    {
        //取配置
        $sqldomain = "select dvalue from mcc_dict where dkey = 'order_auto_finish_hour'";
        $res = $this->db->getAll($sqldomain);
        $autoReciptHour = $res[0]['dvalue'];
        $sql = "select order_no,order_id from " . getTable('order') . " WHERE order_status =3 AND date_modified < NOW() -3600*" . $autoReciptHour;
        $res = $this->db->getAll($sql);

        if (count($res) > 0) {
            $this->log->debug('autoReciptOrder count '.count($res));
            foreach ($res as $order) {
                $order_no = $order['order_no'];
                $this->reciptOrder($order_no, null, 1);
            }
        }

    }

//0	待付款
//1	已取消
//2	已支付
//3	已发货
//4	申请退货
//5	退货审核通过
//6	退货中
//7	退货成功
//8	退货异常
//9	退货审核不通过
//10	订单完成
//11	交易关闭

    /**
     * 取消作废订单
     * @param $orderNo
     * @throws exception
     */
    public function cancelOrder($orderNo)
    {
        $sql = "select order_id, customer_id ,order_status,pay_status,order_payment_id,total   FROM " . getTable('order') . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("订单不存在:" . $orderNo);
        }

        if ($res[0]['order_status'] == 0) {
            $sql = "update " . getTable('order') . " set  order_status=1,order_status_id=1,date_modified=now() WHERE order_no = '" . $this->db->escape($orderNo) . "'";
            $this->db->query($sql);

            $orderId = $res[0]['order_id'];
            //物品库存回退
            $sql = "update " . getTable('product') . "p set quantity=quantity+(select quantity from " . getTable('order_product') . " where product_id = p.product_id  and order_id=" . $orderId . "  ) where product_id in (select product_id from " . getTable('order_product') . " where  order_id=" . $orderId . ")";
            $this->db->query($sql);
        } else if ($res[0]['order_status'] == 1) {
        } else {
            throw new exception("当前订单不能取消");
        }
    }

    /**
     * s删除订单
     * @param $orderNo
     * @throws exception
     */
    public function removeOrder($orderNo)
    {
        if ($orderNo == null) {
            throw new exception("订单编码不能为空");
        }
        $sql = "select customer_id ,order_status,pay_status,order_payment_id,total   FROM " . getTable('order') . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("订单不存在:" . $orderNo);
        }

        //只有已经完成订单才能删除
        if ($res[0]['order_status'] == 10) {
            $sql = "update " . getTable('order') . " set  order_status=11, order_status_id=11, date_modified=now() WHERE order_no = '" . $this->db->escape($orderNo) . "'";
            $this->db->query($sql);
        } else {
            throw new exception("当前订单未完成，不能删除");
        }
    }

    /**
     * 调用第三方支付平台之前的接口，会写入order_payment
     * @param $orderNo
     * @param $scoreAmount
     * @param $payMethod
     * @param $payAmount
     * @throws exception
     *  relateType :: 1.order 2.order_group
     */
    public function payOrder($orderNo, $scoreAmount, $payMethod, $payAmount, $customerId, $relateType, $orderGroupNo)
    {
        $this->log->debug('[lesharePayment][payOrder] orderNo = ' . $orderNo
            . ' scoreAmount = ' . $scoreAmount
            . ' payMethod = ' . $payMethod
            . ' payAmount = ' . $payAmount
            . ' customerId = ' . $customerId
            . ' relateType = ' . $relateType
            . ' orderGroupNo = ' . $orderGroupNo);
        if ($orderNo == null) {
            throw new exception("订单编码不能为空");
        }
        if ($scoreAmount == null) {
            throw new exception("积分支付值不能为空");
        }
        if ($payMethod == null) {
            throw new exception("支付方式不能为空");
        }
        if ($payAmount == null) {
            throw new exception("支付金额不能为空");
        }
        //TODO
        //wx,fake,ali,union
        if ($payMethod != "wx" && $payMethod != "ali" && $payMethod != "union" && $payMethod != "fake") {
            throw new exception("支付方式" . $payMethod . "无效");
        }

        $sql = 'select count(1) as count from ' . getTable('order_payment') . ' a '
            . ' where pay_status = 1 and a.customerId = ' . to_db_int($customerId);
        $count = $this->db->queryCount($sql);
        if ($count > 0) {
            $firstOrder = 0;
        } else {
            $firstOrder = 1;
        }
        if ($relateType == 1) {
            $sql = "select firstOrder, order_id,customer_id ,order_status,pay_status,order_payment_id,total   FROM " . getTable('order')
                . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("订单不存在:" . $orderNo);
            }
            $order = $res[0];
            //订单状态 '0'=>'待付款','1'=>'已取消','2'=>'未发货', '3'=>'部分未发货','4'=>'已发货', '5'=>'已完成',  '6'=>'退货中，等待平台商确认收货','7'=>'平台商同意退款','8'=>'交易关闭'
            if (($order['order_status']) != 0) {
                throw new exception("订单状态异常，不能支付");
            }
            if (($scoreAmount + $payAmount) < round($order['total'], 2)) {
                throw new exception("支付金额不正确，积分和现金不能小于订单金额");
            }

            $orderId = $res[0]['order_id'];
            $transactionId = "";

            //会重复调用
            $sql = "select pay_money,pay_score,pay_status from " . getTable('order_payment') . " where relate_type = 1 and relate_id = " . $orderId;
            $res = $this->db->getAll($sql);
            $this->log->debug('[lesharePayment][payOrder] count($res) = ' . count($res) . ' pay_status = ' . $res[0]['pay_status']);
            if (count($res) == 0) {
                //第一次支付
                $orderPaymentNo = $this->insertOrderPayment($payAmount, $scoreAmount * CREDIT_EXCHANGE_PERCENT, $payMethod, $transactionId, $orderId, 1, $customerId);
                $this->payCredit($scoreAmount * CREDIT_EXCHANGE_PERCENT, $customerId, $orderId, $firstOrder);
                $this->updateOrderOnPayment($orderId, $scoreAmount * CREDIT_EXCHANGE_PERCENT);
                return $orderPaymentNo;
            } else {
                //判断支付状态 0待支付;1已支付;2支付失败
                if ($res[0]['pay_status'] == 1) {
                    throw new exception("订单已经支付成功，不能重复支付");
                } else if ($res[0]['pay_status'] == 2 || $res[0]['pay_status'] == 0) {
                    //重新支付
                    $this->returnCredit($orderId);
                    $this->deleteOrderPayment($orderId, $relateType);
                    $orderPaymentNo = $this->insertOrderPayment($payAmount, $scoreAmount * CREDIT_EXCHANGE_PERCENT, $payMethod, $transactionId, $orderId, 1, $customerId);
                    $this->payCredit($scoreAmount * CREDIT_EXCHANGE_PERCENT, $customerId, $orderId, $firstOrder);
                    $this->updateOrderOnPayment($orderId, $scoreAmount * CREDIT_EXCHANGE_PERCENT);
                    return $orderPaymentNo;
                } else {
                    throw new exception("订单支付状态异常");
                }
            }
        } else if ($relateType == 2) {
            $sql = "select *   FROM " . getTable('order_group')
                . " WHERE order_group_no = '" . $this->db->escape($orderGroupNo) . "'";
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("订单不存在:" . $orderNo);
            }
            $orderGroupId = $res[0]['order_group_id'];
            $orderIdStr = $res[0]['order_ids_str'];
            $orderIdArray = explode(',', $orderIdStr);
            $sql =
                " select firstOrder, order_id,customer_id ,order_status,pay_status,order_payment_id,total FROM "
                . getTable('order')
                . " WHERE order_id in ( ";
            foreach ($orderIdArray as $orderId) {
                $sql .= to_db_int($orderId) . ',';
            }
            $sql = substr($sql, 0, strlen($sql) - 1);
            $sql .= ")";
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("订单不存在");
            }
            $total_order = 0;
            foreach ($res as $order) {
                //订单状态 '0'=>'待付款','1'=>'已取消','2'=>'未发货', '3'=>'部分未发货','4'=>'已发货', '5'=>'已完成',  '6'=>'退货中，等待平台商确认收货','7'=>'平台商同意退款','8'=>'交易关闭'
                if (($order['order_status']) != 0) {
                    throw new exception("订单状态异常，不能支付");
                }
                $total_order += round($order['total'], 2);
            }
            if (($scoreAmount + $payAmount) < $total_order) {
                throw new exception("支付金额不正确，积分和现金不能小于订单金额");
            }

            $transactionId = "";

            //会重复调用
            $sql = "select pay_money,pay_score,pay_status from " . getTable('order_payment') . " where relate_type = 2 and relate_id= " . $orderGroupId;
            $res = $this->db->getAll($sql);
            $this->log->debug('[lesharePayment][payOrder] count($res) = ' . count($res) . ' pay_status = ' . $res[0]['pay_status']);
            if (count($res) == 0) {
                //第一次支付
                $orderPaymentNo = $this->insertOrderPayment($payAmount, $scoreAmount * CREDIT_EXCHANGE_PERCENT, $payMethod, $transactionId, $orderGroupId, 2, $customerId);
                if ($scoreAmount > 0) {
                    $orderlen = sizeof($orderIdArray);
                    $eachScoreAmount = $scoreAmount * CREDIT_EXCHANGE_PERCENT / $orderlen;
                    $addonScoreAmount = $scoreAmount * CREDIT_EXCHANGE_PERCENT % $orderlen;
                    $index = 0;
                    foreach ($orderIdArray as $order_id) {
                        if ($index == 0) {
                            $this->payCredit($eachScoreAmount + $addonScoreAmount, $customerId, $order_id, $firstOrder);
                            $this->updateOrderOnPayment($order_id, $eachScoreAmount + $addonScoreAmount);
                        } else {
                            $this->payCredit($eachScoreAmount, $customerId, $order_id, $firstOrder);
                            $this->updateOrderOnPayment($order_id, $eachScoreAmount);
                        }
                        $index++;
                    }
                } else {
                    foreach ($orderIdArray as $order_id) {
                        $this->payCredit(0, $customerId, $order_id, $firstOrder);
                        $this->updateOrderOnPayment($order_id, 0);
                    }
                }
                return $orderPaymentNo;
            } else {
                //判断支付状态 0待支付;1已支付;2支付失败
                if ($res[0]['pay_status'] == 1) {
                    throw new exception("订单已经支付成功，不能重复支付");
                } else if ($res[0]['pay_status'] == 2) {
                    //重新支付
                    foreach ($orderIdArray as $order_id) {
                        $this->returnCredit($order_id);
                    }
                    $this->deleteOrderPayment($orderGroupId, $relateType);
                    $orderPaymentNo = $this->insertOrderPayment($payAmount, $scoreAmount, $payMethod, $transactionId, $orderGroupId, 2, $customerId);
                    if ($scoreAmount > 0) {
                        $orderlen = sizeof($orderIdArray);
                        $eachScoreAmount = $scoreAmount * CREDIT_EXCHANGE_PERCENT / $orderlen;
                        $addonScoreAmount = $scoreAmount * CREDIT_EXCHANGE_PERCENT % $orderlen;
                        $index = 0;
                        foreach ($orderIdArray as $order_id) {
                            if ($index == 0) {
                                $this->payCredit($eachScoreAmount + $addonScoreAmount, $customerId, $order_id, $firstOrder);
                                $this->updateOrderOnPayment($order_id, $eachScoreAmount + $addonScoreAmount);
                            } else {
                                $this->payCredit($eachScoreAmount, $customerId, $order_id, $firstOrder);
                                $this->updateOrderOnPayment($order_id, $eachScoreAmount);
                            }
                            $index++;
                        }
                    } else {
                        foreach ($orderIdArray as $order_id) {
                            $this->updateOrderOnPayment($order_id, 0);
                        }
                    }
                    return $orderPaymentNo;
                } else {
                    throw new exception("订单支付状态异常");
                }
            }
        } else {
            throw new exception("错误的支付类型");
        }
    }

    private function updateOrderOnPayment($orderId, $payScore)
    {
        $sql = 'update ' . getTable('order')
            . ' set pay_money = total  - ' . to_db_int($payScore / CREDIT_EXCHANGE_PERCENT)
            . ' , pay_score = ' . to_db_int($payScore) . ' where order_id = ' . to_db_int($orderId);
        $this->db->query($sql);
    }

    /**
     * 支付成功后通知接口
     * @param $orderNo   实际上是  orderPaymentNo
     * @param $isSuccess
     * @param $respMsg
     * @param $transactionId
     * @throws exception
     */
    public function confirmPayOrder($orderNo, $isSuccess, $respMsg, $transactionId)
    {
        $this->log->debug('[lesharePayment][confirmPayOrder] orderNo = ' . $orderNo . ' isSuccess = ' . $isSuccess . ' respMsg = ' . $respMsg . ' transactionId = ' . $transactionId);
        if ($orderNo == null) {
            throw new exception("支付编码不能为空");
        }
        if ($isSuccess == null) {
            throw new exception("支付结果不能为空");
        }

        $sql = 'select * from ' . getTable('order_payment') . ' where order_payment_no = ' . to_db_str($orderNo);
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("支付记录不存在:" . $orderNo);
        }
        $payment = $res[0];
        $relateType = $res[0]['relate_type'];
        $relateId = $res[0]['relate_id'];
        if ($relateType == 1) {
            $sql = "select  a.firstOrder, a.order_id,a.customer_id ,a.order_status,a.pay_status,a.order_payment_id,a.total FROM "
                . getTable('order') . " a WHERE order_id = " . to_db_int($relateId);
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("订单不存在:" . $relateId);
            }

            $sql = "select  a.firstOrder, a.order_id,a.customer_id ,a.order_status,a.pay_status,a.order_payment_id,a.total FROM "
                . getTable('order') . " a WHERE order_id = " . to_db_int($relateId);
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("订单不存在:");
            }

            if (($res[0]['order_status']) != 0) {
                throw new exception("订单状态异常，不能支付");
            }
            $order_id = $res[0]['order_id'];
            $customer_id = $res[0]['customer_id'];
            // 0待支付;1已支付;2支付失败
            if ($payment['pay_status'] != 0) {
                throw new exception("订单支付状态异常");
            }


            if ($isSuccess == 1) {
                //支付成功
                //更改订单状态
                $sql = "update " . getTable('order') . " set  pay_status = '1' ,order_status=2,order_status_id=2,date_modified=now() WHERE order_id = " . to_db_int($order_id);
                $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                $this->db->query($sql);
                $sql = "update " . getTable('order_payment')
                    . " set pay_status = 1,pay_msg = '" . $this->db->escape($respMsg)
                    . "',transaction_id = '" . $this->db->escape($transactionId) . "' , date_payment=now() WHERE  order_payment_no = " . to_db_str($orderNo);
                $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                $this->db->query($sql);
                //写入购买记录
                $sql = "INSERT INTO "
                    . getTable('customer_ophistory')
                    . " (`product_id`,`operation_type`,`customer_id`,`status`) select product_id, 0," . $customer_id . ",0 from "
                    . getTable('order_product') . " where order_id=" . $order_id;
                $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                $this->db->query($sql);

            } else {
                $sql = "update " . getTable('order_payment') . " set  pay_status = 2 ,pay_msg = '" . $this->db->escape($respMsg) . "' WHERE  order_payment_no = " . to_db_str($orderNo);
                $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                $this->db->query($sql);
            }

            $this->logOrderPaymentHistory($payment['order_payment_id'], $order_id);


        } else if ($relateType == 2) {
            $sql = "select a.order_ids_str FROM "
                . getTable('order_group') . " a WHERE order_group_id = " . to_db_int($relateId);
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                throw new exception("订单合并记录不存在:" . $relateId);
            }
            $orderIdsStr = $res[0]['order_ids_str'];
            $orderIdArray = explode(',', $orderIdsStr);

            if ($isSuccess == 1) {
                $sql = "update " . getTable('order_payment')
                    . " set pay_status = 1,pay_msg = '" . $this->db->escape($respMsg)
                    . "',transaction_id = '" . $this->db->escape($transactionId) . "'  , date_payment=now() WHERE  order_payment_no = " . to_db_str($orderNo);
                $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                $this->db->query($sql);
            } else {
                $sql = "update " . getTable('order_payment')
                    . " set  pay_status = 2 ,pay_msg = '" . $this->db->escape($respMsg)
                    . "' WHERE  order_payment_no = " . to_db_str($orderNo);
                $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                $this->db->query($sql);
            }
            foreach ($orderIdArray as $orderId) {
                $sql = "select  a.firstOrder, a.order_id,a.customer_id ,a.order_status,a.pay_status,a.order_payment_id,a.total FROM "
                    . getTable('order') . " a WHERE order_id = " . to_db_int($orderId);
                $res = $this->db->getAll($sql);
                if (count($res) == 0) {
                    throw new exception("订单不存在:" . $relateId);
                }

                if (($res[0]['order_status']) != 0) {
                    throw new exception("订单状态异常，不能支付");
                }
                $order_id = $res[0]['order_id'];
                $customer_id = $res[0]['customer_id'];
                // 0待支付;1已支付;2支付失败
                if ($payment['pay_status'] != 0) {
                    throw new exception("订单支付状态异常");
                }
                if ($isSuccess == 1) {
                    //支付成功
                    //更改订单状态
                    $sql = "update " . getTable('order') . " set pay_status = '1' ,order_status=2,order_status_id=2,date_modified=now() WHERE order_id = " . to_db_int($order_id);
                    $this->db->query($sql);
                    $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                    //写入购买记录
                    $sql = "INSERT INTO "
                        . getTable('customer_ophistory')
                        . " (`product_id`,`operation_type`,`customer_id`,`status`) select product_id, 0," . $customer_id . ",0 from "
                        . getTable('order_product') . " where order_id=" . $order_id;
                    $this->log->debug('[lesharePayment][confirmPayOrder] [ sql = ' . $sql . ']');
                    $this->db->query($sql);

                }
                $this->logOrderPaymentHistory($payment['order_payment_id'], $order_id);
            }

        }

    }

    /**
     *
     * @param $orderId
     */
    private function deleteOrderPayment($orderId, $relateType)
    {
        if ($relateType == 1) {
            $sql = "delete from " . getTable('order_payment') . " where relate_type = 1 and relate_id = " . $orderId;
        } else if ($relateType == 2) {
            $sql = "delete from " . getTable('order_payment') . " where relate_type = 1 and relate_id =  " . $orderId;
        }
        $this->db->query($sql);
    }

    /**
     * @param $payAmount
     * @param $scoreAmount
     * @param $payMethod
     * @param $transactionId
     * @param $orderId
     */
    private function insertOrderPayment($payAmount, $scoreAmount, $payMethod, $transactionId, $orderId, $relateType, $customerId)
    {
        //mcc_order_payment
        $order_payment_no = $this->generatOrderNo();

        $sql = "INSERT INTO " . getTable('order_payment')
            . " ( `order_payment_no`,  `pay_money`,  `pay_score`,  `pay_desc`,  `pay_type` ,`pay_status` ,`transaction_id`,`relate_id`,`relate_type`,`customerId`  ) values ( "
            . to_db_str($order_payment_no) . " ,"
            . $payAmount . " ,"
            . $scoreAmount . "  ,'' ,"
            . to_db_str($payMethod) . " , 0 ,"
            . to_db_str($transactionId) . " ,"
            . to_db_int($orderId) . " ,"
            . to_db_int($relateType) . " ,"
            . to_db_int($customerId) . " ) ";
        $this->log->debug('[lesharePayment][insertOrderPayment] insert sql = [' . $sql . ']');
        $this->db->query($sql);
        $orderPaymentId = $this->db->getLastId();
        $this->logOrderPaymentHistory($orderPaymentId);
        if ($relateType == 1) {
            $sql = "update " . getTable('order') . " set order_payment_id = " . $orderPaymentId . " ,pay_status = 0 ,date_modified=now() WHERE order_id = " . $orderId;
            $this->log->debug('[lesharePayment][insertOrderPayment] update order sql = [' . $sql . ']');
            $this->db->query($sql);
        } else if ($relateType == 2) {
            $sql = 'select * from ' . getTable('order_group') . ' where order_group_id = ' . to_db_int($orderId);
            $og = $this->db->querySingleRow($sql);
            $order_ids_str = $og['order_ids_str'];
            $orderIdArray = explode(',', $order_ids_str);
            foreach ($orderIdArray as $order_id) {
                $sql = "update " . getTable('order') . " set order_payment_id = " . $orderPaymentId . " ,pay_status = 0 ,date_modified=now() WHERE order_id = " . $order_id;
                $this->log->debug('[lesharePayment][insertOrderPayment] update order sql = [' . $sql . ']');
                $this->db->query($sql);
            }
        }
        return $order_payment_no;
    }

    /**
     * 记录历史表，每当order_payment写入或更新，都写入到历史表中
     * @param $orderPaymentId
     * @param $orderIdd
     */
    private function logOrderPaymentHistory($orderPaymentId)
    {
        //mcc_order_payment_history
        $sql = "insert into " . getTable('order_payment_history') .
            " (  `date_added`,  `pay_money`,  `pay_score`,  `pay_type`,  `pay_status`,  `transaction_id`,  `pay_msg`,  `relate_id`,`relate_type`,`customerId`)
        select    now(), pay_money, pay_score, pay_type, pay_status, transaction_id, pay_msg , relate_id,relate_type,customerId from " . getTable('order_payment') . " where order_payment_id = " . $orderPaymentId;
        $this->db->query($sql);
    }

    /**
     * 订单支付积分
     * @param $scoreAmount
     * @param $customerId
     * @param $orderId
     * @throws exception
     */
    private function payCredit($scoreAmount, $customerId, $orderId, $firstOrder)
    {
        $this->log->debug('[lesharePayment][payCredit] scoreAmount = ' . $scoreAmount . ' customerId = ' . $customerId . ' orderId = ' . $orderId . ' firstOrder = ' . $firstOrder);
        // 由于积分和现金存在兑换比例，所以先把需要由积分来支付的金额转换成对应的积分。
//        $scoreAmount = $this->registry->get('CreditController')->getCreditAmount($scoreAmount);
        $productId=null;
        if ($firstOrder == 1) {
            if ($scoreAmount > 0) {
                $this->registry->get('CreditController')->recordCredit($this->registry->get('CreditController')->TYPE_BUY_CREDIT, $orderId, $scoreAmount, $customerId, $productId, null, 1,$orderId);
                $this->registry->get('CreditController')->recordCredit($this->registry->get('CreditController')->TYPE_SPEND_CREDIT, $orderId, $scoreAmount, $customerId, $productId, null, 1,$orderId);
            }
            return;
        }

        if ($scoreAmount < 0) {
            throw new exception("支付积分不能为负值");
        }

        if ($scoreAmount > 0) {
            //检查积分余额
            $sql = "select IFNULL( credit,0) as credit from " . getTable('customer') . " where customer_id = " . $customerId;
            $res = $this->db->getAll($sql);
            if (count($res) == 0) {
                $this->log->error('[lesharePayment][payCredit] 用户不存在');
                throw new exception("用户不存在:" . $customerId);
            }

            if ($firstOrder != 1 && $scoreAmount > $res[0]['credit']) {
                $this->log->error('[lesharePayment][payCredit] 支付积分不能大于用户当前积分余额');
                throw new exception("支付积分不能大于用户当前积分余额");
            }

//            $sql = "INSERT INTO " . getTable('credithistory') . " (`type`,  `ref_id`,  `adddate`,  `credit`,  `customerid`, `comment`) VALUES
//            (3, null, NOW(), " . $scoreAmount . ", " . $customerId . ", '" . $this->db->escape($orderNo) . "') ";
//            $this->db->query($sql);
//
            $sql = "UPDATE " . getTable('customer') . "  SET credit = credit - " . $scoreAmount . "  WHERE customer_id = " . $customerId;
            $this->db->query($sql);
            $this->log->debug('[lesharePayment][payCredit] $this->registry->get(\'CreditController\')' . $this->registry->get('CreditController'));
            $this->registry->get('CreditController')->recordCredit($this->registry->get('CreditController')->TYPE_SPEND_CREDIT, $orderId, $scoreAmount, $customerId, $productId, null,-1,$orderId);

        }

        $res = $this->registry->get('ProductController')->getProductCreditByOrderAndType($orderId);
        $this->log->debug('[lesharePayment][payCredit] total buy credit = ' . $res['total_buy_credit']);
        $this->log->debug('[lesharePayment][payCredit] number of activity credit = ' . count($res['total_activity_credit_list']));
        if ($res['total_buy_credit'] > 0) {
            $this->registry->get('CreditController')->recordCredit($this->registry->get('CreditController')->TYPE_BUY_CREDIT, $orderId, $res['total_buy_credit'], $customerId, $productId, null,-1,$orderId);
        }
            if (count($res['total_activity_credit_list']) > 0) {
            for ($i = 0; $i < count($res['total_activity_credit_list']); $i++) {
                $this->log->debug('[lesharePayment][payCredit] total_activity_credit_list ref_id = ' . $res['total_activity_credit_list'][$i]['ref_id']);
                $this->log->debug('[lesharePayment][payCredit] total_activity_credit_list s_credit  = ' . $res['total_activity_credit_list'][$i]['s_credit']);
                $this->log->debug('[lesharePayment][payCredit] total_activity_credit_list product_id  = ' . $res['total_activity_credit_list'][$i]['product_id']);
                $this->registry->get('CreditController')->recordCredit($this->registry->get('CreditController')->TYPE_DEVELOP_CREDIT, $res['total_activity_credit_list'][$i]['ref_id'], $res['total_activity_credit_list'][$i]['credit'], $customerId, $productId, null,-1,$orderId);
            }

        }
        // 积分无需即时入账
//            $this->registry->get('CreditController')->applyCredit($customerId);
    }

    /**
     * 订单取消，返还积分
     * @param $scoreAmount
     * @param $customerId
     * @param $orderId
     */
    private function returnCredit($orderId)
    {
        $this->registry->get('CreditController')->removeCreditByOrder($orderId);
//        $this->registry->get('CreditController')->applyCredit($customerId);
    }

    private function returnCredits($order_id,$productId,$return_num){
        $this->registry->get('CreditController')->removeCreditByOrderProducts($order_id,$productId,$return_num);
    }

    public function reciptSupplierOrder($orderNo, $supplierId)
    {
        if ($orderNo == null) {
            //throw new exception("订单编码不能为空");
            $this->log->debug('autoReciptOrder ' .'订单编码不能为空');
            return;
        }
        if ($supplierId == null) {
            //throw new exception("供货商id不能为空");
            $this->log->debug('autoReciptOrder ' .'供货商id不能为空');
            return;
        }

        $sql = "select firstOrder, order_id,customer_id ,order_status,order_status_id,pay_status,order_payment_id,total   FROM " . getTable('order') . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        $customerId = $res[0]['customer_id'];
        $orderId = $res[0]['order_id'];
        $firstOrder = $res[0]['firstOrder'];
        if (count($res) == 0) {
            //throw new exception("订单不存在:" . $orderNo);
            $this->log->debug('autoReciptOrder ' . $orderNo.'订单不存在');
            return;
        }
        //判断 order_status
        $orderStatus = $res[0]['order_status'];
        if ($orderStatus != 3&&$orderStatus != 4) {
            //throw new exception("当前订单非发货状态，不能进行收货确认");
            $this->log->debug('autoReciptOrder ' . $orderNo.'当前订单非发货状态，不能进行收货确认');
            return;
        }
        $sql = "select product_id,order_product_status,supplier_id from " . getTable('order_product') . " where order_product_status in (0,1) and order_id = " . $orderId;

        $res = $this->db->getAll($sql);
        $sqlall = "select product_id,order_product_status,supplier_id from " . getTable('order_product') . " where order_id = " . $orderId;
        $resall = $this->db->getAll($sqlall);
        if ((count($res) == 0)&&(count($res)==count($resall))) {
            //已全部确认收货
            $this->finishOrder($orderId, $orderNo, $customerId, $firstOrder);
        } else {
            $reciptProduct = 0;
//        case '0':return '未发货';
//        case '1':return '已发货，未收货';
//        case '2':return '已收货'
            foreach ($res as $orderPorductDetail) {
                if (($orderPorductDetail['supplier_id'] == $supplierId)&&($orderPorductDetail['order_product_status']!=4)) {
                    $reciptProduct++;
                    $sql = "update " . getTable('order_product') . " set order_product_status = 2  where order_product_status= 1 and order_id = " . $orderId . " and supplier_id = " . $supplierId;
                    $this->db->query($sql);
                }
            }
            if ($reciptProduct == count($resall)) {
                //已全部确认收货
                $this->finishOrder($orderId, $orderNo, $customerId, $firstOrder);
            }

            if ($reciptProduct == 0) {
                //没有确认收货的商品
            }

        }

    }


    /**
     * 确认收货
     * @param $orderNo
     * @param $productId
     * @param $reciptAll
     * @throws exception
     */
    public function reciptOrder($orderNo, $productId, $reciptAll)
    {
        if ($orderNo == null) {
            //throw new exception("订单编码不能为空");
            $this->log->debug('autoReciptOrder '.'订单编码不能为空');
            return;
        }
        if ($reciptAll == null && $productId == null) {
            //throw new exception("收货商品必选");
            $this->log->debug('autoReciptOrder '.'收货商品必选');
            return;
        }

        $sql = "select firstOrder,order_id,customer_id ,order_status,order_status_id,pay_status,order_payment_id,total   FROM " . getTable('order') . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        $customerId = $res[0]['customer_id'];
        $firstOrder = $res[0]['firstOrder'];

        if (count($res) == 0) {
            //throw new exception("订单不存在:" . $orderNo);
            $this->log->debug('autoReciptOrder '.'订单不存在' . $orderNo);
            return;
        }
        //判断 order_status
        $orderStatus = $res[0]['order_status'];
        if ($orderStatus != 3&&$orderStatus != 4) {
            //throw new exception("当前订单非发货状态，不能进行收货确认");
            $this->log->debug('autoReciptOrder ' . $orderNo.'|'.$orderStatus.'| 当前订单非发货状态，不能进行收货确认');
            return;
        }
        $orderId = $res[0]['order_id'];

        if ($reciptAll == 1) {
            //$sql = "update " . getTable('order_product') . " set order_product_status = 10  where order_product_status in (0,1) and order_id = " . $orderId;
            //$this->log->debug('autoReciptOrder '.$sql);
            //$this->db->query($sql);
            //------recipt order products
            $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_product_status in (0,1) and order_id = " . $orderId;
            $res = $this->db->getAll($sql);
            $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_id = " . $orderId;
            $resall = $this->db->getAll($sql);
            if (count($res) == 0) {
                //已全部确认收货
                $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_product_status =2 and order_id = " . $orderId;
                $resfinish = $this->db->getAll($sql);
                if(count($resfinish)==count($resall))
                    $this->finishOrder($orderId, $orderNo, $customerId, $firstOrder);
            } else {
                $reciptProduct = 0;
                foreach ($res as $orderPorductDetail) {
                    $t_productId=$orderPorductDetail['product_id'];
                    $sql = "update " . getTable('order_product') . " set order_product_status = 2  where order_product_status = 1 and order_id = " . $orderId . " and product_id = " . $t_productId;
                    $this->log->debug('autoReciptOrder update order_product'.$sql);
                    $this->db->query($sql);
                    $reciptProduct++;
                }
            }

            $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_product_status =2 and order_id = " . $orderId;
            $resfinish = $this->db->getAll($sql);
            if(count($resfinish)==count($resall))
                $this->finishOrder($orderId, $orderNo, $customerId, $firstOrder);


            if ($reciptProduct == 0) {
                //没有确认收货的商品
            }
        } else {
            $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_product_status in (0,1) and order_id = " . $orderId;
            $this->log->debug('autoReciptOrder '.$sql);
            $res = $this->db->getAll($sql);
            $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_id = " . $orderId;
            $resall = $this->db->getAll($sql);
            if (count($res) == 0) {
                $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_product_status =2 and order_id = " . $orderId;
                $resfinish = $this->db->getAll($sql);
                if(count($resfinish)==count($resall))
                    $this->finishOrder($orderId, $orderNo, $customerId, $firstOrder);
            } else {
                $reciptProduct = 0;
                foreach ($res as $orderPorductDetail) {
                    if ($orderPorductDetail['product_id'] == $productId) {
                        $sql = "update " . getTable('order_product') . " set order_product_status = 2  where order_product_status = 1 and order_id = " . $orderId . " and product_id = " . $productId;
                        $this->log->debug('autoReciptOrder update order_product'.$sql);
                        $this->db->query($sql);
                        //break;
                        $reciptProduct++;
                        break;
                    }
                }
                $sql = "select product_id,order_product_status from " . getTable('order_product') . " where order_product_status =2 and order_id = " . $orderId;
                $resfinish = $this->db->getAll($sql);
                if(count($resfinish)==count($resall))
                    $this->finishOrder($orderId, $orderNo, $customerId);


                if ($reciptProduct == 0) {
                    //没有确认收货的商品
                }

            }
        }


    }


    /**
     * 完成整张订单
     */
    private function finishOrder($orderId, $orderNo, $customerId, $firstOrder)
    {
        $sql = "update " . getTable('order') . " set order_status = 10 , order_status_id = 10 ,date_modified = now() where order_id = " . $orderId;
        $this->db->query($sql);
    }

    //生成验证码
    private function generatOrderNo()
    {
        $length = 16;
        $pattern = '1234567890abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)};    //生成php随机数
        }
        return $key;
    }

    private function generateRefundNo()
    {
        //modify 20160216
        //改为字母DD+14位数字，采用时间戳避免重复
        $length = 4;
        $pattern = '1234567890';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)};    //生成php随机数
        }

        $my_t = gettimeofday();

        return "RR" . $my_t['sec'] . $key;
    }


    /**
     * 申请退货
     * @param $orderNo
     */
    public function submitRefund($orderNoProductId,$shippmentCompany,$shippmentNo,$bankId,$cardId,$cardHolder){
        $sql = "select refund_status from  " . getTable('refound_history') . " where order_product_id=" . $orderNoProductId;
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            if ($res[0]['refund_status'] != 2) {
                //退货状态：1申请；2可退货；3已发货；4退货完成；5关闭
                throw new exception("退货状态异常");
            }
        }else{
            throw new exception("未申请退货");
        }
        $sql = 'update '.getTable('refound_history')
            .' set shippment_no = '.to_db_str($shippmentNo).' ,'
            .' shippment_company = '.to_db_str($shippmentCompany).' ,'
            .' bank_id = '.to_db_int($bankId).' ,'
            .' card_id = '.to_db_str($cardId).' ,'
            .' card_holder = '.to_db_str($cardHolder).' ,'
            .' refund_status = 4  '
            .' where order_product_id = '.to_db_int($orderNoProductId);

        $this->db->executeSql($sql);
        //,order_product.return_goods_status
        //0. 正常订单状态 1.退货申请中 2.退货审核通过 3.退货审核不通过 4.退货成功 5.退货异常 6. 已提交账号信息
        $sql = 'update ' . getTable('order_product') . ' set return_goods_status = 4 where order_product_id = ' . to_db_int($orderNoProductId);
        $this->db->executeSql($sql);
    }
    public function applyRefond($orderNo, $productId, $reason, $mode, $phone, $imgurl,$return_num)
    {
        if ($orderNo == null) {
            throw new exception("订单编码不能为空");
        }
        if ($productId == null) {
            throw new exception("商品id不能为空");
        }
        if (!is_valid($reason)) {
            throw new exception("退货理由未填写");
        }
        if (!is_valid($mode)) {
            throw new exception("责任方未填写");
        }
        if (!is_valid($phone)) {
            throw new exception("退货联系电话未填写");
        }

        $sql = "select firstOrder,order_id,customer_id ,order_status,order_status_id,pay_status,order_payment_id,total   FROM " . getTable('order') . " WHERE order_no = '" . $this->db->escape($orderNo) . "'";
        $this->log->debug($sql);
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("订单不存在:" . $orderNo);
        }
        $order_id = $res[0]['order_id'];

        //        case '0':return '未发货';
//        case '1':return '已发货，未收货';
//        case '2':return '已收货'
        $sql = "select a.order_product_id,a.return_goods_status,a.supplier_id,a.order_product_status,b.order_status,b.customer_id from " . getTable('order_product') . " a," . getTable('order')
            . " b where a.order_id = b.order_id and a.order_id=" . $order_id . " and product_id=" . $productId;
        $this->log->debug($sql);

        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("订单中商品不存在");
        }
        $order_product_status = $res[0]['order_product_status'];
        $order_status = $res[0]['order_status'];
        $return_goods_status = $res[0]['return_goods_status'];
        $order_product_id = $res[0]['order_product_id'];
        $supplier_id = $res[0]['supplier_id'];
        $customer_id = $res[0]['customer_id'];
        if ($order_status == 0 || $order_status == 1 || $order_status == 11) {
            throw new exception("订单状态错误");
        }
        if ($return_goods_status != 0) {
            throw new exception("商品退货状态错误");
        }

        $sql = "select refund_status from  " . getTable('refound_history') . " where order_product_id=" . $order_product_id;
        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            if ($res[0]['refund_status'] != 5) {
                //退货状态：1申请；2可退货；3已发货；4退货完成；5关闭
                throw new exception("已申请退货");
            }
        }

        $refundNo = $this->generateRefundNo();

        $sql = "INSERT INTO " . getTable('refound_history')
            . " ( `refound_history_no`,`order_product_id`,`order_id`,  `product_id`,  `supplier_id`,`customer_id`,`createdate`,  `checked`,`refund_status`,`reason`,`mode`,`phone`,`return_num`";
        if (is_valid($imgurl)) {
            $sql .= ' , `image` ';
        }
        $sql .= ") VALUES ( "
            . to_db_str($refundNo) . ","
            . $order_product_id . ","
            . $order_id . ","
            . $productId . ","
            . $supplier_id . ","
            . $customer_id . ",NOW(),0,1"
            . ',' . to_db_str($reason)
            . ',' . to_db_int($mode)
            . ',' . to_db_str($phone)
            . ',' . to_db_str($return_num);
        if (is_valid($imgurl)) {
            $sql .= ',' . to_db_str($imgurl);
        }
        $sql .= ") ";
        $this->log->debug($sql);

        $this->db->query($sql);

        $sql = 'update ' . getTable('order_product') . ' set return_goods_status = 1,order_product_status = 3 where order_id = ' . to_db_int($order_id) . ' and product_id = ' . to_db_int($productId);
        $this->db->query($sql);
        $sql = 'update ' . getTable('order') . ' set order_status = 4,order_status_id = 4 where order_id = ' . to_db_int($order_id);
        $this->db->query($sql);

        // 退货后需要返还积分
        $this->returnCredits($order_id,$productId,$return_num);
    }


    public function showSameProductWithOrder($orderNo)
    {
        $res = array();
        if ($orderNo == null) {
            throw new exception("订单编号不能为空");
        }
        $sqlOrderProduct = "select a . product_id as product_id  from " . getTable('order_product') . " a," . getTable('order') . " b where b . order_id = a . order_id and b . order_no = '" . $this->db->escape($orderNo) . "'";
        $orderProductRes = $this->db->getAll($sqlOrderProduct);
        foreach ($orderProductRes as $orderProduct) {
            $resProduct = $this->showSamePopProduct($orderProduct['product_id']);
            foreach ($resProduct as $tmp) {
                array_push($res, $tmp);
            }
        }

        return $res;

    }


    //购买成功后显示订单同类产品top10
    public function showSamePopProduct($productId)
    {
        $sql = "select bg_id from " . getTable('brandgroup_product') . " where product_id = " . $productId;
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("产品品牌配置信息缺失，产品id ：" . $productId);
        }
        $bg_id = $res[0]['bg_id'];
        //查询mcc_brandgroujp_product下面与$productId是同一个brand值的productid列表
        //统计视图mcc_buyhistory_view 并根据条件in (productId1,productId,....)查出top10的茶品嘻嘻

        $sql = "SELECT a . saledProductNum,a . product_id ,a . name FROM `mcc_buyhistory_view` a, `mcc_brandgroup_product` b WHERE  a . product_id = b . product_id AND b . bg_id = " . $bg_id . " LIMIT 10";
        $res = $this->db->getAll($sql);
        return $res;
    }


    public function showOrderFeeInfo($orderNo)
    {

        $this->log->debug("[lesharePayment][showOrderFeeInfo] orderNo = $orderNo");

        if ($orderNo == null) {
            throw new exception("订单编号不能为空");
        }
        //要配置，积分对应价值
        $sql = "select a.order_id as order_id, a . order_no as order_no,round(a . total, 2) as totalMoney ,IFNULL(b . credit, 0) as jifen,a.firstOrder as firstOrder  from " . getTable('order') . " a ," . getTable('customer') . " b where a . customer_id = b . customer_id and a . order_no = '" . $this->db->escape($orderNo) . "'";
        $this->log->debug("[lesharePayment][showOrderFeeInfo] order sql = " . $sql);
        $res = $this->db->getAll($sql);
        $this->log->debug("[lesharePayment][showOrderFeeInfo] order number = " . count($res));
        if (count($res) > 0) {
            $this->log->debug("[lesharePayment][showOrderFeeInfo] firstOrder = $res[0]['firstOrder']");

            if ($res[0]['firstOrder'] == 1) {
                $productController = $this->registry->get('ProductController');
                $orderCredit = $productController->getProductCreditByOrder($res[0]['order_id']);
            } else {
                $orderCredit = 0;
            }
            $money = $res[0]['totalMoney'] - $orderCredit;

            $this->log->debug("[lesharePayment][showOrderFeeInfo] orderCredit = $orderCredit");
            $this->log->debug("[lesharePayment][showOrderFeeInfo] money = $money");

            if ($money < $res[0]['jifen']) {
                //全用积分支付
                $jifenMoney = round($money);
                $money = 0;
            } else {
                //部分使用积分支付
                $money = round($money - $res[0]['jifen'], 2);
                $jifenMoney = round($jifenMoney = $res[0]['jifen']);
            }
//        祖凯:
//        这里返回一个字段，是不是首单的字段给我
//        然后再返回订单的应付金额 （减去积分）  和  实际金额
//        然后如果是首单的话
//        还要返回这次订单获得的积分
//        比如 你那里返回  388 现金  + 11 积分给我
//        我会只显示 388现金给他选择
//        然后提交的时候
//        提交388现金  + 11 积分
//        这样只改这一个接口，其他的接口都不用动
            $resArray = array("order_no" => $orderNo,
                "firstOrder" => $res[0]['firstOrder'],
                "firstOrderJifen" => $orderCredit,
                "totalMoney" => $res[0]['totalMoney'],
                "money" => $money,
                "jifen" => $res[0]['jifen'],
                "jifenMoney" => $jifenMoney
            );
        }

        return $resArray;
    }

    public function showOrderFeeInfoByOrderNos($orderNoArray = array(), $customerId)
    {

        $result = array(
            "firstOrder" => 0,
            "firstOrderJifen" => 0, // credit of first order
            "firstOrderJifenMoney" => 0, // credit money of first order
            "totalMoney" => 0, // order amount
            "money" => 0,    // order amount to pay with money
            "jifen" => 0, // order amount to pay with credit
            "jifenMoney" => 0 //order amount to pay with credit exchange to money
        );
        $creditController = $this->registry->get('CreditController');
        $creditToCashTransferPercent = $creditController->creditToCashTransferPercent;
        //$result['creditToCashTransferPercent'] = $creditToCashTransferPercent;
        $sql = 'select count(1) as count from ' . getTable('order_payment') . ' a '
            . ' where pay_status = 1 and a.customerId = ' . to_db_int($customerId);
        $count = $this->db->queryCount($sql);
        if ($count > 0) {
            $firstOrder = 0;
            $result['firstOrder'] = 0;
            $result['firstOrderJifen'] = 0;
            $result['firstOrderJifenMoney'] = 0;
        } else {
            $firstOrder = 1;
            $result['firstOrder'] = 1;
        }

        $sql = "select a.order_id as order_id, a . order_no as order_no,round(a . total, 2) as totalMoney ,IFNULL(b . credit, 0) as jifen,a.firstOrder as firstOrder  from "
            . getTable('order') . " a ," . getTable('customer') . " b where a . customer_id = b . customer_id and a . order_no  in ( ";
        foreach ($orderNoArray as $orderNo) {
            $sql .= to_db_str($orderNo) . ',';
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= ' ) ';


        $res = $this->db->getAll($sql);
        if (count($res) > 0) {
            $customerJifen = 0;
            $customerJifenMoney = 0;
            $totalMoney = 0;
            foreach ($res as $order) {
                $customerJifen = $order['jifen'];
                $customerJifenMoney = $order['jifen'] / $creditToCashTransferPercent;
                if ($firstOrder == 1) {
                    $productController = $this->registry->get('ProductController');
                    $orderCredit = $productController->getProductCreditByOrder($order['order_id']);
                    $result['firstOrderJifen'] += $orderCredit;
                    $result['firstOrderJifenMoney'] += $orderCredit / $creditToCashTransferPercent;
                } else {
                    $orderCredit = 0;
                }
//                $totalMoney = $totalMoney + $order['totalMoney'] - $orderCredit/$creditToCashTransferPercent;
                $totalMoney = $totalMoney + $order['totalMoney'];
            }
            $result['totalMoney'] = $totalMoney;
            if ($totalMoney < $customerJifenMoney) {
                $result['money'] = 0;
                $result['jifen'] = $totalMoney * $creditToCashTransferPercent;
                $result['jifenMoney'] = $totalMoney;
            } else {
                $result['money'] = $totalMoney - $customerJifenMoney;
                $result['jifen'] = $customerJifen;
                $result['jifenMoney'] = $customerJifenMoney;
            }
        }
        return $result;
    }

    public function showOrderDetailV2($orderNo)
    {
        if ($orderNo == null) {
            throw new exception("订单编号不能为空");
        }
        $sql = "select order_id as orderId,order_no as orderNo ,pay_money,pay_score,receiver_address ,receiver_phone,receiver_fullname ,date_added as orderDate, total,express_price as expMoney,order_status as orderStatus,order_payment_id as order_payment_id from " .
            getTable('order') . " where order_status <> 8 and order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);
        if (count($res) == 0) {
            throw new exception("订单编号无效");
        }
        $result = $res[0];
        //支付方式
        if (isset($res[0]['order_payment_id'])) {
            $sql = "select (to_days(now()) - to_days(date_added)) as payment_days,pay_status,date_payment,pay_type from " . getTable('order_payment') . " where order_payment_id = '" . $result['order_payment_id'] . "'";
            $res = $this->db->getAll($sql);
            $result = array_merge($result, $res[0]);
        }
        $domain = $this->getDomain();
        $sqlSupplier = "SELECT DISTINCT supplier_id as supplier_id,supplier_name   FROM  " . getTable('order_product') . " where order_id = " . $result['orderId'];
        $suppliers = $this->db->getAll($sqlSupplier);
        foreach ($suppliers as &$supplier) {
            $supplierId = $supplier['supplier_id'];
            $sql = "select a.refoundlimit ,a.op_express_id, a.order_product_id,a.product_id,a.return_goods_status,a.name as name,a.supplier_id,a.supplier_name,a.price ,a.total,a.quantity as num,a.unit_score,a.total_score,CONCAT('" . $domain
                . "/image/', b.img_3)   as image,supplier_name,a.order_product_status as order_product_status,
             a.express_price as shippmentPrice from " . getTable('order_product')
                . " a,  ".getTable('product')." b where a.product_id=b.product_id and a.order_id = " . $result['orderId']
                . ' and a.supplier_id = ' . to_db_int($supplierId);

            $products = $this->db->getAll($sql);
            foreach ($products as &$product) {
                if($result['orderStatus'] == 10 || $result['orderStatus'] == 11){
                    $product['can_return_goods'] = 0;
                }else{
                    if(isset($product['refoundlimit'])){
                        if($product['refoundlimit'] <= 0 ){
                            $product['can_return_goods'] = 0;
                        }else{
                            if(isset($result['payment_days']) && $result['payment_days'] > $product['refoundlimit']){
                                $product['can_return_goods'] = 0;
                            }else{
                                $product['can_return_goods'] = 1;
                            }
                        }
                    }else{
                        $product['can_return_goods'] = 0;
                    }
                }

                if(is_valid($product['op_express_id'])){
                    $sql = 'select * from '.getTable('order_product_express').' where op_express_id = '.to_db_int($product['op_express_id']);
                    $row = $this->db->querySingleRow($sql);
                    $product['express_no'] = $row['express_no'];
                    $product['express_name'] = $row['express_name'];
                }
            }

            $supplier['products'] = $products;
        }
        $result['suppliers'] = $suppliers;
        $result['pay_money_with_express'] = $result['pay_money'] + $result['expMoney'];
        return $result;
    }

    public function showOrderDetail($orderNo, $supplierId)
    {
        if ($orderNo == null) {
            throw new exception("订单编号不能为空");
        }
        $sql = "select order_id,order_no ,receiver_address ,receiver_phone,receiver_fullname ,date_added as orderDate, total,order_status from " . getTable('order') . " where order_no = '" . $this->db->escape($orderNo) . "'";
        $res = $this->db->getAll($sql);

        $sql = "select supplier_id,supplier_name from " . getTable('supplier') . " where supplier_id = " . $supplierId;
        $supplierRes = $this->db->getAll($sql);

        $resArray = array_merge($res[0], $supplierRes[0]);

        $domain = $this->getDomain();
        $oderId = $res[0]['order_id'];
        $sqlOrderProduct = "select product_id,name,supplier_id,supplier_name,price ,total,quantity as num,unit_score,total_score,CONCAT('" . $domain . "/image/', main_image)   as image,supplier_name,
             0 as shippmentPrice from " . getTable('order_product') . " where order_id = " . $oderId . " and supplier_id = " . $supplierId;
        $orderProductRes = $this->db->getAll($sqlOrderProduct);

        $res = array_merge($resArray, array("orderProduct" => $orderProductRes));
        return $res;
    }

    public function showOrderListV3($customerId, $status_id)
    {
        return $this->getOrderListWithoutGroup($customerId, $status_id);
    }

    private function getOrderListWithoutGroup($customerId, $status_id)
    {
        $sql = "SELECT order_id as orderId, order_no as orderNo, round(total, 2) as total, order_status as orderStatus, date_added as orderDate
            FROM " . getTable('order') . " a  WHERE  a . customer_id = " . $customerId;

        //订单状态 '0'=>'待付款','1'=>'已取消','2'=>'未发货', '3'=>'部分未发货','4'=>'已发货', '5'=>'已完成',  '6'=>'退货中，等待平台商确认收货','7'=>'平台商同意退款','8'=>'交易关闭'
        if ($status_id == null) {
            //已取消、已关闭订单不显示
            $sql = $sql . " and a . order_status <> 11 and a . order_status <> 1 ";
        } else {
            $sql = $sql . " and a . order_status = " . $status_id;
        }
        $sql = $sql . " order by a.date_modified desc ";
        $domain = $this->getDomain();
        $orders = $this->db->getAll($sql);
        foreach ($orders as &$order) {
            $sql = "SELECT DISTINCT supplier_id as supplier_id,supplier_name   FROM  " . getTable('order_product') . " where order_id = " . $order['orderId'];
            $suppliers = $this->db->getAll($sql);

            foreach ($suppliers as &$supplier) {
                $supplierId = $supplier['supplier_id'];
                $sql =
                    "select a.product_id,a.name as name,a.price ,a.total,a.quantity,a.unit_score,a.total_score,CONCAT('" . $domain . "/image/', b.img_3)   as image, a.express_price,a.shipment_type from " .
                    getTable('order_product')  ." a ,".getTable('product')." b  where a.product_id=b.product_id and  a.order_id = " . $order['orderId'] . ' and a.supplier_id = ' . to_db_int($supplierId);
                //echo $sql;
                $products = $this->db->getAll($sql);
                $totalGhsMoney = 0;
                foreach ($products as &$product) {
                    $totalGhsMoney += $product['total'];
                    if ($product['express_price'] > 0) {
                        $product['baoyou'] = '快递直邮:￥' . parseFormatNum($product['express_price'], 2);
                    } else {
                        $product['baoyou'] = '现货包邮';
                    }
                }
                $supplier['products'] = $products;
                $supplier = array_merge($supplier, array("supplierTotal" => $totalGhsMoney));

            }
            $order['suppliers'] = $suppliers;
        }
        return $orders;
    }

    private function getOrderListByGhsGroup($customerId)
    {
        $domain = $this->getDomain();
        $sql = "select DISTINCT a . order_id as order_id,a . supplier_id  as supplier_id,a . supplier_name as supplier_name,
          b . order_no as order_no,b . order_status as orderStatus, b . date_added as orderDate
          from " . getTable('order_product') . " a, " . getTable('order') . " b  where a . order_id = b . order_id and b . order_status in(2, 3, 4, 5, 6, 7) and b . customer_id = " . $customerId;


        $orderRes = $this->db->getAll($sql);
        for ($index = 0; $index < count($orderRes); $index++) {
            $supplierId = $orderRes[$index]['supplier_id'];
            $oderId = $orderRes[$index]['order_id'];

            $sqlOrderProduct = "select product_id,name,supplier_id,supplier_name,price ,total,quantity as num,unit_score,total_score,CONCAT('" . $domain . "/image/', main_image)   as image,supplier_name,
             0 as shippmentPrice from " . getTable('order_product') . " where order_id = " . $oderId . " and supplier_id = " . $supplierId;
            $orderProductRes = $this->db->getAll($sqlOrderProduct);


            $orderRes[$index] = array_merge($orderRes[$index], array("orderProduct" => $orderProductRes));

        }
        return $orderRes;
    }

    public function showOrderListV2($customerId, $status_id)
    {
        $domain = $this->getDomain();
        $sql = "select DISTINCT a . order_id as order_id,a . supplier_id  as supplier_id,a . supplier_name as supplier_name,
          b . order_no as order_no,b . order_status as orderStatus, b . date_added as orderDate
          from " . getTable('order_product') . " a, " . getTable('order') . " b  where a . order_id = b . order_id and b . customer_id = " . $customerId;
        if ($status_id != null) {
            $sql = $sql . " and b . order_status = " . $status_id;
        }

        $orderRes = $this->db->getAll($sql);
        for ($index = 0; $index < count($orderRes); $index++) {
            $supplierId = $orderRes[$index]['supplier_id'];
            $oderId = $orderRes[$index]['order_id'];

            $sqlOrderProduct = "select product_id,name,supplier_id,supplier_name,price ,total,quantity as num,unit_score,total_score,CONCAT('" . $domain . "/image/', main_image)   as image,supplier_name,
             0 as shippmentPrice from " . getTable('order_product') . " where order_id = " . $oderId . " and supplier_id = " . $supplierId;
            $orderProductRes = $this->db->getAll($sqlOrderProduct);


            $orderRes[$index] = array_merge($orderRes[$index], array("orderProduct" => $orderProductRes));

        }

        return $orderRes;

    }

    //取得用户的订单信息详细列表
    public function showOrderList($customerId, $status_id)
    {
        //循环扫描order列表，得出orderId,查询mc_order_product表，并结合mcc_product查出需要展示的数据

        $customerSql = "SELECT fullname AS userName,shareCode ,credit AS jifen FROM  " . getTable('customer') . " WHERE customer_id = " . $customerId;
        $customer = $this->db->getAll($customerSql);

        $orderSql = "SELECT order_id as orderId, order_no as orderNo, round(total, 2) as total, order_status as orderStatus, date_added as orderDate
            FROM " . getTable('order') . " a  WHERE  a . customer_id = " . $customerId;
        if ($status_id != null) {
            $orderSql = $orderSql . " and a . order_status = " . $status_id;
        }
        $orderRes = $this->db->getAll($orderSql);
        $orderArray = array();
        foreach ($orderRes as $order) {
            $sqlSupplier = "SELECT DISTINCT supplier_id as supplier_id,supplier_name   FROM  " . getTable('order_product') . " where order_id = " . $order['orderId'];

            $supplierRes = $this->db->getAll($sqlSupplier);

            $sqlOrderProduct = "select product_id,supplier_id,supplier_name,price ,total,quantity,unit_score,total_score,main_image as image from " . getTable('order_product') . " where order_id = " . $order['orderId'];
            $orderProductRes = $this->db->getAll($sqlOrderProduct);

            //按照供貨商分組
            for ($index = 0; $index < count($supplierRes); $index++) {

                $supplierId = $supplierRes[$index]['supplier_id'];
                $orderProductArray = array();
                foreach ($orderProductRes as $orderProduct) {
                    if ($orderProduct['supplier_id'] == $supplierId) {
                        array_push($orderProductArray, $orderProduct);
                    }
                }
                $supplierRes[$index] = array_merge($supplierRes[$index], array("suppliers" => $orderProductArray));

            }

            array_push($orderArray, array_merge($order, array("orderProduct" => $supplierRes)));
        }

        $res = array_merge($customer[0], array("orders" => $orderArray));

        return $res;
    }


    //展示我的积分--消费获得积分
    public function creditFromConsume($customerId)
    {
        //查询mcc_credithistory表中对应customerid且type=0
        $sql = "SELECT credit,ADDDATE FROM " . getTable('credithistory') . " where type = 0 and customerid = " . $customerId;
        $res = $this->db->getAll($sql);
        return $res;
    }

    //展示我的积分--活动奖励积分
    public function creditFromPromotion($customerId)
    {
        //查询mcc_credithistory表中对应customerid且type=1
        $sql = "SELECT credit,ADDDATE FROM " . getTable('credithistory') . " where type = 1 and customerid = " . $customerId;
        $res = $this->db->getAll($sql);
        return $res;

    }

    //展示我的积分--分享获得积分
    public function creditFromSharing($customerId)
    {
        //查询mcc_credithistory表中对应customerid且type=2
        $sql = "SELECT credit,ADDDATE FROM " . getTable('credithistory') . " where type = 2 and customerid = " . $customerId;
        $res = $this->db->getAll($sql);
        return $res;
    }

    //展示我的积分--积分支出
    public function consumeCredit($customerId)
    {
        //查询mcc_credithistory表中对应customerid且type=3
        $sql = "SELECT credit,ADDDATE FROM " . getTable('credithistory') . " where type = 3 and customerid = " . $customerId;
        $res = $this->db->getAll($sql);
        return $res;
    }

    public function queryReturnGoodsInfo($supplierId, $orderProductId)
    {
        $sql = "SELECT a.name as salesreturn,a.telephone as telephone, b.name as prov,c.name as  city,d.name as distic, a.addr_info as street FROM " . getTable('express_salesreturn') . " a,".getTable('addressbook_china_province')." b ,".getTable('addressbook_china_city').
            " c,".getTable('addressbook_china_district')." d where a.addr_prov=b.id and a.addr_city = c.id and a.addr_dist=d.id  and  a.supplier_id = " . $supplierId;
        $res = $this->db->querySingleRow($sql);
//expsr_idint(11) NOT NULLid
//supplier_idint(11) NOT NULL供应商id
//namevarchar(11) NOT NULL收件人姓名
//telephonevarchar(11) NOT NULL收件人联系方式
//addr_provint(11) NOT NULL收件人地址：省份
//addr_cityint(11) NOT NULL收件人地址：市
//addr_distint(11) NOT NULL收件人地址：区
//addr_infovarchar(60) NOT NULL收件人地址：详细地址

        $res['address'] = $res['prov'] . '-' . $res['city'] . '-' . $res['distic'] . ' ' . $res['street'];
        $sql = ' select * from ' . getTable('refound_history') . ' where order_product_id = ' . to_db_int($orderProductId);
        $refound_history = $this->db->querySingleRow($sql);
        $sql = "select * from ".getTable('bank')." where bank_status = 1";
        $resBank = $this->db->getAll($sql);
        $res = array_merge($res, $refound_history);
        $res = array_merge($res, array("bankList"=>$resBank));
        return $res;
    }

    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey = 'domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }
}
