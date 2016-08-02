<?php
class ControllerActivitySpecial extends MyController {

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

    private $module_name = 'activity/special';

    public function index() {
        $lans = $this->load->language($this->module_name);
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model($this->module_name);
        $this->getSpecial($lans);
    }
    public function create(){
        $this->load->model($this->module_name);
        $this->load->model('common/product');
        $array= [
            'act_name'=>['field'=>'活动名称'],
            'act_start_date'=>['field'=>'活动开始日期'],
            'act_end_date'=>['field'=>'活动结束日期'],
            'act_memo'=>['field'=>'活动说明'],
            'special_type'=>['field'=>'活动类型'],
            'imgurl'=>['field'=>'活动图片'],
            'act_status'=>['field'=>'活动状态']];
        $valid = $this->validFields($array);
        if($valid['success'] !== true){
            writeJson($valid);
            return;
        }
        $post = $this->request->post;
        $act_product_ids = $post['act_product_ids'];
        $duplicate_productids = [];
        if(is_array($act_product_ids) && sizeof($act_product_ids) > 0){
            foreach($act_product_ids as $act_product_id){
                if(
//                    isset($post['p_'.$act_product_id.'_act_price']) &&
//                    isset($post['p_'.$act_product_id.'_act_credit'])
                true
                ){
                    $dup = $this->model_activity_special->isAlreadyInSpecial($act_product_id,1);

                    if($dup==true){
                        $duplicate_productids[] = $act_product_id;
                    }
                }else{
                    writeJson(['success'=>false,'errMsg'=>'活动产品价格、积分信息不能为空！']);
                    return;
                }
            }

            if(sizeof($duplicate_productids) > 0){
                $duplicate_nos = $this->model_common_product->queryNosByIds($duplicate_productids,0);
                $msg = '部分产品已参加活动，不可添加[';
                foreach($duplicate_nos as $duplicate_no){
                    $msg.= $duplicate_no['product_no'].' ,';
                }
                $msg = substr($msg,0,strlen($msg)-1);
                $msg.=']';
                writeJson(['success'=>false,'errMsg'=>$msg]);
                return;
            }
        }else{
            writeJson(['success'=>false,'errMsg'=>'产品编码不为空！']);
            return;
        }
        $img_name = basename($post['imgurl']);
        $img_path = 'activity/special/main/'.str_replace('_temp','',$img_name);
        if(is_file(DIR_IMAGE.$img_path)){
            FileUtil::unlinkFile(DIR_IMAGE.$img_path);
        }
        $is_ok = FileUtil::moveFile(DIR_IMAGE.$post['imgurl'],DIR_IMAGE.$img_path);
        if($is_ok !== true){
            writeJson(['success'=>false,'errMsg'=>'上传图片失败！']);
            return;
        }
        $post['imgurl'] = $img_path;
        $success = $this->model_activity_special->createSpecial($post);
        if($success===true){
            writeJson(['success'=>true,'location'=> html_entity_decode(
                $this->url->link('activity/list', 'token=' . $this->session->data['token'])
            )]);
        }else{
            writeJson(['success'=>false,'errMsg'=>'创建活动失败！']);
        }
    }
    public function modify(){
        $this->load->model($this->module_name);
        $this->load->model('common/product');
        $array= [
            'act_name'=>['field'=>'活动名称'],
            'act_start_date'=>['field'=>'活动开始日期'],
            'act_end_date'=>['field'=>'活动结束日期'],
            'act_memo'=>['field'=>'活动说明'],
            'act_status'=>['field'=>'活动状态']];
        $valid = $this->validFields($array);
        if($valid['success'] !== true){
            writeJson($valid);
            return;
        }
        $post = $this->request->post;
        $act_product_ids = $post['act_product_ids'];
        $act_id = $post['act_id'];
        $duplicate_productids = [];
        if(is_array($act_product_ids) && sizeof($act_product_ids) > 0){
            foreach($act_product_ids as $act_product_id){
                if(
//                    is_valid($post['p_'.$act_product_id.'_act_price']) &&
//                    is_valid($post['p_'.$act_product_id.'_act_credit'])
//                isset($post['p_'.$act_product_id.'_act_credit'])
                 true
                ){
                    $dup =
                        $this->model_activity_special->isAlreadyInSpecial($act_product_id,1,$act_id);
                    if($dup == true){
                        $duplicate_productids[] = $act_product_id;
                    }
                }else{
                    writeJson(['success'=>false,'errMsg'=>'活动产品信息不为空！']);
                    return;
                }
            }
            if(sizeof($duplicate_productids) > 0){
                $duplicate_nos = $this->model_common_product->queryNosByIds($duplicate_productids);
                $msg = '部分产品已参加活动，不可添加[';
                foreach($duplicate_nos as $duplicate_no){
                    $msg.= $duplicate_no['product_no'].' ,';
                }

                $msg = substr($msg,0,strlen($msg)-1);
                $msg.=']';
                writeJson(['success'=>false,'errMsg'=>$msg]);
                return;
            }
        }else{
            writeJson(['success'=>false,'errMsg'=>'产品编码不为空！']);
            return;
        }
        if(is_valid($post['imgurl'])){
            $img_name = basename($post['imgurl']);
            $img_path = 'activity/special/main/'.str_replace('_temp','',$img_name);
            if(is_file(DIR_IMAGE.$img_path)){
                FileUtil::unlinkFile(DIR_IMAGE.$img_path);
            }
            $is_ok = FileUtil::moveFile(DIR_IMAGE.$post['imgurl'],DIR_IMAGE.$img_path);
            if($is_ok !== true){
                writeJson(['success'=>false,'errMsg'=>'上传图片失败！']);
                return;
            }
            $post['imgurl'] = $img_path;
        }
        $success = $this->model_activity_special->modifySpecial($post);
        if($success===true){
            writeJson(['success'=>true,'location'=> html_entity_decode(
                $this->url->link('activity/list', 'token=' . $this->session->data['token'])
            )]);
        }else{
            writeJson(['success'=>false,'errMsg'=>'修改活动失败！']);
        }
    }
    protected function getSpecial($lans) {
        $data = array();
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $link_mode = $this->request->get['link_mode'];
        if($link_mode=='create'){
            $special = ['promotion_id' => $this->model_activity_special->queryNextSpecialId()];
        }else if($link_mode=='modify'){
            $sub_id = $this->request->get['sub_id'];
            $special = $this->model_activity_special->querySpecialById($sub_id);
        }
        $data['link_mode'] = $link_mode;
        $data['act'] =$special;
        $data['producttypes'] = $this->model_activity_special->queryProductTypes();
        $data = array_merge($data,$lans);
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
    }

    public function getDateFromGivenPeriod($start, $end){
        $res = Array();
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        while ($dt_start<=$dt_end) {
            array_push($res, date('Y-m-d', $dt_start));
            $dt_start = strtotime('+1 day', $dt_start);
        }
        return $res;
    }

    // 获取无效活动时间
    public function getInvalidDate(){
        $this->load->model($this->module_name);
        $result = Array();
        // 获取所有活动的起始和结束时间
        $res = $this->model_activity_special->queryAllSpecial();
        // 分别计算无效活动时间
        for($i = 0; $i < count($res); $i++){
            $result = array_merge($result, $this->getDateFromGivenPeriod($res[$i]['starttime'],$res[$i]['endtime'])) ;
        }
        // 返回
        writeJson($result);
//        return $result;
    }

}