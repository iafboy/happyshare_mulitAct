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
      <form id="gift-fm">
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
                            <input name="act_name" class="lfx-text" value="<?php echo $act['gp_name']; ?>" />
                          </span>
                        </span>
                        <span  class="entry-group">
                          <label>活动类型：</label>
                          <span>后台赠送积分活动</span>
                        </span>
                        </div>
                        <div class="content-row">
                        <span  class="entry-group">
                          <label>活动编号：</label>
                          <span><?php echo $act['gp_id']; ?></span>
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
                  <span class="entry-group">
                    <button class="lfx-btn" type="button" onclick="importCustomerList()">导入赠送账号列表</button>
                    <button class="lfx-btn" type="button" onclick="renderWindowTipsHtml()">查看赠送账号列表</button>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <label>
                      <?php
                       if($act['is_gift_credit'].''==='1'){ ?>
                        <input id="is_gift_credit" name="is_gift_credit" value="1" type="checkbox" checked />
                       <?php }else{ ?>
                        <input id="is_gift_credit" name="is_gift_credit" value="1" type="checkbox" />
                       <?php }
                       ?>
                      <span style="margin-left: 5px;">赠送积分</span>
                    </label>
                    <span style="margin-left: 10px;">
                      <input name="gift_credit" class="lfx-text lfx-text-sm" type="number" value="<?php echo $act['gift_credit']; ?>" />
                      <span style="margin-left: 5px;">积分</span>
                    </span>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <label>
                      <span style="margin-left: 5px;">积分来源文案:</span>
                    </label>
                    <span style="margin-left: 10px;">
                      <input name="description" class="lfx-text lfx-text-lg" value="<?php echo $act['description']; ?>" />
                    </span>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <label>
                      <span style="margin-left: 5px;">积分到账时间:</span>
                    </label>
                    <span>
                      <div class="date" style="display: inline-block;">
                        <input style="display: inline-block;width: 80%;" type="text"
                               name="gift_trans_time"
                               value="<?php echo $act['gift_trans_time']; ?>"
                               data-date-format="YYYY-MM-DD" class="lfx-text" />
                        <span  style="width: 20%;">
                        <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                      </div>
                    </span>
                  </span>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row customer-list-box" style="display:none;">

      </div>
      </form>
      <!--<div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn">审批通过直接上架</button>
        <button class="btn lfx-btn">审批通过，暂不上架</button>
        <button class="btn lfx-btn">审批不通过</button>
      </div>-->
        <?php if($link_mode == 'create'){ ?>
        <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="createAct(0)">提交并直接上架</button>
        <button class="btn lfx-btn" type="button" onclick="createAct(1)">提交暂不上架</button>
        <a href="<?php echo 'index.php?route=activity/gift&token='.$token.'&link_mode=create'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>

        </div>
        <?php }else if($link_mode == 'modify'){ ?>
        <div class="row" style="text-align: center;margin-top: 20px;">
            <button class="btn lfx-btn" type="button" onclick="saveAct(0)">保存并上架</button>
            <button class="btn lfx-btn" type="button" onclick="saveAct(1)">保存，暂不上架</button>
        <a href="<?php echo 'index.php?route=activity/gift&token='.$token.'&link_mode=modify'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>
        </div>
        <?php } ?>
    </div>
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
        var url = 'index.php?route=activity/gift/create&token=<?php echo $token; ?>';
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
        params.act_id = '<?php echo $act["gp_id"]; ?>';
        var url = 'index.php?route=activity/gift/modify&token=<?php echo $token; ?>';
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
        var form = $('#gift-fm');
        var params = $(form).formJSON();
        var valid_arr = [
            {field:'act_name',required:true,errMsg:'活动名称不能为空！'},
            {field:'gift_trans_time',required:true,errMsg:'积分到账日期不能为空！'},
            {field:'act_memo',required:true,errMsg:'活动说明不能为空！'},
            {field:'imgurl',required:mode=='create',errMsg:'活动图片不能为空！'}
        ];
        if(validFormParams(params,valid_arr)!==true){
            return false;
        }
        if($('#is_gift_credit:checked').length==0){
            params.is_gift_credit = 0;
        }else{
            params.is_gift_credit = 1;
        }
        if($.isArray(params['customer_ids[]'])){
        }else if(is_valid_str(params['customer_ids[]'])){
            params['customer_ids[]'] = [params['customer_ids[]']];
        }else{
            showErrorText('赠送账号列表不为空！');
            return false;
        }
        return params;
    }

  function renderWindowTipsHtml(){
      var html = $('.customer-list-box').html();
      showWideHtmlWin('赠送积分用户',html);
  }
  function renderWinowTips(list){
    var html =
            '<div class="container-fluid">' +
            '<table class="table table-bordered lfx-table">' +
            '<thead><tr><th>ID</th><th>Name</th></tr></thead>' +
            '<tbody>';
    for(var i = 0;i < list.length;i ++){
      html += '<tr>';
      html = html + '<td class="text-center">'+list[i]['id']+'<input name="customer_ids[]" value="'+list[i]['id']+'" type="hidden" ></td>';
      html = html + '<td class="text-center">'+list[i]['name']+'</td>';
      html += '</tr>'
    }
    html = html + '</tbody>' + '</table>' + '</div>';
    $('.customer-list-box').empty().append(html);
    showWideHtmlWin('赠送积分用户',html);
  }

  function importCustomerList(){
    var upload_url = 'index.php?route=common/temp/genericuploader&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
      {file_name:'customer_list_temp'}
      ,function(data){
        if(data.success===true){
          var file_path = data['file_path'];
          var parse_url = 'index.php?route=common/api/parseCustomerList&token=<?php echo $token; ?>';
          $.model.commonAjax(parse_url,{file_path:file_path}, function (data) {
            renderWinowTips(data);
          });
        }else{
          showErrorText(data.error);
        }
      });
  }

  function changeMainImage(image){
    image = $(image);
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/gift/main';
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["gp_id"]; ?>_main_temp',resize_width:200,resize_height:200,delete_path:old_image}
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
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/gift/main';
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["gp_id"]; ?>_main_temp',resize_width:200,resize_height:200}
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
