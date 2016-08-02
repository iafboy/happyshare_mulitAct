<div id="confirmReturnGoodsWin">
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
                        <img src="<?php echo DIR_IMAGE_URL.$refound['image']; ?>" width="30%"/>
                    </div>
                    <?php } ?>
                    <input hidden name="refound_status" value="8"/>

                <div class="form-group" style="display: block">
                    <label class="label-control">退货商品数量:</label>
                    <span><?php echo $refound['return_num']; ?></span>
                </div>
                    <div class="form-group" style="display: block">
                        <label class="label-control">返款金额:</label>
                        <span><?php echo parseFormatNum($refound['shippment_cost'],2); ?></span>
                        <span>当客户支付现金小于退货金额时，以上金额含还返积分金额</span>
                    </div>
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