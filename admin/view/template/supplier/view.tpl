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
  <!-- Content Area  -->
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
    <form id="mod-supplier-fm">
    <div class="row">
        <div class="col-sm-12">
          <span class="form-group" style="margin-right: 10px;">
              <label class="control-label">供货商账号：</label>
            <?php echo $supplier['username']; ?>
              <input type="hidden" name="username" value="<?php echo $supplier['username']; ?>">
            <input type="hidden" name="supplier_name" value="<?php echo $supplier['supplier_name']; ?>"/>
          </span>
          <span class="form-group">
            <label class="control-label"><?php echo $entry_supplier_no; ?></label>
            <span><?php echo $supplier['supplier_no']; ?></span>
          </span>
        </div>
        <div class="col-sm-12">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;初始密码：</label>
            <input type="password" name="password" autocomplete="off" class="lfx-text"  />
          </span>
          <span class="form-group">
            <label class="control-label">确认初始密码：</label>
            <input type="password" name="password2" autocomplete="off" class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group">
            <label class="control-label">允许自设积分：</label>
              <?php
              if($supplier['can_edit_credit']=='1'){ ?>
                <input checked type="checkbox" name="can_edit_credit" id="can_edit_credit_ck" />

              <?php }else{ ?>
                <input type="checkbox" name="can_edit_credit" id="can_edit_credit_ck" />

              <?php }
              ?>
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label"><?php echo $entry_supplier_username; ?></label>
            <?php echo $supplier['supplier_name']; ?>
          </span>
          <span class="form-group">
            <label class="control-label">供应商售后客服联系电话：</label>
            <input type="number" name="service_phone" value="<?php echo $supplier['service_phone']; ?>" class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">供应商结算银行账号：</label>
            <select type="text" name="bankid" class="lfx-select" >
              <?php
              foreach($banks as $bank){
                if($supplier['bankid']==$bank['bank_id']){ ?>
              <option value="<?php echo $bank['bank_id']; ?>" selected><?php echo $bank['bank_name']; ?></option>
                <?php }else{ ?>
              <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']; ?></option>
                <?php }
              ?>
              <?php }
               ?>
            </select>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <input type="number" name="bankcard" value="<?php echo $supplier['bankcard']; ?>" class="lfx-text" />
          </span>
        </div>
      </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <label class="control-label"><?php echo $entry_supplier_desc; ?></label>
        </div>
        <div class="">
          <div class="supplier-images-box" style="padding: 5px;border: 1px solid #e4e4e4;">
            <?php
             foreach($supplier['images'] as $image){ ?>
              <div class="image-container-sm sup-image" data-sup-index="<?php echo $image['seq']; ?>" style="width: 150px;">
                <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                  <img class="image-button" width="100%" height="100%"
                       src="<?php echo DIR_IMAGE_URL.$image['imgurl']; ?>"
                       data-placeholder="<?php echo $placeholder; ?>"
                       onclick="changeSupplierImage(this,<?php echo $image['vis_id']; ?>)" />
                </a>
                <a onclick="doDelSubImage(this,'<?php echo $image['seq']; ?>')" class="del-btn fa fa-fw fa-trash"></a>
              </div>
             <?php }
             ?>


            <div class="image-container-sm add-image" style="width: 150px;text-align: center;">
              <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                <img class="image-button" width="100%" height="100%"
                     src="<?php echo $to_add_img; ?>"
                     data-placeholder="<?php echo $placeholder; ?>"
                     onclick="appendSupplierImage(this)" />
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12" style="margin-top: 30px;">
        <div style="text-align: center;">
          <button type="button" id="button-filter" class="btn lfx-btn" onclick="modSupplier()"><i class="fa fa-search"></i> <?php echo $btn_supplier_upload; ?></button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>
