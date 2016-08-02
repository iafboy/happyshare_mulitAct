<div xmlns="http://www.w3.org/1999/html">
  <form>
    <div class="content-row">
      <table>
        <tr>
          <td>
              <span class="entry-group">
                <label>用户名：</label>
                  <span><?php echo $refundInfo[0]['fullname']; ?></span>
              </span>
          </td>
        <tr>
          <td>
              <span class="entry-group">
                <label>退款商品数量：</label>
                <span>
                  <?php echo $refundInfo[0]['return_num']; ?>
                </span>
              </span>
          </td>
        </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>退款金额：</label>
                <span>
                  <?php echo $refundInfo[0]['shippment_cost']; ?>
                </span>
              </span>
          </td>
          </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>退款现金：</label>
                <span>
                  <?php echo $refundInfo[0]['return_pay']; ?>
                </span>
              </span>
          </td>
        </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>退还积分：</label>
                <span>
                  <?php echo $refundInfo[0]['return_score']; ?>
                </span>
              </span>
          </td>
        </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>银行名称：</label>
                <span>
                  <?php echo $refundInfo[0]['bank_name']; ?>
                </span>
              </span>
          </td>
          </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>卡号：</label>
                <span>
                  <?php echo $refundInfo[0]['card_id']; ?>
                </span>
              </span>
          </td>
        </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>联系人姓名：</label>
                <span>
                  <?php echo $refundInfo[0]['card_holder']; ?>
                </span>
              </span>
          </td>
        </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>退款状态：</label>
                <span id="refundStaus">
                  <?php echo $refundInfo[0]['refund_status']; ?>
                </span>
              </span>
          </td>
        </tr>
        <tr>
          <td>
              <span class="entry-group">
                <label>转账单号：</label>
                <input class="refund" value="<?php echo $refundInfo[0]['transferNo']; ?>">

                </input>
              </span>
          </td>
        </tr>
        </table>
    </div>
  </form>
</div>