<?php

class ControllerSaleShippingcoderecord extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('sale/order');

		$this->load->model('sale/administrator_managment');
		$express_id= $this->request->post['express_id'];
		$order = $this->request->post['orderid'];
		$code = $this->request->post['code'];
      	$supplier = $this->request->post['suppliers'];
      	$product_status = $this->request->post['product_status'];
		$order_item = $this->request->post['order_item'];
		$searchresult = $this->model_sale_administrator_managment->express_record($order,$code,$product_status,$supplier,$express_id,$order_item);
		  $searchresult = json_encode($searchresult);
		  echo $searchresult;

	}
}
?>
