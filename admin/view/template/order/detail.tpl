<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--<div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>-->
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
      <div class="row">
        <div class="col-sm-12" style="min-width: 100%;overflow: auto;">
          <!-- Address Part -->
          <table style="width: 100%;">
            <tr class="navi-row">
              <td colspan="2">
                <div class="row-header">
                  <span>订单号：<?php echo $order['order_no']; ?></span>
                </div>
              </td>
            </tr>
            <tr class="navi-row">
              <td class="navi-title"><span>收货信息</span></td>
              <td class="navi-content">
                <div>
                  <form>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>地址：</label>
                        <span><?php echo $order['province_name'].' '.$order['city_name'].' '.$order['district_name'].'    '.$order['receiver_address']; ?></span>
                      </span>
                    </div>

                    <div class="content-row">
                      <span class="entry-group">
                        <label>姓名：</label>
                        <span><?php echo $order['receiver_fullname']; ?></span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>电话：</label>
                        <span><?php echo substr($order['receiver_phone'],0,3).'****'.substr($order['receiver_phone'],7); ?></span>
                      </span>
                    </div>
                  </form>
                </div>
              </td>
            </tr>
            <tr class="navi-row">
              <td colspan="2" class="navi-content" style="padding-bottom: 0px;">
                <div>
                  <form>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>订单状态：</label>
                          <span class="label label-default"><?php echo $order['pay_status']; ?></span>
                                              </span>
                      <span class="entry-group">
                        <label>实付款：</label>
                        <span>
                          <?php echo parseFormatNum($order['pay_money'],2).'元'; ?>
                          +
                          <?php echo parseFormatNum($order['pay_score']).'积分'; ?>
                        </span>
                      </span>
                    </div>
                  </form>
                </div>
              </td>
            </tr>
          </table>
          <!-- Store Part -->
          <?php
          foreach($stores as $store){
            $title = $store['name'];
            $product_list = $store['product_list'];
            ?>
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td>
                <div class="row-header">
                  <span><?php echo $title; ?></span>
                </div>
              </td>
            </tr>
            <tr class="navi-row" style="border: none;">
              <td>
                <table style="width: 100%;border: 1px solid #e4e4e4;border-top: none;"  class="product-row">
                  <?php
                   foreach($product_list as $product){
                   ?>
                  <tr>
                    <td style="min-width: 400px;width: 40%;" class="first">
                      <div>
                        <div style="float: left;padding: 5px;">
                          <div style="background-color: #666;width: 260px;height:214px;">
                            <img src="<?php echo $product['pic']; ?>" width="100%" height="100%" />
                          </div>
                        </div>
                        <div style="float: left;min-width: 250px;height:100px;padding: 5px;">
                          <div>
                              <span>
                                  <a href="index.php?route=product/view&token=<?php echo $token; ?>&product_id=<?php echo $product['product_id']; ?>">
                                    <label style="cursor: pointer;"><?php echo $product['product_no']; ?></label>
                                  </a>
                              </span>
                          </div>
                          <div><span><?php echo $product['name']; ?></span></div>
                          <div><span><?php echo $product['express_name']; ?></span></div>
                          <div style="line-height: 20px;">
                            <span style="margin-right: 10px;"><?php echo $product['count']; ?>个</span>
                            <span style="margin-right: 10px;">
                              <?php echo parseFormatNum($product['price'],2); ?>
                              <?php echo $product['price_unit']; ?>
                            </span>
                            <span><?php echo parseFormatNum($product['score']); ?>积分</span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td style="width: 25%;"><?php echo $product['product_status']; ?></td>
                    <?php
                     if($product['refund_info'] != null && $product['refund_info']['refund_status'] < 6){ ?>
                    <td><button type="button" class="btn btn-success btn-xs" onclick="getRefundInfo(<?php echo $product['product_id']?>)"><span class="fa fa-edit"></span></button>
                    </td>
                    <?php }
                     ?>
                    <td style="width: 10%;">
                     <?php
                     if($product['product_status_code']==='0'){ ?>
                      <!--<button class="btn btn-sm lfx-btn" type="button" onclick="chooseShipmentWin('<?php echo $product['product_id']; ?>')">选择物流</button>-->
                     <?php
                     }else if($product['product_status_code']==='1'){ ?>
                      <span>物流单号: <?php echo $product['express_no']; ?></span>
                     <?php
                     }else if($product['product_status_code']==='2'){ ?>
                      <!--<button class="btn btn-sm lfx-btn">确认退款</button>-->
                     <?php }
                     ?>
                    </td>
                  </tr>
                  <?php }
                   ?>
                </table>
              </td>
            </tr>
          </table>
          <?php } ?>
        </div>
      </div>


  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">

  function chooseShipmentWin(product_id){

  }
  function showShipmentWin(product_id){
    var url = '<?php echo html_entity_decode($shipment_url); ?>';
    $.model.order.getShipment(url,{product_id:product_id},function(data) {
      var html = '<div class="container-fluid">';
      if(data && data.shipment){
          var shipment = data.shipment;
          var processes = shipment.processes;
          html = html +
                  '<div class="row">';
          for(var j = 0;j < processes.length;j++){
            var process = processes[j];
            html = html +
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
                    '</div>';
          }
          html = html +
                  '</div>';
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

  function getRefundInfo(order_product_id){
    var url = 'index.php?route=order/detail/getRefundInfo&token=<?php echo $token; ?>&order_id=<?php echo $order['order_id']; ?>&product_id='+order_product_id;
    var win = showUrlWin('退货详情',url, function () {
      var transferNo = $('.refund').val();
      var urlToSave = 'index.php?route=order/detail/saveTransferNo&token=<?php echo $token; ?>&order_id=<?php echo $order['order_id']; ?>&product_id='+order_product_id+'&transfer_no='+transferNo;
      $.post(urlToSave,null,function(data){
//        data = JSON.parse(data);
//        if(debug){
//          console.log(data);
//        }
//        if(callback && $.isFunction(callback)){
//          callback(data);
//        }
      })
    });
  }

</script>
<style>
  .navi-row > div{
    display: inline-block;
    padding: 10px;
  }
  .navi-row .row-header{
    text-align: center;
    background-color: #666;
    color: #fff;
    padding: 5px;
  }
  .navi-row {
    border: 1px solid #e4e4e4;
    min-width: 100%;
  }
  .navi-row .navi-title{
    width: 30px;
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
  .product-row tr td{
    text-align: center;
  }
  .product-row tr td.first{
    text-align: left;
  }
</style>
