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
    <form id="add-supplier-fm">
      <div class="row">
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">结算支付账户：</label>
            <select name="pay_bankid" class="lfx-select" >
              <?php
              foreach($banks as $bank){
                if($bank['bank_id']==$cashreport['pay_bank_id']){ ?>
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
            <input type="text" name="pay_bankcard" value="<?php echo $cashreport['pay_bankcard']; ?>"  class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;客户账户：</label>
            <select type="text" disabled name="supplier_bankid" class="lfx-select" >
              <?php
              foreach($banks as $bank){
                if($bank['bank_id']==$cashreport['bankId']){ ?>
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
            <input type="number" readonly name="customer_bankcard" value="<?php echo $cashreport['cardId']; ?>" class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">交易单号：</label>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <input type="text" name="transfer_no" value="<?php echo $cashreport['cash_pay_no']; ?>" class="lfx-text" />
          </span>
        </div>
        <div class="col-sm-12" style="margin-top: 10px;">
          <span class="form-group" style="margin-right: 10px;">
            <label class="control-label">结算支付金额：</label>
          </span>
          <span class="form-group">
            <label class="control-label"></label>
            <span><?php echo parseFormatNum($cashreport['cash_amount'],2); ?>元</span>
          </span>
        </div>
      </div>
      <?php if($is_view==1){ ?>
      <div class="row">
        <div class="col-sm-12" style="margin-top: 30px;">
          <div style="text-align: center;">
            <button type="button" class="btn lfx-btn btn-default" onclick="history.go(-1)"><i class="fa fa-search"></i>返回 </button>
          </div>
        </div>
      </div>
      <?php }else{ ?>
      <div class="row">
        <div class="col-sm-12" style="margin-top: 30px;">
          <div style="text-align: center;">
            <button type="button" class="btn lfx-btn btn-default" onclick="addSupplier()"><i class="fa fa-search"></i>确认支付 </button>
          </div>
        </div>
      </div>
      <?php } ?>

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

  function addSupplier(){
    var url = 'index.php?route=cashreports/pay/doPay&token=<?php echo $token; ?>';
    var form = '#add-supplier-fm';
    var params = $(form).formJSON();
    if(!is_valid_str(params.pay_bankid)){
      return showErrorText('支付银行未选择！');
    }
    if(!is_valid_str(params.pay_bankcard)|| !verifyBankCard(params.pay_bankcard)){
      return showErrorText('支付银行卡号无效！');
    }
    if(!is_valid_str(params.transfer_no) ){
      return showErrorText('交易单号无效！');
    }
    var cashreport_id = '<?php echo $cashreport_id; ?>';
    params.cashreport_id = cashreport_id;
    if(!is_valid_str(params.cashreport_id)){
      return showErrorText('提现记录错误！');
    }
    $.model.commonAjax(url,params, function (data) {
      if(data.success === true){
        history.go(-1);
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }

</script>