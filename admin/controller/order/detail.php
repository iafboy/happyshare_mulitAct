<?php
class ControllerOrderDetail extends MyController {

	/**
	 * @var array
	 *
	 *	Model View Controller
	 *
	 * 	Model :  if load model by $this->load->model('supplier/supplier');
	 *
	 * 		a attribute model_supplier_supplier is gonna be injected into this Class;
	 *
	 * 		As you can see, the rule is modelName.replace('/','_');
	 *
	 *  View :
	 *
	 *
	 */

	private $error = array();

	private $base_url= '';

	private $module_name = 'order/detail';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getOrderDetail($lans);
	}

	public function getShipment(){
		$this->load->model($this->module_name);
		$product_id = $this->request->post['product_id'];
		$shipment = $this->model_order_detail->queryProductShipment($product_id);
		$processes = $this->model_order_detail->getShipmentProcesses($shipment['shipments_id']);
		$results = [
					'supplier_name'=>$shipment['supplier_name'],
					'processes'=>$processes
			];
		writeJson(['shipment'=>$results]);
	}
	protected function getOrderDetail($lans) {
		$data = array();
		$data['shipment_url'] = $this->url->link($this->module_name.'/getShipment', 'token=' . $this->session->data['token']);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$order_id = $this->request->get['order_id'];
		$order = $this->model_order_detail->getOrderDetail($order_id);
		$order['pay_status_code'] = $order['order_status'];
		$order['pay_status'] = $lans['status_order_status'][$order['order_status']];
		$data['order'] = $order;
		$suppliers = $this->model_order_detail->queryOrderSuppliers($order_id);
		$stores = array();
		foreach($suppliers as $supplier){
			$store = array();
			$store['name'] = $supplier['supplier_name'];
			$products = $this->model_order_detail->queryOrderProducts($order_id,$supplier['supplier_id']);
			$product_list = array();
			$quantity = $supplier['quantity'];
			foreach($products as $product){
				$refundInfo = $this->getRefundInfoByID($order_id, $product['product_id']);
//				writeJson(['order_id'=>$order_id]);
//				writeJson(['product_id'=>$product['product_id']]);
//				writeJson(['data'=>$refundInfo]);
				$product_list[] = [
						'product_id' => $product['product_id'],
						'product_no' => $product['product_no'],
						'pic' => get_img_url($product['img_3']),
						'name' => $product['name'],
						'count' => $product['quantity'],
						'express_name' => $product['shipment_type'],
						'express_no' => $product['express_no'],
						'express_name' => $product['express_name'],
						'price' => $product['total'],
						'price_unit' => '元',
						'score' => $product['total_score'],
						'product_type' => $product['product_sale_text'],
						'product_status' => $lans['status_order_product_status'][$product['order_product_status']],
						'product_status_code' => $product['order_product_status'],
						'express_desc' => '',
						'refund_info' => $refundInfo
				];
			}
			$store['product_list'] = $product_list;
			$stores[] = $store;
		}
		$data['stores'] = $stores;
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function getRefundInfo(){
		$this->load->model($this->module_name);
		$orderId = $this->request->get['order_id'];
		$productId = $this->request->get['product_id'];
		$data = $this->getRefundInfoByID($orderId, $productId);
//		writeJson(['data'=>$data]);
		$this->response->setOutput($this->load->view('order/refundDetail.tpl', $data));
	}

	public function getRefundInfoByID($orderId, $productId){
		$data = [];
		$data['orderId'] = $orderId;
		$data['productId'] = $productId;
		$data['token'] = $this->session->data['token'];
		$refundInfo = $this->model_order_detail->getRefundInfo($orderId,$productId);
		if($refundInfo == null){
			return null;
		}
		$refundInfo[0]['refund_status'] = $this->getRefoundStatusText($refundInfo[0]['refund_status']);
		$data['refundInfo'] = $refundInfo;
		return $data;
	}

	public function getRefoundStatusText($status){
		if(!$status){
			return '';
		}
		$status += '';
		switch ($status){
			case '1':return '退货申请';
			case '2':return '退货审核通过';
			case '3':return '退货审核不通过';
			case '4':return '客户已发货';
			case '5':return '供货商已收货，等待退款';
			case '6':return '退货款已经返还';//改成由平台退款，并且将退款金额累加到order表中
			case '7':return '退货完成';
			case '8':return '退货关闭';
		}
//退货状态：1申请；2可退货；3不可退货 4.客户已发货；5.供货商已收货。 6.退货款已经返还 7.退货完成；8.关闭
	}

	public function saveTransferNo(){
		$this->load->model($this->module_name);
		$orderId = $this->request->get['order_id'];
		$productId = $this->request->get['product_id'];
		$transferNo = $this->request->get['transfer_no'];
		$data = $this->getRefundInfoByID($orderId, $productId);
		$creditInfo = $this->model_order_detail->getCredit($orderId);
		$credit = $creditInfo[0]['credit'];
		$customer_id = $creditInfo[0]['customer_id'];
		$cost = $data['refundInfo'][0]['shippment_cost'];
		if ($transferNo==''|| $transferNo==null){
			return;
		}
		// 如果退货已完成，则不再更新
		if ($data['refundInfo'][0]['refund_status'] == 7){
			return;
		}
		$this->model_order_detail->saveTransferNo($orderId,$productId,$transferNo,$cost,$credit,$customer_id);

	}

}