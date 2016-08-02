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
    <div class="container">
      <form id="free-fm">
      <div class="row">
        <div class="col-sm-12" style="min-width: 100%;overflow: auto;">
          <!-- Base Part -->
          <table style="width: 100%;">
            <tr class="navi-row">
              <td class="navi-title"><span>活动基本信息</span></td>
              <td class="navi-content">
                <div>
                    <div>
                      <div style="float: left;width: 80%;">
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动名称：</label>
                          <span>
                            <input name="act_name" value="<?php echo $act['fp_name']; ?>" class="lfx-text" />
                          </span>
                        </span>
                        <span  class="entry-group">
                          <label>活动编号：</label>
                          <span><?php echo $act['fp_id']; ?></span>
                        </span>
                        </div>
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动有效期：</label>
                          <span>
                            <div class="date" style="display: inline-block;">
                              <input style="display: inline-block;width: 80%;" type="text"
                                     name="act_start_date" value="<?php echo $act['starttime']; ?>"
                                     data-date-format="YYYY-MM-DD" class="lfx-text" />
                              <span  style="width: 20%;">
                              <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </span>
                          -
                          <span>
                            <div class="date" style="display: inline-block;">
                              <input style="display: inline-block;width: 80%;" type="text"
                                     name="act_end_date" value="<?php echo $act['endtime']; ?>"
                                     data-date-format="YYYY-MM-DD" class="lfx-text" />
                              <span  style="width: 20%;">
                              <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </span>
                        </span>
                        </div>
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动说明：</label><br />
                          <span>
                            <textarea style="width: 90%;height:100px;" name="act_memo" class="lfx-text"><?php echo $act['memo']; ?></textarea>
                          </span>
                        </span>
                        </div>
                      </div>
                      <div style="float:left;width: 20%;">
                        <div class="content-row">
                        <span class="entry-group">
                          <label>活动图片：</label>
                          <span>
                            <button id="main-image-btn" type="button"
                                    onclick="uploadMainImage('#main-image')" class="lfx-btn">上传/更新</button>
                          </span>
                        </span>
                        </div>
                        <div class="content-row">
                          <div>
                            <div style="width: 200px;height:200px">
                              <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:100%;height:100%;">
                                <?php
                                if(is_valid($act['imgurl'])){ ?>
                                <img id="main-image" class="image-button" width="100%" height="100%"
                                     src="<?php echo DIR_IMAGE_URL.$act['imgurl']; ?>"
                                     data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />
                                <?php }else{ ?>
                                <img id="main-image" class="image-button" width="100%" height="100%" src="<?php echo $to_add_img; ?>"
                                     data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />
                                <?php }
                             ?>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </td>
            </tr>
          </table>
          <!-- Base Part -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>活动详情介绍页</span></td>
              <td class="navi-content ">
                <table style="width: 100%;">
                  <tr>
                    <td style="padding: 5px;">
                      <div  >
                        <div style="text-align: center">
                          <span>文案图片（不超过9）</span>
                        </div>

                        <div class="sub-images-box">
                          <?php
                           if(isset($act['sub_images'])){
                            $i = 0;
                            foreach($act['sub_images'] as $sub_image){
                            $i++;?>
                          <div class="image-container-sm sub-image" data-sub-index="<?php echo $i; ?>"
                               style="width: 150px;">
                            <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;height:100%;">
                              <img class="image-button" width="100%" height="100%"
                                   src="<?php echo DIR_IMAGE_URL.$sub_image['imgurl']; ?>"
                                   data-placeholder="<?php echo $placeholder; ?>"
                                   onclick="changeSubImage(this)" />
                            </a>
                          </div>
                          <?php }
                              if(sizeof($act['sub_images']) < 9){ ?>
                          <div class="image-container-sm add-image" style="width: 150px;text-align: center;">
                            <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;height:100%;">
                              <img class="image-button" width="100%" height="100%"
                                   src="<?php echo $to_add_img; ?>"
                                   data-placeholder="<?php echo $placeholder; ?>"
                                   onclick="appendSubImage(this)" />
                            </a>
                          </div>
                          <?php }
                           }else{ ?>
                          <div class="image-container-sm add-image image-button" style="width: 150px;height:150px;text-align: center;">
                            <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:100%;height:100%;">
                              <img class="image-button" width="100%" height="100%"
                                   src="<?php echo $to_add_img; ?>"
                                   data-placeholder="<?php echo $placeholder; ?>"
                                   onclick="appendSubImage(this)" />
                            </a>
                          </div>
                          <?php }
                           ?>
                        </div>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!-- Picture Part -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>规则设置</span></td>
              <td class="navi-content">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <label>申请资格设置:</label>
                  </div>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <input name="is_regduration" type="checkbox" id="is_regduration" <?php echo $act['regduration'] > 0?"checked":"" ?> />
                    <span>注册时间超过
                      <input name="regduration" value="<?php echo $act['regduration']; ?>" class="lfx-text lfx-text-sm" type="number" />
                      天</span>
                  </span>
                  <span class="entry-group" id="buysetting" style="margin-right: 0px;display: inline-block;">
                    <?php if($act['buysetting'].''==='1'){ ?>
                      <span class="lfx-label st_1 pointer buysetting_on lfx-label-on">并且</span>
                      <span class="lfx-label st_1 pointer buysetting_off">或者</span>
                    <?php }else { ?>
                      <span class="lfx-label st_1 pointer buysetting_on ">并且</span>
                      <span class="lfx-label st_1 pointer buysetting_off lfx-label-on">或者</span>
                    <?php } ?>
                  </span>
                  <span class="entry-group" style="margin-right: 0px;">
                    <input name="is_buyamount" id="is_buyamount" type="checkbox" <?php echo $act['buyamount'] > 0?"checked":"" ?> />
                    <span>累计消费超过
                      <input name="buyamount"  value="<?php echo $act['buyamount']; ?>" class="lfx-text lfx-text-sm" type="number" />
                      元</span>
                  </span>
                  <span class="entry-group" id="amountsetting" style="margin-right: 0px;display: inline-block;">
                    <?php if($act['amountsetting'].''==='1'){ ?>
                    <span class="lfx-label st_1 pointer amountsetting_on lfx-label-on">并且</span>
                      <span class="lfx-label st_1 pointer amountsetting_off">或者</span>
                    <?php }else { ?>
                    <span class="lfx-label st_1 pointer amountsetting_on ">并且</span>
                      <span class="lfx-label st_1 pointer amountsetting_off lfx-label-on">或者</span>
                    <?php } ?>
                  </span>
                  <span class="entry-group" style="margin-right: 0px;">
                    <input name="is_buynumber" id="is_buynumber" type="checkbox" <?php echo $act['buynumber'] > 0?"checked":"" ?>  />
                    <span>发展用户超过
                      <input name="buynumber"  value="<?php echo $act['buynumber']; ?>" class="lfx-text lfx-text-sm" type="number" />
                      人</span>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <label>无申请资格告知说明:</label>
                    <input name="no_auth_memo" value="<?php echo $act['no_auth_memo']; ?>" style="width: 400px" class="lfx-text" />
                  </span>
                </div>
                <div class="content-row" style="margin-top: 10px;">
                  <span class="entry-group">
                    <label>面退回资格设置:</label>
                  </span>
                </div>
                <div class="content-row">
                  <span class="entry-group" style="margin-right: 0px;">
                    <input id="is_sharenumber" name="is_sharenumber" type="checkbox" <?php echo $act['sharenumber'] > 0?"checked":"" ?>  />
                    <span>发展用户超过
                      <input name="sharenumber" value="<?php echo $act['sharenumber']; ?>" class="lfx-text lfx-text-sm" type="number" />
                      人</span>
                  </span>
                  <span class="entry-group" style="margin-right: 0px;display: inline-block;">
                    <?php if($act['refoundsharesetting'].''==='1'){ ?>
                      <span class="lfx-label st_1 pointer refoundsharesetting_on lfx-label-on">并且</span>
                      <span class="lfx-label st_1 pointer refoundsharesetting_off">或者</span>
                    <?php }else { ?>
                      <span class="lfx-label st_1 pointer refoundsharesetting_on ">并且</span>
                      <span class="lfx-label st_1 pointer refoundsharesetting_off lfx-label-on">或者</span>
                    <?php } ?>
                  </span>
                  <span class="entry-group" style="margin-right: 0px;">
                    <input name="is_rfsharenumber" id="is_rfsharenumber" type="checkbox" <?php echo $act['rfsharenumber'] > 0?"checked":"" ?>  />
                    <span>发展用户购买同款商品超过
                      <input name="rfsharenumber" value="<?php echo $act['rfsharenumber']; ?>" class="lfx-text lfx-text-sm" type="number" />
                      个</span>
                  </span>
                </div>
              </td>
            </tr>
          </table>
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>需要退回的商品</span></td>
              <td class="navi-content">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <label>商品编码:</label>
                    <input id="productIdText_1" class="lfx-text" />
                    <button class="lfx-btn btn btn-sm" type="button" onclick="addProductRow(1)">加入活动列表</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="lfx-btn btn btn-sm" type="button" onclick="importProductXls(1)">导入商品编码</button>
                  </div>
                </div>
                <div class="content-row" style="margin-top: 20px;">
                  <table class="table table-bordered lfx-table" id="product-table-1">
                    <thead>
                    <tr>
                      <th>商品编码</th>
                      <th>商品名称</th>
                        <th>原价/元</th>
                        <th>活动价/元</th>
                      <th>商品积分回馈</th>
                      <th>体验名额</th>
                      <th>免费体验期</th>
                      <th>商品免退回条件</th>
                      <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                     if(sizeof($act['refounds']) > 0){
                      foreach($act['refounds'] as $product){ ?>
                      <tr class="product-item-<?php echo $product['product_id']; ?>">
                      <td><?php echo $product['product_id']; ?><input type="hidden" name="refounds[]" value="<?php echo $product['product_id']; ?>" /></td>
                      <td><?php echo $product['product_name']; ?></td>
                      <td><?php echo $product['storeprice']; ?></td>
                      <td>
                          <input type="number" name="rfp_<?php echo $product['product_id']; ?>_act_price" class="lfx-text" style="width: 100px;"
                                 value="<?php echo $product['act_price']; ?>" />
                      </td>
                      <td>
                        <input type="number" name="rfp_<?php echo $product['product_id']; ?>_credit" class="lfx-text" style="width: 100px;"
                                value="<?php echo $product['credit']; ?>" />
                      </td>
                      <td>
                        <input type="number" name="rfp_<?php echo $product['product_id']; ?>_limitpeople" class="lfx-text" style="width: 100px;"
                               value="<?php echo $product['limitpeople']; ?>" />
                      </td>
                      <td>
                        <input type="number" name="rfp_<?php echo $product['product_id']; ?>_freedays" class="lfx-text" style="width: 100px;"
                               value="<?php echo $product['freedays']; ?>" />天
                      </td>
                      <td>
                        <input id="is_rfp_<?php echo $product['product_id']; ?>_sharenumber" type="checkbox" <?php echo ($product['sharenumber']>0?"checked":""); ?> />
                        <span>发展用户超过</span>
                        <input name="rfp_<?php echo $product['product_id']; ?>_sharenumber" type="number" class="lfx-text" style="width: 40px;"
                               value="<?php echo $product['sharenumber']; ?>" />
                        <span>个</span>
                      </td>
                      <td><button class="lfx-btn" type="button" onclick="$(this).parentsUntil('tr').parent().remove()">删除</button></td>
                    </tr>

                      <?php }
                     }
                     ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </table>
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>不需要退回的商品</span></td>
              <td class="navi-content">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <label>商品编码:</label>
                    <input id="productIdText_2" class="lfx-text" />
                    <button class="lfx-btn btn btn-sm" type="button" onclick="addProductRow(2)">加入活动列表</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="lfx-btn btn btn-sm" type="button" onclick="importProductXls(2)">导入商品编码</button>
                  </div>
                </div>
                <div class="content-row" style="margin-top: 20px;">
                  <table class="table table-bordered lfx-table" id="product-table-2">
                    <thead>
                    <tr>
                      <th>商品编码</th>
                      <th>商品名称</th>
                      <th>原价/元</th>
                      <th>活动价/元</th>
                      <th>商品积分回馈</th>
                      <th>体验名额</th>
                      <th>免费体验期</th>
                      <th>退费要求</th>
                      <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                     if(sizeof($act['norefounds']) > 0){
                    foreach($act['norefounds'] as $product){
                        $product_name = $product['product_name'] || '';
                        if(strlen($product_name)>20){
                            $product_name = substr($product_name,0,20).'...';
                        }

                    ?>

                    <tr class="product-item-<?php echo $product['product_id']; ?>">
                      <td><?php echo $product['product_id']; ?><input type="hidden" name="norefounds[]" value="<?php echo $product['product_id']; ?>" /></td>
                      <td><?php echo $product_name; ?></td>
                      <td><?php echo $product['storeprice']; ?></td>
                      <td>
                          <input type="number" name="nrfp_<?php echo $product['product_id']; ?>_act_price" class="lfx-text" style="width: 100px;"
                                 value="<?php echo $product['act_price']; ?>" />
                      </td>
                      <td>
                        <input type="number" name="nrfp_<?php echo $product['product_id']; ?>_credit" class="lfx-text" style="width: 100px;"
                               value="<?php echo $product['credit']; ?>" />
                      </td>
                      <td>
                        <input type="number" name="nrfp_<?php echo $product['product_id']; ?>_limitpeople" class="lfx-text" style="width: 100px;"
                               value="<?php echo $product['limitpeople']; ?>" />
                      </td>
                      <td>
                        <input type="number" name="nrfp_<?php echo $product['product_id']; ?>_freedays" class="lfx-text" style="width: 100px;"
                               value="<?php echo $product['freedays']; ?>" />天
                      </td>
                      <td>
                        <input
                               id="is_nrfp_<?php echo $product['product_id']; ?>_sharenumber" type="checkbox" <?php echo ($product['sharenumber']>0?"checked":""); ?> />
                        <span>发展用户超过</span>
                        <input name="nrfp_<?php echo $product['product_id']; ?>_sharenumber" type="number" class="lfx-text" style="width: 40px;"
                               value="<?php echo $product['sharenumber']; ?>" />
                        <span>个</span>
                        <br />
                        <br />
                        <input
                               id="is_nrfp_<?php echo $product['product_id']; ?>_wxshare" type="checkbox" <?php echo ($product['wxshare']>0?"checked":""); ?> />
                        <span>微信朋友圈体验</span>
                        <input name="nrfp_<?php echo $product['product_id']; ?>_wxshare" type="number" class="lfx-text" style="width: 40px;" value="<?php echo $product['wxshare']; ?>" />
                      </td>
                      <td><button class="lfx-btn" type="button" onclick="$(this).parentsUntil('tr').parent().remove()">删除</button></td>
                    </tr>
                    <?php }
                     }
                     ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      </form>
      <?php if($link_mode == 'create'){ ?>
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="createAct(0)">提交并直接上架</button>
        <button class="btn lfx-btn" type="button" onclick="createAct(1)">提交暂不上架</button>
        <a href="<?php echo 'index.php?route=activity/free&token='.$token.'&link_mode=create'; ?>" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>
      </div>
      <?php }else if($link_mode == 'modify'){ ?>
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button class="btn lfx-btn" type="button" onclick="saveAct(0)">保存并上架</button>
        <button class="btn lfx-btn" type="button" onclick="saveAct(1)">保存，暂不上架</button>
        <a onclick="history.go(-1)" class="btn lfx-btn"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></a>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
  function renderProductRows(products,type){
    var html = '';
    for(var i = 0; i < products.length; i++){
      var product = products[i];
      if($('#product-table-'+type+' tbody tr.product-item-'+product.product_id).length!=0){
        continue;
      }
      var p_fs = '';
      var p_prefix = '';
      if(type==1){
        p_fs = 'refounds[]';
        p_prefix = 'rf';
      }else{
        p_fs = 'norefounds[]';
        p_prefix = 'nrf';
      }
      var product_name = product.product_name || '';
      if(product_name.length>20){
	product_name = product_name.substring(0,20)+'...';
      }
        html = html +
          '<tr class="product-item-'+product.product_id+'"> \
          <td>'+product.product_id+'<input type="hidden" name="'+p_fs+'" value="'+product.product_id+'" /></td> \
          <td>'+product_name+'</td> \
          <td>'+product.storeprice+'</td> \
          <td> \
          <input type="number" name="'+p_prefix+'p_'+product.product_id+'_act_price" value="'+product.storeprice+'" class="lfx-text" style="width: 100px;" /> \
                  </td> \
          <td> \
          <input type="number" name="'+p_prefix+'p_'+product.product_id+'_credit" value="'+product.credit_percent+'" class="lfx-text" style="width: 100px;" /> \
                  </td> \
                  <td>\
                  <input type="number" name="'+p_prefix+'p_'+product.product_id+'_limitpeople" class="lfx-text" style="width: 100px;" \
          value="'+product.limitpeople+'" /> \
                  </td> \
                  <td> \
                  <input type="number" name="'+p_prefix+'p_'+product.product_id+'_freedays" class="lfx-text" style="width: 100px;" \
          value="'+product.freedays+'" />天 \
                  </td> \
                  <td> \
                  <input id="is_'+p_prefix+'p_'+product.product_id+'_sharenumber" type="checkbox" '+(product.sharenumber>0?"checked":"")+' /> \
                  <span>发展用户超过</span> \
                  <input name="'+p_prefix+'p_'+product.product_id+'_sharenumber" type="number" class="lfx-text" style="width: 40px;" \
          value="'+product.sharenumber+'" /> \
                  <span>个</span>';
        if(type==2){
          html = html +
                  '<br /> \
                  <br /> \
                  <input id="is_'+p_prefix+'p_'+product.product_id+'_wxshare" type="checkbox" '+(product.wxshare>0?"checked":"")+' /> \
                  <span>微信朋友圈体验</span> \
                  <input name="'+p_prefix+'p_'+product.product_id+'_wxshare" type="number" class="lfx-text" style="width: 40px;" value="'+product.wxshare+'" />';
        }
        html = html + '</td> \
              <td><button class="lfx-btn" type="button" onclick="$(this).parentsUntil(\'tr\').parent().remove()">删除</button></td></tr>';
    }
    $('#product-table-'+type+' tbody').append(html);
  }
  function addProductRow(type){
    var product_id = $('#productIdText_'+type).val();
    if(!is_valid_str(product_id)){
      return showErrorText('商品编码为空！');
    }
    if($('#product-table-'+type+' tbody tr.product-item-'+product_id.trim()).length!=0){
      return showErrorText('商品已存在！');
    }
    var url = 'index.php?route=common/api/queryProductById&token=<?php echo $token; ?>';
    var params = {product_id:product_id};
    $.model.commonAjax(url,params, function (data) {
      if(data.success === true){
        var product = data.product;
        if(product.status != 3){
            return showErrorText('产品未上架！');
        }
        renderProductRows([product],type);
      }else{
        return showErrorText(data.errMsg);
      }
    });

  }
  function importProductXls(type){
    var upload_url = 'index.php?route=common/temp/genericuploader&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
        {file_name:'product_list_temp'}
        ,function(data){
          if(data.success===true){
            var file_path = data['file_path'];
            var parse_url = 'index.php?route=common/api/parseProductList&token=<?php echo $token; ?>';
            $.model.commonAjax(parse_url,{file_path:file_path}, function (data) {
              renderProductRows(data,type);
            });
          }else{
            showErrorText(data.error);
          }
        });
  }


  function createAct(status){
    var params = getParams();
    if(params===false){
      return;
    }
    params.act_status = status;
    var url = 'index.php?route=activity/free/create&token=<?php echo $token; ?>';
    $.model.commonAjax(url,params,function(data){
      if(data.success){
        window.location = data.location;
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function saveAct(status){
    var params = getParams('modify');
    if(params===false){
      return;
    }
    params.act_status = status;
    params.act_id = '<?php echo $act["fp_id"]; ?>';
    var url = 'index.php?route=activity/free/modify&token=<?php echo $token; ?>';
    $.model.commonAjax(url,params,function(data){
      if(data.success){
        history.go(-1);
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function getParams(mode){
    mode = mode || 'create';
    var form = $('#free-fm');
    var params = $(form).formJSON();
    console.debug(params);
    var valid_arr = [
      {field:'act_name',required:true,errMsg:'活动名称不能为空！'},
      {field:'act_start_date',required:true,errMsg:'活动开始日期不能为空！'},
      {field:'act_end_date',required:true,errMsg:'活动结束日期不能为空！'},
      {field:'act_memo',required:true,errMsg:'活动说明不能为空！'},
      {field:'imgurl',required:mode=='create',errMsg:'活动图片不能为空！'}
    ];
    if(validFormParams(params,valid_arr)!==true){
      return false;
    }

    //sub images
    if(mode=='create'){
      if($.isArray(params['subimages[]']) && params['subimages[]'].length>0){
      }else if(is_valid_str(params['subimages[]']) ){
        params['subimages[]'] = [params['subimages[]']];
      }else{
        showErrorText('文案图片不为空！');
        return false;
      }
    }else if(mode == 'modify'){
        var sub_image_indexes = [];
        $('.sub-images-box img.sub-image-changed').each(function(){
            var that = this;
            var imgurl = $($(that).siblings('input')[0]).val();
            var index = $(that).parents('.sub-image').data('sub-index');
            sub_image_indexes.push(index);
            params['sub_image_'+index] = imgurl;
        });
        params['sub_image_indexes[]'] = sub_image_indexes;
    }
    // rules configuration
    if($('#is_regduration:checked').length==0){
      delete params.regduration;
    }
    if($('.st_1.lfx-label-on.amountsetting_on').length==1){
      params.amountsetting = 1;
    }else if($('.st_1.lfx-label-on.amountsetting_off').length==1){
      params.amountsetting = 0;
    }
    if($('#is_buyamount:checked').length==0){
      delete params.buyamount;
    }
    if($('.st_1.lfx-label-on.buysetting_on').length==1){
      params.buysetting = 1;
    }else if($('.st_1.lfx-label-on.buysetting_off').length==1){
      params.buysetting = 0;
    }
    if($('#is_buynumber:checked').length==0){
      delete params.buynumber;
    }
    if($('#is_sharenumber:checked').length==0){
      delete params.sharenumber;
    }
    if($('.st_1.lfx-label-on.refoundsharesetting_on').length==1){
      params.refoundsharesetting = 1;
    }else if($('.st_1.lfx-label-on.refoundsharesetting_off').length==1){
      params.refoundsharesetting = 0;
    }
    if($('#is_rfsharenumber:checked').length==0){
      delete params.rfsharenumber;
    }

    //refounds
    if(params['refounds']){
      if($.isArray(params['refounds'])){
      }else{
        params['refounds'] = [params['refounds']];
      }
      for(var i = 0;i<params['refounds'].length;i++){
        var product_id = params['refounds'][i];
        if($('#is_rfp_'+product_id+'_sharenumber:checked').length==0){
          delete params['rfp_'+product_id+'_sharenumber'];
        }
      }
    }
    //norefounds
    if(params['norefounds']){
      if($.isArray(params['norefounds'])){
      }else{
        params['norefounds'] = [params['norefounds']];
      }
      for(var i = 0;i<params['norefounds'].length;i++){
        var product_id = params['norefounds'][i];
        if($('#is_nrfp_'+product_id+'_sharenumber:checked').length==0){
          delete params['nrfp_'+product_id+'_sharenumber'];
        }
        if($('#is_nrfp_'+product_id+'_wxshare:checked').length==0){
          delete params['nrfp_'+product_id+'_wxshare'];
        }
      }
    }
    return params;
  }
  function changeSubImage(image){
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/free/sub';
    var index = $(image).parents('.sub-image').data('sub-index');
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["fp_id"]; ?>_main_temp'}
            ,function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image_path'];
                var html = '<img class="image-button sub-image-changed" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
                data-placeholder="<?php echo $placeholder; ?>" onclick="changeSubImage(this)" /> \
                <input name="subimages[]" value="'+image_path+'" type="hidden" />';
                $(image).parent().empty().append(html);
              }else{
                showErrorText(data.error);
              }
            });
  }
  function appendSubImage(image){
    var index = $('.sub-images-box .sub-image').length + 1;
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/free/sub';
    commonFileUpload(upload_url,
            {file_name:'act_<?php echo $act["fp_id"]; ?>_sub_'+index+'_temp'}
            ,function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image_path'];
                var html =
                '<div class="image-container-sm sub-image" data-sub-index="'+index+'" style="width: 150px;height:150px;"> \
                <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;"> \
                <img class="image-button sub-image-changed" width="100%" height="100%" \
                  src="'+path+'?t='+new Date().getTime()+'" \
                  data-placeholder="<?php echo $placeholder; ?>" \
                  onclick="changeSubImage(this)" /> \
                  <input name="subimages[]" value="'+image_path+'" type="hidden" /> \
                </a> \
                </div>';
                $(html).insertBefore($(image).parents('.add-image'));
                if(index==9){
                  $(image).parents('.add-image').remove();
                }
              }else{
                showErrorText(data.error);
              }
            });
  }



  function changeMainImage(image){
    image = $(image);
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/free/main';
    var old_image = $(image).parent().find('input').val();
    commonFileUpload(upload_url,
      {file_name:'act_<?php echo $act["fp_id"]; ?>_main_temp',resize_width:200,resize_height:200,delete_path:old_image}
      ,function(data){
        if(data.success===true){
          var path = data['file_path'];
          var image_path = data['image_path'];
          var html =
            '<img id="main-image" class="image-button" width="100%" height="100%" \
            src="'+path+'?t='+new Date().getTime()+'" onclick="changeMainImage(this)" /> \
            <input name="imgurl" value="'+image_path+'" type="hidden" />';
          $(image).parent().empty().append(html);
        }else{
          showErrorText(data.error);
        }
      });
  }
  function uploadMainImage(image){
    if($('#main-image-btn').data('hasImage')===true){
      changeMainImage(image);
      return;
    }
    image = $(image);
    var upload_url = 'index.php?route=common/temp/fileuploader&token=<?php echo $token; ?>&directory=temp/activity/free/main';
    commonFileUpload(upload_url,
      {file_name:'act_<?php echo $act["fp_id"]; ?>_main_temp',resize_width:200,resize_height:200}
      ,function(data){
        if(data.success===true){
          var path = data['file_path'];
          var image_path = data['image_path'];
          var html =
            '<img id="main-image" class="image-button" width="100%" height="100%" \
            src="'+path+'?t='+new Date().getTime()+'" onclick="changeMainImage(this)" /> \
              <input name="imgurl" value="'+image_path+'" type="hidden" />';
          $(image).parent().empty().append(html);
          $('#main-image-btn').data('hasImage',true);
        }else{
          showErrorText(data.error);
        }
      });
  }

  $('span.st_1').on('click',function(){
    $(this).parent().find('span.st_1').removeClass('lfx-label-on');
    $(this).addClass('lfx-label-on');
  });

</script>
<style>
  .navi-row > div{
    display: inline-block;
    padding: 10px;
  }
  .navi-row{
    border: 1px solid #e4e4e4;
    min-width: 100%;
  }
  .navi-row .navi-title{
    width: 30px;
    font-size: 16px;
    line-height:30px;
    border-right: 1px solid #e4e4e4;
    text-align: center;
  }

  .navi-row .navi-content .image-container-sm{
    margin-right:5px;width: 150px;height:150px;display: inline-block;
    float: left;
  }
  .navi-row .navi-content.img-content a
  {
    margin-right:5px;width: 200px;height:200px;display: inline-block;
  }
  .navi-row .navi-content{
    vertical-align: top;
    padding: 10px;
  }
  .navi-row .navi-content .entry-group{
    margin-right: 20px;
  }
  .navi-row .navi-content .content-row{
    margin-bottom: 10px;
  }
</style>
<script type="text/javascript">
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
</script>
