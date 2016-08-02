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
    <form>
      <div class="table-responsive">
        <table class="table table-bordered table-hover lfx-table">
          <thead>
          <tr>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($comments) { ?>
          <?php foreach ($comments as $comment) { ?>
          <tr>
            <td class="text-center"><?php echo $comment['product_no']; ?></td>
            <td class="text-center"><?php echo $comment['product_name']; ?></td>
            <td class="text-center"><?php echo substr($comment['fullname'],0,3).'****'.substr($comment['fullname'],7); ?></td>
            <td class="text-center"><?php echo $comment['date_added']; ?></td>
            <td class="text-center"><?php echo $comment['text']; ?></td>
            <td class="text-center">
              <button type="button" class="btn btn-default btn-xs lfx-btn" onclick="delComment('<?php echo $comment['review_id']; ?>')">删除</button>
              <button type="button" class="btn btn-default btn-xs lfx-btn" onclick="addReply('<?php echo $comment['product_id']; ?>')">回复</button>

            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="15"><?php echo $text_no_results; ?></td>
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
  function delComment(reviewId){
    var url = '<?php echo html_entity_decode($del_comment_url); ?>';
    var dialog = confirmWideHtmlWin('提示','确认删除么？', function (data) {
      $.model.commonAjax(url,{comment_id:reviewId}, function (data) {
        if(data.success===true){
          showSuccessText('删除成功！');
          dialog.close();
          var l = location.href;
          window.location = l;
        }else{
          showErrorText('删除失败！');
        }

      });
    });
  }
  function addReply(product_id){
    var url = '<?php echo html_entity_decode($reply_url); ?>';
    var html =
            '<div class="container-fluid">' +
            '<form id="add-reply-fm" class="form-horizontal">' +
            '<div class="form-group">' +
            '<label class="col-sm-2 control-label">回复内容:</label>' +
            '<div class="col-sm-10">' +
            '<input type="text" class="form-control" name="reply_text">'+
            '</div>' +
            '</div>' +
            '</form>' +
            '</div>';
    var dialog = confirmWideHtmlWin('提示',html, function (data) {
      var params = $('#add-reply-fm').formJSON();
      if(!is_valid_str(params.reply_text)){
        showErrorText('回复内容不可为空！');
        return;
      }
      $.model.commonAjax(url,{product_id,reply_text:params.reply_text}, function (data) {
        if(data.success===true){
          dialog.close();
          showSuccessText('管理员评论成功！');
        }else{
          showErrorText('管理员评论失败！');
        }
      });
    });
  }
</script>
