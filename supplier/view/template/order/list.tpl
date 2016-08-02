<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--<div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div> -->
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
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr class="order-item-<?php echo $order['order_id']; ?>">
            <td class="text-center" style="display: none;"><?php echo $order['order_id']; ?></td>
            <td class="text-center"><a href="<?php echo html_entity_decode($order_detail_url.'&order_id='.$order['order_id']); ?>"><?php echo $order['order_no']; ?></a></td>
            <td class="text-center" class="order-status"><?php echo $order['order_status_text']; ?></td>
            <td class="text-center"><?php echo $order['fullname']; ?></td>
            <td class="text-center"><?php echo $order['receiver_fullname']; ?></td>
            <td class="text-center"><?php echo $order['receiver_phone']; ?></td>
            <td class="text-center"><?php echo $order['province_name'].' '.$order['city_name'].' '.$order['district_name'].' '.$order['receiver_address']; ?></td>
            <td class="text-center"><?php echo $order['total']; ?></td>
            <td class="text-center"><?php echo $order['date_added']; ?></td>
            <td class="text-center"><?php echo $order['comment']; ?></td>
            <!--<td class="text-center" class="oper">
              <?php if($order['order_status']==5){ ?>
              <button type="button" class="lfx-btn btn btn-xs btn-default" onclick="confirmReturnGoods('<?php echo $order['order_id']; ?>')">确认退货</button>
              <?php } ?>
            </td>-->
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?> <button type="button" id="button-export" class="btn" style="margin-top:10px"><i class="fa fa-download"></i>导出</button></div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">


  function confirmReturnGoods(orderId){

      var dialog = confirmLargeHtmlWin('确认','确认同意退货么？', function () {
          var url = 'index.php?route=order/list/confirmReturnGoods&token=<?php echo $token; ?>';
          $.model.commonAjax(url,{orderId:orderId}, function (data) {
             if(data.success === true){
                 dialog.close();
                 $('#form-supplier table tr.order-item-'+orderId+' td.order-status').empty().append('退货中');
                 $('#form-supplier table tr.order-item-'+orderId+' td.oper').empty().append('');
                 return showSuccessText('操作成功!');
             }else{
                 return showErrorText('操作失败！');
             }
          });
      });


  }

  function showShipmentMsg(order_id){
    var url = '<?php echo html_entity_decode($shipment_url); ?>';
    $.model.order.getShipment(url,{order_id:order_id},function(data) {
      var html = '<div class="container-fluid">';
      if(data && data.shipments && data.shipments.length>0){
        for(var i = 0;i < data.shipments.length;i++){
          var shipment = data.shipments[i];
          var processes = shipment.processes;
          var supplier_name = shipment.supplier_name;
          var products = shipment.products;
          html = html +
                  '<div class="row">' +
                  '<div class="col-sm-12">' +
                  '<div class="panel panel-default" style="border:none;">' +
                  '<div class="panel-header" style="    padding: 5px;border-radius: 5px 5px 0px 0px;background: #ccc;"><h3 class="panel-title">'+supplier_name+'</h3></div>';

          html = html +
                  '<div class="panel-body" style="border: 1px solid #e4e4e4;border-top: none;">';

          for(var j = 0;j < processes.length;j++){
            var process = processes[j];
            html = html +
                    '<div class="container-fluid">' +
                    '<div class="row">' +
                    '<div class="col-sm-3">' +
                    '<span>'+process.time+'</span>' +
                    '</div>' +
                    '<div class="col-sm-3">' +
                    '<span>已抵达' + process.current_shipments_site +'</span>' +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span>下一站点：'+process.next_shipments_site+'</span>' +
                    '</div>' +
                    '<div class="col-sm-2">' +
                    '<span>'+process.process_text+'</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
          }
          html = html +
                  '</div>';



          html = html +
                  '</div>' +
                  '</div>' +
                  '</div>';


        }

      }else{
        html = html +
                '<div class="row">' +
                '<div class="col-sm-12">' +
                '<span>暂无物流信息</span>' +
                '</div>' +
                '</div>';
      }
      html += '</div>';
      showWideHtmlWin('物流信息',html);
      return false;
    });
  }
  $('#button-export').on('click', function() {
      url = '<?php echo HTTP_SERVER ?>index.php?route=order/list/exportOrders&token=<?php echo $token; ?>';
      $('#order_list_fm').attr('action',url);
      $('#order_list_fm').attr('method','post');
      $('#order_list_fm').submit();
      $('#order_list_fm').attr('method','get');
  });
</script>
