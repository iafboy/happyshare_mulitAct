<?php
	
	class ModelSaleAdministratorManagment extends Model {
		public function order_data_link()
		{
			$link = mysqli_connect('localhost','root','likun','lfx20151130');
			return $link;
		}
		public function order_search($dataarray, $supplierID){
			//$data = new ModelSaleAdministratorManagment;
			//$data_link = $data->order_data_link();
			$jsondata = $dataarray;
			$id = $jsondata['orderid']; // order id mcc_order
			$type = $jsondata['ordertype']; // order type id  mcc_order 必须存在 默认值 0
			$status = $jsondata['orderstatus']; // order status mcc_order 必须存在 默认值
			$customer = $jsondata['customer']; // customer id 
			$recipient = $jsondata['recipient']; // receiver_full name mcc_order 
			$phone = $jsondata['phone'];  // receiver phone mcc_order
			$s_date = $jsondata['start_date']; //date added mcc_order
			$e_date = $jsondata['end_date']; //date added mcc_order
			$supplier = $supplierID; // supplier id
			$query1 = "SELECT DISTINCT `order_id` From mcc_order_product Where `supplier_id`='$supplier' ORDER BY order_id desc";
			$result1 = $this->db->query($query1);
			$resultarray = array();
			foreach($result1->rows as $row)
			{
				$order = $row['order_id'];
				$query = "SELECT * From mcc_order WHERE `order_id`='$order'";
				if(!empty($id))
				{
					$query .="AND `order_no` LIKE '%$id%'";
				}
				if(!empty($type))
				{
					$query .="AND `order_type_id`='$type'";
				}
				if($status!=0)
				{
					$query .="AND `order_status`='$status'";
				}
				if(!empty($customer))
				{
					$query .="AND `fullname` LIKE '%$customer%'";
				}
				if(!empty($recipient))
				{
					$query .="AND `receiver_fullname` LIKE '%$recipient%'";
				}
				if(!empty($phone))
				{
					$query .="AND `receiver_phone` LIKE '%$phone%'";
				}
				if(!empty($s_date))
				{
					$query .="AND `date_added`>='$s_date'";
					//$query .="AND `date_added` Like '%$s_date%'";
				}
				if(!empty($e_date))
				{
					$query .="AND `date_added`<='$e_date'";
					//$query .="AND `date_added` Like '%$s_date%'";
				}
				$query=$query." ORDER BY order_id desc";
				$result = $this->db->query($query);

				  //if($result->row)
				  foreach($result->rows as $row)
				  {
							//订单号
							$orderid = $row['order_no'];
							//订单状态;
							$orderstatus = $row['order_status'];
							$status = "SELECT name From mcc_order_status WHERE `order_status_id`=$orderstatus";
							//$result2 = mysqli_query($data_link,$status);
							$result2 = $this->db->query($status);
							//while($row2 = mysqli_fetch_array($result2))
							if($result2->row)
							{
								$row2 = $result2->row;
								$orderstatus_name = $row2['name'];
							}
							/*
							//供货商编号
							$supplier_id = $row['supplier_id'];
							// 客户名字
							$customerid = $row['customer_id'];
							$customer_select = "SELECT * FROM mcc_customer WHERE `customer_id`='$customerid'";
							//$customer_result = mysqli_query($data_link,$customer_select);
							$customer_result = $this->db->query($customer_select);
							//while($customer_row = mysqli_fetch_array($customer_result))
							if($customer_result->row)
							{
								$customer_row = $customer_result->row; */
							$customerfullname = $row['fullname'];
							/*}*/
							//收件人
							$receiver = $row['receiver_fullname'];
							//收件人电话
							$receiver_phone = $row['receiver_phone'];
							//地址
							$address = $row['receiver_address'];
							//订单总价
							$price = $row['total'];
							//下单时间
							$datetime = $row['date_added'];
							//说明
							$comment = $row['comment'];
							$info = compact(
								'order',
								'orderid',
								'orderstatus',
								'orderstatus_name',
								'customerid',
								'customerfullname',
								'receiver',
								'receiver_phone',
								'comment',
								'address',
								'price',
								'datetime',
								'supplier'
							);
							array_push($resultarray,$info);
						}
			}
			

			//$query .= " LIMIT " . (int)$start . "," . (int)$limit;

			//$result = mysqli_query($data_link,$query);
			
			return $resultarray;
		}
	public function order_item_detail($orderID,$userID){
			//$link = $this->order_data_link();
			$query = "SELECT * FROM mcc_order WHERE `order_id`='$orderID'";
			//$order_list = mysqli_query($link,$query);
			$order_list = $this->db->query($query);
			$payment_detail = array();
			//while($row = mysqli_fetch_array($order_list))
			foreach($order_list->rows as $row)
     		{
				$customer_id = $row['customer_id'];
				$query_address = "SELECT * FROM mcc_address WHERE `customer_id`='$customer_id'";
				$express_address = $this->db->query($query_address);
				foreach($express_address->rows as $row2)
				{
					$address = $row2['address'];
					$postcode = $row2['postcode'];
					$name = $row2['fullname'];
					$phone = $row2['phone'];
				}
				$price = $row['total'];
				if($row['pay_status']== 0)
				{
					$payment_status = '未支付'; //支付状态
				}
				else if($row['pay_status']==1)
				{
					$payment_status = '已支付';
				}
				$customer_payment_status = $row['pay_status'];
				//支付状态详情				
				$order_payment_id = $row['order_payment_id']; //账单编号
				//支付详情数据
				$orderquery = "SELECT * FROM mcc_order_payment WHERE `order_payment_id`='$order_payment_id'";
				//$orderresult  = mysqli_query($link,$orderquery);
				$orderresult = $this->db->query($orderquery);
				foreach($orderresult->rows as $rows)
        		{
					$payed = $rows['pay_money'];
					$payed_credit_score = $rows['pay_score'];
					$payed_reference = $rows['pay_desc'];
				}
				$payment_info = compact(
					'address',
					'postcode',
					'name',
					'phone',
					'price',
					'payment_status',
					'payed',
					'payed_credit_score',
					'payed_reference',
					'customer_payment_status'				
				);
				array_push($payment_detail,$payment_info);
			}
			$pay = $payment_detail;
			//订单物品
			$itemquery = "SELECT * FROM mcc_order_product WHERE `order_id`='$orderID' and `supplier_id`='$userID'";
			//$itemresult = mysqli_query($link,$itemquery);
			$itemresult = $this->db->query($itemquery);
			$item_array = array();
			//while($rows2 = mysqli_fetch_array($itemresult))
		  	foreach ($itemresult->rows as $rows2)
      		{
				// 物品名，投递方式，购买数量， 积分，总价，样图，销售类型 
				$itemname = $rows2['name']; //物品名
				$quantity = $rows2['quantity']; //购买数量
				//$item_type = $rows2['product_sale_text'];//销售类型
				$item_type = $rows2['product_sale_type'];//销售类型
				if ($item_type == 1) {
				  $item_type = "普通商品";
				} else {
				  $item_type = "免费体验商品";
				}
				$order_product_id = $rows2['order_product_id'];
				$item_image = $rows2['main_image']; //样图
				$shipment_type = $rows2['shipment_type'];//投递方式
				$total_price = $rows2['total']; //总价
				$total_score = $rows2['total_score']; //总积分
				$order_product_id = $rows2['order_product_id'];
				$item_status = $rows2['order_product_status'];
				$status_words = "SELECT * From mcc_order_status WHERE `order_status_id`='$item_status' AND `language_id`='1'";
				$status_result = $this->db->query($status_words);
        /*
        foreach($status_result->rows as $statusrow)
				{
					$product_status = $statusrow['name'];
        }
         */

        /* *
         * 注意：此处的状态为订单中单个商品的独立状态，与订单状态不同，
         *       状态值的定义也与订单状态的定义有差别，以代码实现为准；
         *       详细可以参考mcc_order_product表中order_product_status
         *       字段的说明。
         * */
				switch($item_status){
				  case 0:
					$product_status = "未发货";
					break;
				  case 1:
					$product_status = "已发货，未收货";
					break;
				  case 2:
					$product_status = "已收货";
					break;
				  default:
					break;
				}
				$express_no = $rows2['express_no'];
				$express_company_id = $rows2['express_company_id'];
				if($express_company_id!=0)
				{
					$status_words = "SELECT * From mcc_express_company WHERE `expco_id`='$express_company_id'";
					$status_result = $this->db->query($status_words);
					$express_company = $status_result->row['name'];
				}
				else
				{
					$express_company = 'NULL';
				}
		
				$item_info = compact(
					'order_product_id',
					'itemname',
					'quantity',
					'item_type',
					'item_image',
					'shipment_type',
					'total_price',
					'total_score',
					'order_product_id',
					'product_status',
					'express_company',
					'express_no',
					'item_status'
				);	
				array_push($item_array,$item_info);			
			}
			$item = $item_array;
			//订单退货信息
			$return_query = "SELECT * FROM mcc_return WHERE `order_id`='$orderID' ";
			$return_list = $this->db->query($return_query);
			$return = array();
			foreach($return_list->rows as $row3)
			{
				//return reason id
				$express_owner = $row3['express_owner'];
				if($express_owner==0)
				{
					$express_owner="客户";
				}
				else if($express_owner==1)
				{
					$express_owner="商家";
				}
				if(!empty($row3['imgurl1']))
				{
					$img1 = $row3['imgurl1'];
				}
				else
				{
					$img1 = "";
				}
				if(!empty($row3['imgurl2']))
				{
					$img2 = $row3['imgurl2'];
				}
				else
				{
					$img2 = "";
				}
				if(!empty($row3['imgurl3']))
				{
					$img3 = $row3['imgurl3'];
				}
				else
				{
					$img3 = "";
				}
				if(!empty($row3['imgurl4']))
				{
					$img4 = $row3['imgurl4'];
				}
				else
				{
					$img4 = "";
				}
				//return reasons
				$reasons = $row3['return_reason_id'];
				$reason_words = "SELECT * From mcc_return_reason WHERE `return_reason_id`='$reasons' AND `language_id`='1'";
				$return_reasons = $this->db->query($reason_words);
				foreach($return_reasons->rows as $row4)
				{
					$reason = $row4['name'];
				}
				//return status
				$status_id = $row3['return_status_id'];
				$status_words = "SELECT * From mcc_return_status WHERE `return_status_id`='$reasons' AND `language_id`='1'";
				$return_status = $this->db->query($status_words);
				foreach($return_status->rows as $row5)
				{
					$status = $row5['name'];
				}
				//item_name
				$return_item_name=$row3['product'];
				//return_quantity
				$return_qty = $row3['quantity'];
				$return_array = compact(
					'return_item_name',
					'express_owner',
					'img1',
					'img2',
					'img3',
					'img4',
					'reason',
					'status',
					'return_qty'
				);
				array_push($return,$return_array);
			}
			
			$result_array = compact(
				'pay',
				'item',
				'return'
			);
			$arraydetail = array();
			array_push($arraydetail,$result_array);
			return $arraydetail;
		}
		//单一物品退货
		public function product_return_option($order,$user,$item,$amount,$deduction){
			//更新客户申请退货退款
			$change_return_status = "UPDATE mcc_return SET `refund_amount`='$amount',`return_status_id`='3',`refund_change_reason`='$deduction' WHERE `order_id`='$order' AND `product`='$item'";
			$return_status = $this->db->query($change_return_status);	
			//更新订单内物品状态及金额
			$change_order_product_status ="UPDATE mcc_order_product SET `unit_score`='0',`total_score`='0', `product_status`='11' WHERE `order_id`='$order' AND `name`='$item'";
			$product_status = $this->db->query($change_order_product_status);
			//更新整个订单金额以及检查修改状态
			$order_search = "Select * from mcc_order Where `order_id`='$order' AND `supplier_id`='$user' ";
			$order_search_result = $this->db->query($order_search);
			foreach ($order_search_result->rows as $row)
			{
				$newAmount = $row['supplier_price']-$amount;
				if($newAmount <=0)
				{
					$order_status = 11;
				}
				else
				{
					$order_status = 15;
				}
				$update_order = "UPDATE mcc_order SET `order_status`='$order_status',`supplier_price`='$newAmount' WHERE `order_id`='$order' AND `supplier_id`='$user'";
				$update_result = $this->db->query($update_order);
			}
			
			$final = true;
			
			return $final;
			
			
				
		}
		
		public function express_company(){
			$array = array();
			$selectall = "Select * From mcc_express_company";
			$company_result = $this->db->query($selectall);
			foreach($company_result->rows as $row)
			{
				$companyID = $row['expco_id'];
				$company = $row['name'];
				$companyarray = compact(
					'companyID',
					'company'
				);
				array_push($array,$companyarray);
			}
			return $array;
		}
		public function express_record($order,$code,$product_status,$supplier,$express_id,$order_item){
			$update_query = "UPDATE `mcc_order_product` SET `express_company_id`='$express_id', `express_no`='$code', `order_product_status`='1' WHERE `order_product_id`='$order_item' AND `supplier_id`='$supplier'";
			$result = $this->db->query($update_query);
			if($result===true)
			{
				$result_confirm = 0;
				$select = "SELECT * FROM `mcc_order_product` WHERE `order_id`='$order' ";
				$select_result = $this->db->query($select);
				foreach($select_result->rows as $row)
				{
					if($row['order_product_status']!= 1)
					{
						break;
					}
				}
				if($row['order_product_status']==1)
				{
					$update_order = "UPDATE `mcc_order` SET `order_status`='3', `order_status_id`='3' WHERE `order_id`='$order'";
					$result_update_order = $this->db->query($update_order);
				}
				else
				{
					$update_order = "UPDATE `mcc_order` SET `order_status`='2', `order_status_id`='2' WHERE `order_id`='$order'";
					$result_update_order = $this->db->query($update_order);
				}
			}
			else
			{
				$result_confirm = 1;
			}
			return $result_confirm;
		}
		public function order_type_select(){
			$select_type_query = "SELECT * From mcc_order_type";
			$result = $this->db->query($select_type_query);
			$array = array();
			foreach($result->rows as $row)
			{
				$type_id = $row['order_type_id'];
				$type_name = $row['order_type_name'];
				$type_array = compact(
					'type_id',
					'type_name'
				);
				array_push($array,$type_array);
			}
			return $array;
		}
		
	}




?>
