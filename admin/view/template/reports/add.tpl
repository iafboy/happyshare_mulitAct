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
    <?php echo $entries ?>
    <form id="main-table-fm">
      <div class="table-responsive">
        <table class="table table-bordered table-hover lfx-table">
          <thead>
          <tr>
            <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr data-order-id="<?php echo $order['order_id'];?>" data-supplier-id="<?php echo $order['supplier_id'];?>">
            <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
              <?php } ?></td>
            <td class="text-center"><?php echo $order['order_no']; ?></td>
            <td class="text-center"><?php echo $order['order_status']; ?></td>
            <td class="text-center"><?php echo $order['repay_status']; ?></td>
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
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <button class="btn cal-fee-btn lfx-btn"><?php echo $btn_reports_cal_fee; ?></button>
        <button class="btn lfx-btn btn-default" onclick="turn2ReportsPay()"><?php echo $btn_reports_pay_fee; ?></button>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">
  function turn2ReportsPay(){
    var supplierId = '<?php echo $supplier_id ?>';
    if(!is_valid_str(supplierId)){
      showErrorText('未选择供应商');
      return;
    }
    var form = '#main-table-fm';
    var params = $(form).formJSON();
    if(!params['selected[]']){
      return showErrorText('未选择订单！');
    }
    var url = 'index.php?route=reports/add/checkCanRepay&token=<?php echo $token; ?>';
    $.model.commonAjax(url,params, function (data) {
      //if(data.success === true){
      $.extend(params,{supplier_id:supplierId});
          var orderIds = params['selected[]'];
          if(orderIds){
            var orderIdsStr;
            if($.isArray(orderIds) && orderIds.length>0){
              orderIdsStr =orderIds.join(',');
            }
            else if(is_valid_str(orderIds)){
              orderIdsStr =orderIds;
            }
            if(!is_valid_str(orderIdsStr)){
              return showErrorText('参数错误');
            }
            var loc = 'index.php?route=reports/pay&token=<?php echo $token ?>&supplier_id='+supplierId
                    +'&order_ids='+orderIdsStr;
            window.location = loc;
          }
    });
  }
  $(function(){

    $('.cal-fee-btn').on('click',function(e){
      var form = '#main-table-fm';
      var params = $(form).formJSON();
      if(!params['selected[]']){
            return showErrorText('未选择订单！');
      }
      params.supplier_id ='<?php echo $supplier_id ?>';
      var url = '<?php echo html_entity_decode($cal_fee_url); ?>';
      $.model.reports.calfee(url,params, function (data) {
        var html =
                '<div class="container-fluid">' +
                '<div class="row">' +
                '<div class="col-sm-12">' +
                '<span>总计结算金额为：<span style="color: #00BC3F;">￥'+data.amount+'</span></span>' +
                '</div>' +
                '</div>' +
                '</div>';
        showHtmlWin('结算金额',html);
      });
    });
  });
</script>