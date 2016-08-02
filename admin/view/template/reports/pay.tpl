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
  <!-- Content Area  -->
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
    <form id="repay-fm">
      <div class="row">
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">结算支付账户：</label>
            <select type="text" name="pay_bankid" class="lfx-select" >
              <?php
              foreach($banks as $bank){ ?>
              <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']; ?></option>
              <?php }
               ?>
            </select>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <input type="number" name="pay_bankcard"  class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">&nbsp;&nbsp;&nbsp;供货商账户：</label>
            <select type="text" name="supplier_bankid" class="lfx-select" >
              <?php
              foreach($banks as $bank){
                if($bank['bank_id'].''===$supplier['bankid']){ ?>
                  <option value="<?php echo $bank['bank_id']; ?>" selected><?php echo $bank['bank_name']; ?></option>
                <?php }else{ ?>
                  <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']; ?></option>
                <?php }
              ?>
              <?php }
               ?>
            </select>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <input type="number" name="supplier_bankcard" value="<?php echo $supplier['bankcard']; ?>" class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">&nbsp;&nbsp;&nbsp;交易单号：</label>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <input type="text" name="transfer_no"  class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">结算支付金额：</label>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <span><?php echo $transfer_amount; ?>元</span>
          </span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12" style="margin-top: 30px;">
          <div style="text-align: center;">
            <button type="button" class="btn lfx-btn btn-default" onclick="repay()"><i class="fa fa-search"></i>确认已支付 </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>
<style>
  .image-container-sm a{
    margin-right:5px;width: 200px;height:200px;display: inline-block;
  }
  .image-container-sm{
    margin-right: 5px;
    width: 150px;
    display: inline-block;
    margin-bottom: 10px;
  }
</style>
<script type="text/javascript">

  var supplierId = getQueryString('supplier_id');
  function repay(){
    var url = 'index.php?route=reports/pay/dopay&token=<?php echo $token; ?>';
    var form = '#repay-fm';
    var params = $(form).formJSON();
    var transferamount = <?php echo $transfer_amount; ?>;
    params.transferamount = transferamount;
    params.order_ids = '<?php echo $order_ids; ?>';
    params.supplier_id = '<?php echo $supplier_id; ?>';
    if(!is_valid_str(params.pay_bankid)){
      return showErrorText('支付银行卡银行不为空！');
    }
    if(!is_valid_str(params.pay_bankcard)){
      return showErrorText('支付银行卡号不为空！');
    }
    if(!is_valid_str(params.supplier_bankid)){
      return showErrorText('供货商银行卡银行不为空！');
    }
    if(!is_valid_str(params.supplier_bankcard)){
      return showErrorText('供货商银行卡号不为空！');
    }
    if(!is_valid_str(params['transfer_no'])){
      return showErrorText('交易号不为空!');
    }
    if(!is_valid_str(params['transferamount'])){
      return showErrorText('金额错误!');
    }
    if(!is_valid_str(params['order_ids'])){
      return showErrorText('订单号异常!');
    }
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }



</script>