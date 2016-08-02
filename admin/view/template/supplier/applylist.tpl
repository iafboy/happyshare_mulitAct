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
    <div class="">
      <form id="supplier-applylist-fm" action="<?php echo $base_url; ?>">
        <input type="hidden" name="route" value="<?php echo $route; ?>" />
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label" ><?php echo $entry_supplier_company_name; ?></label>
            <input type="text" name="filter_supplier_company_name" value="<?php echo $filter_supplier_company_name; ?>"
                   placeholder="<?php echo $entry_supplier_company_name; ?>"  class="lfx-text w-10" />
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label" style="display: block;"><?php echo $entry_supplier_create_date; ?></label>
            <span>
            <div class=" date" style="display: inline-block;width: 45%;">
              <input style="width: 70%;display: inline-block;" type="text" name="filter_supplier_create_date_start"
                     data-date-format="YYYY-MM-DD" class="lfx-text" value="<?php echo $filter_supplier_create_date_start; ?>" />
                    <span style="width: 30%;">
                    <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
            </div>
            </span>
            -
            <span>
            <div class=" date" style="display: inline-block;width: 45%;">
              <input style="width: 70%;display: inline-block;" type="text" name="filter_supplier_create_date_end"
                     data-date-format="YYYY-MM-DD" class="lfx-text" value="<?php echo $filter_supplier_create_date_end; ?>" />
                    <span style="width: 30%;">
                    <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
            </div>
            </span>
          </div>
          </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label class="control-label"><?php echo $entry_supplier_approve_status; ?></label>
            <select name="filter_supplier_approve_status" class="lfx-select w-10">
              <option value="*"><?php echo $status_supplier_approve_status_all; ?></option>
              <?php
                foreach($status_supplier_approve_status as $key => $value){
                  if(($key.'')===($filter_supplier_approve_status.'')){ ?>
                    <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                  <?php }else{ ?>
                    <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                  <?php }
                }
              ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12" style="text-align: center;">
          <button type="button" class="main-search-btn lfx-btn lfx-btn-lg"><i class="fa fa-search"></i> <?php echo $text_button_query; ?></button>
        </div>
      </div>
      </form>
    </div>
    <div class="table-responsive supplier_table" style="margin-top: 20px;">
      <table class="table table-bordered table-hover lfx-table">
        <thead>
        <tr>
          <td class="text-center"><?php echo $column_supplier_company_name; ?></td>
          <td class="text-center"><?php echo $column_supplier_company_address; ?></td>
          <td class="text-center"><?php echo $column_supplier_company_contactor; ?></td>
          <td class="text-center"><?php echo $column_supplier_company_contactor_phone; ?></td>
          <td class="text-center"><?php echo $column_supplier_company_contactor_email; ?></td>
          <td class="text-center"><?php echo $column_supplier_approve_status; ?></td>
          <td class="text-center">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if ($suppliers) { ?>
        <?php foreach ($suppliers as $supplier) { ?>
        <tr class="supplier_<?php echo $supplier['supplier_reg_id']; ?>">
          <td class="text-center"><?php echo $supplier['supplier_company_name']; ?></td>
          <td class="text-center"><?php echo $supplier['supplier_company_address']; ?></td>
          <td class="text-center"><?php echo $supplier['supplier_company_contactor']; ?></td>
          <td class="text-center"><?php echo $supplier['supplier_company_contactor_phone']; ?></td>
          <td class="text-center"><?php echo $supplier['supplier_company_contactor_email']; ?></td>
          <td class="text-center approve_status"><?php echo $supplier['supplier_approve_status']; ?></td>
          <td class="text-center">
            <button onclick="changeStatus('<?php echo $supplier["supplier_reg_id"]; ?>')" class="btn btn-xs lfx-btn">更新状态</button>
              <?php
              if($supplier['supplier_is_registered']==0){ ?>
                <button onclick="turn2addSupplier('<?php echo $supplier["supplier_reg_id"]; ?>')" class="btn btn-xs lfx-btn">创建账号</button>
              <?php }
              ?>
            </td>
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
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>

<script type="text/javascript">
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
  function turn2addSupplier(supplierRegId){
      window.location = 'index.php?route=supplier/add&token=<?php echo $token; ?>&supplier_reg_id='+supplierRegId;
  }
  function changeStatus(supplierId){
    showUrlWin('修改状态',
    'view/template/supplier/applylist-modsupplier.html',
    function() {
      var params = $('#modSupplierWin form').formJSON();
      $.extend(params,{supplier_reg_id:supplierId});
      var url = '<?php echo html_entity_decode($changestatus_url); ?>';
      $.model.supplier.changeSupplierStatus(url,params,function(data){
        if(data.success){
          var meta = data.data;
          showSuccessText('操作成功!');
          $('.supplier_table .supplier_'+supplierId+' .approve_status').text(meta.status);
        }else{
          showErrorText('操作失败!');
        }
      });
    });
  }
  $(function () {
    $('.main-search-btn').on('click',function(e){
      e.preventDefault();
      var form = '#supplier-applylist-fm';
      $(form).submit();
    });
  });
</script>
