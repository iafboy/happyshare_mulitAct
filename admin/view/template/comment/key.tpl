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
    <div class="container">
      <div class="row">
        <div class="col-sm-6 pull-left">
          <label>评价关键字库列(逗号分隔)：</label>
        </div>
        <div class="col-sm-6 pull-right" style="text-align: right;">
          <span><button type="button" class="btn btn-sm lfx-btn" onclick="addKey()">新增关键字</button></span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12" >
          <table class="table table-bordered lfx-table comment-key-table" style="margin-top: 10px;">
            <thead>
              <tr>
                <th>关键字</th>
                <th>出现频率</th>
                <th>状态</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
            <?php
              foreach($keys as $key){ ?>
                <tr class="main-row-<?php echo $key['comment_key_id']; ?>">
                  <td><?php echo $key['key_name']; ?></td>
                  <td><?php echo ($key['key_frequency']); ?>%</td>
                  <td>
                    <?php
                      if($key['status'].''==='0'){ ?>
                        <span class="label label-danger">已禁用</span>
                    <?php }else if($key['status'].''==='1'){ ?>
                        <span class="label label-success">已启用</span>
                    <?php }
                    ?>
                  </td>
                  <td>
                    <?php
                      if($key['status'].''==='0'){ ?>
                        <button type="button" class="btn btn-sm lfx-btn" onclick="setKeyStatus('<?php echo $key['comment_key_id'];?>','1')">启用</button>
                        <button type="button" class="btn btn-sm lfx-btn" onclick="delKey('<?php echo $key['comment_key_id'];?>')">删除</button>
                    <?php }else if($key['status'].''==='1'){ ?>
                    <button type="button" class="btn btn-sm lfx-btn" onclick="setKeyStatus('<?php echo $key['comment_key_id'];?>','0')">禁用</button>
                    <?php }
                    ?>
                  </td>
                </tr>
              <?php }
             ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left">
          <ul id="comment-key-pg" class="pagination-sm"></ul>
        </div>
        <div class="col-sm-6 text-right comment-key-pageinfo" style="line-height: 200%;">
          <?php echo $paginator['info']; ?>
        </div>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script>
  function renderTable(table,list){
    var _html = '';
    for(var i = 0;i < list.length;i++){
      var key = list[i];
      var html = '<tr class="main-row-'+key['comment_key_id']+'">'
              +'<td>'+key['key_name']+'</td>'
              +'<td>'+key['key_frequency']+'%</td>'
              +'<td>';
      if(key['status']=='0'){
        html += '<span class="label label-danger">已禁用</span>';
      }else if(key['status']=='1') {
        html += '<span class="label label-success">已启用</span>';
      }
      html+= '</td>'
              +'<td>';
      if(key['status']=='0'){
        html += '<button type="button" style="margin-right: 5px;" class="btn btn-sm lfx-btn" onclick="setKeyStatus('+key['comment_key_id']+',\'1\')">启用</button>';
        html += '<button  type="button" class="btn btn-sm lfx-btn" onclick="delKey(\''+key['comment_key_id']+'\')">删除</button>';
      }else if(key['status']=='1') {
        html += '<button type="button" class="btn btn-sm lfx-btn" onclick="setKeyStatus(\''+key['comment_key_id']+'\',\'0\')">禁用</button>';
      }
      html += '</td></tr>';
      _html += html;
    }
    $(table + ' tbody').empty().append(_html);
  }
  function renderPageInfo(dv,pager){
    $(dv).empty().append(pager.info);
    $('#comment-key-pg').bootstrapPaginator('setOptions',{currentPage:pager.page,totalPages:pager.totalPages});
  }
  function ajaxPager(page){
    var url = '<?php echo html_entity_decode($paginator['url']); ?>';
    $.model.commonAjax(url,{page:page}, function (data) {
      renderTable('.comment-key-table',data.list);
      renderPageInfo('.comment-key-pageinfo',data.paginator);
    });
  }
  $('#comment-key-pg').bootstrapPaginator({
    totalPages : <?php echo $paginator['totalPages']; ?>,
      useBootstrapTooltip:true,
      bootstrapMajorVersion:3,
      size:'small',
      onPageChanged: function (event, oldPage,newPage) {
          ajaxPager(newPage);
      }
  });
  function addKey(){
    var url = '<?php echo html_entity_decode($add_key_url); ?>';
    var html =
            '<div class="container-fluid">' +
            '<form id="add_key_fm" class="form">' +
            '<div class="form-group">' +
            '<label class="col-sm-2 control-label">关键字</label>' +
            '<div class="col-sm-10">' +
            '<input type="text" class="form-control" name="key_name" placeholder="关键字，英文逗号分隔添加多个">'+
            '</div>' +
            '</div>' +
            '</form>' +
            '</div>';
    var dialog = confirmWideHtmlWin('添加关键字',html, function () {
      var params = $('#add_key_fm').formJSON();
      if(!is_valid_str(params.key_name)){
        showErrorText('关键字不可为空！');
        return;
      }
      $.model.commonAjax(url,params, function (data) {
        if(data.success===true){
          showSuccessText('操作成功！');
          dialog.close();
          $('#comment-key-pg').bootstrapPaginator('show',1);
        }else{
          showErrorText('操作失败！');
        }
      });
    });
  }
  function setKeyStatus(keyId,status){
    var url = '<?php echo html_entity_decode($set_key_status_url); ?>';
    var dialog = confirmWideHtmlWin('提示','确定更改么？', function () {
      $.model.commonAjax(url,{key_id:keyId,status:status}, function (data) {
        if(data.success===true){
          showSuccessText('操作成功！');
          dialog.close();
          var page = $('#comment-key-pg').bootstrapPaginator('getCurrent');
          $('#comment-key-pg').bootstrapPaginator('show',page);
        }else{
          showErrorText('操作失败！');
        }
      });
    });
  }
  function delKey(keyId){
    var url = '<?php echo html_entity_decode($delete_key_url); ?>';
    var dialog = confirmWideHtmlWin('提示','确定删除么？', function () {
      $.model.commonAjax(url,{key_id:keyId}, function (data) {
        if(data.success===true){
          showSuccessText('操作成功！');
          dialog.close();
          $('#comment-key-pg').bootstrapPaginator('show',1);
        }else{
          showErrorText('操作失败！');
        }
      });
    });
  }
</script>
