<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  
  <div class="page-header">
    <div class="container-fluid">

      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
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
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" style="display: block;"><?php echo $entry_product_code; ?></label>
                <input type="text" name="filter_product_no" autocomplete="off" value="<?php echo $filter_product_no; ?>" class="lfx-text w-10" />
              </div>
              <div class="form-group">
                <label class="control-label" style="display: block"><?php echo $entry_product_price_supplier; ?></label>
                <div style="display:inline-block;">
                  <input type="text" name="filter_product_price_supplier_min" value="<?php echo $filter_product_price_supplier_min; ?>" class="lfx-text w-4" />
                  <div style="display:inline;position:relative;width:10%;"><?php echo "&#8211;&#8211;&#8211;"; ?></div>
                  <input type="text" name="filter_product_price_supplier_max" value="<?php echo $filter_product_price_supplier_max; ?>" class="lfx-text w-4" />
                </div>
              </div>

              <div class="form-group">
                <label class="control-label" style="display: block;"><?php echo $entry_status; ?></label>
                <select name="filter_status"  class="lfx-select w-10">
                  <option value="*">全部</option>
                  <?php foreach ($pstatus as $status ) { ?>
                  <?php if (isset($filter_status) && $filter_status == $status['pstatus_id']) { ?>
                  <option value="<?php echo $status['pstatus_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $status['pstatus_id']; ?>"><?php echo $status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>

            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" style="display: block"><?php echo $entry_product_name; ?></label>
                <input type="text" autocomplete="off" name="filter_name" value="<?php echo $filter_name; ?>"  placeholder="<?php echo $entry_product_name; ?>" class="lfx-text w-10" />
              </div>
              <div class="form-group">
                <label class="control-label" style="display: block;"><?php echo $entry_product_stock; ?></label>
                <div>
                  <input type="text" autocomplete="off" name="filter_quantity_min" value="<?php echo $filter_quantity_min; ?>" class="lfx-text w-4"/>
                  <div style="display:inline;position:relative;width:8%;"><?php echo "&#8211;&#8211;&#8211;"; ?></div>
                  <input type="text" autocomplete="off" name="filter_quantity_max" value="<?php echo $filter_quantity_max; ?>" class="lfx-text w-4" style="float:right"/>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label" style="display: block"><?php echo $entry_product_sales; ?></label>
                <div style="display:inline-block;">
                  <input type="text" name="filter_product_sales_min" value="<?php echo $filter_product_sales_min; ?>" class="lfx-text w-4" />
                  <div style="display:inline;position:relative;width:10%;"><?php echo "&#8211;&#8211;&#8211;"; ?></div>
                  <input type="text" name="filter_product_sales_max" value="<?php echo $filter_product_sales_max; ?>" class="lfx-text w-4" style="float:right"/>
                </div>
              </div>

           </div>
            <div class="col-sm-3">
              <div class="form-group" >
                <label class="control-label" style="display: block"><?php echo $entry_product_place; ?></label>
                <select name="filter_origin"  class="lfx-select w-10" >
                  <option value="*">全部</option>
                  <?php foreach( $countries as $country ) {  ?>
                  <option value="<?php echo $country['origin_place_id']; ?>"><?php echo $country['place_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-product-price-market"><?php echo $entry_product_price_market; ?></label>
                <div style="display:block;">
                  <input type="text" name="filter_product_price_market_min" value="<?php echo $filter_product_price_market_min; ?>" class="lfx-text w-4" />
                  <div style="display:inline;position:relative;width:10%;"><?php echo "&#8211;&#8211;&#8211;"; ?></div>
                  <input type="text" name="filter_product_price_market_max" value="<?php echo $filter_product_price_market_max; ?>" class="lfx-text w-4" style="float:right"/>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-product-recommend-index"><?php echo $entry_product_recommend_index; ?></label>
                <div style="display:block;">
                  <input type="text" autocomplete="off" name="filter_product_recommend_index_min" value="<?php echo $filter_product_recommend_index_min; ?>" class="lfx-text w-4" />
                  <div style="display:inline;position:relative;width:10%;"><?php echo "&#8211;&#8211;&#8211;"; ?></div>
                  <input type="text" autocomplete="off" name="filter_product_recommend_index_max" value="<?php echo $filter_product_recommend_index_max; ?>" class="lfx-text w-4" style="float:right"/>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group" >
                <label class="control-label" style="display: block"><?php echo $entry_product_catagory; ?></label>
                <select name="filter_product_category" id="input-product-category" class="lfx-select w-10">
                  <option value="*">全部</option>
                  <?php foreach ($categories as $category ) { ?>
                  <?php if (isset($filter_product_category) && $filter_product_category == $category['cid']) { ?>
                  <option value="<?php echo $category['cid']; ?>" selected="selected"><?php echo $category['cname']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['cid']; ?>"><?php echo $category['cname']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" style="display: block"><?php echo $entry_product_comments; ?></label>
                <div style="display:inline-block;">
                  <input type="text" name="filter_product_comments_min" value="<?php echo $filter_product_comments_min; ?>" class="lfx-text w-4" />
                  <div style="display:inline;position:relative;width:10%;"><?php echo "&#8211;&#8211;&#8211;"; ?></div>
                  <input type="text" name="filter_product_comments_max" value="<?php echo $filter_product_comments_max; ?>" class="lfx-text w-4"  style="float:right"/>
                </div>
              </div>
            </div>
          </div>

          <div class="row" style="text-align:center;margin-bottom: 20px;">
            <button type="button" id="button-filter" class="btn btn-primary" ><i class="fa fa-search"></i> <?php echo $button_query; ?></button>
          </div>
          <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
            <div class="table-responsive">
              <!--<table class="table table-bordered table-hover">-->
              <table class="table table-bordered lfx-table"  style="table-layout:fixed">
                <thead>
                  <tr>
                    <!-- "商品编号" -->
                    <td class="text-center" style="width: 10%" >
                      <?php echo $column_id; ?>
                    <!-- "名称" -->
                    <td class="text-center" width="15%">
                      <?php echo $column_name; ?>
                    <!-- "状态" -->
                    <td class="text-center">
                      <?php echo $column_status; ?>
                    <!-- "来源地" -->
                    <td class="text-center">
                      <?php echo $column_origin; ?>
                    <!-- "商品类型" -->
                    <td class="text-center" width="10%">
                      <?php echo $column_model; ?>
                    <!-- "市场价" -->
                    <td class="text-center">
                      <?php echo $column_price; ?>
                    <!-- "供货价" -->
                    <td class="text-center">
                      <?php echo $column_supply_price; ?>
                    <!-- "库存量" -->
                    <td class="text-center">
                      <?php echo $column_quantity; ?>
                    <!-- "商品累计销量" -->
                    <td class="text-center">
                      <?php echo $column_sales; ?>
                    <!-- "分享文案数量" -->
                    <td class="text-center">
                      <?php echo $column_document; ?>
                    <!-- "评论数量" -->
                    <td class="text-center">
                      <?php echo $column_comments; ?>
                    <!-- "推荐指数" -->
                    <td class="text-center"><?php echo $column_product_recommend_index; ?></td>
                    <!-- "编辑商品" -->
                    <td class="text-center">操作</td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($products) { ?>
                  <?php foreach ($products as $product) { ?>
                  <tr>
                    <td class="text-center" style="word-break:break-all;"><?php echo $product['product_no']; ?></td>

                    <td class="text-center" title="<?php echo $product['name']; ?>" style="word-break:break-all;"><?php echo $product['name']; ?></td>
                    <td class="text-center" style="word-break:break-all;"><?php echo $product['status']; ?></td>

                    <!-- liuhang add-->
                    <td class="text-center" style="word-break:break-all;"><?php echo $product['origin']; ?></td>

                    <td class="text-center" title="<?php echo $product['model']; ?>" style="word-break:break-all;" ><?php echo $product['model']; ?></td>
                    <td class="text-center" style="word-break:break-all;" ><?php echo $product['price']; ?></td>
                    <td class="text-center" style="word-break:break-all;" ><?php echo $product['supplyprice']; ?></td>

                    <td class="text-center" style="word-break:break-all;" >
                      <span><?php echo $product['quantity']; ?></span>
                      <a style="float: right;" onclick="editQuantity(<?php echo $product['product_id']; ?>,<?php echo $product['quantity']; ?>)"
                         data-toggle="tooltip" title="调整库存" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>
                    </td>
                    <td class="text-center" style="word-break:break-all;" ><?php echo $product['sales']; ?></td>
                    <td class="text-center" style="word-break:break-all;" ><?php echo $product['document']; ?></td>
                    <td class="text-center" style="word-break:break-all;" ><?php echo $product['comments']; ?></td>
                    <td class="text-center" style="word-break:break-all;" ><?php echo $product['recommend']; ?></td>

                    <?php
                      if($product['status_code'] == 3){ ?>
                      <td class="text-center" style="word-break:break-all;" >
                        <a href="index.php?route=product/view&token=<?php echo $token; ?>&product_id=<?php echo $product['product_id']; ?>"
                           data-toggle="tooltip" title="查看" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a>
                        <a onclick="putOffShell('<?php echo $product['product_id']; ?>')"
                           data-toggle="tooltip" title="下架" class="btn btn-danger btn-xs"><i class="fa fa-download"></i></a>
                      </td>

                    <?php }else{ ?>
                    <td class="text-center" style="word-break:break-all;" >
                      <a onclick="turn2Edit(<?php echo $product['product_id']; ?>,<?php echo $product['status_code']; ?>)"
                         data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                    </td>
                    <?php }
                    ?>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </form>
          <div class="row">
          <div class="col-sm-6 text-center"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-center"><?php echo $results; ?></div>
        </div>
  </div>
  <script type="text/javascript"><!--

    function turn2Edit(productId,status){
      var url = 'index.php?route=product/edit&token=<?php echo $token; ?>&product_id='+productId;
      location.href = url;
    }

    function editQuantity(productId,curQuantity){
      var html =
              '<div class="row">' +
              '<form id="qua-fm">' +
              '<div class="form-group ">' +
              '<label class="label-control col-sm-2 col-sm-push-2" style="line-height: 200%;">库存调整为：</label>' +
              '<div class="col-sm-6 col-sm-push-2">' +
              '<input type="number" name="quantity" class="form-control " value="'+curQuantity+'" />' +
              '</div>' +
              '</div>' +
              '</form>' +
              '</div>';
      confirmLargeHtmlWin('操作',html, function () {
        var params = $('#qua-fm').formJSON();
        if(is_valid_str(params.quantity)){
          var url = 'index.php?route=catalog/product/modQuantity&token=<?php echo $token; ?>';
          $.model.commonAjax(url,{product_id:productId,quantity:params.quantity}, function (data) {
            if(data.success === true){
              window.location = location.href;
            }else{
              showErrorText(data.errMsg);
            }
          })
        }else{
          return showErrorText('未填写库存');
        }
      });
    }
    function putOffShell(productId){
      var html ='确定下架么？';
      confirmLargeHtmlWin('操作',html, function () {
        var params = { };
          var url = 'index.php?route=catalog/product/putOffShell&token=<?php echo $token; ?>';
          $.model.commonAjax(url,{product_id:productId}, function (data) {
            if(data.success === true){
              window.location = location.href;
            }else{
              showErrorText(data.errMsg);
            }
          })
      });
    }





$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

  var filter_product_no = $('input[name=\'filter_product_no\']').val();

	if (filter_product_no) {
		url += '&filter_product_no=' + encodeURIComponent(filter_product_no);
	}

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_origin = $('select[name=\'filter_origin\']').val();

	if (filter_origin != '*') {
		url += '&filter_origin=' + encodeURIComponent(filter_origin);
	}
  
  var filter_product_category = $('select[name=\'filter_product_category\']').val();

	if (filter_product_category != '*') {
		url += '&filter_product_category=' + encodeURIComponent(filter_product_category);
	}
  var filter_product_price_market_min = $('input[name=\'filter_product_price_market_min\']').val();

	if (filter_product_price_market_min) {
		url += '&filter_product_price_market_min=' + encodeURIComponent(filter_product_price_market_min);
	}
 
  var filter_product_price_market_max = $('input[name=\'filter_product_price_market_max\']').val();

	if (filter_product_price_market_max) {
		url += '&filter_product_price_market_max=' + encodeURIComponent(filter_product_price_market_max);
	}
 
  var filter_product_price_supplier_min = $('input[name=\'filter_product_price_supplier_min\']').val();

	if (filter_product_price_supplier_min) {
		url += '&filter_product_price_supplier_min=' + encodeURIComponent(filter_product_price_supplier_min);
	}
  
  var filter_product_price_supplier_max = $('input[name=\'filter_product_price_supplier_max\']').val();

	if (filter_product_price_supplier_max) {
		url += '&filter_product_price_supplier_max=' + encodeURIComponent(filter_product_price_supplier_max);
	}
 
  var filter_quantity_min = $('input[name=\'filter_quantity_min\']').val();

	if (filter_quantity_min) {
		url += '&filter_quantity_min=' + encodeURIComponent(filter_quantity_min);
	}

  var filter_quantity_max = $('input[name=\'filter_quantity_max\']').val();

	if (filter_quantity_max) {
		url += '&filter_quantity_max=' + encodeURIComponent(filter_quantity_max);
	}
 
  var filter_product_sales_min = $('input[name=\'filter_product_sales_min\']').val();

	if (filter_product_sales_min) {
		url += '&filter_product_sales_min=' + encodeURIComponent(filter_product_sales_min);
	}
 
  var filter_product_sales_max = $('input[name=\'filter_product_sales_max\']').val();

	if (filter_product_sales_max) {
		url += '&filter_product_sales_max=' + encodeURIComponent(filter_product_sales_max);
	}
 
  var filter_product_comments_min = $('input[name=\'filter_product_comments_min\']').val();

	if (filter_product_comments_min) {
		url += '&filter_product_comments_min=' + encodeURIComponent(filter_product_comments_min);
	}

  var filter_product_comments_max = $('input[name=\'filter_product_comments_max\']').val();

	if (filter_product_comments_max) {
		url += '&filter_product_comments_max=' + encodeURIComponent(filter_product_comments_max);
	}

  var filter_product_recommend_index_min = $('input[name=\'filter_product_recommend_index_min\']').val();

	if (filter_product_recommend_index_min) {
		url += '&filter_product_recommend_index_min=' + encodeURIComponent(filter_product_recommend_index_min);
	}

  var filter_product_recommend_index_max = $('input[name=\'filter_product_recommend_index_max\']').val();

	if (filter_product_recommend_index_max) {
		url += '&filter_product_recommend_index_max=' + encodeURIComponent(filter_product_recommend_index_max);
	}


	location = url;
});
//--></script> 
  </div>
<?php echo $footer; ?>
