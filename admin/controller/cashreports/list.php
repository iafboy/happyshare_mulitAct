<?php
class ControllerCashreportsList extends MyController {

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

	private $module_name = 'cashreports/list';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getOrderList($lans);
	}

	protected function getOrderList($lans) {
		$data = array();
		$array = [
			'customer_name',
			'cash_apply_id',
			[
				'cash_apply_time_start',
				'cash_apply_time_end'
			],
			'cash_pay_status',
			'cash_pay_no'
		];

		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(3,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('cashreports_list_fm');
		$entries_form->setBaseUrl($this->base_url);
//		$entries_form->setExportUrl($this->url->link($this->module_name.'/export', 'token=' . $this->session->data['token']));
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = ['cash_pay_status'];
		$list_array = [
			'cash_pay_status'=> $lans['status_cash_pay_status']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$entries_form->setEntriesInputType(['cash_apply_time_start', 'cash_apply_time_end'],'date');
		$btns = array();
		$btns[] = new Button('btn_reports_query main-search-btn',$lans['btn_reports_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);
		$columns =
		['column_customer_name'=>$lans['column_customer_name'],
		'column_cash_apply_id'=>$lans['column_cash_apply_id'],
		'column_cash_apply_time'=>$lans['column_cash_apply_time'],
		'column_cash_amount'=>$lans['column_cash_amount'],
		'column_cash_pay_status'=>$lans['column_cash_pay_status'],
		'column_cash_pay_no'=>$lans['column_cash_pay_no'],
		'column_operation'=>'操作'
		];
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);

		$page = $filter_data['page'];
		// query results
		$results = $this->model_cashreports_list->queryCashreports($filter_data);
		$data['reports'] = $results;
		// query total count
		$count_total = $this->model_cashreports_list->queryCashreportsCount($filter_data);
		$url = $this->parseUrl($array,false,false,false);
		$pagination = $this->buildPagination($page,$count_total,$url);
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
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}