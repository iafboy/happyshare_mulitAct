<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-repeat"></i> <?php echo $heading_title; ?></h1>
          </div>
          <div class="panel-body" id="company-address">
            <?php if ($error_warning) { ?>
              <div class="text-danger"><p id="text-warning" style="font-size:18px" ><strong><?php echo $error_warning; ?></strong></p></div>
            <?php } ?>
            
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <!-- 公司名称 -->
              <div class="form-group required" >
                <label class="col-sm-4 control-label" for="input-company-name"><?php echo $text_company_name." : "; ?></label>
                <div class="col-sm-12">
                  <input type="text" name="company_name" placeholder="<?php echo $text_company_name; ?>" value="<?php echo $company_name;?>" id="input-company-name" class="form-control" />
                </div>
              </div>
              
              <!-- 公司地址 -->
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-company-addr"><?php echo $text_company_addr." : "; ?></label>
                <div class="col-sm-12">
                  <select name="company_addr_province" id="input-company-addr-province" class="form-control">
                  <option disabled="disabled" selected="selected" value="0"><?php echo "--省--"; ?></option>
                  <?php foreach ($provinces as $province) { ?>
                  <?php if ($province['id'] == $province_id) { ?>
                  <option value="<?php echo $province['id']; ?>" selected="selected"><?php echo $province['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $province['id']; ?>"><?php echo $province['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                  </select>
                  <select name="company_addr_city" id="input-company-addr-city" class="form-control">
                    <?php if($city_id) { ?>
                      <option selected="selected" value="<?php echo $city_id;?>"><?php echo $city_name; ?></option>
                    <?php } else { ?>
                      <option disabled="disabled" selected="selected" value="0"><?php echo "--市--"; ?></option>
                    <?php }?>
                  </select>
                  <select name="company_addr_district" id="input-company-addr-district" class="form-control" >
                    <?php if($district_id) { ?>
                      <option selected="selected" value="<?php echo $district_id;?>"><?php echo $district_name; ?></option>
                    <?php } else { ?>
                      <option disabled="disabled" selected="selected" value="0"><?php echo "--区--"; ?></option>
                    <?php }?>
                  </select>
                 
                  <input type="text" name="company_addr_details" placeholder="<?php echo $text_company_addr_details; ?>" value="<?php echo $company_addr_details;?>" id="input-company-addr-details" class="form-control" />
                </div>
              </div>
              
              <!-- 公司联系人 -->
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-company-contacts"><?php echo $text_company_contacts; ?></label>
                <div class="col-sm-12">
                  <input type="text" name="company_contacts"  placeholder="<?php echo $text_company_contacts; ?>" value="<?php echo $company_contacts;?>" id="input-company-contacts" class="form-control" />
                </div>
              </div>
              
              <!-- 联系人手机号 -->
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-contacts-phone"><?php echo $text_contacts_phone; ?></label>
                <div class="col-sm-12">
                  <input type="text" name="contacts_phone"  placeholder="<?php echo $text_contacts_phone; ?>" value="<?php echo $contacts_phone;?>" id="input-contacts-phone" class="form-control" />
                </div>
              </div>

                <!-- 联系人email -->
                <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-contacts-mail">联系人邮箱</label>
                    <div class="col-sm-12">
                        <input type="text" name="contacts_email"  placeholder="<?php echo $text_contacts_email; ?>" value="<?php echo $contacts_email;?>" id="input-contacts-email" class="form-control" />
                    </div>
                </div>
\

              <!-- 验证码（目前是图片验证码） -->
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-check-code"><?php echo $text_check_code; ?></label>
                <div id="div_check_code" class="col-sm-12">
                <!--
                  <p><input type="text" name="check_code"  placeholder="<?php echo ""; ?>" id="input-check-code" style="vertical-align:middle;height:35px;padding:6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;  background-color: #fff;border: 1px solid #ccc;border-radius: 4px;" onblur="check(this.value)"/><span id="result"></span></p>
                  -->
                  <input type="text" name="check_code" id="input-check-code" style="vertical-align:middle;height:35px;padding:6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;  background-color: #fff;border: 1px solid #ccc;border-radius: 4px;" />
                  <img title="<?php echo "点击图片换一张！"; ?>" src="index.php?route=common/supplier_reg/captcha" style="cursor:pointer;vertical-align:middle" onClick="this.src='index.php?route=common/supplier_reg/captcha'"/>
                  <img id="check-code-img" /><span  name="check_code_img" ></span>
                </div>
              </div>

           
              <!-- 提交信息 (按钮) -->
              <div class="form-group">
                <div class="col-sm-12" style="margin-top: 10px;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_submit; ?></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                </div>
              </div>

            </form> 

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ***************************************************** -->
<!-- liuhang add : to show right or wrong pictures when input check_code -->
<script type="text/javascript"><!--
$("#input-check-code").on("mouseleave", function() {

//alert('liuhang test!');

	$.ajax({
		url: 'index.php?route=common/supplier_reg/check_code_img&check_code_img_value='+this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#div_check_code  input[name=\'check_code\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.fa-spin').remove();
			if (json['correct'] == 1) {
        $("#check-code-img").attr("src","view/image/right.gif")
//alert('liuhang test1!');
			} else if(json['correct'] == 2) {
        $("#check-code-img").attr("src","view/image/wrong.gif");
//alert('liuhang test2!');
			} else {
        $("#check-code-img").attr("src","");
      }
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('#company-address input[name=\'check_code\']').trigger("blur");
//--></script>


<!-- liuhang add : for selecting province-city-district interactively -->
<script type="text/javascript"><!--
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

			if (json['city'] != '') {
				for (i = 0; i < json['city'].length; i++) {
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

      html = '<option disabled="disabled" selected="selected" value="0"><?php echo "--区--"; ?></option>';

			if (json['district'] != '') {
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
<?php echo $footer; ?>
