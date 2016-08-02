<?php
class ControllerReportsList extends MyController {

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

	private $module_name = 'reports/list';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language('reports/list');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('reports/list');
		$this->getOrderList($lans);
	}

	public function export(){
		$lans = $this->load->language($this->module_name);
		$this->load->model($this->module_name);
		$array = [
				'order_no',
				'buyer_name',
				'receiver_name',
				'receiver_phone',
				'order_type',
				'order_status',
				'supplier_id',
				'repay_status',
				'repay_no',
				['order_finishtime_start',
						'order_finishtime_end']
		];
		$filter_data = $this->parsePostEntries($array,false,false,true);
		$results = $this->model_reports_list->queryOrder($filter_data);
		foreach ($results as $result) {
			$reports[] = array(
					'order_no' => $result['order_no'],
					'order_status' => $lans['status_order_status'][$result['order_status']],
					'repay_status' => $lans['status_repay_status'][$result['repay_status']],
					'order_amount' => $result['supplier_price'],
					'finish_time' => $result['finish_time'],
					'transfer_no' => $result['transfer_no']
			);
		}
		$excel = new Excel();
		$columns =
				['column_order_no'=>$lans['column_order_no'],
						'column_order_status'=>$lans['column_order_status'],
						'column_order_repay_amount'=>$lans['column_order_repay_amount'],
						'column_order_repay_status'=>$lans['column_order_repay_status'],
						'column_order_repay_date'=>$lans['column_order_repay_date'],
						'column_order_repay_trade_no'=>$lans['column_order_repay_trade_no']
				];
		$excel->addHeader($columns);
		$excel->addBody(
				$reports
		);
		$excel->downLoad('export.xls');
	}

	protected function getOrderList($lans) {
		$data = array();
		$array = [
			'order_no',
			'buyer_name',
			'receiver_name',
			'receiver_phone',
			'order_type',
			'order_status',
			'supplier_id',
			'repay_status',
			'repay_no',
			['order_finishtime_start',
			'order_finishtime_end']
		];

		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('reports_list_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setExportUrl($this->url->link($this->module_name.'/export', 'token=' . $this->session->data['token']));
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = ['order_status','repay_status'];
		$list_array = [
			'order_status'=> $lans['status_order_status'],
			'repay_status'=> $lans['status_repay_status']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$entries_form->setEntriesInputType(['order_finishtime_start', 'order_finishtime_end'],'date');
//		$entries_form->setEntriesInputType(['btn_reports_query', 'btn_reports_export'],'submit');
		$btns = array();
		$btns[] = new Button('btn_reports_query main-search-btn',$lans['btn_reports_query'],'lfx-btn');
		$btns[] = new Button('btn_reports_export main-export-btn',$lans['btn_reports_export'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);
		$columns =
		['column_order_no'=>$lans['column_order_no'],
		'column_order_status'=>$lans['column_order_status'],
		'column_order_repay_amount'=>$lans['column_order_repay_amount'],
		'column_order_repay_status'=>'订单结算状态',
		'column_supplier_repay_status'=>'供货商结算状态',
		'column_order_repay_date'=>$lans['column_order_repay_date'],
		'column_order_repay_trade_no'=>$lans['column_order_repay_trade_no']
		];
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);

		$page = $filter_data['page'];
		// query results
		$results = $this->model_reports_list->queryOrder($filter_data);
		// query total count
		$count_total = $this->model_reports_list->queryOrderCount($filter_data);
		foreach ($results as $result) {
			$data['reports'][] = array(
				'order_id' => $result['order_id'],
				'order_no' => $result['order_no'],
				'order_status' => $lans['status_order_status'][$result['order_status']],
				'repay_status' => $lans['status_repay_status'][$result['repay_status']],
				'order_amount' => $result['transfer_amount'],
				'finish_time' => $result['repay_time'],
				'transfer_no' => $result['transfer_no']
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
		$this->response->setOutput($this->load->view('reports/list.tpl', $data));
	}
}