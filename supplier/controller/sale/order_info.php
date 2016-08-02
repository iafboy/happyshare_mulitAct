<?php

/*
if($_POST)
{
	$order = $_POST['order_id'];
	$user = $_POST['user_id'];
}
	include("../../../supplier/model/sale/administrator_managment.php");
	$search = new ModelSaleAdministratorManagment;
	$searchresult = $search->order_item_detail($order,$user);
	$result = json_encode($searchresult);
  echo $result;
 */

class ControllerSaleOrderInfo extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('sale/order');

		$this->load->model('sale/administrator_managment');

		//if ($this->request->server['REQUEST_METHOD'] == 'POST') 
  
      $order =$this->request->post['order_id'];
      $user = $this->request->post['user_id'];
   

    $searchresult = $this->model_sale_administrator_managment->order_item_detail($order,$user);
	  $result = json_encode($searchresult);
	  echo $result;

	}

}
