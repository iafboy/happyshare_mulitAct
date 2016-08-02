<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

<!-- Fix Up for ie 5,6,7,8 -->
  <!--[if lt IE 9]>
  <script src="view/javascript/IE9.js">IE7_PNG_SUFFIX=".png";</script>
  <![endif]-->

<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" />

<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/leshare.css" rel="stylesheet" media="screen" />
  <link href="favicon.ico" mce_href="favicon.ico" rel="bookmark" type="image/x-icon" />
  <link href="favicon.ico" mce_href="favicon.ico" rel="icon" type="image/x-icon" />
  <link href="favicon.ico" mce_href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
<!-- Bootstrap Dialog -->
  <link href="view/javascript/bootstrap-dialog/assets/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
  <link href="view/javascript/bootstrap-dialog/assets/my-dialog.css" rel="stylesheet" />
  <script src="view/javascript/bootstrap-dialog/assets/my-dialog.js"></script>
  <script src="view/javascript/jquery.ui.widget.js"></script>
  <script src="view/javascript/jquery.fileupload.js"></script>
  <!-- Bootstrap Pagination -->
  <script src="view/javascript/jquery.twbsPagination.js"></script>
  <script src="view/javascript/bootstrap-paginator.js"></script>


  <script src="view/javascript/date.js"></script>
  <script src="view/javascript/dateutil.js"></script>
  <script src="view/javascript/jquery.cookie.js"></script>
  <script src="view/javascript/tools.js"></script>
  <script src="view/javascript/admin.js"></script>
  <script src="view/javascript/dialog.js"></script>
  <script src="view/javascript/$.model.js"></script>

<!-- 163 ScrollBar -->
  <!--
<link type="text/css" href="view/stylesheet/163/standard.css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/163/base64_compress.css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/163/geditor.css" rel="stylesheet" media="screen" />
-->
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>




</head>
<body>
<div id="container">
  <?php if($is_login===true){
  }else{
  echo $is_login;
  ?>

  <header id="header" class="navbar navbar-static-top">
    <div class="navbar-header">
      <?php if ($logged) { ?>
      <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
      <?php } ?>
      <a href="<?php echo $home; ?>" class="navbar-brand" style="padding-bottom: 0px;">
        <!--<img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" />-->
        <h3 class="leshare-logo"><?php echo $text_logo; ?></h3>
      </a></div>
    <?php if ($logged) { ?>
	  <!--
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-life-ring fa-lg"></i></a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li class="dropdown-header"><?php echo $text_store; ?> <i class="fa fa-shopping-cart"></i></li>
          <?php foreach ($stores as $store) { ?>
          <li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
          <?php } ?>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_help; ?> <i class="fa fa-life-ring"></i></li>
        </ul>
      </li>-->

    <ul class="nav pull-right">
      <!--
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><span class="label label-danger pull-left"><?php echo $alerts; ?></span> <i class="fa fa-bell fa-lg"></i></a>
        <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
          <li class="dropdown-header"><?php echo $text_order; ?></li>
          <li><a href="<?php echo $order_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $order_status_total; ?></span><?php echo $text_order_status; ?></a></li>
          <li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
          <li><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_customer; ?></li>
          <li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
          <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_product; ?></li>
          <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
          <li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_affiliate; ?></li>
          <li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger pull-right"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
        </ul>
      </li>
      -->
    <!-- 登陆用户信息提示 -->
    <li><a href="<?php echo $login_user; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo "登录为 : ".$text_login_user; ?></span> <i class="fa fa-user fa-lg"></i></a></li>
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg" style="color:red;"></i></a></li>
    </ul>
    <?php } ?>
  </header>



















  <?php } ?>
