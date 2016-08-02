<?php

class ControllerSaleShipment extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('sale/order');

		$this->load->model('sale/administrator_managment');

      $searchresult = $this->model_sale_administrator_managment->express_company();
	  $searchresult = json_encode($searchresult);
	  echo $searchresult;

	}
}



?>