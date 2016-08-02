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
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-supplier">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
          <tr>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($acts) { ?>
          <?php foreach ($acts as $act) { ?>
          <tr>
            <td class="text-center"><?php echo $act['pid']; ?></td>
            <td class="text-center"><?php echo $act['promotion_name']; ?></td>
            <td class="text-center"><?php echo $status_act_status[$act['status'].'']; ?></td>
            <td class="text-center"><?php echo $type_act_type[$act['type'].'']; ?></td>
            <td class="text-center"><?php echo $act['startdate']; ?></td>
            <td class="text-center"><?php echo $act['enddate']; ?></td>
            <td class="text-center"><?php echo $act['usernumber']; ?></td>
            <td class="text-center">
              <button class="btn btn-xs lfx-btn" type="button"
                onclick="toEditAct(<?php echo $act['subpromotionid']; ?>,<?php echo $act['type'];?>)">编辑</button>
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
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">
  function toEditAct(subId,type){
    var type_str = '';
    if(type==0){
      type_str = 'special';
    }else if(type == 1){
      type_str = 'free';
    }else if(type == 2){
      type_str = 'credit';
    }else if(type == 3){
      type_str = 'gift';
    }else if(type == 4){
      type_str = 'trial';
    }
    var url = 'index.php?route=activity/'+type_str+'&token=<?php echo $token; ?>&link_mode=modify&sub_id='+subId;
    window.location=url;
  }
</script>
