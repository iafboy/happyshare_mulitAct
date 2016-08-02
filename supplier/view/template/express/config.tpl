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
    <div class="row">
        <div class="col-sm-10 col-sm-push-1 main-content">
          <div class="my-panel">
            <form class="form-horizontal" id="setting-fm">
              <div class="hor-item-group">
                <label class="">包邮标准:</label>
                <span>消费满&nbsp;&nbsp;&nbsp;&nbsp;</span><input name="free_shipping" value="<?php echo $setting['free_shipping']; ?>" placeholder="" class="lfx-text" />&nbsp;&nbsp;&nbsp;&nbsp;元
              </div>
              <div class="hor-item-group">
                <label class="">免税标准:</label>
                <span>国际直邮商品消费低于&nbsp;&nbsp;&nbsp;&nbsp;</span><input name="free_tax_min" value="<?php echo $setting['free_tax_min']; ?>" placeholder="" class="lfx-text" />&nbsp;&nbsp;&nbsp;&nbsp;元
              </div>
              <div class="hor-item-group">
                <label class="">包税标准:</label>
                <span>总订单消费高于&nbsp;&nbsp;&nbsp;&nbsp;</span><input name="free_tax_max" value="<?php echo $setting['free_tax_max']; ?>" placeholder="" class="lfx-text" />&nbsp;&nbsp;&nbsp;&nbsp;元
              </div>
                  <button class="btn btn-default btn-xs" style="float: right;" type="button" onclick="saveSetting()">保存</button>
            </form>
          </div>

          <div class="my-panel">
            <form id="return-fm" class="form-horizontal" style="margin-top: 10px;">
              <div class="hor-item-group">
                <label class="">姓名:</label>
                <span><input name="name" value="<?php echo $return['name']; ?>" placeholder="" class="lfx-text" />
              </div>
              <div class="hor-item-group">
                <label class="">联系方式:</label>
                <input name="telephone" value="<?php echo $return['telephone']; ?>" placeholder="" class="lfx-text" />
              </div>
              <div class="v-item-group">
                <label class="">收货地址:</label>
                <select name="addr_prov" placeholder="" class="lfx-select main-prov-sel" onchange="getMainCities()">
                    <?php
                         foreach($privs as $p){
                            if($return['addr_prov'] == $p['id'] ){ ?>
                                <option value="<?php echo $p['region_code']; ?>" selected><?php echo $p['name']; ?></option>
                            <?php
                                }else{ ?>
                                    <option value="<?php echo $p['region_code']; ?>"><?php echo $p['name']; ?></option>
                            <?php
                                }
                         } ?>
                </select>
                <select name="addr_city" placeholder="" class="lfx-select main-city-sel" >
                </select>
                <select name="addr_dist" placeholder="" class="lfx-select main-dist-sel" >
                </select>
                <input name="addr_info" value="<?php echo $return['addr_info']; ?>" class="lfx-text" style="width: 400px;" />
                <button class="btn btn-default btn-xs" style="float: right;" type="button" onclick="saveReturn()">保存</button>
              </div>
            </form>
          </div>
            <div class="my-panel">
                <div class="my-panel-header">
                    <button type="button" onclick="addConfigRow()">添加</button>
                </div>

                <div class="content-container">
                    <table class="table table-bordered table-hover lfx-table">
                        <thead>
                        <tr>
                            <td class="text-center">模板名称</td>
                            <td class="text-center">发货地</td>
                            <td class="text-center">包邮</td>
                            <td class="text-center">计费方式</td>
                            <td class="text-center">初始单位</td>
                            <td class="text-center">初始价格</td>
                            <td class="text-center">后续单位</td>
                            <td class="text-center">后续价格</td>
                            <td class="text-center">状态</td>
                            <td class="text-center">操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($templates as $template){ ?>
                        <tr>
                            <td class="text-center"><?php echo $template['express_template_name']; ?></td>
                            <td class="text-center"><?php echo $template['fromwhere']; ?></td>
                            <td class="text-center"><?php echo $type_baoyoutype[$template['baoyou_type']]; ?></td>
                            <td class="text-center"><?php echo $type_modetype[$template['express_mode']]; ?></td>
                            <td class="text-center"><?php echo parseFormatNum($template['default_unit'],2); ?></td>
                            <td class="text-center"><?php echo parseFormatNum($template['default_price'],2); ?></td>
                            <td class="text-center"><?php echo parseFormatNum($template['default_add_unit'],2); ?></td>
                            <td class="text-center"><?php echo parseFormatNum($template['default_add_price'],2); ?></td>
                            <td class="text-center"><?php echo $status_express[$template['status']]; ?></td>
                            <td class="text-center">
                                <?php
                                if($template['status'] == 0){ ?>
                                <button type="button" class="btn btn-default btn-xs lfx-btn" onclick="activeTemplate('<?php echo $template['express_template_id']; ?>')">启用</button>
                                <?php }else if($template['status'] == 1){ ?>
                                <button type="button" class="btn btn-default btn-xs lfx-btn" onclick="inactiveTemplate('<?php echo $template['express_template_id']; ?>')">禁用</button>
                                <?php }
                                ?>
                                <button type="button" class="btn btn-default btn-xs lfx-btn" onclick="modifyTemplate('<?php echo $template['express_template_id']; ?>')">修改</button>
                                <button type="button" class="btn btn-default btn-xs lfx-btn" onclick="delTemplate('<?php echo $template['express_template_id']; ?>')">删除</button>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
  </div>
</div>

<style>
  .my-panel{
    padding: 20px;
    border: 1px solid #e4e4e4;
    border-radius: 10px;
    margin-bottom: 10px;
  }
  .hor-item-group{
    display: inline-block;
    margin-right: 20px;;
  }
  .v-item-group{
    display: block;
    margin-bottom: 10px;
    margin-top: 10px;
  }
    .main-content .my-panel:nth-child(3){
        border-top: 30px solid #eee;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
        position: relative;
    }
    .my-panel-header button:hover{
        color: red;
    }
    .my-panel-header button{
        font-size: 10px;
        border: none;
        background: #eee;
    }
    .my-panel-header{
        position: absolute;
        right: 0px;
        top: -25px;
    }
</style>


<script type="text/javascript">

    function configArea(){

        /*showLargeUrlWin('添加计费规则','index.php?route=express/config/renderConfigArea&token=<?php echo $token; ?>', function () {
            var params = $('#express-row form').formJSON();
            var url = 'index.php?route=express/config/configArea&token=<?php echo $token; ?>';
            $.model.commonAjax(url,params, function (data) {
                if(data.success === true){
                    window.location = 'index.php?route=express/config/renderConfigArea&token=<?php echo $token; ?>';
                }else{
                    return showErrorText(data.errMsg);
                }
            });
        });*/
    }
    function addConfigRow(){
        window.location = 'index.php?route=express/rule&token=<?php echo $token; ?>';
        /*showLargeUrlWin('添加计费规则','index.php?route=express/config/renderConfig&token=<?php echo $token; ?>', function () {
            var params = $('#express-row form').formJSON();
            var url = 'index.php?route=express/config/addExpressConfig&token=<?php echo $token; ?>';
            $.model.commonAjax(url,params, function (data) {
                if(data.success === true){
                    window.location = location.href;
                }else{
                    return showErrorText(data.errMsg);
                }
            });
        });*/
    }
    function modifyTemplate(templateId){
        window.location = 'index.php?route=express/rule/renderModifyTemplate&token=<?php echo $token; ?>&template_id='+templateId;
    }
    function activeTemplate(templateId){
        confirmLargeHtmlWin('提示','确定启用么？', function () {
            var url = 'index.php?route=express/config/activeTemplate&token=<?php echo $token; ?>';
            var params = { };
            params.template_id = templateId;
            $.model.commonAjax(url,params, function (data) {
                if(data.success === true){
                    window.location = location.href;
                }else{
                    return showErrorText(data.errMsg);
                }
            });
        });
    }
    function inactiveTemplate(templateId){
        confirmLargeHtmlWin('提示','确定禁用么？', function () {
            var url = 'index.php?route=express/config/inactiveTemplate&token=<?php echo $token; ?>';
            var params = { };
            params.template_id = templateId;
            $.model.commonAjax(url,params, function (data) {
                if(data.success === true){
                    window.location = location.href;
                }else{
                    return showErrorText(data.errMsg);
                }
            });
        });
    }
    function delTemplate(templateId){
        confirmLargeHtmlWin('提示','确定删除么？', function () {
            var url = 'index.php?route=express/config/delTemplate&token=<?php echo $token; ?>';
            var params = { };
            params.template_id = templateId;
            $.model.commonAjax(url,params, function (data) {
                if(data.success === true){
                    window.location = location.href;
                }else{
                    return showErrorText(data.errMsg);
                }
            });
        });
    }
    function getMainCities(){
        var provCode = $('.main-prov-sel').val();
        $.model.commonAjax('index.php?route=express/config/getCities&token=<?php echo $token; ?>',
        {priv_code:provCode}, function (data) {
            var html = '';
            if(data && $.isArray(data) && data.length > 0){
                for(var i = 0; i < data.length;i++){
                    if(data[i].id == '<?php echo $return['addr_city']; ?>'){
                        html = html + '<option value="'+data[i].region_code+'" selected>'+data[i].name+'</option>';
                    }else{
                        html = html + '<option value="'+data[i].region_code+'">'+data[i].name+'</option>';
                    }
                }
            }
            $('.main-city-sel').empty().append(html);
            var cityCode = $('.main-city-sel').val();
            $.model.commonAjax('index.php?route=express/config/getDists&token=<?php echo $token; ?>',
            {city_code:cityCode}, function (data) {
                var html = '';
                if(data && $.isArray(data) && data.length > 0){
                    for(var i = 0; i < data.length;i++){
                        if(data[i].id == '<?php echo $return['addr_dist']; ?>'){
                            html = html + '<option value="'+data[i].region_code+'" selected>'+data[i].name+'</option>';
                        }else{
                            html = html + '<option value="'+data[i].region_code+'">'+data[i].name+'</option>';
                        }
                    }
                }
                $('.main-dist-sel').empty().append(html);

            });
        });
    }
    getMainCities();

    function saveReturn(){
        var params = $('#return-fm').formJSON();
        var url = 'index.php?route=express/config/saveOrModReturn&token=<?php echo $token; ?>';
        $.model.commonAjax(url,params, function (data) {
            if(data.success === true){
                showSuccessText('保存成功');
            }else{
                return showErrorText(data.errMsg);
            }
        });
    }
    function saveSetting(){
        var params = $('#setting-fm').formJSON();
        var url = 'index.php?route=express/config/saveOrModSetting&token=<?php echo $token; ?>';
        $.model.commonAjax(url,params, function (data) {
            if(data.success === true){
                showSuccessText('保存成功');
            }else{
                return showErrorText(data.errMsg);
            }
        });
    }

</script>


<?php echo $footer; ?>
