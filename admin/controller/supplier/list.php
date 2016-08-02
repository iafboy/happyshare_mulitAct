<?php
class ControllerSupplierList extends MyController {

	/**
	 *
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

	private $module_name = 'supplier/list';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language('supplier/list');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('supplier/list');
		$this->getList($lans);
	}

    public function changeStatus(){
        $this->load->language($this->module_name);
        $this->load->model($this->module_name);
        $supplier_status = $this->request->post['status'];
        $supplier_id = $this->request->post['supplier_id'];
        $success = $this->model_supplier_list->changeStatus($supplier_id,$supplier_status);
		//change related product's status to be 4
		$this->model_supplier_list->changeRelatedProductStatus($supplier_id);
        $msg = new ReturnMsg();
        $msg->success = $success;
        if($success===false){
            $msg->err_msg='操作失败！';
        }
        $msg->writeJson();
    }

	protected function getList($lans) {

		$array = [
				'supplier_no',
				'supplier_name',
				['supplier_git_count_start','supplier_git_count_end'],
				['supplier_online_count_start','supplier_online_count_end'],
				['supplier_sold_count_start','supplier_sold_count_end'],
				['supplier_supply_amount_start','supplier_supply_amount_end'],
				['supplier_sold_amount_start','supplier_sold_amount_end'],
				['supplier_interest_amount_start','supplier_interest_amount_end'],
				['supplier_time_start','supplier_time_end'],
				'is_in_brand_display','is_self_set_score'
		];
		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setFormId('supplier_list_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);

		$list = ['is_in_brand_display','is_self_set_score'];
		$list_array = [
				'is_in_brand_display'=>['*'=>'全部','0'=>'否','1'=>'是'],
				'is_self_set_score'=> ['*'=>'全部','0'=>'否','1'=>'是']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$entries_form->setEntriesInputType(
				['supplier_time_start', 'supplier_time_end'
				]
				,'date');
		$btns = array();
		$btns[] = new Button('btn_supplier_list_query main-search-btn','查询','lfx-btn');
		$btns[] = new Button('btn_supplier_list_query main-clear-btn','清空','lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);

		$data['form_id'] = 'supplier_list_fm';

		$data['breadcrumbs'] = $this->parseBreadCrumbs();

		$data['heading_title'] = $this->language->get('heading_title');
		$data = array_merge($data,$lans);
		$data['token'] = $this->session->data['token'];
		$data['route'] = $this->module_name;

		$data['suppliers'] = array();

		// query results
		$results = $this->model_supplier_list->getSuppliers($filter_data);
		// query total count
		$product_total = $this->model_supplier_list->getSupplierTotalCount($filter_data);
		foreach ($results as $result) {
			$status = $result['status'];
			$oper=null;
			if($status=='0'){
				$status = '已下架';
				$oper = '上架';
            }else if($status=='1'){
                $status = '已上架';
                $oper = '下架';
			}
			$data['suppliers'][] = array(
				'supplier_id' => $result['supplier_id'],
				'supplier_no' => $result['supplier_no'],
				'supplier_name'           => $result['supplier_name'],
				'supplier_git_count'      => $result['supplier_git_count'],
				'supplier_online_count'   => $result['supplier_online_count'],
				'supplier_sold_count'     => $result['supplier_sold_count'],
				'supplier_supply_amount'  => $result['supplier_supply_amount'],
				'supplier_sold_amount'    =>$result['supplier_sold_amount'],
				'supplier_interest_amount'    => $result['supplier_interest_amount'],
				'supplier_status_text'=> $status,
				'supplier_status'=> $result['status'],
				'supplier_status_oper'=> $oper,
				'supplier_brand_status'=> $result['brand_status'],
                'supplier_own_brand' => $result['own_brand'],
				'supplier_desc'       => $this->url->link('supplier/list/view', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['vendor_id'], 'SSL')
			);
		}


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$page = $filter_data['page'];
		$url = $this->parseUrl($array,false,false,false);
		$pagination = $this->buildPagination($page,$product_total,$url,$this->module_name);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
		$data = array_merge($data,$this->parseEntries($array,false,false,false));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('supplier/list.tpl', $data));
	}

}