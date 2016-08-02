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

class ControllerSaleOrdersearch extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('sale/order');

		$this->load->model('sale/administrator_managment');

		//if ($this->request->server['REQUEST_METHOD'] == 'POST') 

      $result =$this->request->post['order_search'];
      $supplier = $this->request->post['supplierid'];
      //$page = $this->request->post['page'];
      //$limit = $this->config->get('config_limit_admin');
      /*if($limit < 1){
        $limit = 20;
      }
			$start = ($page - 1) * $limit;*/

    $searchresult = $this->model_sale_administrator_managment->order_search($result,$supplier);
	  $searchresult = json_encode($searchresult);
	  echo $searchresult;

	}
}
