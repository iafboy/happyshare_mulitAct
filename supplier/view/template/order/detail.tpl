<link href="view/stylesheet/leshare.css" />
<style>
    label,span{
        font-size: 14px;
    }
    .navi-row .navi-title span{
        font-size: 20px;
    }
</style>
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
                        <span><?php echo $order['province_name'].' '.$order['city_name'].' '.$order['district_name'].'    '. $order['receiver_address']; ?></span>
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
                        <span><?php echo $order['receiver_phone']; ?></span>
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
                        <?php if($order['order_status']==2){ ?>
                            <button class="btn btn-sm lfx-btn" style="float: right;" type="button" onclick="chooseShipmentWin('<?php echo $product['product_id']; ?>')">填写发货单</button>
                        <?php }else { ?>
                        <button class="btn btn-sm lfx-btn" style="float: right;" type="button" onclick="chooseShipmentWin('<?php echo $product['product_id']; ?>')">查看发货单</button>
                        <?php } ?>
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
                  <span>订单列表</span>
                </div>
              </td>
            </tr>
            <tr class="navi-row" style="border: none;">
              <td>
                <table style="width: 100%;border: 1px solid #e4e4e4;border-top: none;"  class="product-row">
                    <thead>
                    <tr>
                        <td>商品信息</td>
                        <td>货物状态</td>
                        <td>退货状态</td>
                        <td>操作</td>
                    </tr>
                    </thead>
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
                    <td style="width: 10%;"><?php echo $product['product_status']; ?></td>
                    <td style="width: 10%;"><?php echo $return_goods_status[$product['return_goods_status']]; ?></td>
                    <td style="width: 5%;">
                     <?php
                     if($product['product_status_code']==='0'){ ?>
                      <!--<button class="btn btn-sm lfx-btn" type="button" onclick="chooseShipmentWin('<?php echo $product['product_id']; ?>')">填写发货单</button>-->
                     <?php
                     }else if($product['product_status_code']==='1'){ ?>
                      <!--<button class="btn btn-sm lfx-btn" type="button" onclick="viewShipmentWin('<?php echo $product['product_id']; ?>')">查看发货单</button>-->
                     <?php
                     }else if($product['product_status_code']==='2'){ ?>
                      <!--<button class="btn btn-sm lfx-btn">确认退款</button>-->
                     <?php }
                     ?>
                     <?php
                     if(is_valid($product['return_goods_status']) ){ ?>
                      <button class="btn btn-sm lfx-btn" type="button" onclick="viewReturnGoods('<?php echo $product['order_product_id']; ?>')">查看详情</button>
                     <?php
                        }
                        if($product['return_goods_status']==4 ){ ?>
                        <br />
                        <br />
                        <button class="btn btn-sm lfx-btn" type="button" onclick="confirmReceiveGoods('<?php echo $product['order_product_id']; ?>')">确认收货</button>
                     <?php }else { ?>
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

  function chooseShipmentWin(){

      showHugeUrlWin('发货单','index.php?route=order/detail/renderOrderShippment&token=<?php echo $token; ?>', function () {
          var params = $('#shipmmentsWin form').formJSON();
          console.log(params);
          var templateIds = params['template_ids[]'];
          if($.isArray(templateIds)){
              for(var i = 0;i < templateIds.length;i++){
                  if(!is_valid_str(params['express_no_'+templateIds[i]])){
                      return showErrorText('没有填写物流单号');
                  }
              }
          } else{
              params['template_ids[]'] = [templateIds];
              if(!is_valid_str(params['express_no_'+templateIds])){
                  return showErrorText('没有填写物流单号');
              }
          }
          var url ='index.php?route=order/detail/updateExpressInfo&token=<?php echo $token; ?>';
          params.order_id = '<?php echo $order_id; ?>';
          $.model.commonAjax(url,params, function (data) {
           if(data.success === true){
           window.location =location.href;
           }else{
           return showErrorText(data.errMsg);
           }
           });
      }, function () {

      },{order_id:'<?php echo $order_id; ?>' });
  }
  function viewReturnGoods(order_product_id){
      showHugeUrlWin('退货单','index.php?route=order/detail/renderReturnGoodsView&token=<?php echo $token; ?>', function () {
          var params = $('#returnGoodsWin form').formJSON();

          if(!is_valid_str(params.return_money)|| getNumberOpacity(params.return_money) > 2 || params.return_money < 0){
              showErrorText('退款金额必须大于0，精度最大为2！');
              return false;
          }
          if(params.allow_returngoods == 0){
              params.return_money = 0;
          }

          var url ='index.php?route=order/detail/updateReturnGoods&token=<?php echo $token; ?>';
          params.order_product_id = order_product_id;
          $.model.commonAjax(url,params, function (data) {
              if(data.success === true){
                  window.location =location.href;
              }else{
                  return showErrorText(data.errMsg);
              }
          });
      }, function () {

      },{order_product_id:order_product_id });
  }
  function confirmReceiveGoods(order_product_id){
      showHugeUrlWin('确认收货','index.php?route=order/detail/renderConfirmReturnGoodsView&token=<?php echo $token; ?>', function () {
          var params = $('#confirmReturnGoodsWin form').formJSON();

          var url ='index.php?route=order/detail/confirmReturnGoods&token=<?php echo $token; ?>';
          params.order_product_id = order_product_id;
          $.model.commonAjax(url,params, function (data) {
              if(data.success === true){
                  window.location =location.href;
              }else{
                  return showErrorText(data.errMsg);
              }
          });
      }, function () {

      },{order_product_id:order_product_id });
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
  .product-row thead tr{
      background: #aaa;
      color: white;
  }
    .product-row thead tr td{
        padding: 2px;
    }
    .product-row{
        margin-top: 10px;
    }
</style>
