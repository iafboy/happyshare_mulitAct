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
    <div class="row" id="express-row">
        <div class="col-sm-10 col-sm-push-1">
            <form class="form-horizontal" id="template-fm">
                <div class="form-group" style="display: block">
                    <label class="label-control">模板名称:</label>
                    <input name="express_template_name" class="form-control" value="<?php echo $template['express_template_name']; ?>" />
                </div>
                <input type="hidden" name="baoyou_type" value="1" />
                <!--<div class="form-group" style="display: block">
                    <label class="label-control">是否包邮：</label>
                </div>
                <div class="form-group baoyou_type_box">
                    <?php
                     if(isset($template['baoyou_type'])){
                        if($template['baoyou_type'] == 1){ ?>

                        <input class="form-control" type="radio" name="baoyou_type" value="1" style="display: inline-block;" checked /><span style="margin-right: 10px;">运费自定义</span>
                        <input class="form-control" type="radio" name="baoyou_type" value="2" style="display: inline-block;" /><span style="margin-right: 10px;">包邮</span>
                    <?php }else if($template['baoyou_type'] == 2){ ?>

                        <input class="form-control" type="radio" name="baoyou_type" value="1" style="display: inline-block;"  /><span style="margin-right: 10px;">运费自定义</span>
                        <input class="form-control" type="radio" name="baoyou_type" value="2" style="display: inline-block;" checked /><span style="margin-right: 10px;">包邮</span>
                    <?php }
                    }else{ ?>
                        <input class="form-control" type="radio" name="baoyou_type" value="1" style="display: inline-block;" checked /><span style="margin-right: 10px;">运费自定义</span>
                        <input class="form-control" type="radio" name="baoyou_type" value="2" style="display: inline-block;" /><span style="margin-right: 10px;">包邮</span>
                    <?php }
                    ?>
                </div>-->
                <div class="form-group" style="display: block">
                    <label class="label-control">计费方式</label>
                </div>
                <div class="form-group charge-type-box">
                    <?php if(isset($template)){
                        if($template['express_mode'] == 1){ ?>
                            <input class="form-control" type="radio" name="charge_type" value="1" style="display: inline-block;" checked /><span style="margin-right: 10px;">按件数</span>
                    <!--
                            <input class="form-control" type="radio" name="charge_type" value="2" style="display: inline-block;" /><span style="margin-right: 10px;">按重量</span>
                            <input class="form-control" type="radio" name="charge_type" value="3" style="display: inline-block;" /><span style="margin-right: 10px;">按体积</span>
                            -->
                        <?php } else if($template['express_mode'] == 2){ ?>
                    <!--
                            <input class="form-control" type="radio" name="charge_type" value="1" style="display: inline-block;"  /><span style="margin-right: 10px;">按件数</span>
                            -->
                            <input class="form-control" type="radio" name="charge_type" value="2" style="display: inline-block;" checked /><span style="margin-right: 10px;">按重量</span>
                    <!--
                            <input class="form-control" type="radio" name="charge_type" value="3" style="display: inline-block;" /><span style="margin-right: 10px;">按体积</span>
                            -->
                        <?php } else if($template['express_mode'] == 3){ ?>
                    <!--
                           <input class="form-control" type="radio" name="charge_type" value="1" style="display: inline-block;"  /><span style="margin-right: 10px;">按件数</span>
                           <input class="form-control" type="radio" name="charge_type" value="2" style="display: inline-block;" /><span style="margin-right: 10px;">按重量</span>
                           -->
                           <input class="form-control" type="radio" name="charge_type" value="3" style="display: inline-block;" checked /><span style="margin-right: 10px;">按体积</span>
                       <?php }
                        } else{ ?>
                       <input class="form-control" type="radio" name="charge_type" value="1" style="display: inline-block;" checked /><span style="margin-right: 10px;">按件数</span>
                       <!--
                       <input class="form-control" type="radio" name="charge_type" value="2" style="display: inline-block;" /><span style="margin-right: 10px;">按重量</span>
                       <input class="form-control" type="radio" name="charge_type" value="3" style="display: inline-block;" /><span style="margin-right: 10px;">按体积</span>
                       -->
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label class="label-control">发货地</label>
                    <select name="fromwhere_id" class="form-control">
                        <?php
                         foreach($fromwheres as $f){
                         if(isset($template)){
                                if($template['fromwhere_id'] == $f['fromwhere_id'] ){ ?>
                                    <option value="<?php echo $f['fromwhere_id']; ?>" selected><?php echo $f['place_name']; ?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $f['fromwhere_id']; ?>"><?php echo $f['place_name']; ?></option>
                                <?php
                                }
                                ?>
                        <?php
                        }else{ ?>
                            <option value="<?php echo $f['fromwhere_id']; ?>"><?php echo $f['place_name']; ?></option>
                        <?php }
                        }
                         ?>
                    </select>
                </div>
                <div class="mode-setting-box">

                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <button class="btn btn-default" type="button" onclick="saveTemplate()">保存</button>
            <button class="btn btn-default" type="button" onclick="history.go(-1)">返回</button>
        </div>
    </div>
</div>
</div>
<div class="hidden-box" style="display: none;">
    <div class="mode-setting mode-setting-count on">
        <div class="title-bar">
            <?php if(isset($template) && $template['express_mode'] == 1){ ?>
            <p>
                <span>默认运费：&nbsp;&nbsp;</span><input name="defaultUnit" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_unit'],2); ?>" />
                <span>&nbsp;&nbsp;件内，&nbsp;&nbsp;</span><input name="defaultPrice" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_price'],2); ?>" />
                <span>&nbsp;&nbsp;元，每增加&nbsp;&nbsp;</span><input name="defaultAddUnit" class="lfx-text lfx-text-xs" type="number" value="<?php echo parseFormatNum($template['default_add_unit'],2); ?>" />
                <span>&nbsp;&nbsp;件，增加运费&nbsp;&nbsp;</span>
                <input name="defaultAddPrice" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_add_price'],2); ?>" /><span>&nbsp;&nbsp;元</span>
                <button type="button" class="btn btn-default btn-xs" style="float: right;" onclick="addRuleRow('count')"><span class="fa fa-plus"></span>添加</button>
            </p>
            <?php } else{ ?>
            <p>
                <span>默认运费：&nbsp;&nbsp;</span><input name="defaultUnit" type="number" class="lfx-text lfx-text-xs"  />
                <span>&nbsp;&nbsp;件内，&nbsp;&nbsp;</span><input name="defaultPrice" type="number" class="lfx-text lfx-text-xs"  />
                <span>&nbsp;&nbsp;元，每增加&nbsp;&nbsp;</span><input name="defaultAddUnit" class="lfx-text lfx-text-xs" type="number"  />
                <span>&nbsp;&nbsp;件，增加运费&nbsp;&nbsp;</span>
                <input name="defaultAddPrice" type="number" class="lfx-text lfx-text-xs"  /><span>&nbsp;&nbsp;元</span>
                <button type="button" class="btn btn-default btn-xs" style="float: right;" onclick="addRuleRow('count')"><span class="fa fa-plus"></span>添加</button>
            </p>
            <?php } ?>

        </div>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <td class="text-center">地区</td>
                <td class="text-center">首件</td>
                <td class="text-center">首价</td>
                <td class="text-center">续件</td>
                <td class="text-center">续价</td>
                <td class="text-center">操作</td>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="mode-setting mode-setting-weight on">
        <div class="title-bar">
            <?php if(isset($template) && $template['express_mode'] == 2){ ?>
            <p>
                <span>默认运费：&nbsp;&nbsp;</span><input name="defaultUnit" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_unit'],2); ?>" />
                <span>&nbsp;&nbsp;kg内，&nbsp;&nbsp;</span><input name="defaultPrice" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_price'],2); ?>" />
                <span>&nbsp;&nbsp;元，每增加&nbsp;&nbsp;</span><input name="defaultAddUnit" class="lfx-text lfx-text-xs" type="number" value="<?php echo parseFormatNum($template['default_add_unit'],2); ?>" />
                <span>&nbsp;&nbsp;kg，增加运费&nbsp;&nbsp;</span>
                <input name="defaultAddPrice" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_add_price'],2); ?>" /><span>&nbsp;&nbsp;元</span>
                <button type="button" class="btn btn-default btn-xs" style="float: right;" onclick="addRuleRow('weight')"><span class="fa fa-plus"></span>添加</button>
            </p>
            <?php } else{ ?>
            <p>
                <span>默认运费：&nbsp;&nbsp;</span><input name="defaultUnit" type="number" class="lfx-text lfx-text-xs" />
                <span>&nbsp;&nbsp;kg内，&nbsp;&nbsp;</span><input name="defaultPrice" type="number" class="lfx-text lfx-text-xs"  />
                <span>&nbsp;&nbsp;元，每增加&nbsp;&nbsp;</span><input name="defaultAddUnit" class="lfx-text lfx-text-xs" type="number"  />
                <span>&nbsp;&nbsp;kg，增加运费&nbsp;&nbsp;</span>
                <input name="defaultAddPrice" type="number" class="lfx-text lfx-text-xs"  /><span>&nbsp;&nbsp;元</span>
                <button type="button" class="btn btn-default btn-xs" style="float: right;" onclick="addRuleRow('weight')"><span class="fa fa-plus"></span>添加</button>
            </p>
            <?php } ?>

        </div>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <td class="text-center">地区</td>
                <td class="text-center">首重</td>
                <td class="text-center">首价</td>
                <td class="text-center">续重</td>
                <td class="text-center">续价</td>
                <td class="text-center">操作</td>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="mode-setting mode-setting-volume on">
        <div class="title-bar">
            <?php if(isset($template) && $template['express_mode'] == 3){ ?>
            <p>
                <span>默认运费：&nbsp;&nbsp;</span><input name="defaultUnit" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_unit'],2); ?>" />
                <span>&nbsp;&nbsp;m3内，&nbsp;&nbsp;</span><input name="defaultPrice" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_price'],2); ?>" />
                <span>&nbsp;&nbsp;元，每增加&nbsp;&nbsp;</span><input name="defaultAddUnit" class="lfx-text lfx-text-xs" type="number" value="<?php echo parseFormatNum($template['default_add_unit'],2); ?>" />
                <span>&nbsp;&nbsp;m3，增加运费&nbsp;&nbsp;</span>
                <input name="defaultAddPrice" type="number" class="lfx-text lfx-text-xs" value="<?php echo parseFormatNum($template['default_add_price'],2); ?>" /><span>&nbsp;&nbsp;元</span>
                <button type="button" class="btn btn-default btn-xs" style="float: right;" onclick="addRuleRow('volume')"><span class="fa fa-plus"></span>添加</button>
            </p>
            <?php } else{ ?>
            <p>
                <span>默认运费：&nbsp;&nbsp;</span><input name="defaultUnit" type="number" class="lfx-text lfx-text-xs"  />
                <span>&nbsp;&nbsp;m3内，&nbsp;&nbsp;</span><input name="defaultPrice" type="number" class="lfx-text lfx-text-xs" />
                <span>&nbsp;&nbsp;元，每增加&nbsp;&nbsp;</span><input name="defaultAddUnit" class="lfx-text lfx-text-xs" type="number"  />
                <span>&nbsp;&nbsp;m3，增加运费&nbsp;&nbsp;</span>
                <input name="defaultAddPrice" type="number" class="lfx-text lfx-text-xs"  /><span>&nbsp;&nbsp;元</span>
                <button type="button" class="btn btn-default btn-xs" style="float: right;" onclick="addRuleRow('volume')"><span class="fa fa-plus"></span>添加</button>
            </p>
            <?php } ?>

        </div>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <td class="text-center">地区</td>
                <td class="text-center">首体积</td>
                <td class="text-center">首价</td>
                <td class="text-center">续体积</td>
                <td class="text-center">续价</td>
                <td class="text-center">操作</td>
            </tr>
            </thead>
            <tbody>
            <!--<tr class="rule-row" data-privs="" data-areas="" data-cities="" data-seq="1">
                <td class="text-center">
                    <button type="button" class="btn btn-default btn-xs" onclick="editDistricts(this)"><span class="fa fa-edit"></span></button>
                </td>
                <td><input type="number" name="unit_" class="lfx-text w-10" /></td>
                <td><input type="number" name="unit_" class="lfx-text w-10" /></td>
                <td><input type="number" name="unit_" class="lfx-text w-10" /></td>
                <td><input type="number" name="unit_" class="lfx-text w-10" /></td>
                <td class="text-center">
                    <button type="button" class="btn btn-default btn-xs" onclick="">删除</button>
                    <button type="button" class="btn btn-default btn-xs" onclick="">保存</button>
                </td>
            </tr>-->
            </tbody>
        </table>
    </div>
    <div class="content-tb">
        <table>
        <?php
         if(isset($template)){
            foreach($template['rules'] as $rule){ ?>
            <tr class="rule-row"
                data-privs="<?php echo $rule['privs']; ?>"
                data-areas="<?php echo $rule['areas']; ?>"
                data-cities="<?php echo $rule['cities']; ?>"
                data-seq="<?php echo $rule['seq']; ?>" >
                <td class="text-center">
                    <button type="button" class="btn btn-success btn-xs" onclick="editDistricts(this)"><span class="fa fa-edit"></span></button>
                </td>
                <td><input type="number" name="unit_<?php echo $rule['seq']; ?>" value="<?php echo parseFormatNum($rule['unit'],2); ?>" class="lfx-text w-10" /></td>
                <td><input type="number" name="price_<?php echo $rule['seq']; ?>" value="<?php echo parseFormatNum($rule['price'],2); ?>" class="lfx-text w-10" /></td>
                <td><input type="number" name="addUnit_<?php echo $rule['seq']; ?>" value="<?php echo parseFormatNum($rule['add_unit'],2); ?>" class="lfx-text w-10" /></td>
                <td><input type="number" name="addPrice_<?php echo $rule['seq']; ?>" value="<?php echo parseFormatNum($rule['add_price'],2); ?>" class="lfx-text w-10" /></td>
                <td class="text-center">
                    <button type="button" class="btn btn-default btn-xs" onclick="$(this).parents(\'.rule-row\').remove()">删除</button>
                </td>
            </tr>
            <?php }
         }
         ?>
        </table>
    </div>
</div>
<style>
    .mode-setting{
        margin-left: -15px;
        margin-right: -15px;
    }
    .mode-setting .title-bar{
        border-bottom: 1px solid #e4e4e4;
        background-color: #eee;
        padding: 10px;
        border-radius: 5px 5px 0px 0px;
    }
    .mode-setting .title-bar p{
        margin-bottom: 0px;
    }
    .mode-setting table{
        border: none;
    }
    .mode-setting table thead{
        border:none;
    }
    .mode-setting table  tr{
        border-bottom: 1px solid #E4E4E4;
    }
    .mode-setting table tr td{
        border: none;
    }
    .chargetype-form{
        margin-top: 20px;
        padding: 20px;
        border-radius: 10px;
        background: #e4e4e4;
        position: relative;
    }
    .chargetype-form.on .mask{
        display: none;
    }
    .chargetype-form .mask{
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 10;
        top:0;
        left:0;
        cursor:not-allowed;
    }
    .chargetype-form input{
        disabled:disabled;
    }
    .chargetype-form.on input{
        disabled:false;
    }
    .chargetype-form.on{
        margin-top: 20px;
        padding: 20px;
        border-radius: 10px;
        background: #fff;
    }
    .mode-setting{
        display: none;
    }
    .mode-setting.on{
        display: block;
    }
</style>
<script>
    function saveTemplate(){
        var params = $('#template-fm').formJSON();
        if(!is_valid_str(params.express_template_name)){
            return showErrorText('模板名称无效');
        }
        if(params.baoyou_type==1){
            if(params.charge_type == 1){
                if(!is_valid_str(params.defaultUnit) || getNumberOpacity(params.defaultUnit) > 0 || params.defaultUnit < 0 ){
                    return showErrorText('默认初始单位无效');
                }
                if(!is_valid_str(params.defaultPrice) || getNumberOpacity(params.defaultPrice) > 2 || params.defaultPrice < 0 ){
                    return showErrorText('默认初始价格无效');
                }
                if(!is_valid_str(params.defaultAddUnit) || getNumberOpacity(params.defaultAddUnit) > 0 || params.defaultAddUnit < 0 ){
                    return showErrorText('默认续加单位无效');
                }
                if(!is_valid_str(params.defaultAddPrice) || getNumberOpacity(params.defaultAddPrice) > 2 || params.defaultAddPrice < 0 ){
                    return showErrorText('默认续加价格无效');
                }
            }else{
                if(!is_valid_str(params.defaultUnit) || getNumberOpacity(params.defaultUnit) > 2 || params.defaultUnit < 0 ){
                    return showErrorText('默认初始单位无效');
                }
                if(!is_valid_str(params.defaultPrice) || getNumberOpacity(params.defaultPrice) > 2 || params.defaultPrice < 0 ){
                    return showErrorText('默认初始价格无效');
                }
                if(!is_valid_str(params.defaultAddUnit) || getNumberOpacity(params.defaultAddUnit) > 2 || params.defaultAddUnit < 0 ){
                    return showErrorText('默认续加单位无效');
                }
                if(!is_valid_str(params.defaultAddPrice) || getNumberOpacity(params.defaultAddPrice) > 2 || params.defaultAddPrice < 0 ){
                    return showErrorText('默认续加价格无效');
                }
            }
            var $rows = $('.mode-setting-box .mode-setting table tbody tr');
            var tf = true;
            var seqs = [];
            $.each($rows, function (i, value) {
                var that = this;
                var seq = $(that).data('seq');
                var privs = $(that).data('privs');
                var areas = $(that).data('areas');
                var cities = $(that).data('cities');
                seqs.push(seq);
                if(!is_valid_str(privs) || !is_valid_str(areas) || !is_valid_str(cities)){
                    tf = false;
                    showErrorText('地址无效');
                    return false;
                }
                params['privs_'+seq] = privs;
                params['areas_'+seq] = areas;
                params['cities_'+seq] = cities;
                if(params.charge_type == 1){
                    if(!is_valid_str(params['unit_'+seq]) || getNumberOpacity(params['unit_'+seq]) > 0 || params['unit_'+seq] < 0 ){
                        tf = false;
                        showErrorText('初始单位无效');
                        return false;
                    }
                    if(!is_valid_str(params['price_'+seq]) || getNumberOpacity(params['price_'+seq]) > 2 || params['price_'+seq] < 0 ){
                        tf = false;
                        showErrorText('初始价格无效');
                        return false;
                    }
                    if(!is_valid_str(params['addUnit_'+seq]) || getNumberOpacity(params['addUnit_'+seq]) > 0 || params['addUnit_'+seq] < 0 ){
                        tf = false;
                        showErrorText('续加单位无效');
                        return false;
                    }
                    if(!is_valid_str(params['addPrice_'+seq]) || getNumberOpacity(params['addPrice_'+seq]) > 2 || params['addPrice_'+seq] < 0 ){
                        tf = false;
                        showErrorText('续加价格无效');
                        return false;
                    }
                }else{
                    if(!is_valid_str(params['unit_'+seq]) || getNumberOpacity(params['unit_'+seq]) > 2 || params['unit_'+seq] < 0 ){
                        tf = false;
                        showErrorText('初始单位无效');
                        return false;
                    }
                    if(!is_valid_str(params['price_'+seq]) || getNumberOpacity(params['price_'+seq]) > 2 || params['price_'+seq] < 0 ){
                        tf = false;
                        showErrorText('初始价格无效');
                        return false;
                    }
                    if(!is_valid_str(params['addUnit_'+seq]) || getNumberOpacity(params['addUnit_'+seq]) > 2 || params['addUnit_'+seq] < 0 ){
                        tf = false;
                        showErrorText('续加单位无效');
                        return false;
                    }
                    if(!is_valid_str(params['addPrice_'+seq]) || getNumberOpacity(params['addPrice_'+seq]) > 2 || params['addPrice_'+seq] < 0 ){
                        tf = false;
                        showErrorText('续加价格无效');
                        return false;
                    }
                }
            });
            if(!tf){
                return;
            }
            params['seqs'] = stringfyArray(seqs);
        }
        <?php
            if(isset($template_id)){ ?>
                var url = 'index.php?route=express/rule/modTemplate&token=<?php echo $token; ?>';
                params.template_id = '<?php echo $template_id; ?>';
                $.model.commonAjax(url,params, function (data) {
                    if(data.success === true){
                        window.location = 'index.php?route=express/config&token=<?php echo $token; ?>';
                    }else{
                        showErrorText(data.errMsg);
                    }
                });
            <?php }else{ ?>
                var url = 'index.php?route=express/rule/addTemplate&token=<?php echo $token; ?>';
                $.model.commonAjax(url,params, function (data) {
                    if(data.success === true){
                        window.location = 'index.php?route=express/config&token=<?php echo $token; ?>';
                    }else{
                        showErrorText(data.errMsg);
                    }
                });
            <?php }
            ?>
    }


    function addRuleRow(suffix){
        var seq = $('.mode-setting-'+suffix+' table tbody tr.rule-row').last().data('seq');
        if(!seq){
            seq = 1;
        }else{
            seq = seq + 1;
        }
        var html = '<tr class="rule-row" data-privs="" data-areas="" data-cities="" data-seq="'+seq+'"> \
                <td class="text-center"> \
                <button type="button" class="btn btn-default btn-xs" onclick="editDistricts(this)"><span class="fa fa-edit"></span></button> \
        </td> \
        <td><input type="number" name="unit_'+seq+'" class="lfx-text w-10" /></td> \
                <td><input type="number" name="price_'+seq+'" class="lfx-text w-10" /></td> \
                <td><input type="number" name="addUnit_'+seq+'" class="lfx-text w-10" /></td> \
                <td><input type="number" name="addPrice_'+seq+'" class="lfx-text w-10" /></td> \
                <td class="text-center"> \
                <button type="button" class="btn btn-default btn-xs" onclick="$(this).parents(\'.rule-row\').remove()">删除</button> \
                </td> \
                </tr>';

        $('.mode-setting-'+suffix+' table tbody').append(html);
    }
    function editDistricts(obj){
        var url = 'index.php?route=express/rule/renderDistrictConfig&token=<?php echo $token; ?>';
        var _privs =  $(obj).parents('.rule-row').data('privs');
        var _areas =  $(obj).parents('.rule-row').data('areas');
        var _cities = $(obj).parents('.rule-row').data('cities');
        var win = showHugeUrlWin('编辑地区选择',url, function () {
            var params = $('#express-district-box form').formJSON();
            var privs = params['privs[]'];
            var areas = params['areas[]'];
            var cities = params['cities[]'];
            var str_privs = stringfyArray(privs);
            var str_areas = stringfyArray(areas);
            var str_cities = stringfyArray(cities);
            $(obj).parents('.rule-row').data('privs',str_privs);
            $(obj).parents('.rule-row').data('cities',str_cities);
            $(obj).parents('.rule-row').data('areas',str_areas);
            if(is_valid_str(str_privs) && is_valid_str(str_areas) && is_valid_str(str_cities)){
                $(obj).removeClass('btn-default').addClass('btn-success');
            }else{
                $(obj).removeClass('btn-success').addClass('btn-default');
            }
            win.close();
        },function(){
        },{ privs:_privs,areas:_areas,cities:_cities });
    }
    $(function () {
        function initSettingBox(){
            var baoyou = 1;
//            var baoyou = $('.baoyou_type_box input:checked').val();
            var mode = $('.charge-type-box input:checked').val();
            if(baoyou == 1){
                var suffix;
                if(mode == 1){
                    suffix = 'count';
                }else if(mode == 2){
                    suffix = 'weight';
                }else if(mode == 3){
                    suffix = 'volume';
                }
                $('.mode-setting-box').empty().append($('.hidden-box .mode-setting-'+suffix).clone());
                $('.mode-setting-box table tbody').empty().append($('.hidden-box .content-tb table tbody tr').clone());
            }
        }
        initSettingBox();
        /*$('.baoyou_type_box input').on('change', function () {
            var that = this;
            var index = $(that).index();
            if(index == 0){
                index =1;
            }else if(index == 2){
                index = 2;
            }
            if(index == 1){
                var suffix = '';
                var _index = $('.charge-type-box input:checked').index();
                if(_index == 0){
                    suffix = 'count';
                }else if(_index == 2){
                    suffix = 'weight';
                }else if(_index == 4){
                    suffix = 'volume';
                }
                $('.mode-setting-box').empty().append($('.hidden-box .mode-setting-'+suffix).clone());
            }
            if(index == 2){
                $('.mode-setting-box').empty();
            }
        });*/
        $('.charge-type-box input').on('change', function () {
            if($('.baoyou_type_box input:checked').index() ==2){
                return;
            }
            var that = this;
            var index = $(that).index();
            var suffix = '';
            if(index == 0){
                index =1;
                suffix = 'count';
            }else if(index == 2){
                index = 2;
                suffix = 'weight';
            }else if(index == 4){
                index = 3;
                suffix = 'volume';
            }
            $('.mode-setting-box').empty().append($('.hidden-box .mode-setting-'+suffix).clone());
            var expressMode = '<?php echo $template['express_mode']; ?>';
            if(expressMode == index){
                $('.mode-setting-box table tbody').empty().append($('.hidden-box .content-tb table tbody tr').clone());
            }
        });
        function initChargeType(){
            var charge_type = '';
            <?php if(isset($config)){ ?>
                charge_type = '<?php echo $config['charge_type']; ?>';
                if(is_valid_str(charge_type)){
                    $('#tab-'+charge_type).addClass('on').siblings().removeClass('on');
                }
            <?php }else{ ?>
            <?php }
            ?>
        }
        <?php
        if(isset($config)){ ?>
            initCity();
            initChargeType();
        <?php }else{ ?>
            getCities($('.prov-sel'));
        <?php }
            ?>
    });
    function getCities(obj){
        var privCode = $(obj).val();
        $.model.commonAjax('index.php?route=express/config/getCities&token=<?php echo $token; ?>',
        {priv_code:privCode}, function (data) {
            var html = '';
            if(data && $.isArray(data) && data.length > 0){
                html = html + '<option value="">全部</option>';
                for(var i = 0; i < data.length;i++){
                    html = html + '<option value="'+data[i].region_code+'">'+data[i].name+'</option>';
                }
            }
            $('.city-sel').empty().append(html);
        });
    }
    function initCity(){
        var provId = '<?php echo $config['place_dest_prov']; ?>';
        $.model.commonAjax('index.php?route=express/config/getCities&token=<?php echo $token; ?>',
            {priv_id:provId}, function (data) {
                var html = '';
                if(data && $.isArray(data) && data.length > 0){
                    html = html + '<option value="">全部</option>';
                    for(var i = 0; i < data.length;i++){
                        if(data[i].id == '<?php echo $config['place_dest_city']; ?>'){
                            html = html + '<option value="'+data[i].region_code+'" selected>'+data[i].name+'</option>';
                        }else{
                            html = html + '<option value="'+data[i].region_code+'">'+data[i].name+'</option>';
                        }
                    }
                }
                $('.city-sel').empty().append(html);
            });
    }
</script>

<?php echo $footer; ?>