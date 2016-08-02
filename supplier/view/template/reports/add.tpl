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
    <div class="well">
        <form class="">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" ><?php echo $entry_supplier_no; ?></label>
                <select type="text" name="filter_supplier_id" value="<?php echo $filter_supplier_id; ?>"
                        class="form-control">
                  <option value="*">全部</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label"><?php echo $entry_order_amount; ?></label>
                <input type="text" name="filter_order_amount" value="<?php echo $filter_order_amount; ?>"
                       class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <label class="control-label" style="display: block"><?php echo $entry_order_no; ?></label>
              <div class="form-group" >
                <input type="number" name="filter_order_no" value="<?php echo $filter_order_no; ?>"
                       class="form-control" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2">
              <label class="control-label" style="display: block"><?php echo $entry_repay_payaccount; ?></label>
              <div class="form-group">
                <select type="text" name="filter_repay_bankid" value="<?php echo $filter_repay_bankid; ?>"
                       class="form-control" >
                  <option value="1">中国银行</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <label class="control-label" >&nbsp;</label>
              <div class="form-group" >
                <input type="number" name="filer_repay_bankcard_no" value="<?php echo $filer_repay_bankcard_no; ?>"
                       class="form-control" />
              </div>
            </div>

            <div class="col-sm-2">
              <label class="control-label" style="display: block"><?php echo $entry_repay_supplieraccount; ?></label>
              <div class="form-group">
                <select type="text" name="filter_supplier_bankid" value="<?php echo $filter_supplier_bankid; ?>"
                       class="form-control" >
                  <option value="1">中国银行</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <label class="control-label" >&nbsp;</label>
              <div class="form-group" >
                <input type="number" name="filter_supplier_bankcard_no" value="<?php echo $filter_supplier_bankcard_no; ?>"
                       class="form-control" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2">
              <label class="control-label" ><?php echo $entry_order_status; ?></label>
              <div class="form-group" >
                <select type="number" name="filter_order_status" value="<?php echo $filter_order_status; ?>"
                       class="form-control" >
                  <option value="*">全部</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" style="display: block;">
                  <?php echo $entry_order_finishtime_start; ?></label>
                <div class="input-group date" style="display: inline-block;">
                  <input style="width: 85%;display: inline-block;" type="text" name="filter_order_finishtime_start"
                         value="<?php echo $filter_order_finishtime_start; ?>"
                         data-date-format="YYYY-MM-DD" class="form-control" />
                      <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" style="display: block;">
                  <?php echo $entry_order_finishtime_end; ?></label>
                <div class="input-group date" style="display: inline-block;">
                  <input style="width: 85%;display: inline-block;" type="text" name="filter_order_finishtime_end"
                         value="<?php echo $filter_order_finishtime_end; ?>"
                         data-date-format="YYYY-MM-DD" class="form-control" />
                      <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <label class="control-label">&nbsp;</label>
              <div class="form-group">
                <button class="btn lfx-btn"><?php echo $btn_reports_query; ?></button>
              </div>
            </div>
          </div>

        </form>

    </div>


    <div class="container">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-2"></div>
        <div class="col-sm-11"></div>
      </div>
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10"></div>
        <div class="col-sm-2"></div>
        <div class="col-sm-10"></div>
      </div>
    </div>





    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-supplier">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
          <tr>
            <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($suppliers) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
              <?php } ?></td>
            <td class="text-center"><?php echo $order['order_no']; ?></td>
            <td class="text-center"><?php echo $order['order_status']; ?></td>
            <td class="text-center"><?php echo $order['order_amount']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
    <div class="row">
      <div class="col-sm-12 text-center">
        <button class="btn lfx-btn"><?php echo $btn_reports_cal_fee; ?></button>
        <button class="btn lfx-btn"><?php echo $btn_reports_pay_fee; ?></button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=supplier/applylist&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_price = $('input[name=\'filter_price\']').val();

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script> 
  </div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
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
  //--></script>