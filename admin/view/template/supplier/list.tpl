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
    <form style="margin-top: 20px;" method="post" >
      <div class="table-responsive">
        <table class="table table-bordered table-hover lfx-table supplier-tb">
          <thead>
          <tr>
            <td class="text-center"><?php echo $column_supplier_no; ?></td>
            <td class="text-center"><?php echo $column_supplier_name; ?></td>
            <td class="text-center"><?php echo $column_supplier_git_count; ?></td>
            <td class="text-center"><?php echo $column_supplier_online_count; ?></td>
            <td class="text-center"><?php echo $column_supplier_sold_count; ?></td>
            <td class="text-center"><?php echo $column_supplier_supply_amount; ?></td>
            <td class="text-center"><?php echo $column_supplier_sold_amount; ?></td>
            <td class="text-center"><?php echo $column_supplier_interest_amount; ?></td>
            <!--<td class="text-center"><?php echo $column_supplier_express; ?></td>-->
            <td class="text-center"><?php echo $column_supplier_status; ?></td>
            <td class="text-center">品牌馆状态</td>
            <td class="text-center"><?php echo $column_supplier_status_oper; ?></td>
            <!--<td class="text-center"><?php echo $column_supplier_desc; ?></td>-->
          </tr>
          </thead>
          <tbody>
          <?php if ($suppliers) { ?>
          <?php foreach ($suppliers as $supplier) { ?>
          <tr class="supplier-row-<?php echo $supplier['supplier_id'] ?>">
            <td class="text-center"><a href="index.php?route=supplier/view&token=<?php echo $token; ?>&supplier_id=<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['supplier_no']; ?></a></td>
            <td class="text-center"><a href="index.php?route=supplier/view&token=<?php echo $token; ?>&supplier_id=<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['supplier_name']; ?></a></td>
            <td class="text-center"><?php echo $supplier['supplier_git_count']; ?></td>
            <td class="text-center"><?php echo $supplier['supplier_online_count']; ?></td>
            <td class="text-center"><?php echo $supplier['supplier_sold_count']; ?></td>
            <td class="text-center"><?php echo $supplier['supplier_supply_amount']; ?></td>
            <td class="text-center"><?php echo $supplier['supplier_sold_amount']; ?></td>
            <td class="text-center"><?php echo $supplier['supplier_interest_amount']; ?></td>
            <!--<td class="text-center"><button class="btn btn-xs btn-default lfx-btn" type="button">查看</button></td>-->
            <td class="text-center supplier-status"><?php echo $supplier['supplier_status_text']; ?></td>
            <td class="text-center supplier-status">
            <?php
            if($supplier['supplier_own_brand'] == '1'){
                if($supplier['supplier_brand_status']=='1'){
                    echo '已开启';
                }else{
                    echo '未开启';
                }
            }else{
                echo '未开启';
            }

            ?>
            </td>
            <td class="text-center supplier-opers">
              <button class="btn btn-xs lfx-btn status-btn" type="button" onclick="changeSupplierStatus('<?php echo $supplier['supplier_id']; ?>','<?php echo $supplier['supplier_status']; ?>')"><?php echo $supplier['supplier_status_oper']; ?></button>
              <button class="btn btn-xs lfx-btn supplier-brand-btn" type="button" onclick="turn2Brand('<?php echo $supplier['supplier_id'] ?>')">品牌馆</button>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
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

  function refreshStatusInRow(supplierId,supplierStatus){
    var statusText = '';
    var operText = '';
    var newStatus;
    if(supplierStatus == '0'){
        statusText = '已上架';
        operText = '下架';
        newStatus = 1;
    }else if(supplierStatus =='1'){
        statusText = '已下架';
        operText = '上架';
        newStatus = 0;
    }
    $('.supplier-tb tbody tr.supplier-row-'+supplierId+' .supplier-status').empty().html(statusText);
    $('.supplier-tb tbody tr.supplier-row-'+supplierId+' .supplier-opers .status-btn').remove();
    var html = '<button class="btn btn-xs lfx-btn status-btn" type="button" onclick="changeSupplierStatus(\''+supplierId+'\',\''+newStatus+'\')">'+operText+'</button>';
    $(html).insertBefore($('.supplier-tb tbody tr.supplier-row-'+supplierId+' .supplier-opers .supplier-brand-btn'));
  }

  function changeSupplierStatus(supplierId,supplierStatus){
    var statusText;
    var status;
    if(supplierStatus=='0'){
        statusText= '确定上架么?';
        status = 1;
    }else if(supplierStatus=='1'){
        statusText= '确定下架么?';
        status = 0;
    }else{
        return showErrorText('参数错误！');
    }
    confirmText('提示',statusText, function (data) {
      var url = 'index.php?route=supplier/list/changeStatus&token=<?php echo $token; ?>';
      $.model.commonAjax(url,{supplier_id:supplierId,status:status},function(data){
        if(data.success===true){
            refreshStatusInRow(supplierId,supplierStatus);
        }else{
            return showErrorText(data.errMsg);
        }
      });
    });
  }
  function viewSupplierExpressinfo(supplierId){
      showHtmlWin('');
  }

  function turn2Brand(supplierId){
    window.location = 'index.php?route=supplier/gallery&token=<?php echo $token; ?>&supplier_id='+supplierId;
  }
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

</script>