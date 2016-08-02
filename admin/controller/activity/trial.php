<?php
class ControllerActivityTrial extends MyController {

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

    private $module_name = 'activity/trial';

    public function index() {
        $lans = $this->load->language($this->module_name);
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model($this->module_name);
        $this->getTrial($lans);
    }

    public function create(){
        $this->load->model($this->module_name);
        $array= [
            'act_name'=>['field'=>'活动名称'],
            'act_start_date'=>['field'=>'活动开始日期'],
            'act_end_date'=>['field'=>'活动结束日期'],
            'act_memo'=>['field'=>'活动说明'],
            'imgurl'=>['field'=>'活动图片'],
            'act_status'=>['field'=>'活动状态']];
        $valid = $this->validFields($array);
        if($valid['success'] !== true){
            writeJson($valid);
            return;
        }
        $post = $this->request->post;
        $img_name = basename($post['imgurl']);
        $img_path = 'activity/trial/main/'.str_replace('_temp','',$img_name);
        if(is_file(DIR_IMAGE.$img_path)){
            FileUtil::unlinkFile(DIR_IMAGE.$img_path);
        }
        $is_ok = FileUtil::moveFile(DIR_IMAGE.$post['imgurl'],DIR_IMAGE.$img_path);
        if($is_ok !== true){
            writeJson(['success'=>false,'errMsg'=>'上传图片失败！']);
            return;
        }
        $post['imgurl'] = $img_path;
        $success = $this->model_activity_trial->createTrial($post);
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
        if(is_valid($post['imgurl'])){
            $img_name = basename($post['imgurl']);
            $img_path = 'activity/trial/main/'.str_replace('_temp','',$img_name);
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
        $success = $this->model_activity_trial->modifyTrial($post);
        if($success===true){
            writeJson(['success'=>true,'location'=> html_entity_decode(
                $this->url->link('activity/list', 'token=' . $this->session->data['token'])
            )]);
        }else{
            writeJson(['success'=>false,'errMsg'=>'修改活动失败！']);
        }
    }

    protected function getTrial($lans) {
        $data = array();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $link_mode = $this->request->get['link_mode'];
        if($link_mode=='create'){
            $trial = ['tp_id' => $this->model_activity_trial->queryNextTrialId()];
        }else if($link_mode=='modify'){
            $sub_id = $this->request->get['sub_id'];
            $trial = $this->model_activity_trial->queryTrialById($sub_id);
        }
        $data['link_mode'] = $link_mode;
        $data['act'] =$trial;
        $data = array_merge($data,$lans);
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
    }
}