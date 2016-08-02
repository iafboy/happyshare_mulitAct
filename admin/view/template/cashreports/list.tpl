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
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-supplier">
      <div class="table-responsive">
        <table class="table table-bordered table-hover lfx-table">
          <thead>
          <tr>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($reports) { ?>
          <?php foreach ($reports as $report) { ?>
            <tr>
              <td class="text-center"><?php echo $report['customer_name']; ?></td>
              <td class="text-center"><?php echo $report['cash_report_id']; ?></td>
              <td class="text-center"><?php echo $report['cash_apply_time']; ?></td>
              <td class="text-center"><?php echo parseFormatNum($report['cash_amount'],2); ?>元</td>
              <td class="text-center"><?php echo $status_cash_pay_status[''.$report['cash_pay_status']]; ?></td>
              <td class="text-center"><?php echo $report['cash_pay_no']; ?></td>
              <td class="text-center">
                <?php
                 if($report['cash_pay_status'].''==='0'){ ?>
                  <button type="button" class="btn btn-xs lfx-btn btn-default" onclick="payCash(<?php echo $report['cash_report_id']; ?>)">支付</button>
                 <?php }else if($report['cash_pay_status'].''==='1'){ ?>
                <button type="button" class="btn btn-xs lfx-btn btn-default" onclick="viewCash(<?php echo $report['cash_report_id']; ?>)">查看</button>
                 <?php }
                 ?>
              </td>
            </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
<?php echo $footer; ?>
<script type="text/javascript">
  function payCash(reportId){
    window.location = 'index.php?route=cashreports/pay&token=<?php echo $token; ?>&cash_report_id='+reportId;
  }
  function viewCash(reportId){
    window.location = 'index.php?route=cashreports/pay&token=<?php echo $token; ?>&cash_report_id='+reportId+'&view=1';
  }
</script>