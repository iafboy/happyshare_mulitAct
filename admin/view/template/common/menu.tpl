<ul id="menu">

<li id="dashboard">
<a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a>
</li>

<li id="supplier">
<a class="parent"><i class="fa fa-users fa-fw"></i> <span><?php echo $text_supplier; ?></span></a>
<ul>
<li><a href="<?php echo $supplier_list; ?>"><?php echo $text_supplier_list; ?></a></li>
<li><a href="<?php echo $supplier_apply_list; ?>"><?php echo $text_supplier_apply_list; ?></a></li>
<!--<li><a href="<?php echo $supplier_add; ?>"><?php echo $text_supplier_add; ?></a></li>-->
      <!--<li><a href="<?php echo $supplier_gallery; ?>"><?php echo $text_supplier_gallery; ?></a></li>-->
    </ul>
  </li>
  <li id="report">
    <a class="parent"><i class="fa fa-bar-chart fa-fw"></i> <span><?php echo $text_report_list; ?></span></a>
    <ul>
      <li><a href="<?php echo $report_list; ?>"><?php echo $text_report_list; ?></a></li>
      <li><a href="<?php echo $report_add; ?>"><?php echo $text_report_add; ?></a></li>
      <!--<li><a href="<?php echo $report_pay; ?>"><?php echo $text_report_pay; ?></a></li>-->
    </ul>
  </li>
  <li id="cashreport">
    <a class="parent"><i class="fa fa-signal fa-fw"></i> <span><?php echo $text_cashreport; ?></span></a>
    <ul>
      <li><a href="<?php echo $cashreport_list; ?>"><?php echo $text_cashreport_list; ?></a></li>
      <!--<li><a href="<?php echo $cashreport_pay; ?>"><?php echo $text_cashreport_pay; ?></a></li>-->
    </ul>
  </li>
  <li id="product">
    <a class="parent"><i class="fa fa-cubes fa-fw"></i> <span><?php echo $text_product; ?></span></a>
    <ul>
      <li><a href="<?php echo $product_list; ?>"><?php echo $text_product_list; ?></a></li>
      <!--<li><a href="<?php echo $product_edit; ?>"><?php echo $text_product_edit; ?></a></li>-->
    </ul>
  </li>
  <li id="params">
    <a class="parent"><i class="fa fa-gear fa-fw"></i> <span><?php echo $text_params; ?></span></a>
    <ul>
      <li><a href="<?php echo $params_admin; ?>"><?php echo $text_params_admin; ?></a></li>
    </ul>
  </li>
  <li id="comment">
    <a class="parent"><i class="fa fa-comments fa-fw"></i> <span><?php echo $text_comment; ?></span></a>
    <ul>
      <li><a href="<?php echo $comment_list; ?>"><?php echo $text_comment_list; ?></a></li>
     <!-- <li><a href="<?php echo $comment_key; ?>"><?php echo $text_comment_key; ?></a></li> -->
    </ul>
  </li>
  <li id="order">
    <a class="parent"><i class="fa fa-book fa-fw"></i> <span><?php echo $text_order; ?></span></a>
    <ul>
      <li><a href="<?php echo $order_list; ?>"><?php echo $text_order_list; ?></a></li>
      <!--<li><a href="<?php echo $order_detail; ?>"><?php echo $text_order_detail; ?></a></li>-->
      <!--<li><a href="<?php echo $order_phone; ?>"><?php echo $text_order_phone; ?></a></li>-->
    </ul>
  </li>
  <li id="activity">
    <a class="parent"><i class="fa fa-calendar-o fa-fw"></i> <span><?php echo $text_activity; ?></span></a>
    <ul>
      <li><a href="<?php echo $activity_list; ?>"><?php echo $text_activity_list; ?></a></li>
      <li><a href="<?php echo $activity_special; ?>"><?php echo $text_activity_special; ?></a></li>
      <!--
      <li><a href="<?php echo $activity_free; ?>"><?php echo $text_activity_free; ?></a></li>
      <li><a href="<?php echo $activity_credit; ?>"><?php echo $text_activity_credit; ?></a></li>
      <li><a href="<?php echo $activity_gift; ?>"><?php echo $text_activity_gift; ?></a></li>
      <li><a href="<?php echo $activity_trial; ?>"><?php echo $text_activity_trial; ?></a></li>
      -->
    </ul>
  </li>
  <li id="score">
    <a class="parent"><i class="fa fa-star fa-fw"></i> <span><?php echo $text_score; ?></span></a>
    <ul>
      <li><a href="<?php echo $score_rule; ?>"><?php echo $text_score_rule; ?></a></li>
    </ul>
  </li>
  <li id="banner">
    <a class="parent"><i class="fa fa-bars fa-fw"></i> <span><?php echo $text_banner; ?></span></a>
    <ul>
      <li><a href="<?php echo $banner_admin; ?>"><?php echo $text_banner_admin; ?></a></li>
    </ul>
  </li>
  <!--
  <li id="picwall">
    <a class="parent"><i class="fa fa-picture-o fa-fw"></i> <span><?php echo $text_picwall; ?></span></a>
    <ul>
      <li><a href="<?php echo $picwall_admin; ?>"><?php echo $text_picwall_admin; ?></a></li>
    </ul>
  </li>
  -->
  <li id="statistic">
    <a class="parent"><i class="fa fa-line-chart fa-fw"></i> <span><?php echo $text_statistic; ?></span></a>
    <ul>
      <li><a href="http://tongji.baidu.com/web/18309093/overview/sole?siteId=7786605" target="_blank"><?php echo $text_statistic_report; ?></a></li>
    </ul>
  </li>
  <li id="usermanage">
    <a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_usermanage; ?></span></a>
    <ul>
      <li><a href="<?php echo $user_list; ?>"><?php echo $text_user_list; ?></a></li>

      <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>

    </ul>
  </li>


