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
    <form id="add-supplier-brand-fm">
    <div class="row">
        <div class="col-sm-12">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label"><?php echo $entry_supplier_username; ?></label>
            <input type="text" readonly value="<?php echo $supplier['supplier_name']; ?>" class="lfx-text" />
          </span>
          <span class="form-group">
            <label class="control-label"><?php echo $entry_supplier_no; ?></label>
            <span><?php echo $supplier['supplier_no']; ?></span>
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">品牌倌名称:</label>
            <input type="text" name="name" value="<?php echo $supplier['name']; ?>" class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" >
            <label class="control-label">品牌倌介绍:</label>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <textarea style="resize: none;" type="number" name="desc" class="lfx-text" ><?php echo $supplier['bg_intro']; ?></textarea>
          </span>
        </div>
      </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <label class="control-label">品牌倌图片</label>
        </div>
        <div class="">
          <div class="supplier-images-box" style="padding: 5px;border: 1px solid #e4e4e4;">
            <div class="image-container-sm add-image" style="width: 150px;text-align: center;">
              <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                <?php
                 if(!is_valid($supplier['imgurl'])){ ?>
                  <img class="image-button" width="100%" height="100%"
                       src="<?php echo $to_add_img.'?t='.time(); ?>"
                       data-placeholder="<?php echo $placeholder; ?>"
                       onclick="appendTempImage(this)" />
                 <?php }else{ ?>
                  <img class="image-button" width="100%" height="100%"
                       src="<?php echo DIR_IMAGE_URL.$supplier['imgurl'].'?t='.time(); ?>"
                       data-placeholder="<?php echo $placeholder; ?>"
                       onclick="appendTempImage(this)" />
                 <?php }
                 ?>

              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12" style="margin-top: 30px;">
        <div style="text-align: center;">
          <button type="button" id="button-filter" class="btn lfx-btn" onclick="addOrEditBrand(1)"> <?php echo $btn_supplier_upload; ?></button>
          <button type="button" id="button-filter" class="btn lfx-btn" onclick="addOrEditBrand(0)"> 下架</button>
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

  function addOrEditBrand(status){
    var url = 'index.php?route=supplier/gallery/addOrUpdateBrand&token=<?php echo $token; ?>';
    var form = '#add-supplier-brand-fm';
    var params = $(form).formJSON();
    params['status']=status;
    params['supplier_id'] = '<?php echo $supplier["supplier_id"]; ?>';
    if(!is_valid_str(params.name)){
      return showErrorText('品牌名称必填！');
    }
    /*if(!is_valid_str(params.imgurl)){
      return showErrorText('供货商姓名不为空！');
    }
    if(!is_valid_str(params.desc)){
      return showErrorText('供货商银行卡号不为空！');
    }*/
    <?php if(!is_valid($supplier['brand_id'])){ ?>
      params['action'] = 'add';
    <?php }else{ ?>
      params['action'] = 'update';
    <?php } ?>
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }

  function changeTempImage(image){
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/supplier/brand';
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
    {file_name:'supplier_<?php echo $supplier['supplier_id']; ?>_temp',delete_path:old_image}
    ,function(data){
      if(data.success===true){
        var path = data['file_path'];
        var image_path = data['image_path'];
        var html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
        data-placeholder="<?php echo $placeholder; ?>" onclick="changeTempImage(this)" /> \
        <input name="imgurl" value="'+image_path+'" type="hidden" />';
        $(image).parent().empty().append(html);
      }else{
        showErrorText(data.error);
      }
    });
  }
  function appendTempImage(image){
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/supplier/brand';
    commonFileUpload(upload_url,
            {file_name:'supplier_<?php echo $supplier['supplier_id']; ?>_temp'}
            ,function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image_path'];
                var html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
                data-placeholder="<?php echo $placeholder; ?>" onclick="changeTempImage(this)" /> \
                <input name="imgurl" value="'+image_path+'" type="hidden" />';
                $(image).parent().empty().append(html);
              }else{
                showErrorText(data.error);
              }
            });
  }
</script>