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
            <td class="text-center" style="display: none;"><?php echo $report['order_id']; ?></td>
            <td class="text-center"><a href="index.php?route=order/detail&token=<?php echo $token;?>&order_id=<?php echo $report['order_id']; ?>"><?php echo $report['order_no']; ?></a></td>
            <td class="text-center"><?php echo $report['order_status']; ?></td>
            <td class="text-center"><?php echo $report['order_amount']; ?></td>
            <td class="text-center"><?php echo $report['repay_status']; ?></td>
            <td class="text-center">已结算</td>
            <td class="text-center"><?php echo $report['finish_time']; ?></td>
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
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">

</script>