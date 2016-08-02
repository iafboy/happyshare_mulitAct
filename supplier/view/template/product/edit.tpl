<link href="view/stylesheet/leshare.css" />
<style>
  label,span{
    font-size: 12px;
  }
  .navi-row .navi-title span{
    font-size: 20px;
  }
</style>
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
    <form id="product-fm">
      <input name="route" value="product/edit/passProduct" type="hidden" />
      <input name="token" value="<?php echo $token; ?>" type="hidden" />
    <div class="container">
      <div class="row">
        <div class="col-sm-12" style="min-width: 100%;overflow: auto;">
          <!-- Base Part -->
          <table style="width: 100%;">
            <tr class="navi-row">
              <td class="navi-title"><span>商品基本信息</span></td>
              <td class="navi-content">
                <div>
                  <!--<form id="product-edit-fm">-->
                    <div class="content-row">
                      <span class="entry-group">
                        <label>商品名称：</label>
                        <span><input value="<?php echo $product['product_name']; ?>" class="lfx-text" name="product_name" /></span>
                      </span>
                      <span  class="entry-group">
                        <label>商品编号：</label>
                        <span><?php echo $product['product_no']; ?></span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>商品类型：</label>
                        <span>
                          <select class="lfx-select" name="product_type">
                            <?php
                              foreach($prducttypes as $type){
                                if($product['product_type_id'] == $type['product_type_id']){
                                  echo '<option selected value="'.$type['product_type_id'].'">'.$type['type_name'].'</option>';
                                }else{
                                  echo '<option value="'.$type['product_type_id'].'">'.$type['type_name'].'</option>';
                                }
                              }
                            ?>
                          </select>
                        </span>
                      </span>
                      <span class="entry-group">
                        <label>商品来源地：</label>
                        <span>
                          <select class="lfx-select" name="origin_place_id">
                            <?php
                              foreach($originPlaces as $place){
                                if($product['origin_place_id'] == $place['origin_place_id']){
                                  echo '<option selected value="'.$place['origin_place_id'].'">'.$place['place_name'].'</option>';
                                }else{
                                  echo '<option value="'.$place['origin_place_id'].'">'.$place['place_name'].'</option>';
                                }

                              }

                            ?>
                          </select>
                        </span>
                      </span>
                      <!--
                      <span class="entry-group">
                        <label>商品发货地：</label>
                        <span>
                          <select class="lfx-select" name="fromwhere_id">
                            <?php
                              foreach($expressPlaces as $place){
                                if($product['fromwhere'] == ($place['fromwhere_id'].'')){
                                  echo '<option selected value="'.$place['fromwhere_id'].'">'.$place['place_name'].'</option>';
                                }else{
                                  echo '<option value="'.$place['fromwhere_id'].'">'.$place['place_name'].'</option>';
                                }
                              }

                            ?>
                          </select>
                        </span>
                      </span>
                      -->
                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>商品库存：</label>
                        <span><input name="quantity" class="lfx-text lfx-text-sm" value="<?php echo parseFormatNum($product['quantity']); ?>"/></span>
                      </span>
                      <span class="entry-group">
                        <label>商品重量：</label>
                        <span><input name="weight" class="lfx-text lfx-text-sm" value="<?php echo parseFormatNum($product['weight'],2); ?>"/>Kg</span>
                      </span>
                      <span class="entry-group">
                        <label>商品体积：</label>
                        <span><input name="volume" class="lfx-text lfx-text-sm" value="<?php echo parseFormatNum($product['volume'],2); ?>"/>m<span>3</span></span>
                      </span>
                      <span class="entry-group">
                        <label>运费模板：</label>
                        <span>
                           <select class="lfx-select lfx-text-sm" name="express_template" >
                             <?php
                              foreach($expressTemplates as $etemplate){
                              if($product['express_template'] == ($etemplate['id'].'')){
                                echo '<option selected value="'.$etemplate['id'].'">'.$etemplate['name'].'</option>';
                             }else{
                                echo '<option value="'.$etemplate['id'].'">'.$etemplate['name'].'</option>';
                              }
                             }
                             ?>
                           </select>
                        </span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span  class="entry-group">
                        <label>市场价：</label>
                        <span><input type="number" name="market_price" value="<?php echo parseFormatNum($product['market_price'],2); ?>" class="lfx-text" style="width: 100px;margin-right: 10px;" />元</span>
                      </span>
                      <span  class="entry-group">
                        <label>返利：</label>
                        <span><input type="number" name="interest_price" value="<?php echo parseFormatNum($product['interest_price'],2); ?>" class="lfx-text" style="width: 100px;margin-right: 10px;" />元</span>
                      </span>
                      <span  class="entry-group">
                        <label>平台价：</label>
                        <span><input type="number" name="store_price" value="<?php echo parseFormatNum($product['storeprice'],2); ?>" class="lfx-text" style="width: 100px;margin-right: 10px;" />元</span>
                      </span>
                      <span  class="entry-group">
                        <label>供货价：</label>
                        <span><input type="number" name="price" value="<?php echo $product['price']; ?>" class="lfx-text" style="width: 100px;margin-right: 10px;" />元</span>
                      </span>
                      <span  class="entry-group">
                        <label>推荐指数：</label>
                        <span><input type="number" name="shareLevel" value="<?php echo parseFormatNum($product['shareLevel']); ?>" class="lfx-text" style="width: 100px;margin-right: 10px;" />(100最大)</span>
                      </span>
                    </div>
                  <?php if ($self_set != 0) { ?>
                    <div class="content-row">
                       <span class="entry-group">
                        <label>回馈积分：</label>
                        <span><input type="number" name="credit" value="<?php echo $product['credit']; ?>" class="lfx-text" style="width: 100px;margin-right: 10px;" />1积分=0.1元</span>
                      </span>
                  <?php }?>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>商品退换规则：</label>
                        <span>
                          <?php if ($product['return_limit'] <= 0) { ?>

                          <input type="radio" name="can_return" value="0" checked onchange="changeCanReturn()" />不允许退货
                          <input type="radio" name="can_return" value="1"  />

                          <input class="lfx-text-xs lfx-text" type="number"  id="return_limit" name="return_limit" value="" />天内允许退货
                          <?php } else { ?>
                          <input type="radio" name="can_return" value="0" onchange="changeCanReturn()"/>不允许退货
                          <input type="radio" name="can_return" value="1" checked  />
                          <input class="lfx-text-xs lfx-text" type="number" id="return_limit" name="return_limit" value="<?php echo $product['return_limit']; ?>" />天内允许退货
                          <?php } ?>
                        </span>
                      </span>
                    </div>
                  <!--</form>-->
                </div>
              </td>
            </tr>
          </table>
          <!-- Picture Part -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>商品文案图片</span></td>
              <td class="navi-content img-content" style="width: 220px">
                  <div class="content-row">
                      <div class="entry-group" style="margin-right: 0px;">
                        <div  style="text-align: center;margin-bottom: 10px;">详情图片</div>
                        <div style="width: 200px;height:200px;position:relative;">
                          <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                            <?php
                             if(is_valid($product['image'])){ ?>
                             <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['image']; ?>"
                            data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />
                             <?php }else{ ?>
                            <img class="image-button" width="100%" height="100%" src="<?php echo $to_add_img; ?>"
                                 data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />
                             <?php }
                             ?>
                          </a>
                          <a onclick="javascript:void(0)" class="del-btn off"></a>
                        </div>
                      </div>
                  </div>
              </td>
              <td class="navi-content img-content" style="border-left: 1px solid #e4e4e4;">
                  <div class="content-row">
                      <div class="entry-group" style="margin-right: 0px;">
                        <div style="text-align: center;margin-bottom: 10px;position: relative;">
                          <!--<span class="fa fa-arrow-left pic-mavi" style="left:10px;"></span>-->
                          <span>文案图片</span>
                          <!--<span class="fa fa-arrow-right pic-mavi" style="right:10px;"></span>-->
                        </div>
                        <div class="sub-images-box">
                          <?php
                           if(isset($product['sub_images'])){
                            $i = 0;
                            foreach($product['sub_images'] as $sub_image){
                            $i++;?>
                              <div class="image-container-sm sub-image" data-sub-index="<?php echo $sub_image['sort_order']; ?>"
                                   style="width: 150px;">
                                <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                                  <img class="image-button" width="100%" height="100%"
                                       src="<?php echo DIR_IMAGE_URL.$sub_image['image']; ?>"
                                       data-placeholder="<?php echo $placeholder; ?>"
                                      onclick="changeSubImage(this)" />
                                </a>
                                <a onclick="doDelSubImage(this)" class="del-btn fa fa-fw fa-trash"></a>
                              </div>
                          <?php } ?>

                              <div class="image-container-sm add-image image-button" style="width: 150px;text-align: center;">
                                <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
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
                  </div>
              </td>
            </tr>
            <tr class="navi-row">
              <td class="navi-title"></td>
              <td class="navi-content img-content" style="width: 220px">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <div  style="text-align: center;margin-bottom: 10px;">热销产品推荐标题图片</div>
                    <div style="width: 200px;height:200px;position:relative;">
                      <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                        <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['img_1']; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage1(this)" />
                      </a>
                      <a onclick="javascript:void(0)" class="del-btn off"></a>
                    </div>
                  </div>
                </div>
              </td>
              <td class="navi-content img-content" style="border-left: 1px solid #e4e4e4;">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <div  style="text-align: center;margin-bottom: 10px;">热门分享标题图片</div>
                    <div style="width: 200px;height:200px;position:relative;">
                      <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                        <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['img_2']; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage2(this)" />
                      </a>
                      <a onclick="javascript:void(0)" class="del-btn off"></a>
                    </div>
                  </div>
                </div>

                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <div  style="text-align: center;margin-bottom: 10px;">产品列表标题图片</div>
                    <div style="width: 200px;height:200px;position:relative;">
                      <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                        <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['img_3']; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage3(this)" />
                      </a>
                      <a onclick="javascript:void(0)" class="del-btn off"></a>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </table>
          <!-- Share Part -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td class="navi-title"><span>分享文案</span></td>
              <td class="navi-content ">
                <table style="width: 100%;" class="product-sharecase-box">
                  <?php if(isset($product['share_cases']) && sizeof($product['share_cases']) > 0){
                     $j = 0;
                     foreach($product['share_cases'] as $share_case){
                     $j ++;
                      if($j > 1){ ?>
                        <tr><td style="height: 20px;"></td></tr>
                      <?php } ?>
                          <tr class="case-row" <?php echo ($j>1)?'style="border-top:1px solid #e4e4e4;"':''; ?> data-sharecase-seq="<?php echo $share_case['seq']; ?>">
                            <td style="width: 200px;vertical-align: top;">
                              <div>
                                <label>标题：</label>
                                <span>
                                  <input name="case_<?php echo $j; ?>_title" value="<?php echo $share_case['title']; ?>" style="width: 100%" class="lfx-text" />
                                </span>
                              </div>
                              <div>
                                <label>分享文字：</label>
                                <br />
                                <span>
                                  <textarea name="case_<?php echo $j; ?>_memo"  style="resize:none;width: 100%" class="lfx-text" ><?php echo $share_case['memo']; ?></textarea>
                                </span>
                              </div>
                              <div>
                                <label>分享文案状态：</label>
                                <span>
                                  <select class="lfx-select" name="case_<?php echo $j; ?>_audit">
                                    <?php
                                     if($share_case['audit'].''==='0'){ ?>
                                    <option value="0" selected>暂不发布</option>
                                    <option value="1">发布</option>
                                    <?php }else if($share_case['audit'].''==='1'){ ?>
                                    <option value="0">暂不发布</option>
                                    <option value="1" selected>发布</option>
                                    <?php }else{ ?>

                                    <option value="0">暂不发布</option>
                                    <option value="1">发布</option>
                                    <?php }
                                    ?>
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td style="padding: 5px;">
                              <div class="sc-image-box-outer">
                                <a class="del-btn fa fa-fw fa-trash" onclick="doDealShareCase(this,'<?php echo $share_case['seq']; ?>');"></a>
                                <div style="text-align: center">
                                  <span>分享图片</span>
                                </div>
                                <div class="sharecase-image-box">
                                  <?php
                                  $tmp = 1;
                                  for($x = 1; $x < 10; $x ++){
                                    if(isset($share_case['imgurl'.$x]) && is_valid($share_case['imgurl'.$x])){ $tmp ++; ?>
                                      <div class="image-container-sm sharecase-image" data-case-img-index="<?php echo $x; ?>">
                                        <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;">
                                          <img width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$share_case['imgurl'.$x]; ?>"
                                               data-placeholder="<?php echo $placeholder; ?>"
                                               onclick="replaceShareCaseImage(this,<?php echo $j; ?>,<?php echo $x; ?>);" />
                                        </a>
                                        <a onclick="doDelSharecaseImage(this)" class="del-btn on fa fa-fw fa-trash"></a>
                                      </div>
                                    <?php }else{ ?>

                                    <?php //break;
                                    }
                                  } ?>
                                  <?php if($tmp < 10){ ?>
                                  <div class="image-container-sm add-case-row">
                                    <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;">
                                      <img width="100%" height="100%" src="<?php echo $to_add_img; ?>?t='+new Date().getTime()+'"
                                           data-placeholder="<?php echo $placeholder; ?>"
                                           onclick="appendShareCaseImage(this,<?php echo $j; ?>)" />
                                    </a>
                                  </div>
                                  <?php }?>
                                </div>
                              </div>
                            </td>
                          </tr>
                     <?php }
                     }else{ ?>
                        <tr class="case-row" data-sharecase-seq="1">
                          <td style="width: 200px;vertical-align: top;">
                            <div>
                              <label>标题：</label>
                              <span>
                              <input name="case_1_title" style="width: 100%" class="lfx-text" />
                              </span>
                            </div>
                            <div>
                              <label>分享文字：</label>
                              <br />
                              <span>
                              <textarea name="case_1_memo" style="resize:none;width: 100%" class="lfx-text" ></textarea>
                              </span>
                            </div>
                            <div>
                              <label>分享文案状态：</label>
                            <span>
                            <select class="lfx-select" name="case_1_audit">
                              <option value="0">暂不发布</option>
                              <option value="1">发布</option>
                            </select>
                                    </span>
                            </div>
                          </td>
                          <td style="padding: 5px;">
                            <div class="sc-image-box-outer">
                              <div style="text-align: center">
                                <span>分享图片</span>
                              </div>
                              <div class="sharecase-image-box">
                                <div class="image-container-sm add-case-row">
                                  <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;">
                                    <img width="100%" height="100%" src="<?php echo $to_add_img; ?>?t='+new Date().getTime()+'"
                                         data-placeholder="<?php echo $placeholder; ?>" onclick="appendShareCaseImage(this,1)" />
                                  </a>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                  <?php } ?>
                  <tr class="operation-row" style="border-top: 1px solid #e4e4e4;">
                    <td colspan="2">
                      <div style="text-align: center;margin-top: 10px;">
                        <button type="button" class="btn lfx-btn" onclick="appendShareCase()">新增文案</button>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row" style="text-align: center;margin-top: 20px;">
        <button type="button" class="btn lfx-btn" onclick="saveProduct(0)">提交审批</button>
        <button type="button" class="btn lfx-btn" onclick="saveProduct(5)">出库</button>
        <button type="button" class="btn lfx-btn" onclick="returnToList()">返回</button>
      </div>
    </div>
    <div style="display:none" id="sb-div"></div>
    </form>
  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">

  function returnToList(){
    history.go(-1);
  }
  function changeCanReturn(){
    $("input[name='can_return'][value=0]").attr("checked",true);
    $("#return_limit")[0].value='';

  }
  function saveProduct(status){
    var url = 'index.php?route=product/edit/modProduct&token=<?php echo $token; ?>';
    var params =getParams();
    if(!params){
      return;
    }
    params.status = status ;
    var page = GetQueryString("page");
    if(!is_valid_str(page)){
      page = 1;
    }
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location + "&page=" + page;
      }else{
        return showErrorText(data.errMsg);
      }
    });
  }
  function unpassProduct(){
    var url = 'index.php?route=product/edit/unpassProduct&token=<?php echo $token; ?>';
    var params = { };
    params.product_id = '<?php echo $product["product_id"]; ?>';
    var page = GetQueryString("page");
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location + "&page=" + page;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }
  function passProduct(status){
    var url = 'index.php?route=product/edit/passProduct&token=<?php echo $token; ?>';
    var params = getParams();
    if(!params){
      return;
    }
    params.status = status;
    var page = GetQueryString("page");
    $.model.commonAjax(url,params, function (data) {
      if(data.success){
        window.location = data.location + "&page=" + page;
      }else{
        showErrorText(data.errMsg);
      }
    });
  }

  function getParams(){
    var form = '#product-fm';
    var params = $(form).formJSON();
    params.product_id = '<?php echo $product["product_id"] ?>';

    //console.log(params);
    var product_id = '<?php echo $product["product_id"]; ?>';
    if(!is_valid_str(product_id)){
      params.mode = 'add';
    }else{
      params.product_id = product_id;
      params.mode = 'mod';
    }
    var product_no = '<?php echo $product['product_no']; ?>' ;
    if(!is_valid_str(product_no)){
      showErrorText('产品编号错误');
      return false;
    }else{
      params.product_no = product_no;
    }
    if(!is_valid_str(params.product_name)){
      showErrorText('商品名称必填！');
      return false;
    }
    if(!is_valid_str(params.express_template)){
      showErrorText('未设置物流模板！');
      return false;
    }
    if(!is_valid_str(params.quantity)|| getNumberOpacity(params.quantity) > 0 || params.quantity < 0){
      showErrorText('库存必须是大于0的整数！');
      return false;
    }
    if(!is_valid_str(params.weight)|| getNumberOpacity(params.weight) > 2 || params.weight < 0){
      showErrorText('重量必须大于0，精度最大为2！');
      return false;
    }
    if(!is_valid_str(params.volume)|| getNumberOpacity(params.volume) > 2 || params.volume < 0){
      showErrorText('体积必须大于0，精度最大为2！');
      return false;
    }
    if(!is_valid_str(params.store_price) || getNumberOpacity(params.store_price)>2 || params.store_price < 0){
      showErrorText('平台价必须大于0，精度最大为2！');
      return false;
    }
    if(!is_valid_str(params.interest_price) || getNumberOpacity(params.interest_price)>2 || params.interest_price < 0){
      showErrorText('返利必须大于0，精度最大为2！');
      return false;
    }
    if(parseFloat(params.interest_price) >  parseFloat(params.store_price)){
      showErrorText('平台价必须大于返利！');
      return false;
    }
    if(!is_valid_str(params.shareLevel) || getNumberOpacity(params.shareLevel)>0 || params.shareLevel < 0 || params.shareLevel >100){
      showErrorText('推荐指数必须大于0,且为整数，最大值为100！');
      return false;
    }
    if(params.can_return == 1){
      if(!is_valid_str(params.return_limit) || getNumberOpacity(params.return_limit)>0 || params.return_limit < 0 ){
        showErrorText('退货期限必须大于0,且为整数！');
        return false;
      }

    }else{
      delete params.return_limit;
    }

    /*if(!is_valid_str(params.credit_percent)){
      params.credit_percent = parseInt($('.label-me-box .label.label-success').text());
    }
    if(!is_valid_str(params.credit_percent)){
      params.credit_percent = 0;
    }
    if(parseInt(params.credit_percent) < 0 || parseInt(params.credit_percent) > 100){
      showErrorText('积分反馈率无效！');
      return false;
    }*/
    if($('.label-me-box .label.label-success').length == 0){
//      showErrorText('积分反馈率无效！');
//      return false;
    }else{
      params.credit_percent = parseInt($('.label-me-box .label.label-success').text());
    }
//    if(!is_valid_str(params.credit_percent) || parseInt(params.credit_percent) <= 0 || parseInt(params.credit_percent) > 100){
//      showErrorText('积分反馈率无效！');
//      return false;
//    }

    var $cases = $('.product-sharecase-box .case-row');
    var cases_str = '';
    $cases.each(function () {
      cases_str = cases_str + $(this).data('sharecase-seq')+',';
    });
    if(!is_valid_str(cases_str)){
    }else{
      cases_str = cases_str.substr(0,cases_str.length-1);
      params.cases = cases_str;
    }
      var case_ok = true;
      $cases.each(function(i){
        var index = $(this).data('sharecase-seq');
        //if($(this).find('.sharecase-image-box .sharecase-image').length > 0){
          if($(this).find('.sharecase-image-box .sharecase-image').length == 0){
            case_ok = false;
          }
          var prefix = 'case_'+index+'_';
          if(!is_valid_str($(this).find('input[name="'+prefix+'title"]').val())){
            case_ok = false;
          }
          if(!is_valid_str($(this).find('textarea[name="'+prefix+'memo"]').val())){
            case_ok = false;
          }
        //}
      });

      if(case_ok===false){
        showErrorText('文案参数缺失');
        return false;
      }
    return params;
  }
  function doDelSharecaseImage(obj){
    var image = $(obj).parent().find('a > img');
    var img_index = $(image).parents('.sharecase-image').data('case-img-index');
    var case_index = $(image).parents('.case-row').data('sharecase-seq');
    var params = { product_id:'<?php echo $product['product_id']; ?>', case_index:case_index,image_index:img_index};
    var win =  confirmWideHtmlWin('提示','确认删除么？', function (data) {
      var url = 'index.php?route=product/edit/deleteSharecaseImage&token=<?php echo $token; ?>';
      $.model.commonAjax(url,params, function (data) {
        if(data.success === true){
          $(image).parents('.sharecase-image').remove();
          win.close();
        }else{
          return showErrorText(data.errMsg);
        }
      });
    });
  }

  function appendShareCase(){
    var case_index = $('.product-sharecase-box tr.case-row').last().data('sharecase-seq') + 1;
    var html = '<tr><td style="height: 20px;"></td></tr> \
            <tr class="case-row" style="border-top:1px solid #e4e4e4;" data-sharecase-seq="'+case_index+'" > \
            <td style="width: 200px;vertical-align: top;"> \
            <div> \
            <label>标题：</label> \
            <span> \
            <input name="case_'+case_index+'_title" style="width: 100%" class="lfx-text" /> \
            </span> \
            </div> \
            <div> \
            <label>分享文字：</label> \
            <br /> \
            <span> \
            <textarea name="case_'+case_index+'_memo"  style="resize:none;width: 100%" class="lfx-text" ></textarea> \
            </span> \
            </div> \
            <div> \
            <label>分享文案状态：</label> \
            <span> \
            <select name="case_'+case_index+'_audit" class="lfx-select"> \
            <option value="0">暂不发布</option> \
            <option value="1">发布</option> \
            </select> \
            </span> \
            </div> \
            </td> \
            <td style="padding: 5px;"> \
            <div class="sc-image-box-outer"> \
            <a class="del-btn fa fa-fw fa-trash" onclick="doDealShareCase(this,'+case_index+');"></a> \
            <div style="text-align: center"> \
            <span>分享图片</span> \
            </div> \
            <div class="sharecase-image-box"> \
            <div class="image-container-sm add-case-row"> \
            <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;"> \
            <img width="100%" height="100%" src="<?php echo $to_add_img; ?>?t='+new Date().getTime()+'" \
            data-placeholder="<?php echo $placeholder; ?>" onclick="appendShareCaseImage(this,'+case_index+')" /> \
            </a> \
            </div> \
            </div> \
            </div> \
            </td> \
            </tr>';
    $(html).insertBefore('.product-sharecase-box .operation-row');
  }

  function replaceShareCaseImage(image,case_index,img_index){
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
      {type:3,product_id:'<?php echo $product["product_id"]; ?>',image_index:img_index,case_index:case_index},
      function(data){
      if(data.success===true){
        var path = data['file_path'];
        var html =
        '<img width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
        data-placeholder="<?php echo $placeholder; ?>" \
        onclick="replaceShareCaseImage(this,'+case_index+','+img_index+');" />';
        $(image).parent().empty().append(html);
      }else{
        showErrorText(data.error);
      }
    });
  }
  function appendShareCaseImage(image,case_index){
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    var img_index = 1;
    if($(image).parents('.sharecase-image-box').find('.sharecase-image').length > 0){
      img_index = $(image).parents('.sharecase-image-box').find('.sharecase-image').last().data('case-img-index') + 1;
    }
    if(img_index>=10){
      return;
    }
    commonFileUpload(upload_url,
      {type:3,product_id:'<?php echo $product["product_id"]; ?>',image_index:img_index,case_index:case_index}
      ,function(data){
      if(data.success===true){
        var path = data['file_path'];
        var image_path = data['image_path'];
        var html =
        '<div class="image-container-sm sharecase-image"> \
        <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;"> \
        <img width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
        data-placeholder="<?php echo $placeholder; ?>" onclick="replaceShareCaseImage(this,'+case_index+','+img_index+');" />\
        </a> \
        <a onclick="doDelSharecaseImage(this)" class="del-btn on fa fa-fw fa-trash"></a> \
        </div>';
        $(html).insertBefore($(image).parents('.add-case-row'));
        if(img_index>=9){
          $(image).parents('.add-case-row').remove();
        }
      }else{
        showErrorText(data.error);
      }
    });
  }
  function doDelSubImage(obj){
    var image = $(obj).parent().find('a > img');
    var index = $(image).parents('.sub-image').data('sub-index');
    var win =  confirmWideHtmlWin('提示','确认删除么？', function (data) {
      var url = 'index.php?route=product/edit/deleteSubImage&token=<?php echo $token; ?>';
      $.model.commonAjax(url,{sort_order:index,product_id:'<?php echo $product["product_id"]; ?>'}, function (data) {
        if(data.success === true){
          $(image).parents('.sub-image').remove();
          win.close();
        }else{
          return showErrorText(data.errMsg);
        }
      });
    });
  }
  function doDealShareCase(obj,seq){
    var $row = $(obj).parents('.case-row');
    var win =  confirmWideHtmlWin('提示','确认删除么？', function (data) {
      var url = 'index.php?route=product/edit/deleteShareCase&token=<?php echo $token; ?>';
      $.model.commonAjax(url,{seq:seq,product_id:'<?php echo $product['product_id']; ?>'}, function (data) {
        if(data.success === true){
          $row.remove();
          win.close();
        }else{
          return showErrorText(data.errMsg);
        }
      });
    });
  }

  function uploadMainImage(image){
      //$(image).removeAttr('onclick');
    var html = '';
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
      {type:1,product_id:'<?php echo $product["product_id"]; ?>'},
      function(data){
      if(data.success===true){
          var path = data['file_path'];
          var image_path = data['image_path'];
          html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
        data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage(this)" />'
                  +'<input type="hidden" name="main_image" value="'+image_path+'" />';
        $parent = $(image).parent();
        $parent.empty();
        $parent.append(html);
      }else{
        showErrorText(data.error);
      }
      //$(image).removeAttr('disabled');
    });
  }
  function uploadMainImage1(image){
    //$(image).removeAttr('onclick');
    var html = '';
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
            {type:4,product_id:'<?php echo $product["product_id"]; ?>'},
            function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image'];
                html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
                  data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage1(this)" />'
                        +'<input type="hidden" name="main_image1" value="'+image_path+'" />';
                $parent = $(image).parent();
                $parent.empty();
                $parent.append(html);
              }else{
                showErrorText(data.error);
              }
              //$(image).removeAttr('disabled');
            });
  }
  function uploadMainImage2(image){
    //$(image).removeAttr('onclick');
    var html = '';
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
            {type:5,product_id:'<?php echo $product["product_id"]; ?>'},
            function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image'];
                html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
                  data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage2(this)" />'
                        +'<input type="hidden" name="main_image2" value="'+image_path+'" />';
                $parent = $(image).parent();
                $parent.empty();
                $parent.append(html);
              }else{
                showErrorText(data.error);
              }
              //$(image).removeAttr('disabled');
            });
  }
  function uploadMainImage3(image){
    //$(image).removeAttr('onclick');
    var html = '';
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
            {type:6,product_id:'<?php echo $product["product_id"]; ?>'},
            function(data){
              if(data.success===true){
                var path = data['file_path'];
                var image_path = data['image'];
                html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
                  data-placeholder="<?php echo $placeholder; ?>" onclick="uploadMainImage3(this)" />'
                        +'<input type="hidden" name="main_image3" value="'+image_path+'" />';
                $parent = $(image).parent();
                $parent.empty();
                $parent.append(html);
              }else{
                showErrorText(data.error);
              }
              //$(image).removeAttr('disabled');
            });
  }
  function changeSubImage(image){
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    var index = $(image).parents('.sub-image').data('sub-index');
    commonFileUpload(upload_url,
      {product_id:'<?php echo $product["product_id"]; ?>',sub_index:index,type:2}
      ,function(data){
        if(data.success===true){
          var path = data['file_path'];
          var image_path = data['image_path'];
          var html = '<img class="image-button" width="100%" height="100%" src="'+path+'?t='+new Date().getTime()+'" \
          data-placeholder="<?php echo $placeholder; ?>" onclick="changeSubImage(this)" />';
          $(image).parent().empty().append(html);
        }else{
          showErrorText(data.error);
        }
    });
  };
  function appendSubImage(image){
    var index = 1;
    if($('.sub-images-box .sub-image').length == 0){
      index = 1
    }else{
      index = $('.sub-images-box .sub-image').last().data('sub-index') + 1;
    }
    var upload_url = 'index.php?route=product/edit/uploadImage&token=<?php echo $token; ?>';
    commonFileUpload(upload_url,
            {product_id:'<?php echo $product["product_id"]; ?>',sub_index:index,type:2}
        ,function(data){
      if(data.success===true){
        var path = data['file_path'];
        var image_path = data['image'];
        var html =
//                $('.sub-images-box .sub-image')
        '<div class="image-container-sm sub-image" data-sub-index="'+index+'" style="width: 150px;"> \
        <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;"> \
        <img class="image-button" width="100%" height="100%" \
          src="'+path+'?t='+new Date().getTime()+'" \
          data-placeholder="<?php echo $placeholder; ?>" \
          onclick="changeSubImage(this)" />\
        </a> \
        <a onclick="doDelSubImage(this)" class="del-btn fa fa-fw fa-trash"></a> \
        </div>';
        $(html).insertBefore($(image).parents('.add-image'));
//        if(index==9){
//          $(image).parents('.add-image').remove();
//        }
      }else{
        showErrorText(data.error);
      }
    });
  }





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
  $(function(){
    $('.label.label-me').on('click', function (e) {
      if($(this).hasClass('label-success')){
        $(this).removeClass('label-success');
      }else{
        $('.label-me-box > .label-me').removeClass('label-success');
        $(this).addClass('label-success');
      }
    });

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
    width: 40px;
    font-size: 20px;
    line-height:30px;
    border-right: 1px solid #e4e4e4;
    text-align: center;
  }
  
  .navi-row .navi-content .image-container-sm{
    margin-right: 5px;
    width: 150px;
    display: inline-block;
    margin-bottom: 10px;
    margin-bottom: 10px;
    position: relative;
  }
  .navi-row .navi-content.img-content .image-container{
    /*background-color: grey;*/
    margin-right:5px;width: 200px;height:200px;display: inline-block;
    float: left;
  }
  .navi-row .navi-content.img-content .pic-mavi:hover{
    color: #00b3ee;
  }
  .navi-row .navi-content.img-content .pic-mavi{
    position: absolute;top:10px;
    cursor: pointer;
  }
  .navi-row .navi-content.img-content a
  {
    /*background-color: grey;*/
    margin-right:5px;width: 200px;height:200px;display: inline-block;
  }
  .case-row .sc-image-box-outer{
    position: relative;
  }
  .case-row .sc-image-box-outer a.del-btn
  {
    float: right;
    display: inline-block;
    width: 30px;
    height: 30px;
    position: absolute;
    top: 0px;
    right: -10px;
    font-size: 30px;
    color: #aaa;
  }
  .case-row .sc-image-box-outer a.del-btn:hover
  {
    cursor: pointer;
    color: #999;
  }
  .navi-row .navi-content.img-content a.del-btn
  {
    float: right;
    display: inline-block;
    width: 30px;
    height: 30px;
    position: absolute;
    top: 0px;
    right: -10px;
    font-size: 30px;
    color: #aaa;
  }
  .navi-row .navi-content.img-content a.del-btn:hover
  {
    cursor: pointer;
    color: #999;
  }
  .navi-row .navi-content.img-content a.del-btn.off
  {
    display:none;
  }
  .navi-row .navi-content{
    vertical-align: top;
    padding: 10px;
  }
  .navi-row .navi-content .entry-group{
    margin-right: 20px;
  }
  .navi-row .navi-content .content-row .label-me.label-success{
    cursor: inherit;
  }
  .navi-row .navi-content .content-row .label-me{
    font-weight: normal;
    cursor:pointer;
  }
  .navi-row .navi-content .content-row{
    margin-bottom: 10px;
  }
</style>