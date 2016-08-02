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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-reports" >
            <input type="hidden" id="input-query-or-export" name="input_query_or_export" value=""> 
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_no; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo ''/*$entry_order_no*/; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-order-type"><?php echo $entry_order_type; ?></label>
                <select name="filter_order_type" id="input-order-type" class="form-control">
                <option value="">全部</option>
                <?php foreach ($order_types as $order_type) { ?>
                <?php if ($order_type['order_type_id'] == $filter_order_type) { ?>
                <option value="<?php echo $order_type['order_type_id']; ?>" selected="selected"><?php echo $order_type['order_type_name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_type['order_type_id']; ?>"><?php echo $order_type['order_type_name']; ?></option>
                <?php } ?>
                <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-repay-status"><?php echo $entry_repay_status; ?></label>
                <select name="filter_repay_status" id="input-repay-status" class="form-control">
                  <option value="">全部</option>
                  <?php foreach ($repay_statuses as $repay_status) { ?>
                  <?php if ($repay_status['repay_status_id'] == $filter_repay_status) { ?>
                  <option value="<?php echo $repay_status['repay_status_id']; ?>" selected="selected"><?php echo $repay_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $repay_status['repay_status_id']; ?>"><?php echo $repay_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-buyer-name"><?php echo $entry_buyer_name; ?></label>
                <input type="text" name="filter_buyer_name" value="<?php echo $filter_buyer_name; ?>" placeholder="<?php echo ''/*$entry_buyer_name*/; ?>" id="input-buyer-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="">全部</option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-repay-no"><?php echo $entry_repay_no; ?></label>
                <input type="text" name="filter_repay_no" value="<?php echo $filter_repay_no; ?>" placeholder="<?php echo ''/*$entry_repay_no*/; ?>" id="input-repay-no" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-receiver-name"><?php echo $entry_receiver_name; ?></label>
                <input type="text" name="filter_receiver_name" value="<?php echo $filter_receiver_name; ?>" placeholder="<?php echo ''/*$entry_receiver_name*/; ?>" id="input-receiver-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo "从。。。"; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-receiver-phone"><?php echo $entry_receiver_phone; ?></label>
                <input type="text" name="filter_receiver_phone" value="<?php echo $filter_receiver_phone; ?>" placeholder="<?php echo ''/*$entry_receiver_phone*/; ?>" id="input-receiver-phone" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "&nbsp"; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo "到。。。"; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
          </form>
          </div>
          <div class="row" style="text-align:center">
            <button type="button" id="button-filter" class="btn btn-primary" style="margin-top:10px"><i class="fa fa-search"></i> <?php echo $btn_reports_query; ?></button>
            <button type="button" id="button-export" class="btn btn-primary" style="margin-top:10px"><i class="fa fa-download"></i> <?php echo $btn_reports_export; ?></button>
          </div>
        </div>


    <!--<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-supplier">-->
      <div class="table-responsive">
        <table class="table table-bordered table-hover ">
          <thead>
          <tr>
            <td class="text-center">
              <a><?php echo $column_order_no; ?></a>
            </td>
            <td class="text-center">
              <a><?php echo $column_order_status; ?></a>
            </td>
            <td class="text-center">
              <a><?php echo $column_order_repay_amount; ?></a>
            </td>
            <td class="text-center">
              <a><?php echo $column_order_repay_status; ?></a>
            </td>
            <td class="text-center">
              <a><?php echo $column_order_repay_date; ?></a>
            </td>
            <td class="text-center">
              <a><?php echo $column_order_repay_trade_no; ?></a>
            </td>
          
          </tr>
          </thead>
          <tbody>
          <?php if ($reports) { ?>
          <?php foreach ($reports as $report) { ?>
          <tr>
            <!--
            <td class="text-center" style="display: none;"><?php echo $report['order_id']; ?></td>
            -->
            <td class="text-center"><?php echo $report['order_no']; ?></td>
            <td class="text-center"><?php echo $report['order_status']; ?></td>
            <td class="text-center"><?php echo $report['supplier_price']; ?></td>
            <td class="text-center"><?php echo $report['repay_status']; ?></td>
            <td class="text-center"><?php echo $report['repay_time']; ?></td>
            <td class="text-center"><?php echo $report['transfer_no']; ?></td>
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
    <!--</form>-->
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {

  $('#input-query-or-export').val('0');
  url = 'index.php?route=reports/list&token=<?php echo $token; ?>';
  $('#form-reports').attr('action',url);
  $('#form-reports').submit();

});

$('#button-export').on('click', function() {

  $('#input-query-or-export').val('1');
  url = 'index.php?route=reports/list/export&token=<?php echo $token; ?>';
  $('#form-reports').attr('action',url);
  $('#form-reports').submit();

});

//--></script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>

<?php echo $footer; ?>
