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
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div>
              <div class="lfx-row">
                <label>
                  未支付订单自动关闭时间：
                </label>
                <span style="margin-right: 10px;">
                  <input class="lfx-text unpaid_close_hour_input" name="unpaid_close_hour" style="height: 28px;" type="number" value="<?php echo $unpaid_close_hour; ?>" min="0" />
                </span>
                <span>小时</span>
                <span style="margin-left: 10px;vertical-align: top;display: inline-block;">
                  <button type="button" class="btn btn-sm lfx-btn update_unpaid_close_hour" >提交修改</button>
                </span>
              </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12" style="margin-top: 30px;">
            <label class="pull-left">商品来源地列表：</label>
            <button class="pull-right btn btn-sm lfx-btn" type="button" onclick="addPlace()">新增来源地</button>
        </div>
        <div class="col-sm-12">
          <table class="table table-bordered lfx-table place-table" style="margin-top: 10px;">
            <thead>
              <tr>
                <th width="30%">来源地编码</th>
                <th width="40%">来源地名称</th>
                <th width="30%">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php
               foreach($origin_places as $place){ ?>
                <tr class="place-row-<?php echo $place['origin_place_id']; ?>">
                  <td><?php echo $place['place_code']; ?></td>
                  <td><?php echo $place['place_name']; ?></td>
                  <td class="oper">
                      <?php
                        if($place['status'].''==='0'){ ?>
                           <button type="button" onclick="changeOriginPlaceStatus('<?php echo $place['origin_place_id']; ?>','1')" class="btn btn-sm lfx-btn">上架</button>
                        <?php }else if($place['status'].''==='1'){ ?>
                          <button type="button" onclick="changeOriginPlaceStatus('<?php echo $place['origin_place_id']; ?>','0')" class="btn btn-sm lfx-btn">下架</button>
                        <?php }else{
                        } ?>
                    </button>
                  </td>
                </tr>
               <?php }
               ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <div class="row">
        <div class="col-sm-12" style="margin-top: 30px;">
          <label class="pull-left">商品类型列表：</label>
          <button class="pull-right btn btn-sm lfx-btn" type="button" onclick="addProductType()">新增商品类型</button>
        </div>
        <div class="col-sm-12">
          <table class="table table-bordered lfx-table producttype-table" style="margin-top: 10px;">
            <thead>
              <tr>
                <th width="30%">类型编码</th>
                <th width="40%">商品类型名称</th>
                <th width="30%">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php
               foreach($product_types as $type){ ?>
            <tr class="producttype-row-<?php echo $type['product_type_id']; ?>">
              <td><?php echo $type['product_type_no']; ?></td>
              <td><?php echo $type['type_name']; ?></td>
              <td class="oper">
                <?php
                  if($type['status'].''==='0'){ ?>
                    <button type="button" onclick="changeProductTypeStatus('<?php echo $type['product_type_id']; ?>','1')" class="btn btn-sm lfx-btn">上架</button>
                  <?php }else if($type['status'].''==='1'){ ?>
                    <button type="button" onclick="changeProductTypeStatus('<?php echo $type['product_type_id']; ?>','0')" class="btn btn-sm lfx-btn">下架</button>
                  <?php }else{
                        } ?>
                </button>
              </td>
            </tr>
            <?php }
               ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script>

  function renderPlaceList(list){
    if(list ==null || list.length ==0){
      return '';
    }
    var _html='';
    for(var i = 0;i <list.length;i++){
      var place = list[i];
      var status = place.status;
      var status_text = '';
      if(status+''==='0'){
        status_text = '上架';
        status = '1';
      }else if(status+''==='1'){
        status_text = '下架';
        status = '0';
      }
      var html = '<tr class="place-row-'+place['origin_place_id']+'">'+
              '<td>'+place['place_code']+'</td>'+
              '<td>'+place['place_name']+'</td>'+
              '<td class="oper">'+
              '<button type="button" '+
              'onclick="changeOriginPlaceStatus(\''+place['origin_place_id']+'\',\''+status+'\')" class="btn btn-sm lfx-btn">'+status_text+'</button>'+
              '</button>'+
              '</td>'+
              '</tr>';
      _html += html;
    }
    $('.place-table tbody').empty().append(_html);
  }
  function renderProducttypeList(list){
    if(list ==null || list.length ==0){
      return '';
    }
    var _html='';
    for(var i = 0;i <list.length;i++){
      var producttype = list[i];
      var status = producttype.status;
      var status_text = '';
      if(status+''==='0'){
        status_text = '上架';
        status = '1';
      }else if(status+''==='1'){
        status_text = '下架';
        status = '0';
      }
      var html = '<tr class="producttype-row-'+producttype['product_type_id']+'">'+
              '<td>'+producttype['product_type_no']+'</td>'+
              '<td>'+producttype['type_name']+'</td>'+
              '<td class="oper">'+
              '<button type="button" '+
              'onclick="changeProductTypeStatus(\''+producttype['product_type_id']+'\',\''+status+'\')" class="btn btn-sm lfx-btn">'+status_text+'</button>'+
              '</button>'+
              '</td>'+
              '</tr>';
      _html += html;
    }
    $('.producttype-table tbody').empty().append(_html);
  }


  function changeOriginPlaceStatus(place_id,status){
    var url = '<?php echo html_entity_decode($change_place_status_url); ?>';
    var html =
            '确定更改么？';
    confirmText('提示',html, function (data) {
      $.model.commonAjax(url,{place_id:place_id,status:status}, function (data) {
        if(data.success===true){
          showSuccessText('更改成功！');
          var place = data.place;
          var status = place.status;
          var status_text = '';
          if(status+''==='0'){
            status_text = '上架';
            status = '1';
          }else if(status+''==='1'){
            status_text = '下架';
            status = '0';
          }
          var btn_html =
          '<button type="button" ' +
          'onclick="changeOriginPlaceStatus(\''+place.origin_place_id+'\',\''+status+'\')" class="btn btn-sm lfx-btn">'+status_text+'</button>';
          $('.place-table .place-row-'+place_id+' .oper').empty().append(btn_html);
        }else{
          showErrorTex('更改失败！');
        }
      });
    });

  }
  function changeProductTypeStatus(producttype_id,status){
    var url = '<?php echo html_entity_decode($change_producttype_status_url); ?>';
    var html =
            '确定更改么？';
    confirmText('提示',html, function (data) {
      $.model.commonAjax(url,{producttype_id:producttype_id,status:status}, function (data) {
        if(data.success===true){
          showSuccessText('更改成功！');
          var producttype = data.producttype;
          var status = producttype.status;
          var status_text = '';
          if(status+''==='0'){
            status_text = '上架';
            status = '1';
          }else if(status+''==='1'){
            status_text = '下架';
            status = '0';
          }
          var btn_html =
                  '<button type="button" ' +
                  'onclick="changeProductTypeStatus(\''+producttype.product_type_id+'\',\''+status+'\')" class="btn btn-sm lfx-btn">'+status_text+'</button>';
          $('.producttype-table .producttype-row-'+producttype_id+' .oper').empty().append(btn_html);
        }else{
          showErrorTex('更改失败！');
        }
      });
    });
  }
  function addPlace(){
    var url = '<?php echo html_entity_decode($add_place_url); ?>';
    var html =
            '<div class=container-fluid"">' +
//            '<div class="row">' +
//            '<div class="col-sm-12">' +
            '<form id="change_place_status_fm" class="form-horizontal">' +
            '<div class="form-group">' +
            '<label class="col-sm-2 control-label">来源地编码</label>' +
            '<div class="col-sm-10">' +
            '<input type="number" class="form-control" name="place_code" placeholder="编码">'+
            '</div>' +
            '</div>' +
            '<div class="form-group">' +
            '<label class="col-sm-2 control-label">来源地名称</label>' +
            '<div class="col-sm-10">' +
            '<input type="text" class="form-control" name="place_name" placeholder="名称">'+
            '</div>' +
            '</div>' +
            '</form>' +
//            '</div>' +
//            '</div>' +
            '</div>';
    var dialog = confirmLargeHtmlWin('新增来源地',html, function () {
      var params = $('#change_place_status_fm').formJSON();
      $.model.commonAjax(url,params, function (data) {
        if(data.success===true){
          showSuccessText('新增成功！');
          dialog.close();
          renderPlaceList(data.list);
        }else{
          showErrorTex('新增失败！');
        }
      });
    });

  }
  function addProductType(){
    var url = '<?php echo html_entity_decode($add_producttype_url); ?>';
    var html =
            '<div class=container-fluid"">' +
//            '<div class="row">' +
//            '<div class="col-sm-12">' +
            '<form id="change_producttype_status_fm" class="form-horizontal">' +
            '<div class="form-group">' +
            '<label class="col-sm-2 control-label">产品类型编码</label>' +
            '<div class="col-sm-10">' +
            '<input type="number" class="form-control" name="producttype_code" placeholder="编码">'+
            '</div>' +
            '</div>' +
            '<div class="form-group">' +
            '<label class="col-sm-2 control-label">产品类型名称</label>' +
            '<div class="col-sm-10">' +
            '<input type="text" class="form-control" name="producttype_name" placeholder="名称">'+
            '</div>' +
            '</div>' +
            '</form>' +
//            '</div>' +
//            '</div>' +
            '</div>';
    var dialog = confirmLargeHtmlWin('新增产品类型',html, function (data) {
      var params = $('#change_producttype_status_fm').formJSON();
      $.model.commonAjax(url,params, function (data) {
        if(data.success===true){
          showSuccessText('新增成功！');
          dialog.close();
          renderProducttypeList(data.list);
        }else{
          showErrorTex('新增失败！');
        }
      });
    });

  }
  $('button.update_unpaid_close_hour').on('click', function (e) {
    e.preventDefault();
    var url = '<?php echo html_entity_decode($update_unpaid_hour_url); ?>';
    var hour = $('input.unpaid_close_hour_input').val();
    $.model.commonAjax(url,{unpaid_close_hour:hour}, function (data) {
      if(data.success===true){
        showSuccessText('操作成功');
      }
    });
  });
</script>
