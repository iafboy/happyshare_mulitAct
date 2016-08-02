<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      -->
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
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
        </div>
      <div class="panel-body">
      <div class="table-responsive col-sm-10" >
        <table class="table table-bordered table-hover">
          <thead>
          <tr>
            <td class="text-center"><a><?php echo $column_lvl1; ?></a></td>
            <td class="text-center"><a><?php echo $column_image; ?></a></td>
            <!--
            <td class="text-center"><a><?php echo $column_link; ?></a></td>
            -->
            <td class="text-center"><a><?php echo $column_seq; ?></a></td>
            <td class="text-center"><a><?php echo $column_ops; ?></a></td>
          </tr>
          </thead>
          <tbody>
            <?php $spanflag = false; foreach ($banners as $banner) { ?>
            <tr>
              <?php if (!$spanflag) { ?>
              <td rowspan="<?php echo (count($banners,COUNT_RECURSIVE)+1) ;?>" class="text-center"><a><?php echo "品牌馆置顶广告位"; ?></a></td>
              <?php } $spanflag = true; ?>
              <td class="text-center">
                <div style="display:block;border:1px solid #BEBEBE;" >
                <a><img src="<?php echo $banner['img_src'].'?time='.time(); ?>" width="100px" height="100px" /></a>
                </div>
              </td>
              <!--
              <td class="text-center"><a><?php echo $banner['url']; ?></a></td>
              -->
              <td class="text-center"><a><?php echo $banner['seq']; ?></a></td>
              <td class="text-center" >
                  <a href="<?php echo $banner['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" ><i class="fa fa-trash-o"></i></a>
                  <?php if ($banner['status']) { ?>
                  <a href="<?php echo $banner['disable']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-primary" style="margin-top:3px;">
                    <i class="fa fa-remove"></i></a>
                  <?php } else { ?>
                  <a href="<?php echo $banner['enable']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-primary" style="margin-top:3px;">
                    <i class="fa fa-plus"></i></a>
                  <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <tr>
            <form action="<?php echo $add; ?>" method="post" enctype="multipart/form-data" id="form-brandbanner" >
              <?php if (!$spanflag) { ?>
              <td rowspan="<?php echo (count($banners,COUNT_RECURSIVE)+1) ;?>" class="text-center"><a><?php echo "品牌馆置顶广告位"; ?></a></td>
              <?php } ?>
              <td class="text-center" id="td_img">
                <div style="display:block;border:1px solid #BEBEBE;" id="div-img-upload" >
                <a ><img src="../image/placeholder.png" width="100px" height="100px" id="img-upload" /></a>
                </div>
                <input type="hidden" id="input-upload-img" name="input_upload_img" />
                <div style="display:block;margin-top:5px;">
                <button type="button" class="btn btn-primary" id="btn-upload">上传图片</button>
                </div>
              </td>
              <!--
              <td class="text-center" id="td_url">
                <input type="text" name="input_url" id="input-url" class="form-control" />
              </td>
              -->
              <td class="text-center" id="td_seq">
                <input type="text" name="input_seq"  id="input-seq" class="form-control" />
              </td>
              <td class="text-center">
                <button type="submit" form="form-brandbanner" class="btn btn-primary" id="btn-add"><?php echo $button_add; ?></button>
              </td>
            </form>
            </tr>

          </tbody>
        </table>
      </div>
    <!--</form>-->
  </div>
  </div>
  </div>
<script type="text/javascript"><!--

$('body').delegate('#btn-upload','click', function() {
  $('#form-upload').remove();
  $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
  $('#form-upload input[name=\'file\']').trigger('click');
  timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);

      $.ajax({
        url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=banner',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-upload')[0]),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('#btn-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
          $('#btn-upload').prop('disabled', true);
        },
        complete: function() {
          $('#btn-upload i').replaceWith('<i class="fa fa-upload"></i>');
          $('#btn-upload').prop('disabled', false);
        },
        success: function(json) {
          var path = json['file_path'];
          var pathfull = '<?php echo HTTP_CATALOG."image/";?>' + json['file_path'];
          var html =
                  '<a>'+
                  '<img src="'+pathfull+'" height="100px" width="100px" id="img-upload" />' + '</a>';
                  //'<input type="hidden" id="input-upload-img" name="input_upload_img" value="' + path + '">';
          $('#input-upload-img').val(path);
          $('#div-img-upload').empty().append(html);
          /*below code is used to do debug*/
          //alert("image file path is :"+path);
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }, 500);
});

//--></script>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
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
  //--></script>
