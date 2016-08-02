<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>网关产品示例</title>

</head>
<body style="background-color:#e5eecc;">
<form class="api-form" method="post" action="payment.php" target="_blank">
    <p>
        <label>交易金额：</label>
        <input id="txnAmt" type="text" name="txnAmt" placeholder="交易金额" value="1000" title="单位为分 " required="required"/>
    </p>

    <p>
        <label>商户订单号：</label>
        <input id="orderId" type="text" name="orderId" placeholder="商户订单号" value="<?php echo date('YmdHis') ?>"
               title="自行定义，8-32位数字字母 " required="required"/>
    </p>

    <p>
        <label>前台回调地址：</label>
        <input id="frontUrl" type="text" name="frontUrl" placeholder="前台回调地址"
               value="http://localhost/leshare/system/pay/unionpay/FrontReceive.php" title="自行定义，8-32位数字字母 "
               required="required"/>
    </p>

    <p>
        <label>后台回调地址：</label>
        <input id="backUrl" type="text" name="backUrl" placeholder="后台回调地址"
               value="htttp://localhost/leshare/system/pay/unionpay/BackReceive.php" title="自行定义，8-32位数字字母 "
               required="required"/>
    </p>

    <p>
        <label>&nbsp;</label>
        <input type="submit" class="button" value="跳转银联页面支付"/>
    </p>
</form>

</body>