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
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        
        <!-- "part 1" -->
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-express-part1" >
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-horizontal" >
                <div class="col-sm-6">
                <label class="control-label"><?php echo $text_free_shipping; ?></label>
                </div>
                <div class="col-sm-6" style="margin-left:-40px">
                <input type="text" name="filter_free_shipping" value="<?php echo $filter_free_shipping;?>" placeholder="<?php echo $text_shipping_unit; ?>" id="input-free-shipping" class="form-control placeholder_leshare"/>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-horizontal ">
                <div class="col-sm-8" >
                <label class="control-label"><?php echo $text_free_tax; ?></label>
                </div>
                <div class="col-sm-4" style="margin-left:-40px">
                <input type="text" name="filter_free_tax" value="<?php echo $filter_free_tax;?>" placeholder="<?php echo $text_shipping_unit; ?>" id="input-free-tax" class="form-control placeholder_leshare"/>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-horizontal">
                <div class="col-sm-7" style="text-align:right">
                <label class="control-label"><?php echo $text_free_prepay; ?></label>
                </div>
                <div class="col-sm-5">
                <input type="text" name="filter_free_prepay" value="<?php echo $filter_free_prepay;?>" placeholder="<?php echo $text_shipping_unit; ?>" id="input-free-prepay" class="form-control placeholder_leshare"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        </form>
        
        <!-- "part 2" -->
        <div class="well">
          <div class="row">
            
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-express-details" >
            <input type="hidden" name="input_inner_form" value="0" id="input-inner-form"/>
            <input type="hidden" name="input_expco_text" value="" id="input-expco-text"/>
            <div class="col-sm-8">
              <div style="vertical-align:middle;">
                <label class="control-label"><?php echo $text_express_select; ?></label>
                <div class="col-sm-12" style="margin-top:8px;margin-bottom:0;vertical-align:bottom;">
                  <table class="table table-bordered table-hover">
                    <?php $size = count($text_express_namelist,COUNT_NORMAL);
                          $column = 8;
                          $row = $size/$column;
                          $i = 0;
                          for($r = 0;$r < $row; $r++) {
                            echo "<tr>";
                              for($c = 0;$c < $column;$c++) {
                                echo "<td class=\"text-center\">";
                                $index = $c + ($r * $column);
                                if($index < $size){
                                  //echo "<div class='checkbox'><label><input type='checkbox' value='" . $text_express_namelist[$index]['expco_id'] ."' name='input_expco_id' >" . $text_express_namelist[$index]['name'] . "</label></div>";
                                  echo "<div class='radio'><label><input type='radio' value='" . $text_express_namelist[$index]['expco_id'] ."' id='" . $text_express_namelist[$index]['expco_id'] ."' name='input_expco_id' >" . $text_express_namelist[$index]['name'] . "</label></div>";
                                }
                                echo "</td>";
                              }
                            echo "</tr>";
                          }
                    ?>
                  </table>
                </div>
              </div>
              <div>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td colspan="2">
                      <div style="margin-top:8px;">
                        <label class="control-label">
                        <?php echo "计费方式：";?>
                        </label>
                        <label class="radio-inline">
                        <input type="radio" name="radio_price" id="radio-piece" value="1">按件数
                        </label>
                        <label class="radio-inline">
                        <input type="radio" name="radio_price" id="radio-weight" value="2">按重量
                        </label>
                        <label class="radio-inline">
                        <input type="radio" name="radio_price" id="radio-volume" value="3">按体积
                        </label>
                      </div>
                      <div class="form-inline" style="margin-top:8px;" id="express-address">
                        <label class="control-label"><?php echo "发货地："; ?></label>
                        <select name="input_place_origin" id="input-place-origin" class="form-control">
                          <option value="-1" selected="selected"></option>
                          <?php foreach ($place_origin as $model) { ?>
                          <option value="<?php echo $model['fromwhere_id']; ?>"><?php echo $model['place_name']; ?></option>
                          <?php } ?>
                        </select>
                        <label class="control-label"><?php echo "&nbsp;"; ?></label>
                        <label class="control-label"><?php echo "到达地市："; ?></label>
                        <select name="input_place_dest_prov" id="input-place-dest-prov" class="form-control">
                          <option value="*" selected="selected" disabled="disabled"><?php echo '--省--'; ?></option>
                          <?php foreach ($place_dest_prov as $model) { ?>
                          <option value="<?php echo $model['id']; ?>"><?php echo $model['name']; ?></option>
                          <?php } ?>
                        </select>
                        <select name="input_place_dest_city" id="input-place-dest-city" class="form-control"> 
                        <option disabled="disabled" selected="selected" value="*"><?php echo "--市--"; ?></option>
                        </select>
                     </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-horizontal" >
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "起步重量："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_weight" value="" placeholder="<?php echo "kg/立方米"; ?>" id="input-weight" class="form-control placeholder_leshare"/>
                        </div>
                      </div>
                      <div class="form-horizontal">
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "起步价格："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_price" value="" placeholder="<?php echo "元人民币"; ?>" id="input-price" class="form-control placeholder_leshare"/>
                        </div>
                      </div>
                      <div class="form-horizontal " >
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "续重重量："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_weight_new" value="" placeholder="<?php echo "kg"; ?>" id="input-weight-new" class="form-control placeholder_leshare"/>
                        </div>
                      </div>
                      <div class="form-horizontal " >
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "续重价格："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_price_new" value="" placeholder="<?php echo "元人民币/kg"; ?>" id="input-price-new" class="form-control placeholder_leshare"/>
                        </div>
                      </div>

                    </td>
                    <td>
                      <div class="form-horizontal " >
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "起步价格："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_price1" value="" placeholder="<?php echo "元人民币"; ?>" id="input-price1" class="form-control placeholder_leshare"/>
                        </div>
                      </div>
                      <div class="form-horizontal " >
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "续件价格："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_price2" value="" placeholder="<?php echo "元人民币/件"; ?>" id="input-price2" class="form-control placeholder_leshare"/>
                        </div>
                      </div>
                      <div class="form-horizontal">
                        <div class="col-sm-4" style="margin-bottom:6px">
                        <label class="control-label"><?php echo "体积计价："; ?></label>
                        </div>
                        <div class="col-sm-8" style="margin-left:-40px;margin-bottom:6px">
                        <input type="text" name="filter_price3" value="" placeholder="<?php echo "元人民币/立方米"; ?>" id="input-price3" class="form-control placeholder_leshare"/>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr style="text-align:center">
                    <td colspan="2">
                      <button type="button" id="btn-form-express-details" form="form-express-details" data-toggle="tooltip" title="<?php echo "保存"; ?>" class="btn btn-primary"><i class="fa fa-save"><?php echo "&#12288;保存"; ?></i></button>
                      <button type="button" id="btn-check-express-details" class="btn btn-primary"><i class="fa fa-question"><?php echo "&#12288;查看";?></i></button>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            </form>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-express-part2" >
            <div class="col-sm-4">
              <label class="control-label">
              <?php echo "同一订单，出现多个计费方式时：";?>
              </label>
              <?php if ($inlineRadioOptions == 1){ ?>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" checked='checked' id="inlineRadio1" value="1">分单邮寄，邮费为多种计费方式之和
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2">合单邮寄，按照哪一种计费模式合并计费，收费最低，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为100元）
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="3">合单邮寄，按照哪一种计费模式合并计费，收费最高，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为120元）
              </label>
              <?php } else if ($inlineRadioOptions == 2) { ?>
               <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1">分单邮寄，邮费为多种计费方式之和
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" checked='checked' id="inlineRadio2" value="2">合单邮寄，按照哪一种计费模式合并计费，收费最低，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为100元）
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="3">合单邮寄，按照哪一种计费模式合并计费，收费最高，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为120元）
              </label>
              <?php } else if ($inlineRadioOptions == 3) { ?>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1">分单邮寄，邮费为多种计费方式之和
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2">合单邮寄，按照哪一种计费模式合并计费，收费最低，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为100元）
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" checked='checked' id="inlineRadio3" value="3">合单邮寄，按照哪一种计费模式合并计费，收费最高，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为120元）
              </label>
            
              <?php } else { ?>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1">分单邮寄，邮费为多种计费方式之和
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2">合单邮寄，按照哪一种计费模式合并计费，收费最低，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为100元）
              </label>
              <label class="radio">
                <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="3">合单邮寄，按照哪一种计费模式合并计费，收费最高，则统一按该计费模式计算邮费（如：全部按件数计费为100元，按重量计费为110元，按体积计费为120元，则计算为120元）
              </label>
              <?php } ?>
            </div>
            </form>

          </div>
        </div>

        <!-- "part 3" -->
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-express-part3" >
        <div class="row">
          <div class="well">
            <div class="form-inline" style="margin-top:8px;">
              <label class="control-label"><?php echo $text_sales_return; ?></label>
            </div>
            <div class="form-inline" style="margin-top:8px;">
              <label class="control-label"><?php echo "&#12288;".$text_sales_return_name.'&#12288;'; ?></label>
              <input class="form-control" type="text" name="filter_sales_return_name" value="<?php echo $filter_sales_return_name;?>" id="input-sales-return-name"/>
            </div>
            <div class="form-inline" style="margin-top:8px;">
              <label class="control-label"><?php echo $text_sales_return_phone.' '; ?></label>
              <input class="form-control " type="text" name="filter_sales_return_phone" value="<?php echo $filter_sales_return_phone;?>" id="input-sales-return-phone" placeholder="<?php echo "手机号" ;?>"/>
            </div>
            <div class="form-inline" style="margin-top:8px;" id="company-address">
              <label class="control-label"><?php echo $text_sales_return_addr.' '; ?></label>
              
              <select class="form-control" name="company_addr_province" id="input-company-addr-province" >
              <option disabled="disabled" selected="selected" value="0"><?php echo "--省--"; ?></option>
              <?php foreach ($provinces as $province) { ?>
              <?php if ($province['id'] == $province_id) { ?>
              <option value="<?php echo $province['id']; ?>" selected="selected"><?php echo $province['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $province['id']; ?>"><?php echo $province['name']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
              
              <select class="form-control" name="company_addr_city" id="input-company-addr-city" >
                    <?php if($city_id) {?>
                      <option selected="selected" value="<?php echo $city_id;?>"><?php echo $city_name; ?></option>
                    <?php } else {?>
                      <option disabled="disabled" selected="selected" value="0"><?php echo "--市--"; ?></option>
                    <?php }?>
              </select>
              
              <select class="form-control" name="company_addr_district" id="input-company-addr-district">
                    <?php if($district_id) {?>
                      <option selected="selected" value="<?php echo $district_id;?>"><?php echo $district_name; ?></option>
                    <?php } else {?>
                      <option disabled="disabled" selected="selected" value="0"><?php echo "--区--"; ?></option>
                    <?php }?>
              </select>

              <input class="form-control " style="width:30%" type="text" name="filter_sales_return_addr_street" value="<?php echo $filter_sales_return_addr_street;?>" id="input-sales-return-addr-street"/  placeholder="详细地址">

            </div>
          </div>
        </div>
        </form>

        <!-- "part 4" -->
        <div class="well">
          <div class="row">
            <div style="margin-top:10px;text-align:center">
              <div style="display:inline-block;">
              <button type="button" id="btn-form-express-all" data-toggle="tooltip" title="<?php echo "保存修改"; ?>" class="btn btn-primary"><i class="fa fa-save"><?php echo "&#12288;保存修改"; ?></i></button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- liuhang add : for selecting province-city-district interactively -->
<script type="text/javascript"><!--

$('#btn-check-express-details').on('click',function(){

  //var origin_place_id = $('#input-place-origin').val();
  //if (origin_place_id == -1){
   //html = '<div><a>请选择发货地！</a></div>';
   //showWideHtmlWin('错误！！！',html);
   //console.debug('error origin_place_id');
   // return true;
  //}

	var	url = 'index.php?route=extension/shipping/getExpressPriceList&token=<?php echo $token; ?>';
  $.post(url,{fromwhere_id:$('#input-place-origin').val()},function(data){
    
  var html = '<div class="container-fluid">';

  var fromwhere_name = data['fromwhere_name'];
  var price_list = data['result'];

  if(data && price_list.length>0){
      html = html +
              '<div class="row">' +
              '<div class="col-sm-12">' +
              '<div class="panel panel-default" style="border:none;">' +
              '<div class="panel-header text-center" style="padding: 10px;border-radius: 10px 10px 10px 10px;background: #ccc;"><h2>'+'物流设置'+'</h2></div>';

      html = html +
            '<div class="table-responsive col-sm-12" >' +
              '<table class="table table-bordered table-hover" style="border:solid 1px">' +
                '<thead>' +
                '<tr>' +
              '<td class="text-center"><a><?php echo "发货地"; ?></a></td>' +
                  '<td class="text-center"><a><?php echo "省"; ?></a></td>' +
                  '<td class="text-center"><a><?php echo "地市"; ?></a></td>' +
                  '<td class="text-center"><a><?php echo "快递公司"; ?></a></td>' +
                  '<td class="text-center"><a><?php echo "按重量计费方式"; ?></a></td>' + 
                  '<td class="text-center"><a><?php echo "按体积计费方式"; ?></a></td>' +
                  '<td class="text-center"><a><?php echo "按件数计费方式"; ?></a></td>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

      for(var i = 0;i < price_list.length;i++){
        html += '<tr>';
        html += '<td class="text-center"><a>'+ price_list[i]['fwname'] +'</a></td>';
        html += '<td class="text-center"><a>'+ price_list[i]['pname'] +'</a></td>'; 
        html += '<td class="text-center"><a>'+ price_list[i]['cname'] +'</a></td>'; 
        html += '<td class="text-center"><a>'+ price_list[i]['expconame'] +'</a></td>'; 
        html += '<td class="text-center"><a>'+ 
                price_list[i]['weight_start_price']+
                '+' + 
                price_list[i]['weight_add_price']+
                '</a></td>'; 
        html += '<td class="text-center"><a>'+
                price_list[i]['volume_start_price']+
                '</a></td>'; 
        html += '<td class="text-center"><a>'+
                price_list[i]['piece_start_price']+
                '+' + 
                price_list[i]['piece_add_price']+
                '</a></td>'; 
        html += '</tr>';          
    }

      html = html +
              '</tbody>' +
              '</table>' +
              '</div>';
      html = html +
              '</div>' +
              '</div>' +
              '</div>';
  }else{
    html = html +
            '<div class="row">' +
            '<div class="col-sm-12">' +
            '<span>暂无物流价格信息</span>' +
            '</div>' +
            '</div>';
  }
  
  html += '</div>';
  showWideHtmlWin('物流价格信息',html);
  return false;
  
});

});





$('#btn-form-express-details').on('click',function(){

  $('#input-inner-form').val('1');
  
  var text="";
  $("input[name=input_expco_id]").each(function() {  
      if ($(this).prop("checked")) {  
          text += $(this).val() + ",";  
      }  
  }); 
  //alert(text);
  $("input[name=\'input_expco_text\']").val(text);


  $('#form-express-details').submit();

});



$('#btn-form-express-all').on('click',function(){
  
  //alert("liuhang test!");
  $('#input-inner-form').val('0');
  //$('#form-express-part1').submit();
  //$('#form-express-part2').submit();
  //$('#form-express-part3').submit();
  
  var var1 = $("input[name='filter_free_shipping']").val();
  var var2 = $("input[name='filter_free_tax']").val();
  var var3 = $("input[name='filter_free_prepay']").val();
  var var4 = $("input[name='inlineRadioOptions']:checked").val();
  var var5 = $("input[name='filter_sales_return_name']").val();
  var var6 = $("input[name='filter_sales_return_phone']").val();
  var var7 = $("select[name='company_addr_province']").val();
  var var8 = $("select[name='company_addr_city']").val();
  var var9 = $("select[name='company_addr_district']").val();
  var var10 = $("input[name='filter_sales_return_addr_street']").val();
/*
	var	url = 'index.php?route=extension/shipping/getList_leshare&token=<?php echo $token; ?>';
  $.post(url,{filter_free_shipping:var1,filter_free_tax:var2,filter_free_prepay:var3,inlineRadioOptions:var4,filter_sales_return_name:var5,filter_sales_return_phone:var6,company_addr_province:var7,filter_sales_return_addr_street:var8,input_inner_form:0 },function(data){
    location.href = url;
  });
*/
  console.debug('liuhang add for inlineRadioOptions : '+var4);
	var	url = 'index.php?route=extension/shipping/getList_leshare&token=<?php echo $token; ?>';
  var args = {filter_free_shipping:var1,filter_free_tax:var2,filter_free_prepay:var3,inlineRadioOptions:var4,filter_sales_return_name:var5,filter_sales_return_phone:var6,company_addr_province:var7,company_addr_city:var8,company_addr_district:var9,filter_sales_return_addr_street:var10,input_inner_form:0 };
  //$.extend({
      //StandardPost:function(url,args){
          var body = $(document.body),
              form = $("<form method='post'></form>"),
              input;
          form.attr({"action":url});
          $.each(args,function(key,value){
              input = $("<input type='hidden'>");
              input.attr({"name":key});
              input.val(value);
              form.append(input);
          });

          form.appendTo(document.body);
          form.submit();
          document.body.removeChild(form[0]);
 //     }
 // });


  
});

//--></script>





<script type="text/javascript"><!--

$('#radio-piece').on('click',function(){

  $('#input-weight').val('');
  $('#input-weight').prop('disabled','disabled');

  $('#input-price').val('');
  $('#input-price').prop('disabled','disabled');

  $('#input-weight-new').val('');
  $('#input-weight-new').prop('disabled','disabled');

  $('#input-price-new').val('');
  $('#input-price-new').prop('disabled','disabled');

  //$('#input-price1').val('');
  $('#input-price1').prop('disabled',false);

  //$('#input-price2').val('');
  $('#input-price2').prop('disabled',false);

  //$('#input-price3').val('');
  $('#input-price3').prop('disabled','disabled');

  });


$('#radio-weight').on('click',function(){

  //$('#input-weight').val('');
  $('#input-weight').prop('disabled',false);

  //$('#input-price').val('');
  $('#input-price').prop('disabled',false);

  //$('#input-weight-new').val('');
  $('#input-weight-new').prop('disabled',false);

  //$('#input-price-new').val('');
  $('#input-price-new').prop('disabled',false);

  $('#input-price1').val('');
  $('#input-price1').prop('disabled','disabled');

  $('#input-price2').val('');
  $('#input-price2').prop('disabled','disabled');

  $('#input-price3').val('');
  $('#input-price3').prop('disabled','disabled');

  });


$('#radio-volume').on('click',function(){

  $('#input-weight').val('');
  $('#input-weight').prop('disabled','disabled');

  $('#input-price').val('');
  $('#input-price').prop('disabled','disabled');

  $('#input-weight-new').val('');
  $('#input-weight-new').prop('disabled','disabled');

  $('#input-price-new').val('');
  $('#input-price-new').prop('disabled','disabled');

  $('#input-price1').val('');
  $('#input-price1').prop('disabled','disabled');

  $('#input-price2').val('');
  $('#input-price2').prop('disabled','disabled');

  //$('#input-price3').val('');
  $('#input-price3').prop('disabled',false);

  });

$('body').delegate('#company-address select[name=\'company_addr_province\']','change', function() {
//$('#company-address select[name=\'company_addr_province\']').on('change', function() {

	$.ajax({
		url: 'index.php?route=common/supplier_reg/province_cn&province_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#company-address select[name=\'company_addr_province\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.fa-spin').remove();

      html = '<option disabled="disabled" selected="selected" value="0"><?php echo "--市--"; ?></option>';
      //html = '';
       
      var city_arr = new Array();
      city_arr = json['city'];
			if (json['city'] != '') {
				//for (i = 0; i < json['city'].length; i++) {
				for (i = 0; i < city_arr.length; i++) {
					html += '<option value="' + json['city'][i]['id'] + '"';

					if (json['city'][i]['id'] == '<?php echo $city_id; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['city'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" disabled="disabled" selected="selected"><?php echo "--市--" ; ?></option>';
			}

			$('#company-address select[name=\'company_addr_city\']').html(html);

      //html1 = '<option disabled="disabled" selected="selected" value="0"><?php echo "--区--"; ?></option>';
			//$('#company-address select[name=\'company_addr_district\']').html(html1);
      
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#company-address select[name=\'company_addr_province\']').trigger('change');
//--></script>



<script type="text/javascript"><!--
$('body').delegate('#company-address select[name=\'company_addr_city\']','change', function() {
//$('#company-address select[name=\'company_addr_city\']').on('change', function() {

        //console.debug("liuhang:"+this.value);

	$.ajax({
		url: 'index.php?route=common/supplier_reg/city_cn&cityid=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#company-address select[name=\'company_addr_city\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.fa-spin').remove();

		  html = '<option value="0" disabled="disabled" selected="selected"><?php echo "--区--" ; ?></option>';
      //console.debug("liuhang1 : " + json['district']);

			if (json['district'] != null ) {
			//if (!empty(json['district']) ) {
				for (i = 0; i < json['district'].length; i++) {
					html += '<option value="' + json['district'][i]['id'] + '"';

					if (json['district'][i]['id'] == '<?php echo $district_id; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['district'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" disabled="disabled" selected="selected"><?php echo "--区--" ; ?></option>';
			}

			$('#company-address select[name=\'company_addr_district\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#company-address select[name=\'company_addr_city\']').trigger('change');
//--></script>


<!-- liuhang add : for selecting province-city-district interactively -->
<script type="text/javascript"><!--
$('#express-address select[name=\'input_place_dest_prov\']').on('change', function() {

	$.ajax({
		url: 'index.php?route=common/supplier_reg/province_cn&province_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#express-address select[name=\'input_place_dest_prov\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.fa-spin').remove();

      html = '<option disabled="disabled" selected="selected" value="0"><?php echo "--市--"; ?></option>';

			if ((json['city'] != '') && (json['city'] != undefined)) {
				for (i = 0; i < json['city'].length; i++) {
					html += '<option value="' + json['city'][i]['id'] + '"';

					//if (json['city'][i]['id'] == '<?php echo $city_id; ?>') {
						//html += ' selected="selected"';
					//}

					html += '>' + json['city'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" disabled="disabled" selected="selected"><?php echo "--市--" ; ?></option>';
			}

			$('#express-address select[name=\'input_place_dest_city\']').html(html);

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#express-address select[name=\'input_place_dest_prov\']').trigger('change');

$('body').delegate('#express-address select[name=\'input_place_dest_city\']','change', function() {

  var province_id = $('#express-address select[name=\'input_place_dest_prov\']').val();
  var fromwhere_id = $('#express-address select[name=\'input_place_origin\']').val();
  
	$.ajax({
		url: 'index.php?route=extension/shipping/getExpcoByAddress&token=<?php echo $token; ?>&province_id='+province_id+'&city_id=' + this.value+'&fromwhere_id='+fromwhere_id,
		dataType: 'json',
		success: function(json) {
      
       $("input[name=input_expco_id]").each(function() { 
         $(this).removeAttr("checked");
         $(this).removeAttr("disabled");  
       }); 
      if (json['expco'] != '' &&json['expco'] != undefined) {
       $("input[name=input_expco_id]").each(function() {  
         if($(this).val() == json['expco']){
           $(this).attr("checked",true);
           //alert(json['expco']); 
         } else {
           $(this).attr("disabled","disabled");  
         $(this).removeAttr("checked");
         }
       });
      } 
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


$('#express-address select[name=\'input_place_dest_city\']').trigger('change');


//--></script>





<?php echo $footer; ?>
