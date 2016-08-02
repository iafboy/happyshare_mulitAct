<?php
class ControllerOrderList extends MyController {

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

	private $module_name = 'order/list';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getOrderList($lans);
	}
	public function exportOrders(){
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->exportOrderList($lans);
	}

	public function confirmReturnGoods(){
		$this->load->model($this->module_name);
		$orderId = $this->request->post['orderId'];
		if(!is_valid($orderId)){
			writeJson(['success'=>false,'errMsg'=>'��������']);
			return;
		}
		writeJson($this->model_order_list->confirmReturnGoods($orderId));
	}

	public function getShipment(){
		$this->load->model($this->module_name);
		$order_id = $this->request->post['order_id'];
		$shipments = $this->model_order_list->queryOrderShipments($order_id);
		$results = array();
		foreach($shipments as $shipment){
			$processes = $this->model_order_list->getShipmentProcesses($shipment['shipments_id']);
			$products = $this->model_order_list->getShipmentProducts($shipment['shipments_id']);
			$results[] = [
				'supplier_name'=>$shipment['supplier_name'],
				'processes'=>$processes,
				'products'=>$products
			];
		}
		writeJson(['shipments'=>$results]);
	}
	protected function exportOrderList($lans){
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			if (isset($this->request->post['filter_order_no'])) {
				$filter_order_no = $this->request->post['filter_order_no'];
			} else {
				$filter_order_no = '';
			}
			if (isset($this->request->post['filter_buyer_account'])) {
				$filter_buyer_account = $this->request->post['filter_buyer_account'];
			} else {
				$filter_buyer_account = '';
			}
			if (isset($this->request->post['filter_receiver_name'])) {
				$filter_receiver_name = $this->request->post['filter_receiver_name'];
			} else {
				$filter_receiver_name = '';
			}
			if (isset($this->request->post['filter_receiver_phone'])) {
				$filter_receiver_phone = $this->request->post['filter_receiver_phone'];
			} else {
				$filter_receiver_phone = '';
			}
			if (isset($this->request->post['filter_order_type'])&&($this->request->post['filter_order_type']!='*')) {
				$filter_order_type = $this->request->post['filter_order_type'];
			} else {
				$filter_order_type = '';
			}
			if (isset($this->request->post['filter_order_status'])) {
				$filter_order_status = $this->request->post['filter_order_status'];
			} else {
				$filter_order_status = '';
			}
			if (isset($this->request->post['filter_order_create_time_start'])) {
				$filter_order_create_time_start = $this->request->post['filter_order_create_time_start'];
			} else {
				$filter_order_create_time_start = '';
			}
			if (isset($this->request->post['filter_order_create_time_end'])) {
				$filter_order_create_time_end = $this->request->post['filter_order_create_time_end'];
			} else {
				$filter_order_create_time_end = '';
			}
		}else{
			$filter_order_no=null;
			$filter_buyer_account=null;
			$filter_receiver_name=null;
			$filter_receiver_phone=null;
			$filter_order_type=null;
			$filter_order_status=null;
			$filter_order_create_time_start=null;
			$filter_order_create_time_end=null;
		}
		$filter_data =array(
			'filter_order_no'=>$filter_order_no,
			'filter_buyer_account'=>$filter_buyer_account,
			'filter_receiver_name'=>$filter_receiver_name,
			'filter_receiver_phone'=>$filter_receiver_phone,
			'filter_order_type'=>$filter_order_type,
			'filter_order_status'=>$filter_order_status,
			'filter_order_create_time_start'=>$filter_order_create_time_start,
			'filter_order_create_time_end'=>$filter_order_create_time_end
		);
		$data[] = array();
		// query results
		$results = $this->model_order_list->queryOrder($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_no' => $result['order_no'],
				'order_status_text' => $lans['status_order_status'][$result['order_status']],
				'fullname' => $result['fullname'],
				'receiver_fullname' => $result['receiver_fullname'],
				'receiver_phone'    => $result['receiver_phone'],
				'receiver_address'    => $result['receiver_address'],
				'total'    => $result['total'],
				'date_added'    => $result['date_added'],
				'comment'    => $result['comment']
			);
		}
		$excel = new Excel();
		$columns =
			[	'order_no' => '订单编号',
				'order_status_text' =>'订单状态',
				'fullname' => '会员账号',
				'receiver_fullname' => '收件人',
				'receiver_phone'    => '联系电话',
				'receiver_address'    => '送货地址',
				'total'    => '订单金额',
				'date_added'    => '下单时间',
				'comment'    => '备注'
			];
		$excel->addHeader($columns);
		$excel->addBody($data['orders']);
		$excel->downLoad("orderList_" . date('Y_m_d_H_i_s') . ".xls");
	}
	protected function getOrderList($lans) {
		$data = array();
		$data['shipment_url'] = $this->url->link($this->module_name.'/getShipment', 'token=' . $this->session->data['token']);
		$data['order_detail_url'] = $this->url->link('order/detail', 'token=' . $this->session->data['token']);
//		$array = [
//			'order_no',
//			'buyer_account',
//			'receiver_name',
//			'receiver_phone',
//			'order_type',
//			'order_status',
//			['order_create_time_start','order_create_time_end']
//		];
		$array = [
			'order_no',
			'buyer_account',
			'receiver_name',
			'receiver_phone',
			'order_status',
			['order_create_time_start','order_create_time_end']
		];
		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('order_list_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
//		$list = [
//      'order_status',
//      'order_type'];
//		$list_array = [
//			'order_status' => $lans['status_order_status'],
//			'order_type'   => $lans['type_order_type']
//		];
		$list = [
			'order_status'];
		$list_array = [
			'order_status' => $lans['status_order_status']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$entries_form->setEntriesInputType(
			[
				'receiver_phone'
			]
			,'number');
		$entries_form->setEntriesInputType(
			['order_create_time_start','order_create_time_end']
			,'date');
		$btns = array();
		$btns[] = new Button('btn_orders_query main-search-btn',$lans['btn_orders_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);
		$column_arr =[
			'column_order_no',
		'column_order_status',
		'column_buyer_account',
		'column_receiver_name',
		'column_receiver_phone',
		'column_receiver_address',
		'column_order_amount',
		'column_order_create_time',
		'column_order_remark'
		];
		$columns = array();
		foreach($column_arr as $c){
			$columns[$c] = $lans[$c];
		}
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);
		$page = $filter_data['page'];
		// query results
		$results = $this->model_order_list->queryOrder($filter_data);
		// query total count
		$count_total = $this->model_order_list->queryOrderCount($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id' => $result['order_id'],
				'order_no' => $result['order_no'],
				'order_status_text' => $lans['status_order_status'][$result['order_status']],
				'order_status' => $result['order_status'],
				'fullname' => $result['fullname'],
				'receiver_fullname' => $result['receiver_fullname'],
				'receiver_phone'    => $result['receiver_phone'],
				'receiver_address'    => $result['receiver_address'],
				'total'    => $result['total'],
				'date_added'    => $result['date_added'],
				'comment'    => $result['comment'],
				'oper'    => $lans['order_opers'][$result['order_status']]
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
		//$data['entries'] = $this->load->controller('common/entries');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}
