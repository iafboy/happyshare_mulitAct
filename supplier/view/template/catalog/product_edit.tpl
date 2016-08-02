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
    <!--
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $breadcrumbs[1]['text']; ?></h3>
      </div>
      <div class="panel-body">
          <div class="row">
            <div class="col-sm-12" style="min-width: 100%;overflow: auto;background-color: #f5f5f5;">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" >
            
              <!-- 1. Basic Info Part -->
              <table style="width: 100%;margin-top: 20px;">
                <tr class="navi-row">
                  <td class="navi-title"><span>商品基本信息</span></td>
                  <td class="navi-content">
                    <table style="width: 100%;">
                      <tr>
                        <td style="vertical-align: top;" class="col-sm-3">
                          <div class="form-group">
                            <label class="control-label" for="input-name"><?php echo $entry_name."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $name ;?>" name="input_name" placeholder="<?php echo ""; ?>" id="input-name" class="form-control" />
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-model"><?php echo $entry_model."&nbsp;:&nbsp;"; ?></label>
                            <select name="input_model" id="input-model" class="form-control">
                              <option value=""></option>
                              <?php foreach ($models as $model) { ?>
                              <?php if ($model['product_type_id'] == $category_id ) { ?>
                              <option value="<?php echo $model['product_type_id']; ?>" selected="selected"><?php echo $model['type_name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $model['product_type_id']; ?>" ><?php echo $model['type_name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-product-stock"><?php echo $entry_product_stock."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_stock ;?>" name="input_product_stock" placeholder="<?php echo "件"; ?>" id="input-product-stock" class="form-control placeholder_leshare" />
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-product-price-market"><?php echo $entry_product_price_market."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_price_market ;?>" name="input_product_price_market" placeholder="<?php echo "元人民币"; ?>" id="input-product-price-market" class="form-control placeholder_leshare" />
                          </div>
                        </td>
                        <td style="vertical-align: top;" class="col-sm-3">
                          <div class="form-group">
                            <label class="control-label" for="input-product-code"><?php echo $entry_product_code."(自动生成)&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_code ;?>" name="input_product_code" readonly="readonly" id="input-product-code" class="form-control" />
                          </div>
                          <div class="form-group">
                          <!-- 商品来源地 origin_places -->
                            <label class="control-label" for="input-product-place"><?php echo $entry_product_place."&nbsp;:&nbsp;"; ?></label>
                            <select name="input_product_place_shipment" id="input-product-place-shipment" class="form-control">
                              <option value=""></option>
                              <?php foreach ($origin_places as $place) { ?>
                              <?php if ($place['origin_place_id'] == $origin_place_id) { ?>
                              <option value="<?php echo $place['origin_place_id']; ?>" selected="selected"><?php echo $place['place_name']; ?></option>
                              <?php } else {?>
                              <option value="<?php echo $place['origin_place_id']; ?>"><?php echo $place['place_name']; ?></option>
                              <?php }?>
                              <?php }?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-weight"><?php echo $entry_product_weight."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_weight ;?>" name="input_weight" placeholder="<?php echo "Kg"; ?>" id="input-weight" class="form-control placeholder_leshare" />
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-product-price-store"><?php echo $entry_product_price_store."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_price_store ;?>" name="input_product_price_store" placeholder="<?php echo "元人民币"; ?>" id="input-product-price-store" class="form-control placeholder_leshare" />
                          </div>
                        </td>



                        <td style="vertical-align: top;" class="col-sm-3">
                          <div class="form-group">
                            <label class="control-label" ><?php echo "&nbsp;&nbsp;"; ?></label>
                            <div class="form-control-new2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-product-place-shipment"><?php echo $entry_product_place_shipment."&nbsp;:&nbsp;"; ?></label>
                            <select name="input_product_place" id="input-product-place" class="form-control">
                              <option value=""></option>
                              <?php foreach ($fromwhere_places as $place) { ?>
                              <?php if ($place['fromwhere_id'] == $fromwhere) { ?>
                              <option value="<?php echo $place['fromwhere_id']; ?>" selected="selected"><?php echo $place['place_name']; ?></option>
                              <?php } else {?>
                              <option value="<?php echo $place['fromwhere_id']; ?>"><?php echo $place['place_name']; ?></option>
                              <?php }?>
                              <?php }?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-volume"><?php echo $entry_product_volume."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_product_volume ;?>" name="input_volume" placeholder="<?php echo "立方米"; ?>" id="input-volume" class="form-control placeholder_leshare" />
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-product-tax"><?php echo $entry_product_tax."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_product_tax ;?>" name="input_product_tax" placeholder="<?php echo "元"; ?>" id="input-product-tax" class="form-control placeholder_leshare" />
                          </div>
                        </td>


                        <td style="vertical-align: top;" class="col-sm-3">
                          <div class="form-group">
                            <label class="control-label" ><?php echo "&nbsp;&nbsp;"; ?></label>
                            <div class="form-control-new2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label" ><?php echo "&nbsp;&nbsp;"; ?></label>
                            <div class="form-control-new2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-charge-mode"><?php echo $entry_charge_mode."&nbsp;:&nbsp;"; ?></label>
                            <select name="input_charge_mode" id="input-charge-mode" class="form-control">
                              <option value=""></option>
                              <?php foreach ($chargetypes as $chargetype) { ?>
                              <?php if ($chargetype['id'] == $chargetype_id) { ?>
                              <option value="<?php echo $chargetype['id']; ?>" selected="selected"><?php echo $chargetype['name']; ?></option>
                              <?php } else {?>
                              <option value="<?php echo $chargetype['id']; ?>" ><?php echo $chargetype['name']; ?></option>
                              <?php }?>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-product-recommand-index"><?php echo $entry_product_recommend_index."&nbsp;:&nbsp;"; ?></label>
                            <input type="text" value="<?php echo $product_recommand_index ;?>" name="input_product_recommand_index" placeholder="<?php echo "范围：0-10"; ?>" id="input-product-recommand-index" class="form-control placeholder_leshare" />
                          </div>
                        </td>
                      </tr>
                      
                      <tr>
                        <td colspan="2" style="vertical-align: top;" class="col-sm-3">
                          <div class="form-group row" style="margin-top:6px;margin-bottom:6px;">
                            <div class="col-sm-3" style="display:inline;padding:8px;text-align:left;margin-right:-40px;">
                            <label class="control-label" for="input-product-return"><?php echo $entry_product_return."&nbsp;"; ?></label>
                            </div>

                            <?php if ((empty($product_return_deadline)) ) { ?>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;">
                            <input type="radio" name="input_product_return" id="input-product-return" value="0" checked="checked" onclick="document.getElementById('input-product-return-deadline').readOnly='readonly';document.getElementById('input-product-return-deadline').value=0;" /><?php echo $text_product_return_no."&nbsp&nbsp" ;?>
                            </div>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;margin-left:-40px;">
                            <input type="radio" name="input_product_return" id="input-product-return" value="1" onclick="document.getElementById('input-product-return-deadline').readOnly='';document.getElementById('input-product-return-deadline').value='';" /><?php echo $text_product_return_yes1 ;?>
                            </div>
                            <?php } else if ($product_return_deadline != 0){ ?>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;">
                            <input type="radio" name="input_product_return" id="input-product-return" value="0" onclick="document.getElementById('input-product-return-deadline').readOnly='readonly';document.getElementById('input-product-return-deadline').value=0;" /><?php echo $text_product_return_no."&nbsp&nbsp" ;?>
                            </div>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;margin-left:-40px;">
                            <input type="radio" name="input_product_return" checked="checked" id="input-product-return" value="1" onclick="document.getElementById('input-product-return-deadline').readOnly='';document.getElementById('input-product-return-deadline').value=<?php echo $product_return_deadline ;?>;" /><?php echo $text_product_return_yes1 ;?>
                            </div>
                            <?php } else { ?>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;">
                            <input type="radio" name="input_product_return" checked="checked" id="input-product-return" value="0" onclick="document.getElementById('input-product-return-deadline').readOnly='readonly';document.getElementById('input-product-return-deadline').value=0;" /><?php echo $text_product_return_no."&nbsp&nbsp" ;?>
                            </div>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;margin-left:-40px;">
                            <input type="radio" name="input_product_return" id="input-product-return" value="1" onclick="document.getElementById('input-product-return-deadline').readOnly='';document.getElementById('input-product-return-deadline').value=<?php echo $product_return_deadline ;?>;" /><?php echo $text_product_return_yes1 ;?>
                            </div>
                            <?php }?>

                            <div class="col-sm-2" style="display:inline;padding:0px;text-align:left;margin-left:-80px;">
                            <?php if ($product_return_deadline != 0) { ?>
                            <input type="text" value="<?php echo $product_return_deadline ;?>" name="input_product_return_deadline" id="input-product-return-deadline" class="form-control" />
                            <?php } else {?>
                            <input type="text" readonly="readonly" value="<?php echo $product_return_deadline ;?>" name="input_product_return_deadline" id="input-product-return-deadline" class="form-control" />
                            <?php }?>
                            </div>
                            <div class="col-sm-3" style="display:inline;padding:8px 0px;text-align:left;">
                            <span><?php echo $text_product_return_yes2 ;?></span>
                            </div>
                          </div>

                            <!--
                          <div class="form-group" style="margin-top:6px;margin-bottom:6px;">
                            <label class="control-label" for="input-product-return"><?php echo $entry_product_return."&nbsp;:&nbsp;"; ?></label>
                            <div style="border-left:1px solid #e4e4e4;">
                            <div class="col-sm-3" ><input type="radio" name="input_product_return" id="input-product-return" value="0"/><?php echo $text_product_return_no;?></div>
                            <div class="col-sm-2" ><input type="radio" name="input_product_return" id="input-product-return" value="1"/><?php echo $text_product_return_yes1 ;?></div>
                            <div class="col-sm-2" ><input type="text" name="input_product_return_deadline" class="form-control" /></div>
                            <div class="col-sm-5" style="float:left;" ><span><?php echo $text_product_return_yes2 ;?></span></div>
                            </div>

                          </div>
                          -->

                        </td>
                        <td colspan="2" style="vertical-align: top;" class="col-sm-3">
                          <div class="form-group row" style="margin-bottom:6px;margin-top:6px;">
                            <div class="col-sm-2" style="display:inline;padding:8px;text-align:left;">
                            <label class="control-label" for="input-reward"><?php echo $entry_reward."&nbsp;:&nbsp;"; ?></label>
                            </div>
                            <div class="col-sm-8" style="display:inline;padding:8px 0px;text-align:left;margin-left:-18px">
                            <button type="button" class="btn btn-info" onclick="document.getElementById('input-product-reward').value=document.getElementById('input-product-price-market').value == null?0:document.getElementById('input-product-price-market').value * 0.05;"><?php echo "5%";?></button>
                            <button type="button" class="btn btn-info" onclick="document.getElementById('input-product-reward').value=document.getElementById('input-product-price-market').value == null?0:document.getElementById('input-product-price-market').value * 0.1;"><?php echo "10%";?></button>
                            <button type="button" class="btn btn-info" onclick="document.getElementById('input-product-reward').value=document.getElementById('input-product-price-market').value == null?0:document.getElementById('input-product-price-market').value * 0.15;"><?php echo "15%";?></button>
                            <button type="button" class="btn btn-info" onclick="document.getElementById('input-product-reward').value=document.getElementById('input-product-price-market').value == null?0:document.getElementById('input-product-price-market').value * 0.2;"><?php echo "20%";?></button>
                            <button type="button" class="btn btn-info" onclick="document.getElementById('input-product-reward').value=document.getElementById('input-product-price-market').value == null?0:document.getElementById('input-product-price-market').value * 0.25;"><?php echo "25%";?></button>
                            <button type="button" class="btn btn-info" onclick="document.getElementById('input-product-reward').value=document.getElementById('input-product-price-market').value == null?0:document.getElementById('input-product-price-market').value * 0.3;"><?php echo "30%";?></button>
                            </div>
                            <div class="col-sm-2" style="display:inline;padding:8px 0px;text-align:left;margin-left:-16px">
                            <input type="text" value="<?php echo $product_reward ;?>" name="input_product_reward" id="input-product-reward" placeholder="输入积分额度" class="form-control" />
                            </div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- 2. Picture Part -->
              <table style="width: 100%;margin-top: 20px;">
                <tr class="navi-row">
                  <td class="navi-title"><span>商品文案图片</span></td>
                  <td class="navi-content img-content" style="width: 220px">
                      <div class="content-row">
                        <div class="entry-group" style="margin-right: 0px;">
                          <div style="text-align: center;margin-bottom: 10px;">标题图片</div>
                          <div style="text-align: center;" id="div-upload-img-title">
                            <?php if ($product_title_img) { ?>
                            <img width="160px" height="160px" id="img-product-title" src="<?php echo $product_title_img ;?>"/>
                            <?php } else { ?>
                            <img width="160px" height="160px" id="img-product-title" style='border:1px solid #F2F2F0;background:url(<?php echo HTTP_CATALOG."image/default/to-add-img-160.png"?>) no-repeat' src="" />
                            <?php }?>
                            <input type="hidden" id="input-upload-img-title" name="input_upload_img_title" value=""> 
                          </div>
                          <div style="text-align: center;margin-top:5px;" >
                            <button type="button" class="btn btn-primary" id="btn-upload-img-title">上传/更换图片</button>
                          </div>
                        </div>
                      </div>
                  </td>
                  <td class="navi-content " style="border-left: 1px solid #e4e4e4;">
                      <div class="content-row">
                          <div class="entry-group" style="margin-right: 0px;">
                            <div style="text-align: center;margin-bottom: 10px;position: relative;">
                              <span class="fa fa-arrow-left pic-mavi" style="left:10px;"></span>
                              <span>文案图片</span>
                              <span class="fa fa-arrow-right pic-mavi" style="right:10px;"></span>
                            </div>
                            <div style="text-align:center;overflow: hidden;height: 160px;" >
                              <?php if ($product_imgs) { $i = 0;?>
                              <?php foreach ($product_imgs as $product_img) { $i += 1;?>
                                  <?php if ($product_img['img']) { ?>
                                  <div class="imagebox">
                                    <a >
                                      <img width="160px" height="160px" src="<?php echo $product_img['img'];?>" border="0" id="<?php echo 'product-img-sub-'.$i ;?>"/>
                                      <input type="hidden" id="<?php echo 'input-product-img-sub-'.$i ;?>" name="<?php echo 'input_product_img_sub_'.$i ;?>" value="" /> 
                                    </a>
                                    <div class="keleyitoolbar" id="<?php echo 'div-product-img-sub-'.$i ;?>">
                                      <a href="javascript:void(0)" data-toggle="tooltip" title="" class="btn btn-primary product-del-img-sub"><i class="fa fa-trash"></i></a>
                                    </div>
                                  </div>
                                  <?php } else {?>
                                  <div class="imagebox">
                                    <a >
                                      <img width="160px" height="160px" src="" style='border:1px solid #F2F2F0;background:url(<?php echo HTTP_CATALOG."image/default/to-add-img-160.png"?>) no-repeat' id="<?php echo 'product-img-sub-'.$i ;?>"/>
                                      <input type="hidden" id="<?php echo 'input-product-img-sub-'.$i ;?>" name="<?php echo 'input_product_img_sub_'.$i ;?>" value="" /> 
                                    </a>
                                  <div class="keleyitoolbar_add1" id="<?php echo 'div-product-img-sub-'.$i ;?>">
                                    <!--<a title="<?php echo "添加"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>-->
                                  </div>
                                  </div>
                                  <?php }?>
                              <?php } ?>
                              <?php } ?>
                            </div>
                            <div style="text-align: center;margin-top: 5px;position: relative;">
                              <button type="button" class="btn btn-primary" id="btn-upload-img-sub">上传图片</button>
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
                    <table style="width: 100%;" id="sharedoc_table">
                

                <?php $k = 0; ?>
                <?php if ($share_docs) { ?>
                <?php foreach ($share_docs as $share_doc) { $k++; ?>
                      <tr>
                        <td style="vertical-align: top;" class="col-sm-3">
                          <div>
                            <label class="control-label" for="input-sharedoc-title"><?php echo "标题"."&nbsp;:&nbsp;"; ?></label>
                            <span>
                              <input type="hidden" name="<?php echo "input_share_doc_".$k ;?>" class="input_share_doc" value="<?php echo $share_doc['prdshare_id'];?>" /> 
                              <input type="text" name="<?php echo 'input_sharedoc_title_'.$k; ?>" id="<?php echo 'input-sharedoc-title-'.$k ?>" class="form-control" value="<?php echo $share_doc['title'];?>" />
                            </span>
                          </div>
                          <div>
                            <label class="control-label" for="input-sharedoc-memo"><?php echo "分享文字"."&nbsp;:&nbsp;"; ?></label>
                            <br />
                            <span>
                              <textarea style="width: 100%;height:120px" name="<?php echo 'input_sharedoc_memo_'.$k; ?>" id="<?php echo 'input-sharedoc-memo-'.$k ?>" class="lfx-text" ><?php echo $share_doc['memo'];?></textarea>
                            </span>
                          </div>
                        </td>
                        <td style="padding: 5px;" class="col-sm-9">
                          <div class="col-sm-10" style="margin:0 auto">
                            <div class="row" style="text-align: center">
                              <!--<span>分享图片（不超过9副图片）</span>-->
                              <span>分享图片</span>
                            </div>
                            <div class="row">
                              <?php for($i=1;$i<=1;$i++) { ?>
                              <?php if ($share_doc['imgurl'.$i]) { ?>
                              <div class="imagebox">
                                  <img width="120px" height="120px" src="<?php echo HTTP_CATALOG.'image/'.$share_doc['imgurl'.$i] ;?>" border="0" id="<?php echo 'img-share-doc-'.$k.'-'.$i ;?>"/>
                                  <input type="hidden" name="<?php echo 'input_share_doc_img_'.$k.'_'.$i ;?>" value="<?php echo $share_doc['imgurl'.$i];?>" /> 
                                <div class="keleyitoolbar" >
                                  <a title="<?php echo "删除"; ?>" class="btn-new btn-primary product-del-img"><i class="fa fa-trash"></i></a>
                                </div>
                              </div>
                              <?php } else {?>
                              <div class="imagebox">
                                  <img width="120px" height="120px" style="border:1px solid #F2F2F0;background:#F2F2F0" border="0" id="<?php echo 'img-share-doc-'.$k.'-'.$i ;?>"/>
                                  <input type="hidden" name="<?php echo 'input_share_doc_img_'.$k.'_'.$i ;?>" value="" /> 
                                <div class="keleyitoolbar_add" >
                                  <a href="javascript:void(0)" title="<?php echo "添加"; ?>" class="btn btn-primary product-add-img"><i class="fa fa-plus"></i></a>
                                </div>
                              </div>
                              <?php } ?>
                              <?php } ?>
                            </div>
                          </div>
                        </td>
                      </tr>
                <?php }?>
                <?php }?>
                
                
                
                      <tr>
                        <td style="vertical-align: top;" class="col-sm-3">
                          <div>
                            <label class="control-label" for="input-sharedoc-title"><?php echo "标题"."&nbsp;:&nbsp;"; ?></label>
                            <span>
                              <?php $k++; ?>
                              <input type="hidden" name="<?php echo "input_share_doc_".$k ;?>" class="input_share_doc" value="" /> 
                              <input type="text" name="<?php echo 'input_sharedoc_title_'.$k ?>" class="form-control" />
                            </span>
                          </div>
                          <div>
                            <label class="control-label" for="input-sharedoc-title"><?php echo "分享文字"."&nbsp;:&nbsp;"; ?></label>
                            <br />
                            <span>
                              <textarea style="width: 100%;height:120px" name="<?php echo 'input_sharedoc_memo_'.$k ?>" class="lfx-text" ></textarea>
                            </span>
                          </div>
                        </td>
                        <td style="padding: 5px;" class="col-sm-9">
                          <div class="col-sm-10" style="margin:0 auto">
                            <div class="row" style="text-align: center">
                              <!--<span>分享图片（不超过9副图片）</span>-->
                              <span>分享图片</span>
                            </div>
                            <div class="row">
                              <?php for($i=1;$i<=1;$i++) { ?>
                              <div class="imagebox">
                                  <img width="120px" height="120px" style="border:1px solid #F2F2F0;background:#F2F2F0" id="<?php echo 'img-share-doc-'.$k.'-'.$i ;?>" src="" />
                                  <input type="hidden" name="<?php echo 'input_share_doc_img_'.$k.'_'.$i ;?>" value="" /> 
                                <div class="keleyitoolbar_add" >
                                  <a href="javascript:void(0)" title="<?php echo "添加"; ?>" class="btn btn-primary product-add-img"><i class="fa fa-plus"></i></a>
                                </div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </td>
                      </tr>
                      
                      
                      
                      <tr style="border-top: 1px solid #e4e4e4;" id="tr_sharedoc">
                        <td colspan="2">
                          <div style="text-align: center;margin-top: 10px;">
                            <button class="btn lfx-btn" id="btn-upload-sharedocs">新增文案</button>
                            <input type="hidden" name="total_sharedocs" class="input_share_doc" value="<?php echo $k;?>"> 
                          </div>
                        </td>
                      </tr>



                    </table>
                  </td>
                </tr>
              </table>
              </form> <!-- end of post form --> 
              
              
              
              <div style="padding-top:30px;text-align:center">
                <div style="display:inline-block;">
                <button type="submit" id="btn-submit-all" form="form-product" data-toggle="tooltip" title="<?php echo "提交审批"; ?>" class="btn btn-primary"><i class="fa fa-save"><?php echo "&nbsp;&nbsp;提交审批"; ?></i></button>
                </div>
                <div style="display:inline-block;">
                <a href="<?php echo $delete; ?>" title="<?php echo "出库"; ?>" class="btn btn-danger"><i class="fa fa-remove"><?php echo "&nbsp;&nbsp;出库&nbsp;&nbsp;"; ?></i></a>
                </div>
                <div style="display:inline-block;">
                <a href="<?php echo $cancel; ?>" title="<?php echo "取消"; ?>" class="btn btn-primary"><i class="fa fa-reply"><?php echo "&nbsp;&nbsp;取消&nbsp;&nbsp;"; ?></i></a>
                </div>
              </div>


            </div>
          </div>
          
        </div><!-- end of "panel-body" -->
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

//liuhang add for product code
  var filter_id = $('input[name=\'filter_id\']').val();

	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_name);
	}


	location = url;
});

//$(document).ready(function(){ 
//$('body').delegate('#btn-submit-all','click',function(){
$(function(){
//$('body').delegate(function(){

    var img_title = $('#img-product-title').attr('src');
    img_title = img_title.replace('<?php echo HTTP_CATALOG."image/"?>', '');
    $('#input-upload-img-title').val(img_title);
    //alert(img_title);

    for(var i=1; i<=5; i++){
      
      if($('body').find('#product-img-sub-'+i) == undefined ){
        continue;
        }

      var src = $('#product-img-sub-'+i).attr('src');
      src = src.replace('<?php echo HTTP_CATALOG."image/"?>', '');
      
      if (src === null || src == undefined || src == ''){
        continue;
      } else {
        $('#input-product-img-sub-'+i).val(src);
      } 
    }

    //$('#form-product').submit();

});

  //alert(value);
//$('#btn-upload-sharedocs').on('click', function() {
$('body').delegate('#btn-upload-sharedocs','click',function(){
  
  var value = '';

  var title = $('#input-sharedoc-title').val();
  var product_id = $('#input-product-code').val();
  var memo = $('#input-sharedoc-memo').val();
  var img1 = $('#product-doc-img-1').attr('src');
  var img2 = $('#product-doc-img-2').attr('src');
  var img3 = $('#product-doc-img-3').attr('src');
  var img4 = $('#product-doc-img-4').attr('src');
  var img5 = $('#product-doc-img-5').attr('src');
  var img6 = $('#product-doc-img-6').attr('src');
  var img7 = $('#product-doc-img-7').attr('src');
  var img8 = $('#product-doc-img-8').attr('src');
  var img9 = $('#product-doc-img-9').attr('src');

  if (typeof(img1) == "undefined") img1 = '';
  if (typeof(img2) == "undefined") img2 = '';
  if (typeof(img3) == "undefined") img3 = '';
  if (typeof(img4) == "undefined") img4 = '';
  if (typeof(img5) == "undefined") img5 = '';
  if (typeof(img6) == "undefined") img6 = '';
  if (typeof(img7) == "undefined") img7 = '';
  if (typeof(img8) == "undefined") img8 = '';
  if (typeof(img9) == "undefined") img9 = '';

  value += ('&title=' + title); 
  value += ('&product_id=' + product_id); 
  value += ('&memo=' + memo); 
  value += ('&img1=' + img1); 
  value += ('&img2=' + img2); 
  value += ('&img3=' + img3); 
  value += ('&img4=' + img4); 
  value += ('&img5=' + img5); 
  value += ('&img6=' + img6); 
  value += ('&img7=' + img7); 
  value += ('&img8=' + img8); 
  value += ('&img9=' + img9);
  


  var html = '';
  html = '<tr>' + 
         '<td style="vertical-align: top;" class="col-sm-3">' +
         '  <div> ' +
         '    <label class="control-label" for="input-sharedoc-title"><?php echo "标题"."&nbsp;:&nbsp;"; ?></label>' +
         '    <span> ' +
         '      <?php $k++; ?>' +
         '      <input type="hidden" name="<?php echo "input_share_doc_".$k ;?>" class="input_share_doc" value="<?php echo $k;?>"> '+
         '      <input type="text" name="<?php echo "input_sharedoc_title_".$k ?>" class="form-control" />'+
         '    </span>'+
         '  </div>'+
         '  <div>'+
         '    <label class="control-label" for="input-sharedoc-title"><?php echo "分享文字"."&nbsp;:&nbsp;"; ?></label>'+
         '    <br />'+
         '    <span>'+
         '      <textarea style="width: 100%;height:120px" name="<?php echo "input_sharedoc_memo_".$k ?>" class="lfx-text" ></textarea>'+
         '   </span>'+
         '  </div>'+
         '</td>'+
         '<td style="padding: 5px;" class="col-sm-9">'+
         '  <div class="col-sm-10" style="margin:0 auto">'+
         '    <div class="row" style="text-align: center">'+
         '      <span>分享图片</span>'+
         '    </div>'+
         '    <div class="row">'+
         '      <?php for($i=1;$i<=1;$i++) { ?>'+
         '      <div class="imagebox">'+
         '          <img width="120px" height="120px" style="border:1px solid #F2F2F0;background:#F2F2F0" border="0" id="<?php echo "img-share-doc-".$k."-".$j ;?>" src="" />'+
         '          <input type="hidden" name="<?php echo "input_share_doc_img_".$k."_".$i ;?>" value=""> '+
         '        <div class="keleyitoolbar_add" >'+
         '          <a href="javascript:void(0)" title="<?php echo "添加"; ?>" class="btn btn-primary product-add-img"><i class="fa fa-plus"></i></a>'+
         '        </div>'+
         '      </div>'+
         '      <?php } ?>'+
         '    </div>'+
         '  </div>'+
         '</td>'+
         '</tr>'+
           
         '  <tr style="border-top: 1px solid #e4e4e4;" id="tr_sharedoc">'+
         '    <td colspan="2">'+
         '      <div style="text-align: center;margin-top: 10px;">'+
         '        <button class="btn lfx-btn" id="btn-upload-sharedocs">新增文案</button>'+
         '        <input type="hidden" name="total_sharedocs" class="input_share_doc" value="<?php echo $k;?>">'+ 
         '      </div>'+
         '        <!--'+
         '        <i class="fa fa-check-circle"></i>'+
         '        <?php echo $success; ?>'+
         '        <button type="button" class="close" data-dismiss="alert">&times;</button>'+
         '        -->'+
         '    </td>'+
         '  </tr>';


  $('tr#tr_sharedoc').remove();
  $('table#sharedoc_table').append(html);


  /*
  $.post("index.php?route=catalog/product/addsharedoc&token=<?php echo $token; ?>", value, function(result){
    
    var html = '';

    $('#input-sharedoc-title').val('');
    $('#input-product-code').val('');
    $('#input-sharedoc-memo').val('');

    var i;
    for(i=1;i<=9;i++){
    $('#product-doc-img-' + i).attr('src','');
      html = '<a >' +
             '<img width="120px" height="120px" style="background:grey" border="0" id="product-doc-img-' + i + '"/>' +
             '</a>' +
             '<div class="keleyitoolbar_add" >' +
             '<a href="javascript:void(0)" title="<?php echo "添加"; ?>" class="btn btn-primary product-add-img"><i class="fa fa-plus"></i></a>' + 
             '</div>';
      $('#product-doc-img-' + i).parent().parent().empty().append(html);
    }

    var successinfo = '共享文案添加成功，文案编号：' + result; 
    html = '<i class="fa fa-check-circle"></i>' +
           successinfo + 
           '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    $('#div-alert-success').empty().append(html);       
  //$("span").html(result);

   });
  */

});

$('body').delegate('.product-del-img-sub','click',function(){
//$('.product-del-img').on('click',function(){
 
 //console.log('hahahahahahahah');
 $(this).parent().parent().find("img").attr('src',''); 
 $(this).parent().parent().find("img").css('background','url(<?php echo HTTP_CATALOG."image/default/to-add-img-160.png"?>) no-repeat');
 $(this).parent().parent().find("input").val('');
 //$(this).parent().removeClass('keleyitoolbar'); 
 //$(this).parent().addClass('keleyitoolbar_add1'); 
 //var html = '<a href="javascript:void(0)" title="<?php echo "添加"; ?>" class="btn btn-primary product-add-img"><i class="fa fa-plus"></i></a>';
 //$(this).parent().empty().append(html);
 $(this).parent().empty();

});



$('body').delegate('.product-del-img','click',function(){
//$('.product-del-img').on('click',function(){
 
 //console.log('hahahahahahahah');
 $(this).parent().parent().find("img").attr('src',''); 
 //$(this).parent().parent().find("img").css('background','url(<?php echo HTTP_CATALOG."image/default/to-add-img-160.png"?>) no-repeat');
 $(this).parent().parent().find("img").css('background','#F2F2F0');
 
 $(this).parent().parent().find("input").val('');
 $(this).parent().removeClass('keleyitoolbar'); 
 $(this).parent().addClass('keleyitoolbar_add'); 
 var html = '<a href="javascript:void(0)" title="<?php echo "添加"; ?>" class="btn btn-primary product-add-img"><i class="fa fa-plus"></i></a>';
 $(this).parent().empty().append(html); 

});

$('body').delegate('.product-add-img','click',function(){
//$('.product-add-img').on('click',function(){

 var myimg = $(this).parent().parent().find("img");
 var myinput = $(this).parent().parent().find("input");
 var myclass = $(this).parent();

 var html = '<a href="javascript:void(0)" title="" class="btn-new btn-primary product-del-img"><i class="fa fa-trash"></i></a>';
 //$(this).parent().empty().append(html); 
 
  $('#form-upload').remove();
  $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
  $('#form-upload input[name=\'file\']').trigger('click');

  timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);
      
      $.ajax({
        url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=product',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-upload')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function(json) {
          var path = json['file_path'];
          var pathfull = '<?php echo HTTP_CATALOG."image/";?>' + json['file_path'];
          //alert("image file path is :"+path);
          myimg.attr('src',pathfull);
          myinput.val(path);
          myimg.find("img").css('background','');
          myclass.removeClass('keleyitoolbar_add');
          myclass.addClass('keleyitoolbar'); 
          myclass.empty().append(html); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }, 500);
  
});