<!--


  <li id="catalog">
    <a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
      <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
      <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
      <li><a class="parent"><?php echo $text_attribute; ?></a>
        <ul>
          <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
          <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
        </ul>
      </li>
      <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
      <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
    </ul>
  </li>
  -->
  <!--
 <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
   <ul>

     <li><a href="<?php echo $installer; ?>"><?php echo $text_installer; ?></a></li>
     <li><a href="<?php echo $modification; ?>"><?php echo $text_modification; ?></a></li>
     <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>

     <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
     <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>

     <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
     <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
     <?php if ($openbay_show_menu == 1) { ?>
     <li><a class="parent"><?php echo $text_openbay_extension; ?></a>
       <ul>
         <li><a href="<?php echo $openbay_link_extension; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
         <li><a href="<?php echo $openbay_link_orders; ?>"><?php echo $text_openbay_orders; ?></a></li>
         <li><a href="<?php echo $openbay_link_items; ?>"><?php echo $text_openbay_items; ?></a></li>
         <?php if ($openbay_markets['ebay'] == 1) { ?>
         <li><a class="parent"><?php echo $text_openbay_ebay; ?></a>
           <ul>
             <li><a href="<?php echo $openbay_link_ebay; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
             <li><a href="<?php echo $openbay_link_ebay_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
             <li><a href="<?php echo $openbay_link_ebay_links; ?>"><?php echo $text_openbay_links; ?></a></li>
             <li><a href="<?php echo $openbay_link_ebay_orderimport; ?>"><?php echo $text_openbay_order_import; ?></a></li>
           </ul>
         </li>
         <?php } ?>
         <?php if ($openbay_markets['amazon'] == 1) { ?>
         <li><a class="parent"><?php echo $text_openbay_amazon; ?></a>
           <ul>
             <li><a href="<?php echo $openbay_link_amazon; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
             <li><a href="<?php echo $openbay_link_amazon_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
             <li><a href="<?php echo $openbay_link_amazon_links; ?>"><?php echo $text_openbay_links; ?></a></li>
           </ul>
         </li>
         <?php } ?>
         <?php if ($openbay_markets['amazonus'] == 1) { ?>
         <li><a class="parent"><?php echo $text_openbay_amazonus; ?></a>
           <ul>
             <li><a href="<?php echo $openbay_link_amazonus; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
             <li><a href="<?php echo $openbay_link_amazonus_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
             <li><a href="<?php echo $openbay_link_amazonus_links; ?>"><?php echo $text_openbay_links; ?></a></li>
           </ul>
         </li>
         <?php } ?>
         <?php if ($openbay_markets['etsy'] == 1) { ?>
         <li><a class="parent"><?php echo $text_openbay_etsy; ?></a>
           <ul>
             <li><a href="<?php echo $openbay_link_etsy; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
             <li><a href="<?php echo $openbay_link_etsy_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
             <li><a href="<?php echo $openbay_link_etsy_links; ?>"><?php echo $text_openbay_links; ?></a></li>
           </ul>
         </li>
         <?php } ?>
       </ul>
     </li>
     <?php } ?>
     -->
    </ul>
  </li>
  <!--
  <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
    <ul>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a class="parent"><?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
          <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
          <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
          <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_voucher; ?></a>
        <ul>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_paypal ?></a>
        <ul>
          <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="marketing"><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
    <ul>
      <li><a href="<?php echo $marketing; ?>"><?php echo $text_marketing; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
    </ul>
  </li>
  <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul>
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
      <li><a class="parent"><?php echo $text_design; ?></a>
        <ul>
          <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
          <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
        </ul>
      </li>
      <!--<li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
          <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
          <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
          <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
          <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
          <li><a class="parent"><?php echo $text_return; ?></a>
            <ul>
              <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
              <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
              <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
          <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
          <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
          <li><a class="parent"><?php echo $text_tax; ?></a>
            <ul>
              <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
              <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
          <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="tools"><a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
    <ul>
      <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
      <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
      <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
    </ul>
  </li>
  <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul>
      <li><a class="parent"><?php echo $text_sale; ?></a>
        <ul>
          <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
          <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
          <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
          <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
          <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_product; ?></a>
        <ul>
          <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
          <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
          <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
          <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
          <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
          <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_marketing; ?></a>
        <ul>
          <li><a href="<?php echo $report_marketing; ?>"><?php echo $text_marketing; ?></a></li>
          <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_report_affiliate; ?></a></li>
          <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_report_affiliate_activity; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="others"><a class="parent"><i class="fa fa-key fa-fw"></i> <span><?php echo $text_others; ?></span></a>
  </li>
-->
</ul>
