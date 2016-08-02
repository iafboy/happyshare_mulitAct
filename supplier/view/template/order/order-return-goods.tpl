<div id="returnGoodsWin">
    <div class="container-fluid">
        <div class="row">
            <form class="form-horizontal">
                <div class="col-sm-10 col-sm-push-1">
                    <div class="form-group" style="display: block">
                        <label class="label-control">联系电话:</label>
                        <input class="form-control" readonly value="<?php echo $refound['phone']; ?>" />
                    </div>
                    <div class="form-group" style="display: block">
                        <label class="label-control">退货原因：</label>
                    </div>
                    <div class="form-group reason_box-customer">
                        <span>客户原因：</span>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="商品不合心意"  /><span style="margin-right: 10px;">商品不合心意</span>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="型号拍错了"  /><span style="margin-right: 10px;">型号拍错了</span>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="质量不好"  /><span style="margin-right: 10px;">质量不好</span>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="买贵了"  /><span style="margin-right: 10px;">买贵了</span>
                        <?php if(!in_array($refound['reason'],['商品不合心意','型号拍错了','质量不好','买贵了','商品发错了','商品有质量问题'])){ ?>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="<?php echo $refound['reason']; ?>"  /><span style="margin-right: 10px;">其他:<?php echo $refound['reason']; ?></span>

                           <?php } ?>

                    </div>
                    <div class="form-group reason_box-supplier">
                        <span>商家原因：</span>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="商品发错了"   /><span style="margin-right: 10px;">商品发错了</span>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="商品有质量问题"  /><span style="margin-right: 10px;">商品有质量问题</span>
                        <?php if(!in_array($refound['reason'],['商品不合心意','型号拍错了','质量不好','买贵了','商品发错了','商品有质量问题'])){ ?>
                        <input class="form-control" type="radio" name="reason" onclick="return false;" style="display: inline-block;" value="<?php echo $refound['reason']; ?>"  /><span style="margin-right: 10px;">其他:<?php echo $refound['reason']; ?></span>
                        <?php } ?>
                    </div>
                    <?php if(isset($refound['image'])){ ?>
                    <div class="form-group" style="display: block">
                        <label class="label-control">客户上传图片:</label>
                        <img src="<?php echo DIR_IMAGE_URL.$refound['image']; ?>"  width="30%" />
                    </div>
                    <?php } ?>
                    <div class="form-group" style="display: block">
                        <label class="label-control">是否允许退货:</label>
                        <?php if ($refound['refund_status']==2 || $refound['refund_status']==1 || $refound['refund_status']==3 ){ ?>
                        <select name="allow_returngoods" class="form-control">
                            <?php if($refound['refund_status'] == 1 ){ ?>
                            <option value="1">允许</option>
                            <option value="0">不允许</option>
                            <?php }else if($refound['refund_status'] == 3 ){ ?>
                            <option value="1">允许</option>
                            <option value="0" selected>不允许</option>
                            <?php }else if($refound['refund_status'] == 2 ){ ?>
                            <option value="1" selected>允许</option>
                            <option value="0">不允许</option>
                            <?php }else { ?>
                            <option value="1" selected>允许</option>
                            <option value="0">不允许</option>
                            <?php }
                            ?>
                        </select>
                        <?php } else { ?>
                            <?php if($refound['refund_status'] == 1 ){ ?>
                            <span>不允许</span>
                            <?php }else if($refound['refund_status'] == 3 ){ ?>
                            <span>不允许</span>
                            <?php }else if($refound['refund_status'] == 2 ){ ?>
                            <span>允许</span>
                            <?php }else { ?>
                            <span>允许</span>
                            <?php }
                            ?>
                        <?php } ?>

                    </div>
                    <div class="form-group" style="display: block">
                        <label class="label-control">退货商品数量:</label>
                        <span><?php echo $refound['return_num']; ?></span>
                        <input type="hidden" name="return_num" value="<?php echo $refound['return_num']; ?>" />
                    </div>
                    <div class="form-group" style="display: block">
                        <label class="label-control">协商后的退款金额:</label>
                        <?php if ($refound['refund_status']==2 || $refound['refund_status']==1 || $refound['refund_status']==3 ){ ?>
                        <input class="form-control" name="return_money" value="<?php echo parseFormatNum($refound['shippment_cost'],2); ?>" />
                        <?php } else { ?>
                        <span>￥<?php echo parseFormatNum($refound['shippment_cost'],2); ?></span>
                        <?php } ?>
                        <span>当客户支付现金小于退货金额时，以上金额含还返积分金额</span>
                    </div>
                    <?php if(in_array($refound['refund_status'],[4,5,6,7,8])){ ?>
                    <div class="form-group" style="display: block">
                        <label class="label-control">快递公司:</label><span><?php echo $refound['shippment_company']; ?></span>
                    </div>
                    <div class="form-group" style="display: block">
                        <label class="label-control">快递单号:</label><span><?php echo $refound['shippment_no'] ?></span>
                    </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    label,span,td{
        font-size: 14px;
    }
    .navi-row .navi-title span{
        font-size: 20px;
    }
</style>
<script>
    $(function () {
        var reason = '<?php echo $refound['reason']; ?>';
        var mode = '<?php echo $refound['mode']; ?>';
        if(mode == 1){
            $('.reason_box-customer input[type="radio"][value="'+reason+'"]').attr('checked','checked');
        }else if(mode == 2){
            $('.reason_box-supplier input[type="radio"][value="'+reason+'"]').attr('checked','checked');
        }
    });
</script>