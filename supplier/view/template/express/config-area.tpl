<div class="container-fluid" style="max-height: 600px;overflow-y: scroll">
    <div class="row" id="express-row">
        <div class="col-sm-10 col-sm-push-1">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="label-control">Area</label>
                    <select name="areaId" class="form-control">
                        <?php
                         foreach($areas as $area){ ?>
                            <option value="<?php echo $area['region_code'] ?>"><?php echo $area['name']; ?></option>
                         <?php }
                         ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label-control">Priv</label>
                        <?php
                         foreach($privs as $area){ ?>
                            <input name="privCodes[]" type="checkbox" value="<?php echo $area['region_code'] ?>" /><?php echo $area['name']; ?>
                         <?php }
                         ?>
                </div>
            </form>
        </div>

    </div>
</div>
<style>
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
</style>
<script>
    $(function () {
        $('.charge-type-box input').on('change', function () {
            var that = this;
            var index = $(that).index();
            if(index == 0){
               index =1;
            }else if(index == 2){
               index = 2;
            }else if(index == 4){
                index = 3;
            }
            $('#tab-'+index).addClass('on').siblings().removeClass('on');

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