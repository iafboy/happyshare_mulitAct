<?php
/*
if($_POST)
{
	$result =$_POST['order_search'];
	$supplier = $_POST['supplierid'];
}
	include("../../../supplier/model/sale/Administrator_Managment.php");
	$searchmodel = new ModelAdministratorManagement;
	$searchresult = $searchmodel->order_search($result,$supplier);
	$searchresult = json_encode($searchresult);
	echo $searchresult;
 */

class ControllerSaleOrderInfo extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('sale/order');

		$this->load->model('sale/administrator_managment');

		$order =$this->request->post['order_id'];
		$user = $this->request->post['user_id'];
		$return_product = $this->request->post['return_item'];
		$return_amount = $this->request->post['amount'];
		$return_deduction = $this->request->post['deduction'];	
		
		$return_result = $this->model_sale_administrator_managment->product_return_option($order,$user,$return_product,$return_amount,$return_deduction);
		
		  echo $return_result;

	}

}



?>