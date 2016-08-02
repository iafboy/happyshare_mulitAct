<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      </div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i><?php echo $text_list; ?></h3>
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class=pull-left>
              <input type="text" name="filter_user_name" value="<?php echo $filter_user_name;?>" id="input-user-name" placeholder="<?php echo $text_user_name; ?>" class="form-control pull-left"/>
              </div>
              <div class="pull-right">
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i><?php echo $button_query; ?></button>
              </div>
            </div>
            <!--<div class="col-sm-3">
              <div class="pull-right">
              <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i><?php echo "&nbsp;".$text_add_user; ?></a>
            </div>-->
          </div>
        </div>
      </div>

      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-user">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-center">用户账号</td>
                  <td class="text-center">真实姓名</td>
                  <td class="text-center">联系方式</td>
                  <td class="text-center">管理群组</td>
                  <td class="text-center"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($users) { ?>
                <?php foreach ($users as $user) { ?>
                <tr>
                  <td class="text-center"><?php echo $user['useraccount']; ?></td>
                  <td class="text-center"><?php echo $user['username']; ?></td>
                  <td class="text-center"><?php echo substr($user['userphone'],0,3).'****'.substr($user['userphone'],7); ?></td>
                  <td class="text-center"><?php echo $user['userpermission']; ?></td>
                  <td class="text-center">
                    <a href="<?php echo $user['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" ><i class="fa fa-pencil"></i></a>
                    <?php if($user['usergroup_id'] != 1){ ?>
                      <a href="<?php echo $user['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="if (confirm('<?php echo $text_confirm; ?>') == false) return false;"><i class="fa fa-trash"></i></a>
                    <?php } ?>
                  </td>
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
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=user/user&token=<?php echo $token; ?>';

//liuhang add for product code
  var filter_user_name = $('input[name=\'filter_user_name\']').val();

	if (filter_user_name) {
		url += '&filter_user_name=' + encodeURIComponent(filter_user_name);
	}

	location = url;
});
//--></script> 

<?php echo $footer; ?> 
