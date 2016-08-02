<?php
class ControllerReportsAdd extends MyController {

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

	public function index() {
		$lans = $this->load->language('reports/add');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('reports/add');
		$this->getOrderList($lans);
	}
	protected function getOrderList($lans) {
		$data = array();
		$array = [
			'filter_supplier_id',
			'filter_order_amount',
			'filter_repay_bankid',
			'filer_repay_bankcard_no',
			'filter_supplier_bankid',
			'filter_supplier_bankcard_no',
			'filter_order_no',
			'filter_order_finishtime_start',
			'filter_order_finishtime_end',
			'filter_order_status',
		];

		$filter_data = $this->parseEntries($array,false,false,true);
		$columns =
		['column_order_no'=>$lans['column_order_no'],
		'column_order_status'=>$lans['column_order_status'],
		'column_order_amount'=>$lans['column_order_amount']
		];
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);


		$page = $filter_data['page'];
		// query results
		$results = $this->model_reports_add->queryOrder($filter_data);
		// query total count
		$count_total = $this->model_reports_add->queryOrderCount($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id' => $result['order_id'],
				'order_no' => $result['order_no'],
				'order_status' => $lans['status_repay_status'][$result['order_status']],
				'order_amount' => $result['order_amount']
			);
		}
		$url = $this->parseUrl($array,false,false,false);
		$pagination = $this->buildPagination($page,$count_total,$url);
		$data['pagination'] = $pagination->render();
		$data = array_merge($data,$this->parseEntries($array,false,false,false));
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs($array);
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('reports/add.tpl', $data));
	}
}