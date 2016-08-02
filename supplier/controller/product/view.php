<?php
class ControllerProductView extends MyController {

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

	private $module_name = 'product/view';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language('product/edit');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('product/edit');
		$this->load->model('tool/image');
		$this->load->model('common/product');
		$this->getProductDetail($lans);
	}
	protected function getProductDetail($lans) {
		$data = array();
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$product_id = $this->request->get['product_id'];
		$data['product'] =$this->model_product_edit->queryProduct($product_id);
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['prducttypes'] = $this->model_common_product->queryProducttypes();
		$data['originPlaces'] = $this->model_common_product->queryOriginPlaces();
		$data['expressPlaces'] = $this->model_common_product->queryExpressPlaces();
		$user_id = $this->session->data['supplier_id'];
		$data['self_set']=$this->model_common_product->checkCreditSettingPower($user_id);
		$data['expressTemplates'] = $this->model_common_product->queryExpressTemplates($user_id);
		$chargetypes = [];
		$chargetypes[0] = array(
			'id' => '1' ,
			'name' => '件数计费'
		);

		$chargetypes[1] = array(
			'id' => '2' ,
			'name' => '重量计费'
		);

		$chargetypes[2] = array(
			'id' => '3' ,
			'name' => '体积计费'
		);
		$data['chargetypes'] = $chargetypes;
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}