<style>
  .image-container-sm a:nth-child(1){
    margin-right:5px;width: 200px;height:200px;display: inline-block;
  }
  .image-container-sm{
    margin-right: 5px;
    width: 150px;
    display: inline-block;
    margin-bottom: 10px;
    position: relative;
  }

  .image-container-sm a.del-btn
  {
    float: right;
    display: inline-block;
    width: 30px;
    height: 30px;
    position: absolute;
    top: 0px;
    right: -10px;
    font-size: 30px;
    color: #aaa;
  }
  .image-container-sm a.del-btn:hover
  {
    cursor: pointer;
    color: #999;
  }
</style>
<script type="text/javascript">

  function modSupplier(){
    var url = 'index.php?route=supplier/view/mod&token=<?php echo $token; ?>';
    var form = '#mod-supplier-fm';
    var params = $(form).formJSON();
      //if(!is_valid_str(params.username)){
      //    return showErrorText('供货商账号不为空！');
      //}
      if(params.password && (params.password != params.password2)){
          return showErrorText('确认初始密码不一致！');
      }
      //if(!is_valid_str(params.supplier_name)){
        //return showErrorText('供货商不为空！');
    //}
    if(!is_valid_str(params.bankcard)){
      return showErrorText('供货商银行卡号不为空！');
    }
    if(!is_valid_str(params.service_phone)){
      return showErrorText('供货商售后联系方式不为空！');
    }
    if($('#can_edit_credit_ck:checked').length==1){
        params.can_edit_credit = 1;
    }else{
        params.can_edit_credit = 0;
    }
    params.supplier_id = '<?php echo $supplier["supplier_id"]; ?>';
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }

  function changeSupplierImage(image,visId){
    var upload_url = 'index.php?route=supplier/view/uploadImage&token=<?php echo $token; ?>';
    var index = $(image).parents('.sup-image').data('sup-index');
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
            {type:'1',supplier_id:<?php echo $supplier['supplier_id']; ?>,vis_id:visId}
    ,function(data){
      if(data.success===true){
        var path = data['file_path'];
        var image_path = data['image_path'];
        var html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
        data-placeholder="<?php echo $placeholder; ?>" onclick="changeSupplierImage(this,'+visId+')" />';
        $(image).parent().empty().append(html);
      }else{
        showErrorText(data.error);
      }
    });
  }
  function appendSupplierImage(image){
    var index = $('.supplier-images-box .sup-image').last().data('sup-index') +  1;
    var upload_url = 'index.php?route=supplier/view/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
      {type:'2',seq:index,supplier_id:<?php echo $supplier['supplier_id']; ?>}
      ,function(data){
        if(data.success===true){
          var path = data['file_path'];
          var image_path = data['image_path'];
          var visId = data['vis_id'];
          var html =
          '<div class="image-container-sm sup-image" data-sup-index="'+index+'" style="width: 150px;"> \
          <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;"> \
          <img class="image-button" width="100%" height="100%" \
            src="'+path+'?t='+new Date().getTime()+'" \
            data-placeholder="<?php echo $placeholder; ?>" \
            onclick="changeSupplierImage(this,'+visId+')" /> \
          </a> \
          <a onclick="doDelSubImage(this,\'<?php echo $image['seq']; ?>\')" class="del-btn fa fa-fw fa-trash"></a> \
          </div>';
          $(html).insertBefore($(image).parents('.add-image'));
        }else{
          showErrorText(data.error);
        }
      });
  }
  function doDelSubImage(obj,seq){
    var image = $(obj).parent().find('a > img');
    var win =  confirmWideHtmlWin('提示','确认删除么？', function (data) {
      var url = 'index.php?route=supplier/view/deleteSubImage&token=<?php echo $token; ?>';
      $.model.commonAjax(url,{seq:seq,supplier_id:'<?php echo $supplier_id; ?>'}, function (data) {
        if(data.success === true){
          $(image).parents('.sup-image').remove();
          win.close();
        }else{
          return showErrorText(data.errMsg);
        }
      });
    });
  }
</script>
