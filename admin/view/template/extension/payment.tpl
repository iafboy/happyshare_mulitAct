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
    <div class="table-responsive">
      <table class="table table-bordered table-hover lfx-table">
        <thead>
        <tr>
          <td class="text-center"><?php echo $column_name; ?></td>
          <td></td>
          <td class="text-center"><?php echo $column_status; ?></td>
          <td class="text-center"><?php echo $column_sort_order; ?></td>
          <td class="text-center"><?php echo $column_action; ?></td>
        </tr>
        </thead>
        <tbody>
        <?php if ($extensions) { ?>
        <?php foreach ($extensions as $extension) { ?>
        <tr>
          <td class="text-center"><?php echo $extension['name']; ?></td>
          <td class="text-center"><?php echo $extension['link'] ?></td>
          <td class="text-center"><?php echo $extension['status'] ?></td>
          <td class="text-center"><?php echo $extension['sort_order']; ?></td>
          <td class="text-center"><?php if (!$extension['installed']) { ?>
            <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>"
               class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i></a>
            <?php } else { ?>
            <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;"
               data-toggle="tooltip" title="<?php echo $button_uninstall; ?>"
               class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></a>
            <?php } ?>
            <?php if ($extension['installed']) { ?>
            <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"
               class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
            <?php } else { ?>
            <button type="button" class="btn btn-primary btn-sm" disabled="disabled"><i class="fa fa-pencil"></i></button>
            <?php } ?></td>
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
  </div>
</div>
<?php echo $footer; ?>