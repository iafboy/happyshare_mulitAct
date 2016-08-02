<?php
class ControllerProductList extends MyController {

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

	private $base_url= '';

	private $module_name = 'product/list';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getProductList($lans);
	}
	protected function getProductList($lans) {
		$data = array();
		$data['product_edit_url'] = $this->url->link('product/edit', 'token=' . $this->session->data['token']);
		$array = [
			'product_no',
			'product_name',
			'product_supplier_id',
			'product_place',
			'product_type',
			['supplier_price_start', 'supplier_price_end'],
			['product_price_start','product_price_end'],
			['score_feedback_rate_start', 'score_feedback_rate_end'],
			['product_count_start', 'product_count_end'],
			'product_status',
			['product_submit_time_start', 'product_submit_time_end'],
			['product_sold_count_start', 'product_sold_count_end'],
			['product_share_count_start', 'product_share_count_end'],
      ['product_comment_count_start', 'product_comment_count_end'],
      ['product_shareLevel_start', 'product_shareLevel_end']
		];

		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('products_list_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = ['product_supplier_id','product_place','product_type','product_status'];
		$product_types = $this->model_product_list->queryProductList();
		$product_types_array = array();
		$product_types_array['*'] = '全部';
		foreach($product_types as $product_type){
			$product_types_array[$product_type['product_type_id']] = $product_type['type_name'];
		}
		$suppliers = $this->model_product_list->querySupplierList();
		$suppliers_array = ['*'=>'全部'];
		foreach($suppliers as $supplier){
			$suppliers_array[$supplier['supplier_id']] = $supplier['supplier_name'];
		}
		$places = $this->model_product_list->queryOriginPlaces();
		$places_array = ['*'=>'全部'];
		foreach($places as $place){
			$places_array[$place['origin_place_id']] = $place['place_name'];
		}
		$list_array = [
			'product_supplier_id'=>$suppliers_array,
			'product_place'=> $places_array,
			'product_type'=> $product_types_array,
			'product_status'=> $lans['status_product_status']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$entries_form->setEntriesInputType(
			['product_submit_time_start', 'product_submit_time_end'
			]
			,'date');
		$btns = array();
		$btns[] = new Button('btn_products_query main-search-btn',$lans['btn_products_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);
		$column_arr =[
		'column_product_no',
		'column_product_name',
		'column_product_status',
		'column_supplier',
		'column_product_place',
		'column_product_type',
		'column_market_price',
		'column_supplier_price',
		'column_product_price',
		'column_score_feedback',
		'column_product_count',
		'column_sold_count',
		'column_share_count',
    'column_comment_count',
    'column_product_shareLevel',
		'column_edit_product'];
		$columns = array();
		foreach($column_arr as $c){
			$columns[$c] = $lans[$c];
		}
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);
		$page = $filter_data['page'];
		// query results
		$results = $this->model_product_list->queryProducts($filter_data);
		// query total count
		$count_total = $this->model_product_list->queryProductsCount($filter_data);
		$data['products'] = $results;
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
		$this->response->setOutput($this->load->view('product/list.tpl', $data));
	}
}
