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
  <script language="javascript" type="text/javascript">
  	$(document).ready(function(e) {
        var order = "<?php echo $_GET['order_id']; ?>";
		var user = "<?php echo $_GET['supplier_id']; ?>";
		var token = "<?php echo $_GET['token']; ?>";
		var order_no = "<?php echo $_GET['order_no']; ?>";
		var ajaxobj = {
			type:"POST",
			//url:'controller/sale/order_info.php',
      		url:'index.php?route=sale/order_info&token='+token,
			data:{order_id:order,user_id:user},
			success:function(data){
			console.debug('test : liuhang');
   			console.debug(data);
				var order_item = eval('('+data+')');
       			var i = 0; // 数据数组基数
				var j = 0; // 客户付款以及投递详情数组基数
				var k = 0; // 客户订单物品数组基数
				for(i=0;i<order_item.length;i++)
				{
					for(j=0;j<order_item[i]['pay'].length;j++)
					{
						var address = order_item[i]['pay'][j]['address'];
						var name = order_item[i]['pay'][j]['name'];
						var postcode = order_item[i]['pay'][j]['postcode'];
						var phone = order_item[i]['pay'][j]['phone'];
						var payment_status = order_item[i]['pay'][j]['payment_status'];
						var customer_payment_status = order_item[i]['pay'][j]['customer_payment_status'];
						var payment = order_item[i]['pay'][j]['payed'];
						var reference = order_item[i]['pay'][j]['payed_reference'];
						var price = order_item[i]['pay'][j]['price'];
						var credit_score = order_item[i]['pay'][j]['payed_credit_score'];
					}
					$("<span>").text(address).appendTo($("span#address"));
					$("<span>").text(postcode).appendTo($("span#postcode"));
					$("<span>").text(name).appendTo($("span#reveivername"));
					$("<span>").text(phone).appendTo($("span#phone_number"));
					$("<span>").text(payment_status).appendTo($("span#payment"));
					/* if(customer_payment_status==1)
					{
						$("<input type='button' id='"+order+"' class='express_form_btn' value='填写发货单'>").appendTo($("span#payment"));
					}
					else
					{
						$("<input type='button' id='"+order+"' class='express_form_btn' value='填写发货单'>").appendTo($("span#payment"));
					} */
					$("<span>").text("￥"+payment+"\n\n\n").appendTo($("span#price"));					
					$("<span>").text(credit_score+"积分\n\n\n").appendTo($("span#price"));
					$("<span>").text(reference).appendTo($("span#price"));
					for(k=0;k<order_item[i]['item'].length;k++)
					{
						var image = order_item[i]['item'][k]['item_image'];
            //alert(image);
						var webaddr = "<?php echo HTTP_CATALOG.'image/'; ?>";
						image = webaddr + image;
						var order_item_id = order_item[i]['item'][k]['order_product_id'];
						$('input#order_item_id').val(order_item_id);
						var item_name = order_item[i]['item'][k]['itemname'];
						var shipment = order_item[i]['item'][k]['shipment_type'];
						var qty = order_item[i]['item'][k]['quantity'];
						var total_price = order_item[i]['item'][k]['total_price'];
						var total_credit_score = order_item[i]['item'][k]['total_score'];
						var item_type = order_item[i]['item'][k]['item_type'];
						var order_product_id = order_item[i]['item'][k]['order_product_id'];
						var product_status = order_item[i]['item'][k]['product_status'];
						var item_status = order_item[i]['item'][k]['item_status'];
						var express_tracking_num = order_item[i]['item'][k]['express_no'];
						var express_company = order_item[i]['item'][k]['express_company'];
						var tr = $("<tr id='item_detail'>").appendTo($("table#item_detail"));
						var td = $("<td id='item_detail' style='width: 400px;' class='first'>").appendTo(tr);
						var div = $("<div>").appendTo(td);
						var div2 = $("<div style='float: left;padding: 5px;'>").appendTo(div);
						var div3 = $("<div style='float: left;min-width: 250px;height:100px;padding: 5px;'>").appendTo(div);
						var td2 = $("<td id='item_type'>").appendTo(tr);
						var td3 = $("<td id='processing_status'>").appendTo(tr);
						var td4 = $("<td id='processing_steps_btn'>").appendTo(tr);
						$("<img src='"+image+"' id='item_pic' style='width: 100px;height:60px;'>").appendTo(div2);
						$("<span id='item'>").text(item_name).appendTo(div3);
						$("<br/>").appendTo(div3);
						$("<span id='delivery'>").text(shipment).appendTo(div3);
						$("<br/>").appendTo(div3);
						$("<span style='margin-right: 10px;' id='item_quantity'>").text(qty+"个").appendTo(div3);
						$("<span style='margin-right: 10px;' id='item_price'>").text("￥"+total_price).appendTo(div3);
						$("<span id='credit'>").text(total_credit_score+"积分").appendTo(div3);
						$("<span>").text(item_type).appendTo(td2);
						$("<span>").text(product_status).appendTo(td3);
            			switch(item_status)
						{
							case "0": //未发货
								status_control=$("<input type='button' value='填写发货单' class='express_form_btn' id='"+order_item_id+"'>").text("填写发货单").appendTo(td4); 
								break;
							case "1": //已发货，未收货
		  					case "2": //已收货
								status_control=$("<input type='button' value='查看物流' class='express_info_btn' id='"+order_item_id+"'>").text("查看物流").appendTo(td4); 
								break;
						}
						//var tr2 = $("<tr id='refund'>").appendTo($("table#item_detail"));
						//$("<span>").text("申请退款：\n\n\n").appendTo(td4);
						//$("<button id='refund' value='"+k+"'>").text("退货审核").appendTo(td4);
            
					}
				};
				var ajax_obj2 = {
				url:"index.php?route=sale/shipment&token="+token,
				success:function(data){
					var express_company_detail = eval('('+data+')');
					var i = 0;
					for(i=0;i<express_company_detail.length;i++)
					{
						var companyid = express_company_detail[i].companyID;
						var company = express_company_detail[i].company;
						$("<option class='company_name' value='"+companyid+"'>").text(company).appendTo($('select#express_name'));
						}
						
					}
				};
				$.ajax(ajax_obj2);

					$('input.express_info_btn').click(function(e) {
                        $('div.express_info').attr('style','visibiglity:visible; z-index:1;background:#FFF; position:absolute; top:450px; left:250px; border:solid thin #000; padding:10px;');
						$('div#return_form').attr('style','visibility:hidden; z-index:-1;');
						 $("div#content").attr("style","z-index:-1");
						 var orderID = this.id;
						  for(i=0;i<order_item.length;i++)
						 {
							 for(j=0;j<order_item[i]['item'].length;j++)
							 {
								if(orderID == order_item[i]['item'][j]['order_product_id'])
								{
									var express_tracking_num = order_item[i]['item'][j]['express_no'];
									var express_company = order_item[i]['item'][j]['express_company'];
									$('input#exp_info_express_name').val(express_company);
									$('input#exp_info_order_id').val(order_no);
									$('input#exp_info_express_id').val(express_tracking_num);
								} 
							 }
						 }
                    });





					$('input.express_form_btn').click(function(e) {
                        $('div.express_form').attr('style','visibiglity:visible; z-index:1;background:#FFF; position:absolute; top:450px; left:250px; border:solid thin #000; padding:10px;');
						$('div#return_form').attr('style','visibility:hidden; z-index:-1;');
						 $("div#content").attr("style","z-index:-1");
						 var orderID = this.id;
						 for(i=0;i<order_item.length;i++)
						 {
							 for(j=0;j<order_item[i]['item'].length;j++)
							 {
								if(orderID == order_item[i]['item'][j]['order_product_id'])
								{
									$('input#order_item_id').val(orderID);
									$('input#order_product_id').val(order_no);
								} 
							 }
						 }
						 
						
                    });
					$('input.express_submit').click(function(e) {
						var option_txt = $('select#express_name option:selected').val();
									//var orderID = $('input#order_id').val();
						var orderID = "<?php echo $_GET['order_id']; ?>";
						var delivery_code = $('input#express_id').val();
						var order_item_id = $('input#order_item_id').val();
						var order_product_status = '1';
						//alert(orderID);
						//alert(option_txt);
						//alert(user);
						var ajax_obj2 = {
							type:'post',
							data:{express_id:option_txt,orderid:orderID,code:delivery_code,product_status:order_product_status,suppliers:user,order_item:order_item_id},
							url:"index.php?route=sale/shippingcoderecord&token="+token,
							success:function(data){
                				//alert(data);
								if(data==0)
								{
										alert("运单信息已记录！"); // <-- 这里随便你们写什么提示
										$('div.express_form').attr('style','visibility:hidden; z-index:-1;');
										location.reload();					
								}
							}
						};
						$.ajax(ajax_obj2);
           			 });	
					 $("input#close_btn").click(function(e) {
							 	if($('div.check_delivery_steps:visible'))
								{
									$('div.check_delivery_steps').attr('style','visibility:hidden; z-index:-1;');
								}
								if($('div.express_form:visible'))
								{
									$('div.express_form').attr('style','visibility:hidden; z-index:-1;');
								}
                        });

					 $("input#exp_info_close_btn").click(function(e) {
							 	if($('div.check_delivery_steps:visible'))
								{
									$('div.check_delivery_steps').attr('style','visibility:hidden; z-index:-1;');
								}
								if($('div.express_info:visible'))
								{
									$('div.express_info').attr('style','visibility:hidden; z-index:-1;');
								}
                        });



					$("button#refund").click(function(e) {
						 $("div#return_form").attr("style","z-index:1;opacity:1;position:fixed; top:52px; left:50px; width:100%; height:auto; ");
						 $("div#form").attr("style","background-color:#fff;margin:10%;padding:5%;border:solid #000; border-width:1px; height:auto;");
						 $('div.express_form').attr('style','visibility:hidden; z-index:-1;');
						 $("div#content").attr("style","z-index:-1");
						 var item_num = $(this).val();
						 var item_name = order_item[0]['item'][item_num]['itemname'];
						 var shipment = order_item[0]['item'][item_num]['shipment_type'];
						 var qty = order_item[0]['item'][item_num]['quantity'];
						 var total_price = order_item[0]['item'][item_num]['total_price'];
						 var total_credit_score = order_item[0]['item'][item_num]['total_score'];
						 var item_type = order_item[0]['item'][item_num]['item_type'];
						 for(k=0;k<order_item[0]['return'].length;k++)
						 {
							var return_name = order_item[0]['return'][k]['return_item_name'];
							var img1 = order_item[0]['return'][k]['img1'];
							var img2 = order_item[0]['return'][k]['img2'];
							var img3 = order_item[0]['return'][k]['img3'];
							var img4 = order_item[0]['return'][k]['img4'];
							var pay_return_transaction_fee = order_item[0]['return'][k]['express_owner'];
							var express_owner = order_item[0]['return'][k]['express_owner'];
							var return_reason = order_item[0]['return'][k]['reason'];
								if(return_name == item_name)
								{
									 $("<span id='return_name'>").text(return_name).appendTo("p#name");
									 $("<span id='total_price'>").text(total_price).appendTo("p#price");
									 $("<span id='express_owner'>").text(express_owner).appendTo("p#cost");
									 $("<span id='return_reason'>").text(return_reason).appendTo("p#reason");
									 $("<span id='img1'><img src='"+img1+"'>").appendTo("p#pic");
									 $("<span id='img2'><img src='"+img2+"'>").appendTo("p#pic");
									 $("<span id='img3'><img src='"+img3+"'>").appendTo("p#pic");
									 $("<span id='img4'><img src='"+img4+"'>").appendTo("p#pic");
									 $("<span id='phone'>").text(order_item[0]['pay'][0]['phone']).appendTo("p#phone");	
									 	 
								}
								else
								{
									/*
									alert("客户未申请退款");
									$("div#return_form").attr("style","z-index:-1;opacity:0;position:fixed; top:52px; left:50px; width:100%; height:auto;");
									$("span#item_detail").remove();
									$("div#content").attr("style","z-index:1");
									*/
									 
									 $("<span id='return_name'>").text(return_name).appendTo("p#name");
									 $("<span id='total_price'>").text(total_price).appendTo("p#price");
									 $("<span id='express_owner'>").text(express_owner).appendTo("p#cost");
									 $("<span id='return_reason'>").text(return_reason).appendTo("p#reason");
									 $("<span id='img1'><img src='"+img1+"'>").appendTo("p#pic");
									 $("<span id='img2'><img src='"+img2+"'>").appendTo("p#pic");
									 $("<span id='img3'><img src='"+img3+"'>").appendTo("p#pic");
									 $("<span id='img4'><img src='"+img4+"'>").appendTo("p#pic");
									 $("<span id='phone'>").text(order_item[0]['pay'][0]['phone']).appendTo("p#phone");	
									 /* 
									 */	 
								}		
						 }
									
					});
					$("button#hidden_btn").click(function(e) {
						$("div#return_form").attr("style","z-index:-1;opacity:0;position:fixed; top:52px; left:50px; width:100%; height:auto;");
						$("span#return_name").remove();
						$("span#total_price").remove();
						$("span#express_owner").remove();
						$("span#return_reason").remove();
						$("span#img1").remove();
						$("span#img2").remove();
						$("span#img3").remove();
						$("span#img4").remove();
						$("span#phone").remove();
						$("input#return_price").val("");
						$("input#cost_return").val("");
						$("div#content").attr("style","z-index:1");
					});
					
					$("button#submit_btn").click(function(e) {
						var item_return_name = $("span#return_name").text();
						var return_amount = $("input#return_price").val();
						var deduction_reason = $("input#cost_return").val();
						var return_ajaxobj = {
							type:"POST",
							//url:'controller/sale/order_info.php',
							url:'index.php?route=sale/product_return&token=<?php echo $token; ?>',
							data:{order_id:order,user_id:user,return_item:item_return_name,amount:return_amount,deduction:deduction_reason},
							success:function(data){
								if(data==true)
								{
									alert("退款完成");
									$("div#return_form").attr("style","z-index:-1;opacity:0;position:fixed; top:52px; left:50px; width:100%; height:auto;");
									$("span#return_name").remove();
									$("span#total_price").remove();
									$("span#express_owner").remove();
									$("span#return_reason").remove();
									$("span#img1").remove();
									$("span#img2").remove();
									$("span#img3").remove();
									$("span#img4").remove();
									$("span#phone").remove();
									$("input#return_price").val("");
									$("input#cost_return").val("");
									$("div#content").attr("style","z-index:1");
								}
							}
						};
						$.ajax(return_ajaxobj);
					});
					
			}
		};
		$.ajax(ajaxobj);
    });
  
  </script>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "订单详情"; ?></h3>
      </div>
      <div class="panel-body">
        
        <div class="row">
          <div class="col-sm-12" style="min-width: 100%;overflow: auto;">

          <!-- Address Part -->
          <table style="width: 100%;">
            <tr class="navi-row">
              <td colspan="2">
                <div class="row-header">
                  <span id="order_num">订单号：<?php echo $_GET['order_no']; ?> <!--<?php echo $_GET['order_id']; ?><?php echo $order['order_no'].'10922323232'; ?>--></span>
                </div>
              </td>
            </tr>
            <tr class="navi-row">
              <td class="navi-title"><span>收货信息</span></td>
              <td class="navi-content">
                <div>
                  <form>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>地址：</label>
                        <span id="address"><!--<?php echo $order['order_receiver_address'].'北京市朝阳区xx路120号'; ?> --></span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span  class="entry-group">
                        <label>邮编：</label>
                        <span id="postcode"><!-- <?php echo $order['order_receiver_zipcode'].'100000'; ?>  --></span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>姓名：</label>
                        <span id="reveivername"><!-- <?php echo $order['order_receiver_name'].'李东'; ?> --></span>
                      </span>
                    </div>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>电话：</label>
                        <span id="phone_number"><!-- <?php echo $order['receiver_phone'].'13982819221'; ?> --></span>
                      </span>
                    </div>
                  </form>
                </div>
              </td>
            </tr>
            <tr class="navi-row">
              <td colspan="2" class="navi-content" style="padding-bottom: 0px;">
                <div>
                  <form>
                    <div class="content-row">
                      <span class="entry-group">
                        <label>支付状态：</label>
                        <span id="payment"><!-- <?php echo $order['order_status'].'已支付'; ?> --></span>
                      </span>
                      <span class="entry-group">
                        <label>实付款：</label>
                        <span id="price">
                         <!-- <?php echo $order['order_pay_amount'].'580元'; ?>
                          +
                          <?php echo $order['order_used_score'].'100积分'; ?>
                          (<?php echo $order['order_desc'].'含100元国际包关税快递费'; ?>) -->
                        </span>
                      </span>
                    </div>
                  </form>
                </div>
              </td>
            </tr>
          </table>
          <!-- Store Part -->
         <!-- <?php
          foreach($stores as $store){
            $title = $store['name'];
            $product_list = $store['product_list'];
            ?> -->
          <table style="width: 100%;margin-top: 20px;">
            <tr class="navi-row">
              <td>
                <div class="row-header">
                  <span id="order_listing_doc">订单商品列表</span>
                </div>
              </td>
            </tr>
            <tr class="navi-row" style="border: none;">
              <td>
                <table id="item_detail" style="width: 100%;"  class="product-row">
                  
					
                </table>
              </td>
            </tr>
          </table>
          <!-- <?php } ?> -->


          </div>
        </div>
        
        
        
       
      </div>
    </div>
  </div>
  </div>
 <div class="return_form" id="return_form" style="width:auto; height:auto; border:solid #fff; border-widt:1px;z-index:-1; opacity:0; position:fixed; top:52px; left:50px;">
 	
 	<div id="form">
    	<div align="right"><button type="button" id="hidden_btn" ><span id="icon">X</span> </button></div>
    	<div>
          <p id="name">商品名称：</p>
          <p id="price">商品金额：</p>
          <p id="reason">退货原因：</p>
          <p id="pic">退货图片：</p>
          <p id="cost">退货投递费用支付（客户/卖家)：</p>
          <p id="phone">客户联系电话：</p>
          <p id="return_price">退货金额：<input type="text" id="return_price" /></p>
          <p id="cost_return">扣除费用原因：<input type="text" id="cost_return" /></p>
      	</div>
        <div align="right"><button type="button" id="submit_btn" >提交</button></div>
     </div>
  </div> 
  <div class="express_form" style="visibility:hidden; z-index:-1; ">
    	<input type="button" value="X" id="close_btn" style="float:right;" />
        <br />
    	<form style="float:left">
        	<label>快递公司：</label>
            <select id="express_name" name="express_name" class="express_name">
            	
            </select>
            <br />
            <label>订单号：</label>
            <input type="text" readonly id="order_product_id" />
            <br />
            <input type="text" id="order_item_id" hidden/><br />
            <label>单号：</label><input type="text" id="express_id" size="100px" name="express_id" class="express_id" />
            <input type="button" id='express_submit' class='express_submit' value="提交" />
        </form>
    </div>
  <div class="express_info" style="visibility:hidden; z-index:-1; ">
    	<input type="button" value="X" id="exp_info_close_btn" style="float:right;" />
        <br />
    	<form style="float:left">
        	<label>快递公司：</label>
            <input id="exp_info_express_name" name="exp_info_express_name" />
            <br />
            <label>订单号：</label><input type="text" readonly id="exp_info_order_id" />
            <br />
            <label>单号：</label><input type="text" id="exp_info_express_id" size="100px" name="exp_info_express_id" />
        </form>
    </div>

<?php echo $footer; ?> 
