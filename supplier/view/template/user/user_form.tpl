<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username">用户账号</label>
            <div class="col-sm-10">
              <?php if($supplier_group_id == 1){ ?>
              <input type="text" name="username" readonly value="<?php echo $username; ?>" id="input-username" class="form-control" />
              <?php } else { ?>
              <input type="text" name="username" value="<?php echo $username; ?>" id="input-username" class="form-control" />
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-user-group">用户群组</label>
            <div class="col-sm-10">
              <?php if($supplier_group_id == 1){ ?>
              <select name="supplier_group_id" id="input-user-group" class="form-control">
                <option value="1"><?php echo $super_usergroup_name; ?></option>
              </select>
              <?php } else { ?>
              <select name="supplier_group_id" id="input-user-group" class="form-control">
                <?php foreach ($user_groups as $user_group) {
                ?>
                <?php if ($user_group['supplier_group_id'] == $supplier_group_id) { ?>
                  <option value="<?php echo $user_group['supplier_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $user_group['supplier_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php } ?>
           </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-company-contacter"><?php echo $entry_fullname; ?></label>
            <div class="col-sm-10">
              <?php if($supplier_group_id == 1){ ?>
                <input type="text" name="company_contacter"  readonly value="<?php echo $company_contacter; ?>" id="input-company-contacter" class="form-control" />
              <?php } else { ?>
                <input type="text" name="company_contacter" value="<?php echo $company_contacter; ?>" id="input-company-contacter" class="form-control" />
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-phone">联系方式</label>
            <div class="col-sm-10">
              <?php if($supplier_group_id == 1){ ?>
              <input type="text" name="company_contacter_phone" readonly value="<?php echo $company_contacter_phone; ?>" id="input-phone" class="form-control" />
              <?php } else { ?>
              <input type="text" name="company_contacter_phone" value="<?php echo $company_contacter_phone; ?>" id="input-phone" class="form-control" />
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" autocomplete="off" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <?php if($supplier_group_id == 1){ ?>
                <select name="status" id="input-status" class="form-control">
                  <option value="1">启用</option>
                </select>
              <?php } else { ?>
                <select name="status" id="input-status" class="form-control">
                  <?php if ($status) { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                </select>
              <?php } ?>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
