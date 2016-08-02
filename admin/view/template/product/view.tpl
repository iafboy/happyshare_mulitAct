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
                        <span><?php echo $product['product_name']; ?></span>
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
                            <?php
                              foreach($prducttypes as $type){
                                if($product['product_type_id'] == $type['product_type_id']){
                                  echo ''.$type['type_name'].'';
                                  break;
                                }
                              }
                            ?>
                        </span>
                      </span>
                      <span class="entry-group">
                        <label>商品来源地：</label>
                        <span>
                            <?php
                              foreach($originPlaces as $place){
                                if($product['origin_place_id'] == $place['origin_place_id']){
                                  echo ''.$place['place_name'].'';
                                        break;
                                }

                              }

                            ?>
                        </span>
                      </span>

                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>商品库存：</label>
                        <span><?php echo parseFormatNum($product['quantity']); ?></span>
                      </span>
                      <span class="entry-group">
                        <label>商品重量：</label>
                        <span><?php echo parseFormatNum($product['weight'],2); ?></span>
                      </span>
                      <span class="entry-group">
                        <label>商品体积：</label>
                        <span><?php echo parseFormatNum($product['volume'],2); ?>m<span>3</span></span>
                      </span>
                      <span class="entry-group">
                        <label>运费模板：</label>
                        <span>

                             <?php
                              foreach($expressTemplates as $etemplate){
                                if($product['express_template'] == ($etemplate['id'].'')){
                                  echo ''.$etemplate['name'].'';
                                  break;
                                }
                             }
                           ?>
                        </span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span  class="entry-group">
                        <label>市场价：</label>
                        <span><?php echo parseFormatNum($product['market_price'],2); ?>元</span>
                      </span>
                      <span  class="entry-group">
                        <label>返利：</label>
                        <span><?php echo parseFormatNum($product['interest_price'],2); ?>元</span>
                      </span>
                      <span  class="entry-group">
                        <label>平台价：</label>
                        <span><?php echo parseFormatNum($product['storeprice'],2); ?>元</span>
                      </span>
                      <span  class="entry-group">
                        <label>供货价：</label>
                        <span><?php echo $product['price']; ?>元</span>
                      </span>
                      <span  class="entry-group">
                        <label>推荐指数：</label>
                        <span><?php echo parseFormatNum($product['shareLevel']); ?>(100最大)</span>
                      </span>
                    </div>
                  <div class="content-row">
                      <span class="entry-group">
                        <label>回馈积分：</label>
                        <span><?php echo $product['credit']; ?>1积分=0.1元</span>
                      </span>
                  </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>商品退换规则：</label>
                        <span>
                          <?php if ($product['return_limit'] <= 0) { ?>
                          不允许退货
                          <?php } else { ?>
                            <?php echo $product['return_limit'].'天内允许退货'?>
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
                        <div  style="text-align: center;margin-bottom: 10px;">标题图片</div>
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
            <tr>
              <td class="navi-title"></td>
              <td class="navi-content img-content" style="width: 220px">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <div  style="text-align: center;margin-bottom: 10px;">热销产品推荐标题图片</div>
                    <div style="width: 200px;height:200px;position:relative;">
                      <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                        <?php
                             if(is_valid($product['img_1'])){ ?>
                        <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['img_1']; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="" />
                        <?php }else{ ?>
                        <img class="image-button" width="100%" height="100%" src="<?php echo $to_add_img; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="" />
                        <?php }
                             ?>
                      </a>
                    </div>
                  </div>
                </div>
              </td>
              <td class="navi-content img-content" style="width: 220px">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <div  style="text-align: center;margin-bottom: 10px;">热门分享标题图片</div>
                    <div style="width: 200px;height:200px;position:relative;">
                      <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                        <?php
                             if(is_valid($product['img_2'])){ ?>
                        <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['img_2']; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="" />
                        <?php }else{ ?>
                        <img class="image-button" width="100%" height="100%" src="<?php echo $to_add_img; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="" />
                        <?php }
                             ?>
                      </a>
                    </div>
                  </div>
                </div>
              </td>
              <td class="navi-content img-content" style="width: 220px">
                <div class="content-row">
                  <div class="entry-group" style="margin-right: 0px;">
                    <div  style="text-align: center;margin-bottom: 10px;">产品列表标题图片</div>
                    <div style="width: 200px;height:200px;position:relative;">
                      <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;">
                        <?php
                             if(is_valid($product['img_3'])){ ?>
                        <img class="image-button" width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$product['img_3']; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="" />
                        <?php }else{ ?>
                        <img class="image-button" width="100%" height="100%" src="<?php echo $to_add_img; ?>"
                             data-placeholder="<?php echo $placeholder; ?>" onclick="" />
                        <?php }
                             ?>
                      </a>
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

                                    <?php
                                     if($share_case['audit'].''==='0'){ ?>
                                    暂不发布

                                    <?php }else { ?>
                                    发布
                                    <?php }
                                    ?>
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
                                  <?php for($x = 1; $x < 2; $x ++){
                                    if(isset($share_case['imgurl'.$x]) && is_valid($share_case['imgurl'.$x])){ ?>
                                      <div class="image-container-sm sharecase-image">
                                        <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;">
                                          <img width="100%" height="100%" src="<?php echo DIR_IMAGE_URL.$share_case['imgurl'.$x]; ?>?t='+new Date().getTime()+'"
                                               data-placeholder="<?php echo $placeholder; ?>"
                                               onclick="replaceShareCaseImage(this,<?php echo $j; ?>,<?php echo $x; ?>);" />
                                        </a>

                                      </div>
                                    <?php }else{ ?>
                                      <div class="image-container-sm add-case-row">
                                        <a onclick="return false;" class="img-thumbnail" style="padding: 0px;cursor: pointer;width:150px;height:150px;">
                                          <img width="100%" height="100%" src="<?php echo $to_add_img; ?>?t='+new Date().getTime()+'"
                                               data-placeholder="<?php echo $placeholder; ?>"
                                               onclick="appendShareCaseImage(this,<?php echo $j; ?>)" />
                                        </a>
                                      </div>
                                    <?php break;
                                    }
                                  } ?>
                                </div>
                              </div>
                            </td>
                          </tr>
                     <?php }
                     }else{ ?>
                  <?php } ?>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row" style="text-align: center;margin-top: 20px;">
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

  function saveProduct(status){
  }
  function unpassProduct(){
  }
  function passProduct(status){
  }

  function getParams(){
  }


  function appendShareCase(){
  }

  function replaceShareCaseImage(image,case_index,img_index){
  }
  function appendShareCaseImage(image,case_index){
  }
  function doDelSubImage(obj){
  }
  function doDealShareCase(obj,seq){
  }

  function uploadMainImage(image){
  }
  function changeSubImage(image){
  }
  function appendSubImage(image){
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