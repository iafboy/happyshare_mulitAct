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

	private $module_name = 'reports/add';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language('reports/add');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('reports/add');
		$this->load->model('common/model');
		$this->getOrderList($lans);
	}
	public function calfee(){
		$this->load->model('reports/add');
		$order_ids = $this->request->post['selected'];
		$supplier_id = $this->request->post['supplier_id'];
		$amount = $this->model_reports_add->querySupplierPriceSum($order_ids,$supplier_id);
		$array = array();
		$array['amount'] = $amount;
		writeJson($array);
	}

	public function checkCanRepay(){
		$this->load->model('reports/add');
		$order_ids = $this->request->post['selected'];
		$supplier_id = $this->request->post['supplier_id'];
		$canPay = $this->model_reports_add->queryCanRepay($order_ids,$supplier_id);
		if($canPay){
			writeJson(['success'=>true,'errMsg'=>'']);
		}else{
			writeJson(['success'=>false,'errMsg'=>'部分订单未完成']);
		}
	}
	public function export(){
		$lans = $this->load->language('reports/add');
		$this->load->model('reports/add');
		$array = [
				'supplier_company',
				'order_status',
				['order_finishtime_start',
						'order_finishtime_end'],
				'repay_status'
		];
		$filter_data = $this->parsePostEntries($array,false,false,true);
		$results = $this->model_reports_add->queryOrders($filter_data);
		foreach ($results as $result) {
			$orders[] = array(
					'order_no' => $result['order_no'],
					'order_status' => $lans['status_order_status'][$result['order_status']],
					'repay_status' => $lans['status_repay_status'][$result['repay_status']],
					'order_amount' => $result['supplier_price']
			);
		}
		$excel = new Excel();
		$columns =
				['column_order_no'=>$lans['column_order_no'],
						'column_order_status'=>$lans['column_order_status'],
						'column_repay_status'=>$lans['column_repay_status'],
						'column_order_amount'=>$lans['column_order_amount']
				];
		$excel->addHeader($columns);
		$excel->addBody(
						$orders
		);
		$excel->downLoad('export.xls');
	}
	protected function getOrderList($lans) {
		$data = array();
		$data['cal_fee_url'] = $this->url->link($this->module_name.'/calfee', 'token=' . $this->session->data['token']);
		$array = [
				'supplier_company',
				'order_status',
				['order_finishtime_start',
				'order_finishtime_end'],
				'repay_status'
		];
		$supplier_id = $this->request->get['filter_supplier_company'];
		$data['supplier_id'] = $supplier_id;
		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('reports_add_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setExportUrl($this->url->link($this->module_name.'/export', 'token=' . $this->session->data['token']));
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = ['order_status','repay_status','supplier_company'];
		$supplier_companies = $this->model_common_model->getSupplierCompanies();
		$companies = [''=>'请选择'];
		foreach($supplier_companies as $key=>$company){
			$companies[$company['supplier_id']] = $company['supplier_company'];
		}
		$list_array = [
				'order_status'=> $lans['status_order_status'],
				'repay_status'=> $lans['status_repay_status'],
				'supplier_company'=> $companies
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$entries_form->setEntriesInputType(['order_finishtime_start', 'order_finishtime_end'],'date');
		$btns = array();
		$btns[] = new Button('btn_reports_add_query main-search-btn','查询','lfx-btn');
		$btns[] = new Button('btn_reports_add_export main-export-btn','导出','lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);
		$columns =
		['column_order_no'=>$lans['column_order_no'],
		'column_order_status'=>$lans['column_order_status'],
		'column_repay_status'=>$lans['column_repay_status'],
		'column_order_amount'=>$lans['column_order_amount']
		];
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);

		$page = $filter_data['page'];
		// query results
		$results = $this->model_reports_add->queryOrders($filter_data);
		// query total count
		$count_total = $this->model_reports_add->queryOrdersCount($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id' => $result['order_id'],
				'order_no' => $result['order_no'],
				'supplier_id' => $result['supplier_id'],
				'order_status' => $lans['status_order_status'][$result['order_status']],
				'repay_status' => $lans['status_repay_status'][$result['repay_status']],
				'order_amount' => $result['supplier_price']
			);
		}
		$url = $this->parseUrl($array,false,false,false);
		$pagination = $this->buildPagination($page,$count_total,$url,$this->module_name);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($count_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($count_total - $this->config->get('config_limit_admin'))) ? $count_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $count_total, ceil($count_total / $this->config->get('config_limit_admin')));
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