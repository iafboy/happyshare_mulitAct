<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <form id="credit-fm">
    <div class="container">
      <div class="row">
        <div class="col-sm-12" style="min-width: 100%;overflow: auto;">
          <!-- Base Part -->
          <table style="width: 100%;">
            <tr class="navi-row">
              <td class="navi-title"><span>活动基本信息</span></td>
              <td class="navi-content">
                <div>
                  <form>
                    <div>
                      <div style="float: left;width: 80%;">
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动名称：</label>
                          <span>
                            <input name="act_name" class="lfx-text" value="<?php echo $act['cp_name']; ?>" />
                          </span>
                        </span>
                        <span  class="entry-group">
                          <label>活动类型：</label>
                          <span>积分赠送/直减促销活动</span>
                        </span>
                        </div>
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动有效期：</label>
                          <span>
                            <div class="date" style="display: inline-block;">
                              <input style="display: inline-block;width: 80%;" type="text"
                                     name="act_start_date"
                                     value="<?php echo $act['starttime']; ?>"
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
                                     name="act_end_date"
                                     value="<?php echo $act['endtime']; ?>"
                                     data-date-format="YYYY-MM-DD" class="lfx-text" />
                              <span  style="width: 20%;">
                              <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </span>
                        </span>
                        <span  class="entry-group">
                          <label>活动编号：</label>
                          <span><?php echo $act['cp_id']; ?></span>
                        </span>
                        </div>
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动说明：</label><br />
                          <span>
                            <textarea style="width: 90%;height:100px;" class="lfx-text" name="act_memo"><?php echo $act['memo']; ?></textarea>
                          </span>
                        </span>
                        </div>
                      </div>
                      <div style="float:left;width: 20%;">
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动图片：</label>
                          <span>
                            <button id="main-image-btn" type="button"
                                    onclick="uploadMainImage('#main-image')" class="lfx-btn">上传/更新</button>
                          </span>
                        </span>
                        </div>
                        <div class="content-row">
                          <div>
                            <div style="width: 200px;height: 200px;">
                              <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:100%;height:100%;">
                                <?php
                                if(is_valid($act['imgurl'])){ ?>
                                <img id="main-image" class="image-button" width="100%" height="100%"
                                     src="<?php echo DIR_IMAGE_URL.$act['imgurl']; ?>"
                                     data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />
                                <?php }else{ ?>
                                <img id="main-image" class="image-button" width="100%" height="100%" src="<?php echo $to_add_img; ?>"
                                     data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />
                                <?php }
                             ?>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </td>
            </tr>
          </table>
          <!-- Picture Part -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>活动规则</span></td>
              <td class="navi-content">
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <label>
                      <input id="is_gift_credit" name="score" value="1" type="checkbox" checked />
                      <span style="margin-left: 5px;">赠送积分</span>
                    </label>
                    <span style="margin-left: 10px;">
                      <input name="gift_credit" class="lfx-text lfx-text-sm" type="number" value="<?php echo $act['giftcredit']; ?>" />
                      <span style="margin-left: 5px;">积分</span>
                    </span>
                  </span>
                  <span class="entry-group" style="margin-left: 10px;">
                    <label>
                      <input id="is_ext_gift" name="sup_score" value="1" type="checkbox" checked />
                      <span style="margin-left: 5px;">额外赠送发展该用户的直接上级积分</span>
                    </label>
                    <span style="margin-left: 10px;">
                      <input name="ext_gift" class="lfx-text lfx-text-sm" type="number"  value="<?php echo $act['extgift']; ?>" />
                      <span style="margin-left: 5px;">积分</span>
                    </span>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <label>
                      <input id="is_reducecash" name="money" value="1" type="checkbox" checked />
                      <span style="margin-left: 5px;">现金直减</span>
                    </label>
                    <span style="margin-left: 10px;">
                      <input name="reducecash" class="lfx-text lfx-text-sm" type="number" value="<?php echo $act['reducecash']; ?>" />
                      <span style="margin-left: 5px;">元</span>
                    </span>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 10px;">
                    <label>
                      <span style="margin-left: 5px;">赠送条件</span>
                    </label>
                  </span>
                  <span class="entry-group">
                    <label>
                      <input id="is_consumelimit" name="money_top" value="1" type="checkbox" checked />
                      <span style="margin-left: 5px;">消费金额满</span>
                    </label>
                    <span style="margin-left: 10px;">
                      <input name="consumelimit" class="lfx-text lfx-text-sm" type="number" value="<?php echo $act['consumelimit']; ?>" />
                      <span style="margin-left: 5px;">元</span>
                    </span>
                  </span>
                  <?php
                   if($act['consume_setting'].''==='0'){ ?>
                    <span class="entry-group" style="margin-right: 10px;display: inline-block;">
                    <span class="lfx-label st_1 consume_setting_on pointer ">
                      并且</span>
                    <span class="lfx-label st_1 consume_setting_off pointer lfx-label-on">
                      或者</span>
                  </span>
                   <?php }else if($act['consume_setting'].''==='1'){ ?>
                    <span class="entry-group" style="margin-right: 10px;display: inline-block;">
                    <span class="lfx-label st_1 consume_setting_on pointer lfx-label-on">
                      并且</span>
                    <span class="lfx-label st_1 consume_setting_off pointer">
                      或者</span>
                  </span>
                   <?php }
                   ?>
                  <span class="entry-group">
                    <label>
                      <?php
                       if($act['firstorder'].'' === '1'){ ?>
                      <input id="is_firstorder" name="firstorder" value="1" type="checkbox" checked />
                       <?php }else { ?>
                      <input id="is_firstorder" name="firstorder" value="1" type="checkbox" checked />
                       <?php }
                       ?>
                      <span style="margin-left: 5px;">用户首单</span>
                    </label>
                  </span>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <!--<div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn">审批通过直接上架</button>
        <button class="btn lfx-btn">审批通过，暂不上架</button>
        <button class="btn lfx-btn">审批不通过</button>
      </div>-->
      <?php if($link_mode == 'create'){ ?>
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="createAct(0)">提交并直接上架</button>
        <button class="btn lfx-btn" type="button" onclick="createAct(1)">提交暂不上架</button>
        <a href="<?php echo 'index.php?route=activity/credit&token='.$token.'&link_mode=create'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>

      </div>
      <?php }else if($link_mode == 'modify'){ ?>
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="saveAct(0)">保存并上架</button>
        <button class="btn lfx-btn" type="button" onclick="saveAct(1)">保存，暂不上架</button>
        <a href="<?php echo 'index.php?route=activity/credit&token='.$token.'&link_mode=modify'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>
      </div>
      <?php } ?>
    </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
  function createAct(status){
    var params = getParams();
    if(params===false){
      return;
    }
    params.act_status = status;
    var url = 'index.php?route=activity/credit/create&token=<?php echo $token; ?>';
    $.model.commonAjax(url,params,function(data){
      if(data.success){
        window.location = data.location;
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function saveAct(status){
    var params = getParams('modify');
    if(params===false){
      return;
    }
    params.act_status = status;
    params.act_id = '<?php echo $act["cp_id"]; ?>';
    var url = 'index.php?route=activity/credit/modify&token=<?php echo $token; ?>';
    $.model.commonAjax(url,params,function(data){
      if(data.success){
        window.location = data.location;
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function getParams(mode){
    mode = mode || 'create';
    var form = $('#credit-fm');
    var params = $(form).formJSON();
    var valid_arr = [
      {field:'act_name',required:true,errMsg:'活动名称不能为空！'},
      {field:'act_start_date',required:true,errMsg:'活动开始日期不能为空！'},
      {field:'act_end_date',required:true,errMsg:'活动结束日期不能为空！'},
      {field:'act_memo',required:true,errMsg:'活动说明不能为空！'},
      {field:'imgurl',required:mode=='create',errMsg:'活动图片不能为空！'}
    ];
    if(validFormParams(params,valid_arr)!==true){
      return false;
    }
    if($('#is_gift_credit:checked').length==0){
      delete params.gift_credit;
    }
    if($('#is_ext_gift:checked').length==0){
      delete params.ext_gift;
    }
    if($('#is_reducecash:checked').length==0){
      delete params.reducecash;
    }
    if($('#is_consumelimit:checked').length==0){
      delete params.consumelimit;
    }
    if($('#is_firstorder:checked').length==0){
      params.firstorder = 0;
    }else{
      params.firstorder = 1;
    }
    if($('.st_1.lfx-label-on.consume_setting_on').length==1){
      params.consume_settings = 1;
    }else if($('.st_1.lfx-label-on.consume_setting_off').length==1){
      params.consume_settings = 0;
    }
    return params;
  }
  function changeMainImage(image){
    image = $(image);
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/credit/main';
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["cp_id"]; ?>_main_temp',resize_width:200,resize_height:200,delete_path:old_image}
            ,function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image_path'];
                var html =
                  '<img id="main-image" class="image-button" width="100%" height="100%" \
                  src="'+path+'?t='+new Date().getTime()+'" onclick="changeMainImage(this)" /> \
                  <input name="imgurl" value="'+image_path+'" type="hidden" />';
                $(image).parent().empty().append(html);
              }else{
                showErrorText(data.error);
              }
            });
  }
  function uploadMainImage(image){
    if($('#main-image-btn').data('hasImage')===true){
      changeMainImage(image);
      return;
    }
    image = $(image);
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/credit/main';
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["cp_id"]; ?>_main_temp',resize_width:200,resize_height:200}
            ,function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image_path'];
                var html =
                        '<img id="main-image" class="image-button" width="100%" height="100%" \
                        src="'+path+'?t='+new Date().getTime()+'" onclick="changeMainImage(this)" /> \
                        <input name="imgurl" value="'+image_path+'" type="hidden" />';
                $(image).parent().empty().append(html);
                $('#main-image-btn').data('hasImage',true);
              }else{
                showErrorText(data.error);
              }
            });
  }
  $('span.st_1').on('click',function(){
    $(this).parent().find('span.st_1').removeClass('lfx-label-on');
    $(this).addClass('lfx-label-on');
  });
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

  .navi-row .navi-content .image-container-sm{
    background-color: grey;margin-right:5px;width: 150px;height:150px;display: inline-block;
    float: left;
  }
  .navi-row .navi-content.img-content .image-container{
    background-color: grey;margin-right:5px;width: 200px;height:200px;display: inline-block;
    float: left;
  }
  .navi-row .navi-content.img-content .pic-mavi:hover{
    color: #00b3ee;
  }
  .navi-row .navi-content.img-content .pic-mavi{
    position: absolute;top:10px;
    cursor: pointer;
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
  .navi-row .navi-content .content-row .label-me{
    background-color: #fff;
    border: 1px solid #e4e4e4;
    color: #999;
    font-weight: normal;
  }
  .navi-row .navi-content .content-row{
    margin-bottom: 10px;
  }
</style>
