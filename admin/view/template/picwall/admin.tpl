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
    <form method="post" id="picwall-add-fm">
      <div class="table-responsive">
        <table class="table table-bordered lfx-btn">
          <thead>
          <tr>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($picwalls) { ?>
          <?php foreach ($picwalls as $picwall) {
            for($i = 0; $i < $picwall['size']+1; $i++){
             $img = $picwall['list'][$i];
             ?>
          <tr>
            <?php if($i==0){ ?>
            <td class="text-center" rowspan="<?php echo $picwall['size']+1;?>"><?php echo $picwall['category_level1']; ?></td>
            <td class="text-center" rowspan="<?php echo $picwall['size']+1;?>"><?php echo $picwall['category_level2']; ?></td>
            <td class="text-center" rowspan="<?php echo $picwall['size']+1;?>"><?php echo $picwall['category_level3']; ?></td>
            <td class="text-center" rowspan="<?php echo $picwall['size']+1;?>"><?php echo $picwall['location']; ?></td>
            <?php } ?>
            <?php if($i != $picwall['size']){ ?>
            <td class="text-center">
              <div style="min-width: 100px;max-width:200px;min-height:50px;display: inline-block;">
                <a style="cursor: pointer;" class="img-thumbnail" href="<?php echo $img['image']; ?>" target="_blank">
                  <img width="100%" height="100%" src="<?php echo $img['image']; ?>" data-placeholder="<?php echo $placeholder; ?>"  />
                </a>
              </div>
            </td>
            <td class="text-center">
              <a href="<?php echo $img['link']; ?>" target="_blank"><span><?php echo $img['link']; ?></span></a>
            </td>
            <td class="text-center">
              <span><?php echo $img['sort_order']; ?></span>
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm lfx-btn" onclick="deletepicwallImage('<?php echo $img['picwallbanner_id']; ?>')">删除</button>
              <?php
                     if($img['enable_status'].''==='0'){ ?>
              <button type="button" class="btn btn-sm lfx-btn" onclick="setpicwallImageStatus('<?php echo $img['picwallbanner_id']; ?>','1')">上架</button>
              <?php }else if($img['enable_status'].''==='1'){ ?>
              <button type="button" class="btn btn-sm lfx-btn" onclick="setpicwallImageStatus('<?php echo $img['picwallbanner_id']; ?>','0')">下架</button>
              <?php }
                    ?>
            </td>
            <?php }else{ ?>
            <td class="text-center">
              <button type="button" id="button-upload" class="btn btn-sm lfx-btn">上传</button>
            </td>
            <td class="text-center">
              <input name="link" class="lfx-text" />
            </td>
            <td class="text-center">
              <input name="sort_order" type="number" class="lfx-text" />
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm lfx-btn" onclick="uploadpicwall('<?php echo $picwall['pws_id']; ?>')">添加</button>
            </td>
            <?php } ?>
          </tr>
          <?php } ?>
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
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">

  function setpicwallImageStatus(picwallImageId,status){
    var url = '<?php echo html_entity_decode($set_picwall_status_url); ?>';
    var dialog = confirmWideHtmlWin('提示','确定操作么？', function () {
      $.model.commonAjax(url,{picwall_image_id:picwallImageId,status:status}, function (data) {
        if(data.success){
          dialog.close();
          var l = location.href;
          window.location = l;
        }else{
          showErrorText(data.errMsg);
        }
      });
    });
  }
  function deletepicwallImage(picwallImageId){
    var url = '<?php echo html_entity_decode($del_picwall_url); ?>';
    var dialog = confirmWideHtmlWin('提示','确定删除么？', function () {
      $.model.commonAjax(url,{picwall_image_id:picwallImageId}, function (data) {
        if(data.success){
          dialog.close();
          var l = location.href;
          window.location = l;
        }else{
          showErrorText(data.errMsg);
        }
      });
    });
  }

  function uploadpicwall(picwallId){
    var url = '<?php echo html_entity_decode($add_picwall_url); ?>';
    var fm = '#picwall-add-fm';
    var params = $(fm).formJSON();
    if(
            !is_valid_str(params.imagePath) ||
            !is_valid_str(params.sort_order) ||
            !is_valid_str(params.link)
    ){
      showErrorText('参数或图片错误!');
      return;
    }
    $.extend(params,{picwall_id:picwallId});
    $.model.commonAjax(url,params, function (data) {
      if(data.success===true){
        var l = location.href;
        window.location = l;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }

  var picwalls = [
    {id:1,text:'首页',sub:[]},
    {id:2,text:'分享热榜',sub:[]},
    {id:3,text:'热门商品',sub:[]},
    {id:4,text:'精彩活动',sub:[
       {id:5,text:'特价活动'},
       {id:6,text:'积分翻倍活动'},
       {id:7,text:'免费体验活动'}
    ]}
  ];
  $(function () {
    $('select.cascade_1').on('change', function () {
      var value = $(this).val();
      var picwall;
      for(var j = 0;j < picwalls.length;j++){
        if(value==picwalls[j]['text']){
          picwall = picwalls[j];
          break;
        }
      }
      $('select.cascade_2').empty();
      var html2 = '';
      if(picwall && picwall.sub && picwall.sub.length>0){
        for(var j = 0;j < picwall.sub.length;j++){
          var sb = picwall.sub[j];
          html2 = html2+ '<option value="'+sb['text']+'">'+sb['text']+'</option>';
        }
      }else{
        html2 += '<option value="">--</option>';
      }
      $('select.cascade_2').append(html2);
    });
  });


  $('body').delegate('#button-upload','click', function() {
    $('#form-upload').remove();
    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" method="post" style="display: none;">' +
            '<input type="file" name="file" value="" />' +
            '<input type="hidden" name="file_name" value="picwall_temp" />' +
            '</form>');
    $('#form-upload input[name=\'file\']').trigger('click');
    timer = setInterval(function() {
      if ($('#form-upload input[name=\'file\']').val() != '') {
        clearInterval(timer);

        $.ajax({
          url: 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=picwall',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#form-upload')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
            $('#button-upload').prop('disabled', true);
          },
          complete: function() {
            $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
            $('#button-upload').prop('disabled', false);
          },
          success: function(json) {
            var path = json['file_path'];
            var image_path = json['image_path'];
            var html =
                    '<div style="min-width: 100px;max-width:200px;min-height:50px;display: inline-block;" id="button-upload">'+
                    '<a style="cursor: pointer;" class="img-thumbnail">'+
                    '<img width="100%" height="100%" src="'+path+'" data-placeholder="<?php echo $placeholder; ?>"  />'+
                    '</a>'+
                    '<input type="hidden" name="imagePath" value="'+image_path+'" /></div>';
            $('#button-upload').parent().empty().append(html);
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    }, 500);
  });

</script>
