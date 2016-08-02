<?php
class ControllerScoreRule extends MyController {

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

    private $module_name = 'score/rule';

    public function index() {
        $lans = $this->load->language($this->module_name);
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model($this->module_name);
        $this->getCredit($lans);
    }
    protected function getCredit($lans) {
        $data = array();
        $data['credit'] = $this->model_score_rule->queryCredit();
        $data = array_merge($data,$lans);
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
    }

    public function delRule(){
        $this->load->model($this->module_name);
        $seq = $this->request->post['seq'];
        $success = $this->model_score_rule->delCreditRule($seq);
        if($success===true){
            writeJson(['success'=>true]);
        }else{
            writeJson(['success'=>false,'errMsg'=>'删除失败！']);
        }
    }

    public function addOrSave(){
        $this->load->model($this->module_name);
        $array= [
            'earnCreditLv1'=>['field'=>'积分入账规则参数'],
            'earnCreditLv2'=>['field'=>'积分入账规则参数'],
            'earnCreditLv3'=>['field'=>'积分入账规则参数'],
            'buyCreditLimitLastMonth'=>['field'=>'积分入账规则参数'],
            'buyCreditLimitThisMonth'=>['field'=>'积分入账规则参数'],
            'shareCreditLimitLastMonth'=>['field'=>'积分入账规则参数'],
            'shareCreditLimitThisMonth'=>['field'=>'积分入账规则参数'],
/*            'bonusCreditLimitLastMonth'=>['field'=>'积分入账规则参数'],
            'bonusCreditLimitThisMonth'=>['field'=>'积分入账规则参数'],*/
            'status'=>['field'=>'状态']];
        $valid = $this->validFields($array);
        if($valid['success'] !== true){
            writeJson($valid);
            return;
        }
        $post = $this->request->post;
        $seqs = $post['seqs'];
        if(isset($seqs) && is_array($seqs) && sizeof($seqs) > 0){
        }else{
            writeJson(['success'=>false,'errMsg'=>'参数错误！']);
            return;
        }
        foreach($seqs as $k => $v){
            if(!is_valid($post['is_r_'.$v.'_'.'creditForBuyThresholdOnCredit'])){
                $post['is_r_'.$v.'_'.'creditForBuyThresholdOnCredit'] = 0;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditForBuyThresholdOnCredit'])){
                $post['r_'.$v.'_'.'creditForBuyThresholdOnCredit'] = 0;
            }
            if(!is_valid($post['is_r_'.$v.'_'.'creditForBuyThresholdOnUser'])){
                $post['is_r_'.$v.'_'.'creditForBuyThresholdOnUser'] = 0;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditForBuyThresholdOnUser'])){
                $post['r_'.$v.'_'.'creditForBuyThresholdOnUser'] = 0;
            }
            if(!is_valid($post['is_r_'.$v.'_'.'creditForWithdrawThresholdOnCredit'])){
                $post['is_r_'.$v.'_'.'creditForWithdrawThresholdOnCredit'] = 0;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditForWithdrawThresholdOnCredit'])){
                $post['r_'.$v.'_'.'creditForWithdrawThresholdOnCredit'] = 0;
            }
            if(!is_valid($post['is_r_'.$v.'_'.'creditForWithdrawThresholdOnUser'])){
                $post['is_r_'.$v.'_'.'creditForWithdrawThresholdOnUser'] = 0;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditForWithdrawThresholdOnUser'])){
                $post['r_'.$v.'_'.'creditForWithdrawThresholdOnUser'] = 0;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditToManeyRate'])){
                $post['r_'.$v.'_'.'creditToManeyRate'] = 0;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditRuleValidDateStart'])){
                writeJson(['success'=>false,'errMsg'=>'使用规则开始日期不为空']);
                return;
            }
            if(!is_valid($post['r_'.$v.'_'.'creditRuleValidDateStart'])){
                writeJson(['success'=>false,'errMsg'=>'使用规则结束日期不为空']);
                return;
            }
        }
        $success = $this->model_score_rule->addOrModify($post);
        if($success===true){
            writeJson(['success'=>true]);
        }else{
            writeJson(['success'=>false,'errMsg'=>'保存失败！']);
        }
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
        $res = $this->model_score_rule->queryAllRules();
        // 分别计算无效活动时间
        for($i = 0; $i < count($res); $i++){
            $result = array_merge($result, $this->getDateFromGivenPeriod($res[$i]['creditRuleValidDateStart'],$res[$i]['creditRuleValidDateEnd'])) ;
        }
        // 返回
        writeJson($result);
//        return $result;
    }
}