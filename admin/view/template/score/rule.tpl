<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) {
          if($breadcrumb['type']=='link'){
            echo "<li><a href=".$breadcrumb['href'].">".$breadcrumb['text']."</a></li>";
          }else{
            echo "<li><span class='breadcrumb-cur'>".$breadcrumb['text']."</span></li>";
          }
        } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="container">
      <form id="credit-fm">
      <div class="row">
        <div class="col-sm-12" style="min-width: 100%;overflow: auto;">
          <!-- Base Part -->
          <table style="width: 100%;">
            <tr class="navi-row">
              <td rowspan="2" class="navi-title"><span>积分分级回馈规则设置</span></td>
              <td colspan="2" class="navi-content" style="padding: 0px;">
                <div class="content-row level-container" style="padding:10px;">
                  <table>
                    <tr>
                      <td>
                        <div class="circle-title">
                          <span class="circle-text">A</span>
                        </div>
                      </td>
                      <td><span class="fa fa-long-arrow-right" style="font-size: 20px"></span></td>
                      <td>
                        <div class="circle-title">
                          <span class="circle-text">B</span>
                        </div>
                      </td>
                      <td><span class="fa fa-long-arrow-right" style="font-size: 20px"></span></td>
                      <td>
                        <div class="circle-title">
                          <span class="circle-text">C</span>
                        </div>
                      </td>
                      <td><span class="fa fa-long-arrow-right" style="font-size: 20px"></span></td>
                      <td>
                        <div class="circle-title">
                          <span class="circle-text">D</span>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>B消费回馈给A的比率</td>
                      <td></td>
                      <td>C消费回馈给A的比率</td>
                      <td></td>
                      <td>D消费回馈给A的比率</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>
                        <span><input name="earnCreditLv1" value="<?php echo $credit['earnCreditLv1']; ?>" type="number" class="lfx-text lfx-text-xs" />%</span>
                      </td>
                      <td></td>
                      <td>
                        <span><input name="earnCreditLv2" value="<?php echo $credit['earnCreditLv2']; ?>" type="number" class="lfx-text lfx-text-xs" />%</span>
                      </td>
                      <td></td>
                      <td>
                        <span><input name="earnCreditLv3" value="<?php echo $credit['earnCreditLv3']; ?>" type="number" class="lfx-text lfx-text-xs" />%</span>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
            <tr class="navi-row" style="border-bottom: 1px solid #e4e4e4;">
              <td class="navi-title" style="width: 24px;"><span style="font-size: 12px;">积分入账规则设置</span></td>
              <td class="navi-content" style="padding: 0px;">
                <div class="content-row" style="padding: 10px;">
                  <div class="content-row">
                    <span class="entry-group">
                      <label>用户上月消费积分+奖励积分达到：</label>
                      <span style="margin-left: 10px;">
                        <input name="buyCreditLimitLastMonth" value="<?php echo $credit['buyCreditLimitLastMonth']; ?>" class="lfx-text lfx-text-sm" type="number" />
                        <span style="margin-left: 5px;">,消费积分入账;</span>
                      </span>
                    </span>
                    <span class="entry-group">
                      <label>如果上月消费不达标，则本月达到：</label>
                      <span style="margin-left: 10px;">
                        <input name="buyCreditLimitThisMonth" value="<?php echo $credit['buyCreditLimitThisMonth']; ?>" class="lfx-text lfx-text-sm" type="number" />
                        <span style="margin-left: 5px;">,上月消费积分入账;</span>
                      </span>
                    </span>
                  </div>
                  <div class="content-row">
                    <span class="entry-group">
                      <label>用户上月消费积分+奖励积分达到：</label>
                      <span style="margin-left: 10px;">
                        <input name="shareCreditLimitLastMonth" value="<?php echo $credit['shareCreditLimitLastMonth']; ?>" class="lfx-text lfx-text-sm" type="number" />
                        <span style="margin-left: 5px;">,分享积分入账;</span>
                      </span>
                    </span>
                    <span class="entry-group">
                      <label>如果上月消费不达标，则本月达到：</label>
                      <span style="margin-left: 10px;">
                        <input name="shareCreditLimitThisMonth" value="<?php echo $credit['shareCreditLimitThisMonth']; ?>" class="lfx-text lfx-text-sm" type="number" />
                        <span style="margin-left: 5px;">,上月分享积分入账;</span>
                      </span>
                    </span>
                  </div>
                 <!-- <div class="content-row">
                    <span class="entry-group">
                      <label>用户上月消费积分+奖励积分达到：</label>
                      <span style="margin-left: 10px;">
                        <input name="bonusCreditLimitLastMonth" value="<?php echo $credit['bonusCreditLimitLastMonth']; ?>" class="lfx-text lfx-text-sm" type="number" />
                        <span style="margin-left: 5px;">,奖励积分入账;</span>
                      </span>
                    </span>
                    <!<span class="entry-group">
                      <label>如果上月消费不达标，则本月达到：</label>
                      <span style="margin-left: 10px;">
                        <input name="bonusCreditLimitThisMonth" value="<?php echo $credit['bonusCreditLimitThisMonth']; ?>" class="lfx-text lfx-text-sm" type="number" />
                        <span style="margin-left: 5px;">,上月奖励积分入账;</span>
                      </span>
                    </span>
                  </div>
                  <div class="content-row">
                    <div class="entry-group" style="text-align: center;">
                      <span>
                        <button class="lfx-btn" style="margin-right:20px;">确认</button>
                      </span>
                    </div>
                  </div>
                </div>-->
              </td>
            </tr>
          </table>
          <!-- Picture Part -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title" rowspan="2"><span>积分使用规则设置</span></td>
              <td class="navi-content rule-row-box">
                <?php
                  if( sizeof($credit['rules']) > 0){
                    $seq = 0;
                    foreach($credit['rules'] as $rule){
                      $seq = $rule['seq']; ?>
                      <div class="rule-row" data-rule-index="<?php echo $rule['seq']; ?>">
                        <a class="fa fa-fw fa-remove btn-remove" onclick="delCreditRule(this,'<?php echo $rule['seq']; ?>')"></a>
                        <div class="content-row">
                          <span class="entry-group">
                            <label>积分用于自己购物的使用限制:</label>
                          </span>
                          <span class="entry-group" style="margin-right: 0px;">
                            <label>
                              <input name="is_r_<?php echo $seq; ?>_creditForBuyThresholdOnCredit" id="is_r_<?php echo $seq; ?>_creditForBuyThresholdOnCredit" value="1" type="checkbox"
                                     <?php echo $rule['creditForBuyThresholdOnCredit'] > 0 ?"checked":""; ?> />
                              <span style="margin-left: 5px;">自己累积消费达到</span>
                            </label>
                            <span style="margin-left: 10px;">
                              <input name="r_<?php echo $seq; ?>_creditForBuyThresholdOnCredit"  class="lfx-text lfx-text-sm" type="number"
                                     value="<?php echo $rule['creditForBuyThresholdOnCredit']; ?>" />
                              <span style="margin-left: 5px;">元</span>
                            </span>
                          </span>
                          <span class="entry-group" style="margin-left: 10px;">
                            <label>
                              <input name="is_r_<?php echo $seq; ?>_creditForBuyThresholdOnUser" id="is_r_<?php echo $seq; ?>_creditForBuyThresholdOnUser" value="1" type="checkbox"
                              <?php echo $rule['creditForBuyThresholdOnUser'] > 0 ?"checked":""; ?> />
                              <span style="margin-left: 5px;">发展用户达到:</span>
                            </label>
                            <span style="margin-left: 10px;">
                              <input name="r_<?php echo $seq; ?>_creditForBuyThresholdOnUser" class="lfx-text lfx-text-sm" type="number"
                                     value="<?php echo $rule['creditForBuyThresholdOnUser']; ?>" />
                              <span style="margin-left: 5px;">人</span>
                            </span>
                          </span>
                        </div>
                        <div class="content-row">
                          <span class="entry-group">
                            <label>积分提现限制:</label>
                          </span>
                          <span class="entry-group" style="margin-right: 0px;">
                            <label>
                              <input name="is_r_<?php echo $seq; ?>_creditForWithdrawThresholdOnCredit" id="is_r_<?php echo $seq; ?>_creditForWithdrawThresholdOnCredit" value="1" type="checkbox"
                              <?php echo $rule['creditForWithdrawThresholdOnCredit'] > 0 ?"checked":""; ?> />
                              <span style="margin-left: 5px;">自己累积消费达到</span>
                            </label>
                            <span style="margin-left: 10px;">
                              <input name="r_<?php echo $seq; ?>_creditForWithdrawThresholdOnCredit" class="lfx-text lfx-text-sm" type="number"
                                     value="<?php echo $rule['creditForWithdrawThresholdOnCredit']; ?>"  />
                              <span style="margin-left: 5px;">元</span>
                            </span>
                          </span>
                          <span class="entry-group" style="margin-left: 10px;">
                            <label>
                              <input name="is_r_<?php echo $seq; ?>_creditForWithdrawThresholdOnUser" id="is_r_<?php echo $seq; ?>_creditForWithdrawThresholdOnUser" value="1" type="checkbox"
                              <?php echo $rule['creditForWithdrawThresholdOnUser'] > 0 ?"checked":""; ?> />
                              <span style="margin-left: 5px;">发展用户达到:</span>
                            </label>
                            <span style="margin-left: 10px;">
                              <input name="r_<?php echo $seq; ?>_creditForWithdrawThresholdOnUser" class="lfx-text lfx-text-sm" type="number"
                                     value="<?php echo $rule['creditForWithdrawThresholdOnUser']; ?>"  />
                              <span style="margin-left: 5px;">人</span>
                            </span>
                          </span>
                        </div>
                        <div class="content-row">
                          <span class="entry-group" style="margin-right: 0px;">
                            <label>
                              <span>积分提现折扣率</span>
                            </label>
                            <span style="margin-left: 10px;">
                              <input name="r_<?php echo $seq; ?>_creditToManeyRate" class="lfx-text lfx-text-sm" type="number"
                                     value="<?php echo $rule['creditToManeyRate']; ?>" />
                              <span style="margin-left: 5px;">%</span>
                            </span>
                          </span>
                        </div>
                        <div class="content-row">
                          <span class="entry-group" style="margin-right: 10px;">
                            <label>活动有效期：</label>
                            <span>
                              <div class="date" style="display: inline-block;">
                                <input style="display: inline-block;width: 80%;" type="text"
                                       name="r_<?php echo $seq; ?>_creditRuleValidDateStart"
                                       value="<?php echo $rule['creditRuleValidDateStart']; ?>"
                                       data-date-format="YYYY-MM-DD" class="lfx-text" />
                                <span  style="width: 20%;">
                                <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
                            </span>
                            -
                            <span>
                              <div class="date" style="display: inline-block;">
                                <input style="display: inline-block;width: 80%;" type="text"
                                       name="r_<?php echo $seq; ?>_creditRuleValidDateEnd"
                                       value="<?php echo $rule['creditRuleValidDateEnd']; ?>"
                                       data-date-format="YYYY-MM-DD" class="lfx-text" />
                                <span  style="width: 20%;">
                                <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
                            </span>
                          </span>
                        </div>
                      </div>
                    <?php }
                }else { ?>
                  <div class="rule-row" data-rule-index="1">
                    <div class="content-row">
                      <span class="entry-group">
                        <label>积分用于自己购物的使用限制:</label>
                      </span>
                      <span class="entry-group" style="margin-right: 0px;">
                        <label>
                          <input name="is_r_1_creditForBuyThresholdOnCredit" id="is_r_1_creditForBuyThresholdOnCredit" value="1" type="checkbox" />
                          <span style="margin-left: 5px;">自己累积消费达到</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <input name="r_1_creditForBuyThresholdOnCredit" class="lfx-text lfx-text-sm" type="number" />
                          <span style="margin-left: 5px;">元</span>
                        </span>
                      </span>
                      <span class="entry-group" style="margin-left: 10px;">
                        <label>
                          <input name="is_r_1_creditForBuyThresholdOnUser" id="is_r_1_creditForBuyThresholdOnUser" value="1" type="checkbox" />
                          <span style="margin-left: 5px;">发展用户达到:</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <input name="r_1_creditForBuyThresholdOnUser" class="lfx-text lfx-text-sm" type="number" />
                          <span style="margin-left: 5px;">人</span>
                        </span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>积分提现限制:</label>
                      </span>
                      <span class="entry-group" style="margin-right: 0px;">
                        <label>
                          <input name="is_r_1_creditForWithdrawThresholdOnCredit" id="is_r_1_creditForWithdrawThresholdOnCredit" value="1" type="checkbox" />
                          <span style="margin-left: 5px;">自己累积消费达到</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <input name="r_1_creditForWithdrawThresholdOnCredit" class="lfx-text lfx-text-sm" type="number" />
                          <span style="margin-left: 5px;">元</span>
                        </span>
                      </span>
                      <span class="entry-group" style="margin-left: 10px;">
                        <label>
                          <input name="is_r_1_creditForWithdrawThresholdOnUser" id="is_r_1_creditForWithdrawThresholdOnUser" value="1" type="checkbox" />
                          <span style="margin-left: 5px;">发展用户达到:</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <input name="r_1_creditForWithdrawThresholdOnUser" class="lfx-text lfx-text-sm" type="number" />
                          <span style="margin-left: 5px;">人</span>
                        </span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group" style="margin-right: 0px;">
                        <label>
                          <span>积分提现折扣率</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <input name="r_1_creditToManeyRate" class="lfx-text lfx-text-sm" type="number" />
                          <span style="margin-left: 5px;">%</span>
                        </span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group" style="margin-right: 10px;">
                        <label>活动有效期：</label>
                        <span>
                          <div class="date" style="display: inline-block;">
                            <input style="display: inline-block;width: 80%;" type="text"
                                   name="r_1_creditRuleValidDateStart"
                                   data-date-format="YYYY-MM-DD" class="lfx-text" />
                            <span  style="width: 20%;">
                            <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                          </div>
                        </span>
                        -
                        <span>
                          <div class="date" style="display: inline-block;">
                            <input style="display: inline-block;width: 80%;" type="text"
                                   name="r_1_creditRuleValidDateEnd"
                                   data-date-format="YYYY-MM-DD" class="lfx-text" />
                            <span  style="width: 20%;">
                            <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                          </div>
                        </span>
                      </span>
                    </div>
                </div>
                <?php } ?>
              </td>
            </tr>
            <tr class="navi-row">
              <td class="navi-content">
                <div class="operation-row">
                  <div class="content-row">
                    <div class="entry-group" style="text-align: center;">
                    <span>
                      <button class="lfx-btn btn btn-sm" type="button" onclick="addRule()">新增规则</button>
                    </span>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      </form>
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="saveCredit(1)">保存并上架</button>
        <button class="btn lfx-btn" type="button" onclick="saveCredit(0)">保存,不上架</button>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">

  function saveCredit(status){
    var params = getParams();
    if(params==false){
      return;
    }
    params.status = status;
    var url = 'index.php?route=score/rule/addOrSave&token=<?php echo $token; ?>';
    $.model.commonAjax(url,params,function(data){
      if(data.success){
        return showSuccessText('保存成功!');
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function getParams(){
    var form = $('#credit-fm');
    var params = $(form).formJSON();
    var valid_arr = [
      {field:'earnCreditLv1',required:true,errMsg:'回馈比率不为空！'},
      {field:'earnCreditLv2',required:true,errMsg:'回馈比率不为空！'},
      {field:'earnCreditLv3',required:true,errMsg:'回馈比率不为空！'},
      {field:'buyCreditLimitLastMonth',required:true,errMsg:'积分入账规则参数不为空！'},
      {field:'buyCreditLimitThisMonth',required:true,errMsg:'积分入账规则参数不为空！'},
      {field:'shareCreditLimitLastMonth',required:true,errMsg:'积分入账规则参数不为空！'},
      {field:'shareCreditLimitThisMonth',required:true,errMsg:'积分入账规则参数不为空！'}
    ];
    if(validFormParams(params,valid_arr)!==true){
      return false;
    }
    //rules
//    var count = $('.rule-row-box > .rule-row').length;
    var seqs = [];
    $('.rule-row-box > .rule-row').each(function () {
      var that = this;
      var seq = $(that).data('rule-index');
      seqs.push(seq);
    });
    if(seqs.length == 0){
      return showErrorText('积分规则未填写！');
    }
    params['seqs[]'] = seqs;
    console.log(params);
      for(var i = 0;i < seqs.length;i ++){
        var _seq = seqs[i];
        if($("#is_r_"+_seq+"_"+"creditForBuyThresholdOnCredit:checked").length==0){
          delete params["r_"+_seq+"_"+"creditForBuyThresholdOnCredit"]
        }
        if($("#is_r_"+_seq+"_"+"creditForBuyThresholdOnUser:checked").length==0){
          delete params["r_"+_seq+"_"+"creditForBuyThresholdOnUser"]
        }
        if($("#is_r_"+_seq+"_"+"creditForWithdrawThresholdOnCredit:checked").length==0){
          delete params["r_"+_seq+"_"+"creditForWithdrawThresholdOnCredit"]
        }
        if($("#is_r_"+_seq+"_"+"creditForWithdrawThresholdOnUser:checked").length==0){
          delete params["r_"+_seq+"_"+"creditForWithdrawThresholdOnUser"]
        }
        if(!is_valid_str(params["r_"+_seq+"_"+"creditToManeyRate"])){
          return showErrorText('积分提现折扣率不为空!');
        }
        if(!is_valid_str(params["r_"+_seq+"_"+"creditRuleValidDateStart"])){
          return showErrorText('使用规则开始日期不为空!');
        }
        if(!is_valid_str(params["r_"+_seq+"_"+"creditRuleValidDateEnd"])){
          return showErrorText('使用规则结束日期不为空!');
        }
      }
    return params;
  }
  function delCreditRule(obj,seq){
    var win = confirmWideHtmlWin('提示','确定删除么？',function() {
      var url = 'index.php?route=score/rule/delRule&token=<?php echo $token; ?>';
      $.model.commonAjax(url,{seq:seq}, function (data) {
        if(data.success === true){
          win.close();
          $(obj).parent().remove();
          return showSuccessText('保存成功!');
        }else{
          return showErrorText(data.errMsg);
        }
      });
    });
  }

  function activateCalendar(){
    $('.date').datetimepicker({
      pickTime: false
    });

    $('.time').datetimepicker({
      pickDate: false
    });

    $('.datetime').datetimepicker({
      pickDate: true,
      pickTime: true
    });
    var url = 'index.php?route=score/rule/getInvalidDate&token=<?php echo $token; ?>';

    $.model.commonAjax(url,Array(),function(data){
      var timestr = new Array();
      for(var i = 0; i < data.length; i++){
        timestr.push(data[i]);
      }
//    $('.date').data("DateTimePicker").setDisabledDates(timestr);
      var dates = $('.date');
      $.each(dates,function (index,domEle) {
        $(domEle).data("DateTimePicker").setDisabledDates(timestr);
      });
    });
  }

  function addRule(){
    var index = $('.rule-row-box > .rule-row').last().data('rule-index')+1;
    var html = '<div class="rule-row" data-rule-index="'+index+'"> \
              <a class="fa fa-fw fa-remove btn-remove" onclick="delCreditRule(this,\''+index+'\')"></a> \
            <div class="content-row"> \
            <span class="entry-group"> \
            <label>积分用于自己购物的使用限制:</label> \
    </span> \
    <span class="entry-group" style="margin-right: 0px;"> \
            <label> \
            <input name="is_r_'+index+'_creditForBuyThresholdOnCredit" id="is_r_'+index+'_creditForBuyThresholdOnCredit" value="1" type="checkbox" /> \
            <span style="margin-left: 5px;">自己累积消费达到</span> \
            </label> \
            <span style="margin-left: 10px;"> \
            <input name="r_'+index+'_creditForBuyThresholdOnCredit" class="lfx-text lfx-text-sm" type="number" /> \
            <span style="margin-left: 5px;">元</span> \
            </span> \
            </span> \
            <span class="entry-group" style="margin-left: 10px;"> \
            <label> \
            <input name="is_r_'+index+'_creditForBuyThresholdOnUser" id="is_r_'+index+'_creditForBuyThresholdOnUser" value="1" type="checkbox" /> \
            <span style="margin-left: 5px;">发展用户达到:</span> \
    </label> \
    <span style="margin-left: 10px;"> \
            <input name="r_'+index+'_creditForBuyThresholdOnUser" class="lfx-text lfx-text-sm" type="number" /> \
            <span style="margin-left: 5px;">人</span> \
            </span> \
            </span> \
            </div> \
            <div class="content-row"> \
            <span class="entry-group"> \
            <label>积分提现限制:</label> \
    </span> \
    <span class="entry-group" style="margin-right: 0px;"> \
            <label> \
            <input name="is_r_'+index+'_creditForWithdrawThresholdOnCredit" id="is_r_'+index+'_creditForWithdrawThresholdOnCredit" value="1" type="checkbox" /> \
            <span style="margin-left: 5px;">自己累积消费达到</span> \
            </label> \
            <span style="margin-left: 10px;"> \
            <input name="r_'+index+'_creditForWithdrawThresholdOnCredit" class="lfx-text lfx-text-sm" type="number" /> \
            <span style="margin-left: 5px;">元</span> \
            </span> \
            </span> \
            <span class="entry-group" style="margin-left: 10px;"> \
            <label> \
            <input name="is_r_'+index+'_creditForWithdrawThresholdOnUser" id="is_r_'+index+'_creditForWithdrawThresholdOnUser" value="1" type="checkbox" /> \
            <span style="margin-left: 5px;">发展用户达到:</span> \
    </label> \
    <span style="margin-left: 10px;"> \
            <input name="r_'+index+'_creditForWithdrawThresholdOnUser" class="lfx-text lfx-text-sm" type="number" /> \
            <span style="margin-left: 5px;">人</span> \
            </span> \
            </span> \
            </div> \
            <div class="content-row"> \
            <span class="entry-group" style="margin-right: 0px;"> \
            <label> \
            <span>积分提现折扣率</span> \
            </label> \
            <span style="margin-left: 10px;"> \
            <input name="r_'+index+'_creditToManeyRate" class="lfx-text lfx-text-sm" type="number" /> \
            <span style="margin-left: 5px;">%</span> \
            </span> \
            </span> \
            </div> \
            <div class="content-row"> \
            <span class="entry-group" style="margin-right: 10px;"> \
            <label>活动有效期：</label> \
    <span> \
    <div class="date" style="display: inline-block;"> \
            <input style="display: inline-block;width: 80%;" type="text" \
    name="r_'+index+'_creditRuleValidDateStart" \
    data-date-format="YYYY-MM-DD" class="lfx-text" /> \
            <span  style="width: 20%;"> \
            <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button> \
    </span> \
    </div> \
    </span> \
    - \
    <span> \
    <div class="date" style="display: inline-block;"> \
            <input style="display: inline-block;width: 80%;" type="text" \
    name="r_'+index+'_creditRuleValidDateEnd" \
    data-date-format="YYYY-MM-DD" class="lfx-text" /> \
            <span  style="width: 20%;"> \
            <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button> \
    </span> \
    </div> \
    </span> \
    </span> \
    </div> \
    </div>';
    $('.rule-row-box').append(html);
//    $('.date').datetimepicker({
//      pickTime: false
//    });
//
//    $('.time').datetimepicker({
//      pickDate: false
//    });
//
//    $('.datetime').datetimepicker({
//      pickDate: true,
//      pickTime: true
//    });
    activateCalendar();
  }
  activateCalendar();

</script>
<style>
  .navi-row > div{
    display: inline-block;
    padding: 10px;
  }
  .navi-row{
    border: 1px solid #e4e4e4;
    min-width: 100%;
  }
  .navi-row .navi-title{
    width:30px;
    font-size: 16px;
    line-height:30px;
    border-right: 1px solid #e4e4e4;
    text-align: center;
  }

  .navi-row .navi-content .rule-row a{
    font-size: 24px;
    position: absolute;
    right: 0px;
    top: 0px;
    color: #f77;
    cursor: pointer;
  }
  .navi-row .navi-content .rule-row a:hover{
    color: #f55;
  }
  .navi-row .navi-content .rule-row{
    border-bottom: 1px solid #e4e4e4;
    margin-bottom: 10px;
    position: relative;
  }
  .navi-row .navi-content.img-content a
  {
    background-color: grey;margin-right:5px;width: 200px;height:200px;display: inline-block;
  }
  .navi-row .navi-content{
    vertical-align: top;
    padding: 10px;
  }
  .navi-row .navi-content .entry-group{
    margin-right: 20px;
  }
  .navi-row .navi-content .content-row{
    margin-bottom: 10px;
  }
  .level-container{

  }
  .level-container table{
    width: 100%;
  }
  .level-container table tr td{
    text-align: center;
  }
  .level-container .circle-title{
    width: 30px;height: 30px;border:1px solid #e4e4e4;
    -webkit-border-radius: 30px;-moz-border-radius: 30px;border-radius: 30px;
    text-align: center;display: inline-block;
  }
  .level-container .circle-title .circle-text{
    text-align: center;
    width: 28px;height: 28px;display: inline-block;text-align: center;line-height: 28px;font-size: 20px;
  }

</style>