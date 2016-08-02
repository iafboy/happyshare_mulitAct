<div id="shipmmentsWin">
    <div class="container-fluid">
        <div class="row">
            <form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td class="text-center" style="width: 15%;">计费模板</td>
                    <td class="text-center" style="width: 10%;">运费</td>
                    <td class="text-center" style="width: 25%;">快递公司</td>
                    <td class="text-center" style="width: 25%;">运单号</td>
                    <td class="text-center" style="width: 50%;">产品列表</td>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $show=true;
                     foreach($templates as $template){
                        $i = 0;
                        foreach($template['products'] as $product){
                            $i ++;
                            if($i == 1){ ?>
                                <tr>
                                    <td  class="text-center" rowspan="<?php echo $template['size']; ?>">
                                        <input type="hidden" name="template_ids[]" value="<?php echo $template['express_template']; ?>" />
                                        <?php echo $template['express_template_name']; ?>
                                    </td>
                                    <td  class="text-center" rowspan="<?php echo $template['size']; ?>">
                                        <input name="express_price_<?php echo $template['express_template']; ?>" type="hidden" class="lfx-text w-10" value="<?php echo parseFormatNum($template['express_price'],2); ?>" />
                                        <?php
                                      if($show){
                                        echo parseFormatNum($template['express_price'],2);
                                        $show=false;
                                        }
                                    ?>
                                    </td>
                                    <td  class="text-center" rowspan="<?php echo $template['size']; ?>">
                                        <input name="express_name_<?php echo $template['express_template']; ?>" class="lfx-text w-10" value="<?php echo $template['express_name']; ?>" /></td>
                                    <td  class="text-center" rowspan="<?php echo $template['size']; ?>">
                                        <input name="express_no_<?php echo $template['express_template']; ?>" class="lfx-text w-10" value="<?php echo $template['express_no']; ?>" /></td>
                                    <td  class="text-center"><?php echo $product['name']; ?><span style="float: right;">x <?php echo $product['quantity']; ?></span></td>
                                </tr>
                        <?php }else{ ?>
                                <tr>
                                    <td  class="text-center"><?php echo $product['name']; ?><span style="float: right;">x <?php echo $product['quantity']; ?></span></td>
                                </tr>
                        <?php }
                            }
                        }
                     ?>
                </tbody>
            </table>
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