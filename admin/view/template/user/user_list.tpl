<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
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
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-user">
      <div style="margin-bottom: 10px;">
          <a href="<?php echo $add; ?>" class="btn btn-default btn-sm lfx-btn">添加用户<i class="fa fa-plus"></i></a>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover lfx-table">
          <thead>
          <tr>
            <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
            <td class="text-left">用户ID</td>
            <td class="text-left">用户名(手机号)</td>
            <td class="text-left">员工姓名</td>
            <td class="text-left">员工联系方式</td>
            <td class="text-left">权限设置</td>
            <td class="text-right"><?php echo $column_action; ?></td>
          </tr>
          </thead>
          <tbody>
          <?php if ($users) { ?>
          <?php foreach ($users as $user) { ?>
          <tr>
            <!--<td class="text-center"><?php if (in_array($user['user_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
              <?php } ?></td>-->
            <td class="text-left"><?php echo $user['user_id']; ?></td>
            <td class="text-left"><?php echo $user['username']; ?></td>
            <td class="text-left"><?php echo $user['fullname']; ?></td>
            <td class="text-left"><?php echo substr($user['phone'],0,3).'****'.substr($user['phone'],7); ?></td>
            <td class="text-left"><?php echo $user['user_group']; ?></td>
            <td class="text-right">
                <a href="<?php echo $user['edit']; ?>" class="btn btn-default btn-sm lfx-btn"><i class="fa fa-pencil"></i></a>
                <button type="button" class="btn btn-default btn-sm lfx-btn" style="border-radius: 2px;" onclick="confirm('<?php echo $text_confirm; ?>') ? deleteUser('<?php echo $user["user_id"]; ?>') : false;"><i class="fa fa-remove"></i></button>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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

<script>
    function deleteUser(userId){
        var url = '<?php echo html_entity_decode($delete); ?>';
        $.model.commonAjax(url,{user_id:userId},function(data){
            if(data.success === true){
                window.location = data.location;
            }else{
                return showErrorText(data.errMsg);
            }
        });
    }
</script>