//--></script> 

<script type="text/javascript"><!--

$('body').delegate('#btn-upload-img-title','click', function() {
  $('#form-upload').remove();
  $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
  $('#form-upload input[name=\'file\']').trigger('click');
  timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);

      $.ajax({
        url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=product',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-upload')[0]),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('#btn-upload-img-title i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
          $('#btn-upload-img-title').prop('disabled', true);
        },
        complete: function() {
          $('#btn-upload-img-title i').replaceWith('<i class="fa fa-upload"></i>');
          $('#btn-upload-img-title').prop('disabled', false);
        },
        success: function(json) {
          var path = json['file_path'];
          var pathfull = '<?php echo HTTP_CATALOG."image/";?>' + json['file_path'];
          var html =
                  '<img src="'+pathfull+'" height="160px" width="160px" id="img-upload-title" />' + 
                  '<input type="hidden" id="input-upload-img-title" name="input_upload_img_title" value="' + path + '">';
          $('#div-upload-img-title').empty().append(html);
          //$('#input-upload-img-title').val(path);
          /*below code is used to do debug*/
          //alert("image file path is :"+path);
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }, 500);
});

$('body').delegate('#btn-upload-img-sub','click', function() {
  $('#form-upload').remove();
  $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
  $('#form-upload input[name=\'file\']').trigger('click');
  timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);

      $.ajax({
        url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=product',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-upload')[0]),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('#btn-upload-img-sub i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
          $('#btn-upload-img-sub').prop('disabled', true);
        },
        complete: function() {
          $('#btn-upload-img-sub i').replaceWith('<i class="fa fa-upload"></i>');
          $('#btn-upload-img-sub').prop('disabled', false);
        },
        success: function(json) {
          var path = json['file_path'];
          var pathfull = '<?php echo HTTP_CATALOG . "image/"?>' + json['file_path'];
          //var html = <img src="'+path+'" height="160px" width="160px" id="img-upload-title" />';
          //$('#div-upload-img-title').empty().append(html);
          
          for(var i=1; i<=5; i++){
            
            /*
            if($('body').find('#product-img-sub-'+i) == undefined ){
              break;
              }
              */

            var src = $('#product-img-sub-'+i).attr('src');
            if (src === null || src == undefined || src == ''){
              $('#product-img-sub-'+i).attr('src', pathfull);
              $('#input-product-img-sub-'+i).val(path);
              //alert(i+':'+path);
             
              $('#div-product-img-sub-'+i).removeClass('keleyitoolbar_add1');
              $('#div-product-img-sub-'+i).addClass('keleyitoolbar');
              var html = '<a href="javascript:void(0)" data-toggle="tooltip" title="" class="btn btn-primary product-del-img-sub"><i class="fa fa-trash"></i></a>';
              $('#div-product-img-sub-'+i).empty().append(html);
   
              break;
              } 
            }
         /* 
          for(var i=1; i<=5; i++){
            var value = $('#input-product-img-sub-'+i).val();
            if (value == null || value == undefined || value == '')
              $('#input-product-img-sub-'+i).val(path);
              break;
            }
        */
          /*below code is used to do debug*/
          //alert("image file path is :"+path);
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }, 500);
});

$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});
/*
$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
*/
//--></script>

</div>
<?php echo $footer; ?>
