<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--
      <div class="pull-right">
        <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      -->
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
				<script language="javascript" type="text/javascript">
                    $(document).ready(function(e) {
						//search index
						// 订单类型
						   var supplier = "<?php echo $_SESSION['supplier_id'];  ?>";
							var token_num = "<?php echo $_GET['token']; ?>";
							  var orderid = document.getElementById("input-order-id").value;
							 var ordertype = $("select#input-order-type option:selected").value;
							  var customer = document.getElementById("input-customer").value;
							  var orderstatus = document.getElementById("input-order-status").value;
							  var recipient = document.getElementById("input-recipient").value;
							  var phone = document.getElementById("input-recipient-phone").value;
							  var start_date = document.getElementById("input-date-start").value;
							  var end_date = document.getElementById("input-date-end").value;
							  var today_date = "<?php echo $date = date('Y-m-d')?>";

							if(orderid==null||orderid=="")
							{
								orderid = ""; //默认空值
							}
							if(ordertype==null||ordertype=="")
							{
								ordertype = "";  //默认通用类型
							}
							if(customer==null||customer=="")
							{
								customer =""; //默认空值
							}
							if(orderstatus==null||orderstatus=="")
							{
								orderstatus = ""; //默认所有
							}
							if(recipient==null||recipient=="")
							{
								recipient = "";//默认空值
							}
							if(phone==null||phone=="")
							{
								phone = "";//默认空号
							}
							if(start_date==null||start_date=="")
							{
								start_date = ""; //默认空值
							}
							if(end_date==null||end_date=="")
							{
								//end_date = today_date; //默认结束日期为今日
								end_date = "";
							}
							
						var txt = "{'orderid':'"+ orderid +"','ordertype':'"+ordertype+"','orderstatus':'"+orderstatus+"','customer':'"+customer+"','recipient':'"+recipient+"','phone':'"+phone+"','start_date':'"+start_date+"','end_date':'"+end_date+"','supplier':'"+supplier+"'}";
							var jsondata = eval('('+txt+')');
              //alert(jsondata.orderid);
              //alert(jsondata.supplier);
              //alert(txt);
							var ajaxobj ={
										type:'POST',
										//url:'controller/sale/ordersearch.php&token=<?php echo $_GET["token"]; ?>',
                    url:'index.php?route=sale/ordersearch&token=<?php echo $token; ?>',
										data:{order_search:jsondata,supplierid:supplier},
										success:function(data){
										  var orderdetail = eval('('+data+')');
              //console. debug(orderdetail.length);
              //alert(orderdetail.length);
			  								
											var i = 0;
											var p = 1; //起始页
											$('#search_list tr').remove();
											//var tr = $("<tr>").appendTo($("#search_list tbody"));
											
						  				var base_row = "<?php echo $page_limit; ?>"; //每页基数
											var rows = orderdetail.length;
											var pages = Math.ceil(rows/base_row);
											//分页
											var tr_pages=$("<tr id='listing' >").appendTo($("tbody#search_list"));					
											var td_pages = $("<td id='listing'>").appendTo(tr_pages);
                      if(rows > base_row){
											  var ul_pages_btn = $("<ul class='pagination' id='pages'>").appendTo($("div#page_control"));
                      }
                      var currentrows = (rows > base_row) ? base_row : rows;
											var notice = $("<span id='page_total'>").text("显示1到"+currentrows+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
											if(pages>1)
											{
												var li_first = $("<li class='pages_skip' name='firstpage' id='0' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"|&lt"+"</span>").appendTo(li_first);
												var li_pre = $("<li class='pages_control' name='backward' id='0' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"&lt"+"</span>").appendTo(li_pre);
												for(j=1;j<=pages;j++)
												{
                            var li = $("<li class='pages_num' name='"+j+"' id="+j+" style='cursor:pointer;float:left;list-style:none;'>");
                          	$("<span>"+j+"</span>").appendTo(li);
                          	li.appendTo(ul_pages_btn);
												}
												var li_next = $("<li class='pages_control' name='forward' id='1' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"&gt"+"</span>").appendTo(li_next);
												var li_last = $("<li class='pages_skip' name='firstpage' id='1' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"|&gt"+"</span>").appendTo(li_last);
											}
											
											//翻页控制
											$("li.pages_control").click(function(e) {
                                                var f_b = this.id;
												if(f_b == 1)
												{
													var currentpage = $("div.pages").attr('id');
													if(currentpage<=pages)
													{
														currentpage++;
														var p = currentpage;
														if(p>pages)
														{
															p=pages;
														}
													}
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}												
												}
												else if(f_b==0)
												{
													var currentpage = $("div.pages").attr('id');
													if(currentpage<=pages)
													{
														currentpage--;
														var p = currentpage;
														if(p<1)
														{
															p = 1;
														}
													}
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}								
												}
                                            });
											//翻页控制结束
											//首页尾页控制
											$("li.pages_skip").click(function(e) {
                                                
                                                var f_b = this.id;
												if(f_b == 1)
												{
													var p=pages;
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}												
												}
												else if(f_b==0)
												{
													var p = 1;
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}								
												}
                                            });
											//跳页控制结束
											
											
											//页面控制
											$("li.pages_num").click(function(e) {
												var num = this.id;
												p = num;
												$("div.pages").remove();
												$("span#page_total").remove();
						   						
												for(j=1;j<=pages;j++)
												{
													$("li#"+j).removeClass('active');
													if(j == p){
												  		$("li#"+j).addClass('active');
													} 
												}
												//显示提示信息
												var row_from = (p-1)*base_row+1;
												var row_to = (p*base_row > rows) ? rows : (p*base_row);
											  	var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
												//显示提示信息结束
												var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
												var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
												var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
												var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
												var td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单编号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                           						$("<a>").text("订单状态").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("会员账号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("收件人").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("联系电话").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("送货地址").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单金额").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("下单时间").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("说明").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("操作").appendTo(td)
												//$("<td class='text-center'>").text("订单编号").appendTo(tr);
												var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
												
												i = (p-1)*base_row ;
												var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
												
												//if(i>=base_row*p)
												if(i>=lastrecord)
												{
													i = 0;
												}
												//while(i<base_row*p)
												while(i<lastrecord)
												{
													
														var orderID = orderdetail[i].order;
														var order_id = orderdetail[i].orderid;
														var order_status = orderdetail[i].orderstatus_name;
														var status_id = orderdetail[i].orderstatus;
														var status_control;
														var order_customer = orderdetail[i].customerfullname;
														var receiver_name = orderdetail[i].receiver;
														var phone = orderdetail[i].receiver_phone;
														var deliveryaddress = orderdetail[i].address;
														var orderprice = orderdetail[i].price;
														var date = orderdetail[i].datetime;
														var comments = orderdetail[i].comment;
														var customer_id = orderdetail[i].customerid;
														var tr = $("<tr id='items'>").appendTo(tbody_pages);											
														var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
							  							var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
														order_list_num;
														 
														
														$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
														$("<td>").text(order_status).appendTo(tr);
														$("<td>").text(order_customer).appendTo(tr);
														$("<td>").text(receiver_name).appendTo(tr);
														$("<td>").text(phone).appendTo(tr);
														$("<td>").text(deliveryaddress).appendTo(tr);
														$("<td>").text(orderprice).appendTo(tr);
														$("<td>").text(date).appendTo(tr);
														$("<td id='message'>").text(comments).appendTo(tr);
														var order_control_btn=$("<td id='control'>").appendTo(tr);
														order_control_btn;
													i++;
												}											
											});
											var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
											var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
												var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
												var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
												var td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单编号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                           						$("<a>").text("订单状态").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("会员账号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("收件人").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("联系电话").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("送货地址").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单金额").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("下单时间").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("说明").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("操作").appendTo(td)
												//$("<td class='text-center'>").text("订单编号").appendTo(tr);
												var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
												for(;i<base_row*p;)
												{
													
														var orderID = orderdetail[i].order;
														var order_id = orderdetail[i].orderid;
														var order_status = orderdetail[i].orderstatus_name;
														var status_id = orderdetail[i].orderstatus;
														var status_control;
														var order_customer = orderdetail[i].customerfullname;
														var receiver_name = orderdetail[i].receiver;
														var phone = orderdetail[i].receiver_phone;
														var deliveryaddress = orderdetail[i].address;
														var orderprice = orderdetail[i].price;
														var date = orderdetail[i].datetime;
														var comments = orderdetail[i].comment;
														var customer_id = orderdetail[i].customerid;
														var tr = $("<tr id='items'>").appendTo(tbody_pages);											
														var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
							  							var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
														order_list_num;
														 
														
														$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
														$("<td>").text(order_status).appendTo(tr);
														$("<td>").text(order_customer).appendTo(tr);
														$("<td>").text(receiver_name).appendTo(tr);
														$("<td>").text(phone).appendTo(tr);
														$("<td>").text(deliveryaddress).appendTo(tr);
														$("<td>").text(orderprice).appendTo(tr);
														$("<td>").text(date).appendTo(tr);
														$("<td id='message'>").text(comments).appendTo(tr);
														var order_control_btn=$("<td id='control'>").appendTo(tr);
														order_control_btn;
														i++;
												}							
												
											 
											//分页结束
											
										}
									};
							$.ajax(ajaxobj);
							
						//自动搜索结束
						
						
						
						// search button 
                        $("#button-filter").click(function(e) {
							 var supplier = "<?php echo $_SESSION['supplier_id'];  ?>";
							var token_num = "<?php echo $_GET['token']; ?>";
							  var orderid = document.getElementById("input-order-id").value;
							 var ordertype = $("select#input-order-type option:selected").value;
							  var customer = document.getElementById("input-customer").value;
							  var orderstatus = document.getElementById("input-order-status").value;
							  var recipient = document.getElementById("input-recipient").value;
							  var phone = document.getElementById("input-recipient-phone").value;
							  var start_date = document.getElementById("input-date-start").value;
							  var end_date = document.getElementById("input-date-end").value;
							  var today_date = "<?php echo $date = date('Y-m-d')?>";

							if(orderid==null||orderid=="")
							{
								orderid = ""; //默认空值
							}
							if(ordertype==null||ordertype=="")
							{
								ordertype = "";  //默认通用类型
							}
							if(customer==null||customer=="")
							{
								customer =""; //默认空值
							}
							if(orderstatus==null||orderstatus=="")
							{
								orderstatus = ""; //默认所有
							}
							if(recipient==null||recipient=="")
							{
								recipient = "";//默认空值
							}
							if(phone==null||phone=="")
							{
								phone = "";//默认空号
							}
							if(start_date==null||start_date=="")
							{
								start_date = ""; //默认空值
							}
							if(end_date==null||end_date=="")
							{
								//end_date = today_date; //默认结束日期为今日
								end_date = "";
							}
							
						var txt = "{'orderid':'"+ orderid +"','ordertype':'"+ordertype+"','orderstatus':'"+orderstatus+"','customer':'"+customer+"','recipient':'"+recipient+"','phone':'"+phone+"','start_date':'"+start_date+"','end_date':'"+end_date+"','supplier':'"+supplier+"'}";
							var jsondata = eval('('+txt+')');
              //alert(jsondata.orderid);
              //alert(jsondata.supplier);
              //alert(txt);
							var ajaxobj ={
										type:'POST',
										//url:'controller/sale/ordersearch.php&token=<?php echo $_GET["token"]; ?>',
                    url:'index.php?route=sale/ordersearch&token=<?php echo $token; ?>',
										data:{order_search:jsondata,supplierid:supplier},
										success:function(data){
										  var orderdetail = eval('('+data+')');
              //console. debug(orderdetail.length);
              //alert(orderdetail.length);
			  								
											var i = 0;
											var p = 1; //起始页
											$('#search_list tr').remove();
											$('span#page_total').remove();
											$('ul.pagination').remove();
											//var tr = $("<tr>").appendTo($("#search_list tbody"));
											
						  				var base_row = "<?php echo $page_limit; ?>"; //每页基数
											var rows = orderdetail.length;
											var pages = Math.ceil(rows/base_row);
											//分页
											var tr_pages=$("<tr id='listing' >").appendTo($("tbody#search_list"));					
											var td_pages = $("<td id='listing'>").appendTo(tr_pages);
											
                      if(rows > base_row){
                        var ul_pages_btn = $("<ul class='pagination' id='pages'>").appendTo($("div#page_control"));
                      }
	                    var currentrows = (rows > base_row) ? base_row : rows;
											var notice = $("<span id='page_total'>").text("显示1到"+currentrows+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
											//var notice = $("<span id='page_total'>").text("显示1到20/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
											if(pages>1)
											{
												var li_first = $("<li class='pages_skip' name='firstpage' id='0' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"|&lt"+"</span>").appendTo(li_first);
												var li_pre = $("<li class='pages_control' name='backward' id='0' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"&lt"+"</span>").appendTo(li_pre);
												for(j=1;j<=pages;j++)
												{
                            var li = $("<li class='pages_num' name='"+j+"' id="+j+" style='cursor:pointer;float:left;list-style:none;'>");
                          	$("<span>"+j+"</span>").appendTo(li);
                          	li.appendTo(ul_pages_btn);
												}
												var li_next = $("<li class='pages_control' name='forward' id='1' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"&gt"+"</span>").appendTo(li_next);
												var li_last = $("<li class='pages_skip' name='firstpage' id='1' style='cursor:pointer;float:left;list-style:none;'>").appendTo(ul_pages_btn);
                        $("<span>"+"|&gt"+"</span>").appendTo(li_last);
											}
											
											//翻页控制
											$("li.pages_control").click(function(e) {
                                                var f_b = this.id;
												if(f_b == 1)
												{
													var currentpage = $("div.pages").attr('id');
													if(currentpage<=pages)
													{
														currentpage++;
														var p = currentpage;
														if(p>pages)
														{
															p=pages;
														}
													}
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}												
												}
												else if(f_b==0)
												{
													var currentpage = $("div.pages").attr('id');
													if(currentpage<=pages)
													{
														currentpage--;
														var p = currentpage;
														if(p<1)
														{
															p = 1;
														}
													}
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}								
												}
                                            });
											//翻页控制结束
											//首页尾页控制
											$("li.pages_skip").click(function(e) {
                                                
                                                var f_b = this.id;
												if(f_b == 1)
												{
													var p=pages;
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}												
												}
												else if(f_b==0)
												{
													var p = 1;
													$("div.pages").remove();
													$("span#page_total").remove();
													
													for(j=1;j<=pages;j++)
													{
														$("li#"+j).removeClass('active');
														if(j == p){
															$("li#"+j).addClass('active');
														} 
													}
													//显示提示信息
													var row_from = (p-1)*base_row+1;
													var row_to = (p*base_row > rows) ? rows : (p*base_row);
													var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
													//显示提示信息结束
													var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
													var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
													var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
													var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
													var td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单编号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单状态").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("会员账号").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("收件人").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("联系电话").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("送货地址").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("订单金额").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("下单时间").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("说明").appendTo(td)
													td = $("<td class='text-center'>").appendTo(tr);
													$("<a>").text("操作").appendTo(td)
													//$("<td class='text-center'>").text("订单编号").appendTo(tr);
													var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
													
													i = (p-1)*base_row ;
													var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
													
													//if(i>=base_row*p)
													if(i>=lastrecord)
													{
														i = 0;
													}
													//while(i<base_row*p)
													while(i<lastrecord)
													{
														
															var orderID = orderdetail[i].order;
															var order_id = orderdetail[i].orderid;
															var order_status = orderdetail[i].orderstatus_name;
															var status_id = orderdetail[i].orderstatus;
															var status_control;
															var order_customer = orderdetail[i].customerfullname;
															var receiver_name = orderdetail[i].receiver;
															var phone = orderdetail[i].receiver_phone;
															var deliveryaddress = orderdetail[i].address;
															var orderprice = orderdetail[i].price;
															var date = orderdetail[i].datetime;
															var comments = orderdetail[i].comment;
															var customer_id = orderdetail[i].customerid;
															var tr = $("<tr id='items'>").appendTo(tbody_pages);											
															var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
															var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
															order_list_num;
															 
															
															$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
															$("<td>").text(order_status).appendTo(tr);
															$("<td>").text(order_customer).appendTo(tr);
															$("<td>").text(receiver_name).appendTo(tr);
															$("<td>").text(phone).appendTo(tr);
															$("<td>").text(deliveryaddress).appendTo(tr);
															$("<td>").text(orderprice).appendTo(tr);
															$("<td>").text(date).appendTo(tr);
															$("<td id='message'>").text(comments).appendTo(tr);
															var order_control_btn=$("<td id='control'>").appendTo(tr);
															order_control_btn;
														i++;
													}								
												}
                                            });
											//跳页控制结束
											
											
											//页面控制
											$("li.pages_num").click(function(e) {
												var num = this.id;
												p = num;
												$("div.pages").remove();
												$("span#page_total").remove();
						   						
												for(j=1;j<=pages;j++)
												{
													$("li#"+j).removeClass('active');
													if(j == p){
												  		$("li#"+j).addClass('active');
													} 
												}
												//显示提示信息
												var row_from = (p-1)*base_row+1;
												var row_to = (p*base_row > rows) ? rows : (p*base_row);
											  	var notice = $("<span id='page_total'>").text("显示"+row_from+"到"+row_to+"/"+rows+"(总"+pages+"页)").appendTo($("div#page_total"));
												//显示提示信息结束
												var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
												var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
												var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
												var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
												var td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单编号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                           						$("<a>").text("订单状态").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("会员账号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("收件人").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("联系电话").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("送货地址").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单金额").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("下单时间").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("说明").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("操作").appendTo(td)
												//$("<td class='text-center'>").text("订单编号").appendTo(tr);
												var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
												
												i = (p-1)*base_row ;
												var lastrecord = (rows>(base_row*p))?(base_row*p):rows;
												
												//if(i>=base_row*p)
												if(i>=lastrecord)
												{
													i = 0;
												}
												//while(i<base_row*p)
												while(i<lastrecord)
												{
													
														var orderID = orderdetail[i].order;
														var order_id = orderdetail[i].orderid;
														var order_status = orderdetail[i].orderstatus_name;
														var status_id = orderdetail[i].orderstatus;
														var status_control;
														var order_customer = orderdetail[i].customerfullname;
														var receiver_name = orderdetail[i].receiver;
														var phone = orderdetail[i].receiver_phone;
														var deliveryaddress = orderdetail[i].address;
														var orderprice = orderdetail[i].price;
														var date = orderdetail[i].datetime;
														var comments = orderdetail[i].comment;
														var customer_id = orderdetail[i].customerid;
														var tr = $("<tr id='items'>").appendTo(tbody_pages);											
														var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
							  							var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
														order_list_num;
														 
														
														$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
														$("<td>").text(order_status).appendTo(tr);
														$("<td>").text(order_customer).appendTo(tr);
														$("<td>").text(receiver_name).appendTo(tr);
														$("<td>").text(phone).appendTo(tr);
														$("<td>").text(deliveryaddress).appendTo(tr);
														$("<td>").text(orderprice).appendTo(tr);
														$("<td>").text(date).appendTo(tr);
														$("<td id='message'>").text(comments).appendTo(tr);
														var order_control_btn=$("<td id='control'>").appendTo(tr);
														order_control_btn;
													i++;
												}											
											});
											var div_pages = $("<div class='pages' id='"+p+"' >").appendTo(td_pages);	
											var table_pages = $("<table id='"+p+"' class='table table-bordered table-hover' >").appendTo(div_pages);
												var thead_pages = $("<thead id='pages_title'>").appendTo(table_pages);
												var tr = $("<tr id='pages_title'>").appendTo(thead_pages);
												var td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单编号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                           						$("<a>").text("订单状态").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("会员账号").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("收件人").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("联系电话").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("送货地址").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("订单金额").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("下单时间").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("说明").appendTo(td)
												td = $("<td class='text-center'>").appendTo(tr);
                          						$("<a>").text("操作").appendTo(td)
												//$("<td class='text-center'>").text("订单编号").appendTo(tr);
												var tbody_pages = $("<tbody id='"+p+"'>").appendTo(table_pages);
												for(;i<base_row*p;)
												{
													
														var orderID = orderdetail[i].order;
														var order_id = orderdetail[i].orderid;
														var order_status = orderdetail[i].orderstatus_name;
														var status_id = orderdetail[i].orderstatus;
														var status_control;
														var order_customer = orderdetail[i].customerfullname;
														var receiver_name = orderdetail[i].receiver;
														var phone = orderdetail[i].receiver_phone;
														var deliveryaddress = orderdetail[i].address;
														var orderprice = orderdetail[i].price;
														var date = orderdetail[i].datetime;
														var comments = orderdetail[i].comment;
														var customer_id = orderdetail[i].customerid;
														var tr = $("<tr id='items'>").appendTo(tbody_pages);											
														var order_list_num = $("<td class='text-center' id='order_list'>").appendTo(tr);
							  							var webaddr = "<?php echo HTTP_CATALOG.'supplier/'; ?>";
														order_list_num;
														 
														
														$("<a href='"+webaddr+"index.php?route=sale/order/info&token="+token_num+"&order_id="+orderID+"&supplier_id="+supplier+"&order_no="+order_id+"'>").text(order_id).appendTo(order_list_num);
														$("<td>").text(order_status).appendTo(tr);
														$("<td>").text(order_customer).appendTo(tr);
														$("<td>").text(receiver_name).appendTo(tr);
														$("<td>").text(phone).appendTo(tr);
														$("<td>").text(deliveryaddress).appendTo(tr);
														$("<td>").text(orderprice).appendTo(tr);
														$("<td>").text(date).appendTo(tr);
														$("<td id='message'>").text(comments).appendTo(tr);
														var order_control_btn=$("<td id='control'>").appendTo(tr);
														order_control_btn;
														i++;
												}							
												
											 
											//分页结束
											
										}
									};
							$.ajax(ajaxobj);
                        });
						//搜索结束
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
					
						
						
						
                   });
                </script>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-order-id" ><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $text_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
				  <!--
                <label class="control-label" for="input-order-type"><?php echo $entry_order_type; ?></label>
                <select name="filter_order_type" placeholder="<?php echo $text_order_type; ?>" id="input-order-type" class="form-control" >
                	<option value="">全部</option>
                </select>
                -->
				<input type="hidden" id="input-order-type" value=""/>
             <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $text_customer; ?>" id="input-customer" class="form-control" />
              </div>
				</div>
				  <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                 
                 <option value="" selected="selected">全部</option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="input-recipient"><?php echo $entry_recipient; ?></label>
                <input type="text" name="filter_recipent" value="<?php echo $filter_recipient; ?>" placeholder="<?php echo $text_recipient; ?>" id="input-recipient" class="form-control" />
              </div>
					  </div>
			  <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>

               <div class="form-group">
                <label class="control-label" for="input-recipient-phone"><?php echo $entry_recipient_phone; ?></label>
                <input type="text" name="filter_recipent_phone" value="<?php echo $filter_recipient_phone; ?>" placeholder="<?php echo $text_recipient_phone; ?>" id="input-recipient-phone" class="form-control" />
              </div>
			  </div>
			  <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "&nbsp"; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
          </div>


            <!--
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
            -->

          </div>
          <div class="row" style="text-align:center">
            <button type="button" id="button-filter" class="btn btn-primary" style="margin-top:10px"><i class="fa fa-search"></i> <?php echo $button_query; ?></button>
          </div>

        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table id="search_list" class="table table-bordered table-hover">
              <thead>
                <tr>
                  
                  <!--
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  -->
                  <td class="text-center"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'o.recipient') { ?>
                    <a href="<?php echo $sort_recipient; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_recipient; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_recipient; ?>"><?php echo $column_recipient; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'o.recipient_phone') { ?>
                    <a href="<?php echo $sort_recipient_phone; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_recipient_phone; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_recipient_phone; ?>"><?php echo $column_recipient_phone; ?></a>
                    <?php } ?></td>
                  <td class="text-center">
                    <a href="<?php echo $sort_recipient_address; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_recipient_address; ?></a>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo $sort_order_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_price; ?></a>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo $sort_order_info; ?>" ><?php echo $column_order_info; ?></a>
                  </td>
                  <!--
                  <td class="text-right"><?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
                  -->
                  <td class="text-center">
                    <a class="<?php echo ""; ?>"><?php echo $column_action; ?></a>
                  </td>
                </tr>
              </thead>
              <tbody id="search_list">
              
                
                <!--
                <tr>
                  <?php if ($orders) { ?>
                	<?php foreach ($orders as $order) { ?>
                  <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" /></td>
                     <td class="text-right"><?php echo $order['order_id']; ?></td>
                  <td class="text-left"><?php echo $order['status']; ?></td>
                  <td class="text-left"><?php echo $order['customer']; ?></td>
                  <td class="text-right"><?php echo $order['recipient']; ?></td>
                  <td class="text-right"><?php echo $order['recipient_phone']; ?></td>
                  <td class="text-right"><?php echo $order['recipient_address']; ?></td>
                  <td class="text-right"><?php echo $order['order_price']; ?></td>
                  <td class="text-right"><?php echo $order['date']; ?></td>
                  <td class="text-right"><?php echo $order['order_info']; ?></td>
                  -->
 <!-- 表头title -->
                 
 
                  <!--
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-left"><?php echo $order['date_modified']; ?></td>
                  -->
 <!-- 订单详情 -->
                  <!-- 
                  <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $order['delete']; ?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
                -->
              </tbody>
            </table>
          </div>
        </form>
        <!-- 页数控制 -->
        <div class="row">
          <div class="col-sm-6 text-left" id="page_control"></div>
          <div class="col-sm-6 text-right" id="page_total"></div>
        </div>
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
            <label>订单号：</label><input type="text" readonly id="order_id" />
            <br />
            <label>单号：</label><input type="text" id="express_id" size="100px" name="express_id" class="express_id" />
            <input type="button" id='submit' class='submit' value="提交" />
        </form>
    </div>
    <div class="check_delivery_steps" style="visibility:hidden;z-index:-1; ">
    		<input type="button" value="X" id="close_btn" style="float:right;" />
        <br />
    </div>
  </div>
  <script language="javascript" type="text/javascript">
		$(document).ready(function(e) {
			var supplier = "<?php echo $_SESSION['supplier_id'];  ?>";
			var token_num = "<?php echo $_GET['token']; ?>";
			var ajax_obj = {
				url:"index.php?route=sale/shipment&token="+token_num,
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
			$.ajax(ajax_obj);
			$('input#submit').click(function(e) {
                		var option_txt = $('select#express_name option:selected').val();
						var orderID = $('input#order_id').val();
						var delivery_code = $('input#express_id').val();
						var ajax_obj2 = {
							type:'post',
							data:{express_id:option_txt,orderid:orderID,code:delivery_code,suppliers:supplier},
							url:"index.php?route=sale/shippingcoderecord&token="+token_num,
							success:function(data){
							}
						};
						$.ajax(ajax_obj2);
            });
		});
  </script>
  <script type="text/javascript"><!--
/* $('#button-filter').on('click', function() {
	url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status = $('select[name=\'filter_order_status\']').val();
	
	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}	

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
				
	location = url;
}); */
//--></script> 
  <script type="text/javascript"> <!--
  /* $('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}	
});  */
//--></script> 
  <script type="text/javascript"><!--
   /*
   	$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);
	
	var selected = $('input[name^=\'selected\']:checked');
	
	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}
	
	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);
			
			break;
		}
	}
});

$('input[name^=\'selected\']:first').trigger('change');

$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		location = $(this).attr('href');
	}
}); */
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>





<script language="javascript" type="text/javascript">
	$(document).ready(function(e) {
        $("li").mouseover(function(e) {
             var num = this.id;
			//alert(num);
        });
		
    });

</script>
























<?php echo $footer; ?>
