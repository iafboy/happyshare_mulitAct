<div class="container-fluid" style="height: 400px;overflow-y: scroll">
    <div class="row" id="express-district-box">
        <div class="col-sm-10 col-sm-push-1">
            <form class="form-horizontal">
                <table class="table dist-table table-hover">
                    <tbody>
                    <?php
                     foreach($areas as $area){
                     ?>
                    <tr class="area-item">
                        <td>
                            <input name="areas[]" id="area_ck_<?php echo $area['region_code']; ?>" class="area-ck" type="checkbox" check-mode="half" value="<?php echo $area['region_code']; ?>" />
                            <label style="font-weight: bold;" for="area_ck_<?php echo $area['region_code']; ?>">
                                <?php echo $area['name']; ?></label>
                        </td>
                        <?php
                        $index = 0;
                         foreach($area['privs'] as $priv){
                         $index ++;
                         ?>
                        <td class="priv-item">
                            <input name="privs[]" id="priv_ck_<?php echo $priv['region_code']; ?>" class="priv-ck" type="checkbox" check-mode="half" value="<?php echo $priv['region_code']; ?>" />
                            <span class="priv-name">
                                <label for="priv_ck_<?php echo $priv['region_code']; ?>" class="priv-text" style="width: auto;margin-bottom: 0px;font-weight: normal;display: inline"><?php echo $priv['name']; ?></label>
                                <span class="priv-sel fa fa-caret-down" onclick="showOrHideCities(this)"></span>
                                <div class="cities">
                                    <table class="table" style="margin-bottom: 0px;">
                                        <?php
                                         $cities = $priv['cities'];
                                         $row = sizeof($cities) / 4;
                                         $j = 0;
                                         for($i = 0; $i < $row; $i++){ ?>
                                            <tr>
                                                <?php
                                                for($j = $i*4;$j<$i*4+4 && $j < sizeof($cities);$j++){
                                                $city = $cities[$j];
                                                ?>
                                                <td>
                                                    <input name="cities[]" id="city_ck_<?php echo $city['region_code']; ?>" type="checkbox" check-mode="full" value="<?php echo $city['region_code']; ?>" />
                                                    <label style="width: auto;margin-bottom: 0px;font-weight: normal;display: inline" for="city_ck_<?php echo $city['region_code']; ?>"><?php echo $city['name']; ?></label>
                                                </td>
                                                <?php }
                                                 ?>
                                            </tr>
                                            <?php
                                         }
                                         ?>
                                    </table>
                                </div>
                            </span>
                        </td>
                         <?php }
                         while($index++ < 6){ ?>
                            <td></td>
                         <?php }
                         ?>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
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
    table.dist-table tr td{
        border: none;
    }
    .priv-sel{
        display: inline-block;
        width: 16px;
        height: 16px;
        cursor: pointer;
        transform: rotate(90deg) translateX(3px) translateY(8px);
    }
    .priv-sel.on{
        transform: rotate(0deg);
    }
    .priv-name{
        position: relative;
    }
    .priv-name .cities{
        display: none;
        position: fixed;
        z-index: 1200;
        border: 1px solid #E4E4E4;
        width: 300px;
        box-shadow: 2px 0px 8px 2px;
    }
    .cities table tr td{
        width: 25%;
    }
    .priv-name .priv-text{

    }
    .priv-name .priv-text.selected{

    }
</style>
<script>

    function showOrHideCities(obj){
        $('.cities').not($(obj).siblings('.cities')).hide();
        $('.priv-sel').not($(obj)).removeClass('on');
        $(obj).siblings('.cities').toggle();
        $(obj).toggleClass('on');
    }
    $(function () {

        function initSelectedOptions(){
            var _privs ='<?php echo $tmp_privs; ?>';
            var _areas ='<?php echo $tmp_areas; ?>';
            var _cities ='<?php echo $tmp_cities; ?>';
            var privs ;
            var areas ;
            var cities ;
            if(is_valid_str(_privs)){
                privs = _privs.split(',');
            }else{
                privs = [];
            }
            if(is_valid_str(_areas)){
                areas = _areas.split(',');
            }else{
                areas = [];
            }
            if(is_valid_str(_cities)){
                cities = _cities.split(',');
            }else{
                cities = [];
            }

            $.each(cities, function (i,value) {
                $('#city_ck_'+value).attr('checked','checked');
                $('#city_ck_'+value).prop('checked','checked');
            });
            $.each(privs, function (i,value) {
                $('#priv_ck_'+value).attr('checked','checked');
                $('#priv_ck_'+value).prop('checked','checked');
                if($('#priv_ck_'+value).parents('.priv-item').find('.cities input:checkbox').length ==
                $('#priv_ck_'+value).parents('.priv-item').find('.cities input:checkbox:checked').length){
                    $('#priv_ck_'+value).attr('check-mode','full');
                }else{
                    $('#priv_ck_'+value).attr('check-mode','half');
                }
            });
            $.each(areas, function (i,value) {
                $('#area_ck_'+value).attr('checked','checked');
                $('#area_ck_'+value).prop('checked','checked');
                if($('#area_ck_'+value).parents('.area-item').find('.priv-item input:checkbox').length ==
                        $('#area_ck_'+value).parents('.area-item').find('.priv-item input:checkbox:checked').length){
                    $('#area_ck_'+value).attr('check-mode','full');
                }else{
                    $('#area_ck_'+value).attr('check-mode','half');
                }
            });


        }
        initSelectedOptions();


        $('.cities input:checkbox').on('click', function () {
            var that = this;
            // for priv cascade
            if($(that).parents('.cities').find('input:checkbox:checked').length==$(that).parents('.cities').find('input:checkbox').length){
                $(that).parents('.priv-item').find('.priv-ck').attr('check-mode','full');
            }else{
                $(that).parents('.priv-item').find('.priv-ck').attr('check-mode','half');
            }
            if($(that).parents('.cities').find('input:checkbox:checked').length > 0){
                $(that).parents('.priv-item').find('.priv-ck').attr('checked','checked');
                $(that).parents('.priv-item').find('.priv-ck').prop('checked','checked');
            }else{
                $(that).parents('.priv-item').find('.priv-ck').removeAttr('checked');
                $(that).parents('.priv-item').find('.priv-ck').removeProp('checked');
            }

            // for area cascade
            if($(that).parents('.area-item').find('.priv-ck:checked').length==$(that).parents('.area-item').find('.priv-ck').length
            && $(that).parents('.area-item').find('.priv-ck[check-mode="half"]').length == 0
            ){
                $(that).parents('.area-item').find('.area-ck').attr('check-mode','full');
            }else{
                $(that).parents('.area-item').find('.area-ck').attr('check-mode','half');
            }
            if($(that).parents('.area-item').find('.priv-ck:checked').length > 0){
                $(that).parents('.area-item').find('.area-ck').attr('checked','checked');
                $(that).parents('.area-item').find('.area-ck').prop('checked','checked');
            }else{
                $(that).parents('.area-item').find('.area-ck').removeAttr('checked');
                $(that).parents('.area-item').find('.area-ck').removeProp('checked');
            }

        });

        $('.priv-ck').on('click', function () {
            var that = this;
            // for city cascade
            if($(that).prop('checked')){
                $(that).parents('.priv-item').find('.cities input:checkbox').attr('checked','checked');
                $(that).parents('.priv-item').find('.cities input:checkbox').prop('checked','checked');
                $(that).attr('check-mode','full');
            }else{
                $(that).parents('.priv-item').find('.cities input:checkbox').removeAttr('checked');
                $(that).parents('.priv-item').find('.cities input:checkbox').removeProp('checked');
                $(that).attr('check-mode','half');
            }


            // for area cascade
            if($(that).parents('.area-item').find('.priv-ck:checked').length==$(that).parents('.area-item').find('.priv-ck').length){
                $(that).parents('.area-item').find('.area-ck').attr('check-mode','full');
            }else{
                $(that).parents('.area-item').find('.area-ck').attr('check-mode','half');
            }
            if($(that).parents('.area-item').find('.priv-ck:checked').length > 0){
                $(that).parents('.area-item').find('.area-ck').attr('checked','checked');
                $(that).parents('.area-item').find('.area-ck').prop('checked','checked');
            }else{
                $(that).parents('.area-item').find('.area-ck').removeAttr('checked');
                $(that).parents('.area-item').find('.area-ck').removeProp('checked');
            }
        });

        $('.area-ck').on('click', function () {
            var that = this;
            // for city cascade
            if($(that).prop('checked')){
                $(that).parents('.area-item').find('.priv-item input:checkbox').attr('checked','checked');
                $(that).parents('.area-item').find('.priv-item input:checkbox').prop('checked','checked');
                $(that).parents('.area-item').find('.priv-item .priv-ck').attr('check-mode','full');
                $(that).attr('check-mode','full');
            }else{
                $(that).parents('.area-item').find('.priv-item input:checkbox').removeAttr('checked');
                $(that).parents('.area-item').find('.priv-item input:checkbox').removeProp('checked');
                $(that).parents('.area-item').find('.priv-item .priv-ck').attr('check-mode','half');
                $(that).attr('check-mode','half');
            }

        });




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