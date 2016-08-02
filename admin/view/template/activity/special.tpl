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
      <form id="special-fm">
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
                            <input name="act_name" value="<?php echo $act['promotion_name']; ?>" class="lfx-text" />
                          </span>
                        </span>
                        <span  class="entry-group">
                          <label>活动类型：</label>
                          <span>
                              <select name="special_type" class="lfx-select">
                                  <?php
                                  if($act['special_type'].'' === '1'){ ?>
                                      <option value="0">特价</option>
                                      <option value="1" selected>积分翻倍</option>
                                  <?php }else{ ?>
                                      <option value="0" selected>特价</option>
                                      <option value="1">积分翻倍</option>
                                  <?php }
                                  ?>
                              </select>
                          </span>
                        </span>
                      </div>
                      <div class="content-row">
                        <span class="entry-group">
                          <label>活动有效期：</label>
                          <span>
                            <div class="date1" style="display: inline-block;">
                              <input style="display: inline-block;width: 80%;" type="text"
                                     name="act_start_date" value="<?php echo $act['starttime']; ?>"
                                     data-date-format="YYYY-MM-DD" class="lfx-text" />
                              <span  style="width: 20%;">
                              <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </span>
                          -
                          <span>
                            <div class="date2" style="display: inline-block;">
                              <input style="display: inline-block;width: 80%;" type="text"
                                     name="act_end_date" value="<?php echo $act['endtime']; ?>"
                                     data-date-format="YYYY-MM-DD" class="lfx-text" />
                              <span  style="width: 20%;">
                              <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </span>
                        </span>
                        <span  class="entry-group">
                          <label>活动编号：</label>
                          <span><?php echo $act['promotion_id']; ?></span>
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
                      <div class="content-row" >
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
              <td class="navi-title"><span>活动商品</span></td>
              <td class="navi-content">
                  <div class="content-row">
                      <div class="entry-group" style="margin-right: 0px;">
                        <label>
                          <input name="product_mode" value="1" type="radio" checked onclick="setProductMode(0);" />
                          <span style="margin-left: 10px;">全部商品</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <button class="lfx-btn" type="button" onclick="refreshProductTable()">加入活动列表</button>
                        </span>
                      </div>
                  </div>
                  <div class="content-row product-type-box">
                    <?php
                         foreach($producttypes as $producttype){ ?>
                          <span style="margin-left: 10px;" class="product-category-item">
                            <input name="product_types[]" value="<?php echo $producttype['product_type_id'];?>" type="checkbox" />
                            <span style="margin-left: 10px;"><?php echo $producttype['type_name']; ?></span>
                          </span>
                    <?php }
                         ?>
                  </div>
                  <div class="content-row">
                      <div class="entry-group" style="margin-right: 0px;">
                        <label>
                          <input name="product_mode" value="2" type="radio" onclick="setProductMode(1);" />
                          <span style="margin-left: 10px;">部分商品</span>
                        </label>
                        <span style="margin-left: 10px;">
                          <label>商品编码：</label>
                          <input  type="text" class="lfx-text" id="productIdText" />
                          <button class="lfx-btn" type="button" onclick="appendNewProduct()">加入活动列表</button>
                          <input  type="file" class="lfx-text" id="importPrdIs" />
                          <button class="lfx-btn" type="button" onclick="importNewProduct()">导入产品列表</button>
                        </span>
                      </div>
                  </div>
                  <div class="content-row product-table-box" style="margin-top: 20px;">
                    <table class="table table-bordered lfx-table">
                      <thead>
                      <tr>
                        <th>商品编码</th>
                        <th>商品名称</th>
                        <th>商品原价</th>
                        <th>商品活动价</th>
                        <th>商品原积分</th>
                        <th>商品活动积分</th>
                        <th>操作</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach($act['products'] as $product){ ?>
                        <tr class="product-item-<?php echo $product['product_id']; ?> product-no-<?php echo $product['product_no']; ?>" >
                          <td><?php echo $product['product_no']; ?><input name="act_product_ids[]" type="hidden" value="<?php echo $product['product_id']; ?>" /></td>
                          <td><?php echo $product['product_name']; ?></td>
                          <td><?php echo $product['storeprice']; ?>元</td>
                          <?php if( $act['special_type'].'' === '0'){ ?>
                            <td><input name="p_<?php echo $product['product_id']; ?>_act_price" class="lfx-text lfx-text-xs"
                                     type="number" value="<?php echo $product['act_price']; ?>" />元</td>
                          <?php }else{ ?>
                            <td><?php echo $product['act_price']; ?>元
                              <input name="p_<?php echo $product['product_id']; ?>_act_price" class="lfx-text lfx-text-xs"
                                  type="hidden" value="<?php echo $product['act_price']; ?>" /></td>
                          <?php } ?>
                          <td><?php echo parseFormatNum($product['credit']); ?></td>
                          <?php if( $act['special_type'].'' === '1'){ ?>
                          <td><input name="p_<?php echo $product['product_id']; ?>_act_credit"
                                     class="lfx-text lfx-text-xs" type="number"
                                    value="<?php echo $product['act_credit']; ?>" /></td>
                          <?php }else{ ?>
                          <td><?php echo parseFormatNum($product['act_credit']); ?>
                            <input name="p_<?php echo $product['product_id']; ?>_act_credit"
                              class="lfx-text lfx-text-xs" type="hidden"
                              value="<?php echo $product['act_credit']; ?>" /></td>
                          <?php } ?>
                          <td><button class="lfx-btn" type="button" onclick="$(this).parentsUntil('tr').parent().remove()">删除</button></td>
                        </tr>
                        <?php }
                         ?>
                      </tbody>
                    </table>
                  </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <!--
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="">审批通过直接上架</button>
        <button class="btn lfx-btn" type="button" onclick="">审批通过，暂不上架</button>
        <button class="btn lfx-btn" type="button" onclick="">审批不通过</button>
      </div>
      -->
      <?php if($link_mode == 'create'){ ?>
        <div class="row" style="text-align: center;margin-top: 20px;">
          <button class="btn lfx-btn" type="button" onclick="createAct(0)">提交并直接上架</button>
          <button class="btn lfx-btn" type="button" onclick="createAct(1)">提交暂不上架</button>
          <a href="<?php echo 'index.php?route=activity/special&token='.$token.'&link_mode=create'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>
        </div>
      <?php }else if($link_mode == 'modify'){ ?>
        <div class="row" style="text-align: center;margin-top: 20px;">
          <button class="btn lfx-btn" type="button" onclick="saveAct(0)">保存并上架</button>
          <button class="btn lfx-btn" type="button" onclick="saveAct(1)">保存，暂不上架</button>
          <a href="<?php echo 'index.php?route=activity/special&token='.$token.'&link_mode=modify'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>
        </div>
      <?php } ?>
      </form>
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
    var url = 'index.php?route=activity/special/create&token=<?php echo $token; ?>';
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
    params.act_id = '<?php echo $act["promotion_id"]; ?>';
    var url = 'index.php?route=activity/special/modify&token=<?php echo $token; ?>';
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
    var form = $('#special-fm');
    var params = $(form).formJSON();
    var valid_arr = [
      {field:'act_name',required:true,errMsg:'活动名称不能为空！'},
      {field:'act_start_date',required:true,errMsg:'活动开始日期不能为空！'},
      {field:'act_end_date',required:true,errMsg:'活动结束日期不能为空！'},
      {field:'act_memo',required:true,errMsg:'活动说明不能为空！'},
      {field:'special_type',required:true,errMsg:'活动类型不能为空！'},
      {field:'imgurl',required:mode=='create',errMsg:'活动图片不能为空！'}
    ];
    if(validFormParams(params,valid_arr)!==true){
      return false;
    }
    if(params.product_mode == 1){
    }else if(params.product_mode == 2){
    }else{
      showErrorText('产品选择不为空！');
      return false;
    }
    var valid = true;
    $('.product-table-box table input').each(function(){
      if(!is_valid_str($(this).val())){
        valid = false;
      }
    });
    if(valid !== true){
      showErrorText('产品资料必填！');
      return false;
    }
    if($.isArray(params['act_product_ids[]'])){
    }else if(is_valid_str(params['act_product_ids[]'])){
      params['act_product_ids[]'] = [params['act_product_ids[]']];
    }else{
      showErrorText('产品不为空！');
      return false;
    }
    return params;
  }
  function refreshProductTable(){
    var url = 'index.php?route=common/api/queryProductByProducttypes&token=<?php echo $token; ?>';
    var form = $('#special-fm');
    var params = $(form).formJSON();
    var categories;
    if(is_valid_str(params['product_types[]']) && !$.isArray(params['product_types[]']) ){
      categories = [params['product_types[]']];
    }else if(($.isArray(params['product_types[]']) && params['product_types[]'].length>0)){
      categories = params['product_types[]'];
    }else{
      showErrorText('产品分类不为空！');
      return false;
    }
    params = {producttype_ids:categories};
    $.model.commonAjax(url,params,function(data){
      if($.isArray(data)){
        renderNewProductRows(data);
      }
    });
  }
  function renderNewProductRows(products){
    var html = '';
    for(var i = 0; i < products.length; i++){
      var product = products[i];
      if($('.product-table-box table tbody tr.product-item-'+product.product_id).length!=0){
        continue;
      }

        html +=
                '<tr class="product-item-'+product.product_id+' product-no-'+product.product_no+'" > \
          <td>'+product.product_no+'<input name="act_product_ids[]" type="hidden" value="'+product.product_id+'" /></td> \
          <td>'+product.product_name+'</td> \
          <td>'+product.storeprice+'元</td>  \
          <td><input name="p_'+product.product_id+'_act_price" value="'+product.storeprice+'" class="lfx-text lfx-text-xs" type="number" />元</td> \
          <td>'+product.credit+'</td> \
          <td><input name="p_'+product.product_id+'_act_credit" value="'+product.credit+'" class="lfx-text lfx-text-xs" type="number" /></td> \
          <td><button class="lfx-btn" type="button" onclick="$(this).parentsUntil(\'tr\').parent().remove()">删除</button></td> \
        </tr>';


    }
    $('.product-table-box table tbody').append(html);
  }
  function renderProductsRows(products){
    var html = '';

    for(var i = 0; i < products.length; i++){
      var product = products[i];
      if($('.product-table-box table tbody tr.product-item-'+product.product_id).length!=0){
        continue;
      }

      html +=
              '<tr class="product-item-'+product.product_id+' product-no-'+product.product_no+'" > \
          <td>'+product.product_no+'<input name="act_product_ids[]" type="hidden" value="'+product.product_id+'" /></td> \
          <td>'+product.product_name+'</td> \
          <td>'+product.storeprice+'元</td>  \
          <td><input name="p_'+product.product_id+'_act_price" value="'+product.storeprice+'" class="lfx-text lfx-text-xs" type="number" />元</td> \
          <td>'+product.credit+'</td> \
          <td><input name="p_'+product.product_id+'_act_credit" value="'+product.credit+'" class="lfx-text lfx-text-xs" type="number" /></td> \
          <td><button class="lfx-btn" type="button" onclick="$(this).parentsUntil(\'tr\').parent().remove()">删除</button></td> \
        </tr>';

    }
    $('.product-table-box table tbody').append(html);
  }
  function appendNewProduct(){
    var product_id = $('#productIdText').val();
    if(!is_valid_str(product_id)){
      return showErrorText('商品编码为空！');
    }
    if($('.product-table-box table tbody tr.product-no-'+product_id.trim()).length!=0){
      return showErrorText('商品已存在！');
    }
    var url = 'index.php?route=common/api/queryProductByNo&token=<?php echo $token; ?>';
    var params = {product_no:product_id};
    $.model.commonAjax(url,params, function (data) {
      if(data.success === true){
        var product = data.product;
        if(product.status!=3){
            return showErrorText('该产品未上架！');
        }
        renderNewProductRows([product]);
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function importNewProduct(){
    var productids_path = $('#importPrdIs').val();
    if(!is_valid_str(productids_path)){
      return showErrorText('上传文件地址为空！');
    }
    var upload_url = 'index.php?route=common/temp/genericuploader&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
            {file_name:productids_path}
            ,function(data){
              if(data.success===true){
                var url = 'index.php?route=common/api/uploadProductByFile&token=<?php echo $token; ?>';
                var params = {file_path:data.file_path};
                $.model.commonAjax(url,params, function (data) {
                  if(data.success === true){
                    var products = data.product;
                    renderProductsRows(products);
                  }else{
                    return showErrorText(data.errMsg);
                  }
                });
              }else{
                showErrorText(data.error);
              }
            });

  }
  function setProductMode(type){
    var $box = $('.product-table-box');
    var $type = $('.product-type-box');
    if(type==0){
      $type.show();
    }else if(type ==1){
      $type.hide();
    }
  }

  function changeMainImage(image){
    image = $(image);
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/special/main';
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["promotion_id"]; ?>_main_temp',resize_width:200,resize_height:200,delete_path:old_image}
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
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/special/main';
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["promotion_id"]; ?>_main_temp',resize_width:200,resize_height:200}
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

</script>
<style>
  .content-row .product-category-item{
    width: 180px;
    display: inline-block;
  }
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
<script type="text/javascript">
  $('.date1').datetimepicker({
    pickTime: false
  });
  $('.date2').datetimepicker({
    pickTime: false
  });

//  $('.date').data("DateTimePicker").setDisabledDates(["03/19/2016","03/20/2016","03/21/2016"]);

//  var url = 'index.php?route=activity/special/getInvalidDate&token=<?php echo $token; ?>';
//  $.model.commonAjax(url,Array(),function(data){
//    var timestr = new Array();
//    for(var i = 0; i < data.length; i++){
//      timestr.push(data[i]);
//    }
//    $('.date1').data("DateTimePicker").setDisabledDates(timestr);
//    $('.date2').data("DateTimePicker").setDisabledDates(timestr);
//  });


  $('.time').datetimepicker({
    pickDate: false
  });

  $('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
  });
</script>
