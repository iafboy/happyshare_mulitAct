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
    <form id="add-supplier-fm">
    <div class="row">
        <div class="col-sm-12">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">供货商账号：</label>
            <input type="text" name="username" class="lfx-text" />
          </span>
          <span class="form-group">
            <label class="control-label"><?php echo $entry_supplier_no; ?></label>
            <span><?php echo $supplier_no; ?></span>
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
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label"><?php echo $entry_supplier_username; ?></label>
            <input type="text" name="supplier_name" class="lfx-text" />
          </span>
          <span class="form-group">
            <label class="control-label">供应商售后客服联系电话：</label>
            <input type="number" name="service_phone"  class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">供应商结算银行账号：</label>
            <select type="text" name="bankid" class="lfx-select" >
              <?php
              foreach($banks as $bank){ ?>
                <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']; ?></option>
              <?php }
               ?>
            </select>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <input type="number" name="bankcard"  class="lfx-text" />
          </span>
        </div>
      <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">供应商自设积分：</label>
            <select type="text" name="can_edit_credit" class="lfx-select" >
              <option value="0">否</option>
              <option value="1">是</option>
            </select>
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
            <div class="image-container-sm add-image" style="width: 150px;text-align: center;">
              <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                <img class="image-button" width="100%" height="100%"
                     src="<?php echo $to_add_img; ?>"
                     data-placeholder="<?php echo $placeholder; ?>"
                     onclick="appendTempImage(this)" />
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12" style="margin-top: 30px;">
        <div style="text-align: center;">
          <button type="button" id="button-filter" class="btn lfx-btn" onclick="addSupplier()"><i class="fa fa-search"></i> <?php echo $btn_supplier_upload; ?></button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>
<style>
  .image-container-sm a{
    margin-right:5px;width: 200px;height:200px;display: inline-block;
  }
  .image-container-sm{
    margin-right: 5px;
    width: 150px;
    display: inline-block;
    margin-bottom: 10px;
  }
</style>
<script type="text/javascript">

  function addSupplier(){
    var url = 'index.php?route=supplier/add/add&token=<?php echo $token; ?>';
    var form = '#add-supplier-fm';
    var params = $(form).formJSON();
    params['supplier_reg_id'] = '<?php echo $supplier_reg_id; ?>';
      if(!is_valid_str(params.username)){
          return showErrorText('供货商姓名不为空！');
      }
      if(!is_valid_str(params.password)){
          return showErrorText('初始密码未填写！');
      }
      if(!is_valid_str(params.password2)){
          return showErrorText('确认初始密码未填写！');
      }
      if(params.password2 != params.password){
          return showErrorText('确认初始密码不一致！');
      }
    if(!is_valid_str(params.supplier_name)){
      return showErrorText('供货商不为空！');
    }

    if(!is_valid_str(params.bankcard)){
      return showErrorText('供货商银行卡号不为空！');
    }
    if(!is_valid_str(params.service_phone)){
      return showErrorText('供货商售后联系方式不为空！');
    }
    if(!is_valid_str(params['images[]'])){
      return showErrorText('供货商描述图片缺失!');
    }
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }

  function changeTempImage(image){
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/supplier/desc';
    var index = $(image).parents('.sup-image').data('sup-index');
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
    {file_name:'supplier_<?php echo $supplier_no; ?>_'+index+'_temp',delete_path:old_image}
    ,function(data){
      if(data.success===true){
        var path = data['file_path'];
        var image_path = data['image_path'];
        var html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
        data-placeholder="<?php echo $placeholder; ?>" onclick="changeSubImage(this)" /> \
        <input name="images[]" value="'+image_path+'" type="hidden" />';
        $(image).parent().empty().append(html);
      }else{
        showErrorText(data.error);
      }
    });
  }
  function appendTempImage(image){
    var index = $('.supplier-images-box .sup-image').length + 1;
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/supplier/desc';
    commonFileUpload(upload_url,
      {file_name:'supplier_<?php echo $supplier_no; ?>_'+index+'_temp'}
      ,function(data){
        if(data.success===true){
          var path = data['file_path'];
          var image_path = data['image_path'];
          var html =
          '<div class="image-container-sm sup-image" data-sup-index="'+index+'" style="width: 150px;"> \
          <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;"> \
          <img class="image-button" width="100%" height="100%" \
            src="'+path+'?t='+new Date().getTime()+'" \
            data-placeholder="<?php echo $placeholder; ?>" \
            onclick="changeTempImage(this)" /> \
            <input name="images[]" value="'+image_path+'" type="hidden" /> \
          </a> \
          </div>';
          $(html).insertBefore($(image).parents('.add-image'));
        }else{
          showErrorText(data.error);
        }
      });
  }


</script>