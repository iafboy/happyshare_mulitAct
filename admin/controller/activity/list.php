<?php
class ControllerActivityList extends MyController {

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
	 */

	private $error = array();

	private $module_name = 'activity/list';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getActList($lans);
	}
	protected function getActList($lans) {
		$data = array();
		$array = [
			'act_name',
			'act_product_name',
			'act_type',
			['act_expire_date_start', 'act_expire_date_end'],
			['act_joiner_count_start','act_joiner_count_end'],
			'act_status'
		];
		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(3,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('act_list_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = [
				'act_type',
				'act_status'];
		$list_array = [
				'act_type'=> $lans['type_act_type'],
				'act_status' => $lans['status_act_status']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);

		$entries_form->setEntriesInputType(
				['act_expire_date_start','act_expire_date_end']
				,'date');
		$btns = array();
		$btns[] = new Button('btn_acts_query main-search-btn',$lans['btn_acts_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);
		$column_arr =[
		'column_act_no',
		'column_act_name',
		'column_act_status',
		'column_act_type',
		'column_act_start_time',
		'column_act_end_time',
		'column_act_joiner_count',
		'column_operation'
		];
		$columns = array();
		foreach($column_arr as $c){
			$columns[$c] = $lans[$c];
		}
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);
		$page = $filter_data['page'];
		// query results
		$data['acts'] = $this->model_activity_list->queryActs($filter_data);
		// query total count
		$count_total = $this->model_activity_list->queryActsCount($filter_data);
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
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}