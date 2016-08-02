<?php
class ControllerDashboardOrder extends Controller {
	public function index() {
		$supplierid=$this->session->data['supplier_id'];
		$this->load->language('dashboard/order');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		// Total Orders
		$this->load->model('sale/order');
		
		$today = $this->model_sale_order->getTotalSupplierOrders(array('filter_date_added' => date('Y-m-d', strtotime('-1 day')),'filter_supplier_id'=>$supplierid));

		$yesterday = $this->model_sale_order->getTotalSupplierOrders(array('filter_date_added' => date('Y-m-d', strtotime('-2 day')),'filter_supplier_id'=>$supplierid));

		$difference = $today - $yesterday;

		if ($difference && $today) {
			$data['percentage'] = round(($difference / $today) * 100);
		} else {
			$data['percentage'] = 0;
		}
		
		$order_total = $this->model_sale_order->getAllSupplierOrders($supplierid);
		
		if ($order_total > 1000000000000) {
			$data['total'] = round($order_total / 1000000000000, 1) . 'T';
		} elseif ($order_total > 1000000000) {
			$data['total'] = round($order_total / 1000000000, 1) . 'B';
		} elseif ($order_total > 1000000) {
			$data['total'] = round($order_total / 1000000, 1) . 'M';
		} elseif ($order_total > 1000) {
			$data['total'] = round($order_total / 1000, 1) . 'K';						
		} else {
			$data['total'] = $order_total;
		}
				
		$data['order'] = $this->url->link('order/list', 'token=' . $this->session->data['token'], 'SSL');

		return $this->load->view('dashboard/order.tpl', $data);
	}
}
