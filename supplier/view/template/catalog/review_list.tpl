<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-review').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      -->
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-product-code"><?php echo $entry_product_code; ?></label>
                <input type="text" name="filter_product_code" value="<?php echo $filter_product_code; ?>" placeholder="<?php echo $entry_product_code; ?>" id="input-product-code" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-user-account"><?php echo $entry_user_account; ?></label>
                <input type="text" name="filter_user_account" value="<?php echo $filter_user_account; ?>" placeholder="<?php echo $entry_user_account; ?>" id="input-user-account" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-product-name"><?php echo $entry_product_name; ?></label>
                <input type="text" name="filter_product_name" value="<?php echo $filter_product_name; ?>" placeholder="<?php echo $entry_product_name; ?>" id="input-product-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-key-word"><?php echo $entry_key_word; ?></label>
                <div>
                  <div class="col-sm-8" style="display:inline;padding-left: 0;">
                  <input type="text" name="filter_key_word" value="<?php echo $filter_key_word; ?>" placeholder="<?php echo $entry_key_word; ?>" id="input-key-word" class="form-control" />
                  </div>
                  <div class="col-sm-4" style="display:inline;">
                  <label class="checkbox" >
                  <?php if ($filter_key_word_checkbox == 0) { ?>
                  <input style="width:20px; height:20px;vertical-align: bottom;" type="checkbox"  name="filter_key_word_checkbox" id="filter-key-word-checkbox"><?php echo $text_keyword_cb;?>
                  <?php } else { ?>
                  <input style="width:20px; height:20px;vertical-align: bottom;" type="checkbox" checked name="filter_key_word_checkbox" id="filter-key-word-checkbox" ><?php echo $text_keyword_cb;?>
                  <?php } ?>
                  </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-product-type"><?php echo $entry_product_category; ?></label>
                <select name="filter_product_type" id="input-product-type" class="form-control">
                  <option value="*">全部</option>
                  <?php foreach ($product_types as $product_type ) { ?>
                  <?php if (isset($filter_product_type) && $filter_product_type == $product_type['cid']) { ?>
                  <option value="<?php echo $product_type['cid']; ?>" selected="selected"><?php echo $product_type['cname']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $product_type['cid']; ?>"><?php echo $product_type['cname']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div> 
              <div>
                <label class="control-label" ><?php echo $entry_review_date; ?></label>
                <div style="display:inline-block;">
                  <div class="col-sm-5" style="padding:0;margin:0;border:0;">
                    <div class="input-group date" style="display: inline-block;">
                      <input style="display: inline-block;width: 80%;" type="text" name="filter_start_time" value="<?php echo $filter_start_time; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                      <span class="input-group-btn" style="width: 20%;">
                      <button class="btn-new btn-default" type="button" name="btn1"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-2" style="margin:0px auto;">
                    <label class="control-label"><?php echo "&#8211;&#8211;&#8211;"; ?></label>
                  </div>
                  <div class="col-sm-5" style="padding:0;margin:0;border:0;">
                    <div class="input-group date" style="display: inline-block;">
                      <input style="display: inline-block;width: 80%;" type="text" name="filter_end_time" value="<?php echo $filter_end_time; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                      <span class="input-group-btn" style="width: 20%;">
                      <button class="btn-new btn-default" type="button" name="btn2"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<!--
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
                <input type="text" name="filter_product" value="<?php echo $filter_product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-author"><?php echo $entry_author; ?></label>
                <input type="text" name="filter_author" value="<?php echo $filter_author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
-->

          </div>
          <div class="row" style="text-align:center">
            <button type="button" id="button-filter" class="btn btn-primary" style="margin-top:10px"><i class="fa fa-search"></i> <?php echo $button_query; ?></button>
          </div>

        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-review">
          <div class="table-responsive">
            <!--<table class="table table-bordered table-hover">-->
            <table class="table table-bordered"  style="table-layout:fixed">
              <thead>
                <tr>
                <!--
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  -->
                  <td class="text-center"><?php if ($sort == 'p.product_id') { ?>
                    <a href="<?php echo $sort_pid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_pid; ?>"><?php echo $column_product_id; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_pname; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_pname; ?>"><?php echo $column_product; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'p.supplier_id') { ?>
                    <a href="<?php echo $sort_sid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_sid; ?>"><?php echo $column_author; ?></a>
                    <?php } ?></td>
                  <!--
                  <td class="text-right"><?php if ($sort == 'r.rating') { ?>
                    <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  -->
                  <td class="text-center"><?php if ($sort == 'r.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-center" width="40%">
                  <a><?php echo $column_review_content;?></a>
                  </td>

                 <td class="text-center"><a><?php echo $column_action; ?></a></td>

                </tr>
              </thead>
              <tbody>
                <?php if ($reviews) { ?>
                <?php foreach ($reviews as $review) { ?>
                <tr>
                <!--
                  <td class="text-center"><?php if (in_array($review['review_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-center"><?php echo $review['review_id']; ?></td>
                  <td class="text-center"><?php echo $review['name']; ?></td>
                  <td class="text-center"><?php echo $review['author']; ?></td>
                  <td class="text-right"><?php echo $review['rating']; ?></td>
                  <td class="text-left"><?php echo $review['status']; ?></td>
                  -->
                  <td class="text-center"><a href="index.php?route=product/view&token=<?php echo $token; ?>&product_id=<?php echo $review['product_id']; ?>"><?php echo $review['pid']; ?></a></td>
                  <td class="text-center"><?php echo $review['name']; ?></td>
                  <!--<td class="text-center"><?php echo $review['cid']; ?></td>-->
                  <td class="text-center"><?php echo $review['cname']; ?></td>
                  <td class="text-center"><?php echo $review['date_added']; ?></td>
                  <td class="text-center" title="<?php echo $review['content'];?>" style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"><?php echo $review['content']; ?></td>

                  <td class="text-center">
                    <?php if( $review['reply_count'] <1 ){
                       echo '<a href="'.$review['reply'].'" data-toggle="tooltip" title="'.$button_review_reply.'" class="btn btn-primary"><i class="fa fa-pencil"></i>'.$button_review_reply.'</a>';
                     } else {
                        echo ''.$review['reply_msg'].'';
                     } ?>
                  </td>

                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=catalog/review&token=<?php echo $token; ?>';
	
	/*
  var filter_product = $('input[name=\'filter_product\']').val();
	
	if (filter_product) {
		url += '&filter_product=' + encodeURIComponent(filter_product);
	}
	
	var filter_author = $('input[name=\'filter_author\']').val();
	
	if (filter_author) {
		url += '&filter_author=' + encodeURIComponent(filter_author);
	}
	
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}		
	*/
  var filter_product_code = $('input[name=\'filter_product_code\']').val();
	
	if (filter_product_code) {
		url += '&filter_product_code=' + encodeURIComponent(filter_product_code);
	}
	
  var filter_user_account = $('input[name=\'filter_user_account\']').val();
	
	if (filter_user_account) {
		url += '&filter_user_account=' + encodeURIComponent(filter_user_account);
	}
	
  var filter_product_name = $('input[name=\'filter_product_name\']').val();
	
	if (filter_product_name) {
		url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
	}

  var filter_key_word = $('input[name=\'filter_key_word\']').val();
	
	if (filter_key_word) {
		url += '&filter_key_word=' + encodeURIComponent(filter_key_word);
	}

  var filter_kw_is_checked = $('#filter-key-word-checkbox').is(':checked');

	if (filter_kw_is_checked == true) {
		url += '&filter_key_word_checkbox=1';
	} else {
		url += '&filter_key_word_checkbox=0';
  }

  var filter_product_type = $('select[name=\'filter_product_type\']').val();
	
	if (filter_product_type != '*') {
		url += '&filter_product_type=' + encodeURIComponent(filter_product_type);
	}

  var filter_start_time = $('input[name=\'filter_start_time\']').val();
	
	if (filter_start_time) {
		url += '&filter_start_time=' + encodeURIComponent(filter_start_time);
	}

  var filter_end_time = $('input[name=\'filter_end_time\']').val();
	
	if (filter_end_time) {
		url += '&filter_end_time=' + encodeURIComponent(filter_end_time);
	}

/*
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
  */

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
