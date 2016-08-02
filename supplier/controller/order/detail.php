<?php

class ControllerOrderDetail extends MyController
{

    /**
     * @var array
     *
     *    Model View Controller
     *
     *    Model :  if load model by $this->load->model('supplier/supplier');
     *
     *        a attribute model_supplier_supplier is gonna be injected into this Class;
     *
     *        As you can see, the rule is modelName.replace('/','_');
     *
     *  View :
     *
     *
     */

    private $error = array();

    private $base_url = '';

    private $module_name = 'order/detail';

    public function index()
    {
        $this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
        $lans = $this->load->language($this->module_name);
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model($this->module_name);
        $this->getOrderDetail($lans);
    }

    public function getShipment()
    {
        $this->load->model($this->module_name);
        $product_id = $this->request->post['product_id'];
        $shipment = $this->model_order_detail->queryProductShipment($product_id);
        $processes = $this->model_order_detail->getShipmentProcesses($shipment['shipments_id']);
        $results = [
            'supplier_name' => $shipment['supplier_name'],
            'processes' => $processes
        ];
        writeJson(['shipment' => $results]);
    }

    protected function getOrderDetail($lans)
    {
        $data = array();
        $data['shipment_url'] = $this->url->link($this->module_name . '/getShipment', 'token=' . $this->session->data['token']);
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $order_id = $this->request->get['order_id'];
        $order = $this->model_order_detail->getOrder($order_id);
        $order['pay_status_code'] = $order['order_status'];
        $order['pay_status'] = $lans['status_order_status'][$order['order_status']];
        $data['order'] = $order;
        $data['order_id'] = $order_id;
        $suppliers = $this->model_order_detail->queryOrderSuppliers($order_id);
        $products = [];
        $stores = array();
        foreach ($suppliers as $supplier) {
            $store = array();
            $store['name'] = $supplier['supplier_name'];
            $products = $this->model_order_detail->queryOrderProducts($order_id, $supplier['supplier_id']);
            $product_list = array();
            $quantity = $supplier['quantity'];
            foreach ($products as $product) {
                $product_list[] = [
                    'product_id' => $product['product_id'],
                    'product_no' => $product['product_no'],
                    'pic' => get_img_url($product['img_3']),
                    'name' => $product['name'],
                    'count' => $product['quantity'],
                    'express_name' => $product['shipment_type'],
                    'price' => $product['total'],
                    'price_unit' => '元',
                    'score' => $product['total_score'],
                    'product_type' => $product['product_sale_text'],
                    'product_status' => $lans['status_order_product_status'][$product['order_product_status']],
                    'product_status_code' => $product['order_product_status'],
                    'order_product_id' => $product['order_product_id'],
                    'express_desc' => '',
                    'return_goods_status' => $product['return_goods_status']
                ];
            }
            $products = $product_list;
            $store['product_list'] = $product_list;
            $stores[] = $store;
        }
        $data['products'] = $products;
        $data['stores'] = $stores;
        $data = array_merge($data, $lans);
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view($this->module_name . '.tpl', $data));
    }

    public function renderOrderShippment()
    {
        $this->load->model($this->module_name);
        $order_id = $this->request->post['order_id'];
        $templates = $this->model_order_detail->queryProductsByExpressTemplate($order_id);
        $express_name=$templates['express_name'];
        $data = [];
        $data['order_id'] = $order_id;
        $data['templates'] = $templates;
        $data['express_name']=$express_name;
        $this->response->setOutput($this->load->view('order/order-shippment.tpl', $data));
    }

    public function renderReturnGoodsView()
    {
        $this->load->model($this->module_name);
        $order_product_id = $this->request->post['order_product_id'];
        $data = [];
        $refound = $this->model_order_detail->queryRefound($order_product_id);
        $data['refound'] = $refound;
        $this->response->setOutput($this->load->view('order/order-return-goods.tpl', $data));
    }
    public function renderConfirmReturnGoodsView()
    {
        $this->load->model($this->module_name);
        $order_product_id = $this->request->post['order_product_id'];
        $data = [];
        $refound = $this->model_order_detail->queryRefound($order_product_id);
        $data['refound'] = $refound;
        $this->response->setOutput($this->load->view('order/order-confirm-return-goods.tpl', $data));
    }

    public function  updateExpressInfo()
    {

        $this->load->model($this->module_name);
        $post = $this->request->post;
        $order_id = $post['order_id'];
        if (!is_valid($order_id)) {
            writeJson(['success' => false, 'errMsg' => '操作错误']);
            return;
        }
        $templateIds = $post['template_ids'];
        if (is_array($templateIds) && sizeof($templateIds) > 0) {
            foreach ($templateIds as $templateId) {
                if (!is_valid($post['express_no_' . $templateId])) {
                    writeJson(['success' => false, 'errMsg' => '运单号未设置']);
                    return;
                }
                if (!is_valid($post['express_name_' . $templateId])) {
                    writeJson(['success' => false, 'errMsg' => '快递公司未设置']);
                    return;
                }
            }
        } else {
            writeJson(['success' => false, 'errMsg' => '快递信息设置错误']);
            return;
        }
        $msg = $this->model_order_detail->updateExpressInfo($post);
        writeJson($msg);
    }

    public function  updateReturnGoods()
    {

        $this->load->model($this->module_name);
        $order_product_id = $this->request->post['order_product_id'];
        $allow_returngoods = $this->request->post['allow_returngoods'];
        $shipment_cost = $this->request->post['return_money'];
        $return_num = $this->request->post['return_num'];
        $msg = $this->model_order_detail->updateReturnGoods($order_product_id, $allow_returngoods,$shipment_cost,$return_num);
        writeJson($msg);
    }
    public function  confirmReturnGoods()
    {

        $this->load->model($this->module_name);
        $order_product_id = $this->request->post['order_product_id'];
        $refund_status = $this->request->post['refund_status'];
        $msg = $this->model_order_detail->confirmReturnGoods($order_product_id, $refund_status);
        writeJson($msg);
    }
}
