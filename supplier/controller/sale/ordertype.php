<?php
class ControllerSaleOrderType extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('sale/order');

		$this->load->model('sale/administrator_managment');
		$searchresult = $this->model_sale_administrator_managment->order_type_select();
		  $searchresult = json_encode($searchresult);
		  echo $searchresult;

	}
}


?>