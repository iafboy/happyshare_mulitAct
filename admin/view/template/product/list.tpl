<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--<div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>-->
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) {
            if($breadcrumb['type']=='link'){
              echo "<li><a href=".$breadcrumb['href'].">".$breadcrumb['text']."</a></li>";
            }else{
              echo "<li><span class='breadcrumb-cur'>".$breadcrumb['text']."</span></li>";
            }
        } ?>
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
    <?php echo $entries ?>
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-supplier">
      <div class="table-responsive">
        <!--<table class="table table-bordered table-hover">-->
        <table class="table table-bordered"  style="table-layout:fixed">
          <col style="width: 10%" />
          <col style="width: 15%" />
          <thead>
          <tr>
            <?php echo $theader; ?>
          </tr>
          </thead>
          <tbody>
          <?php if ($products) { ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="text-center" title="<?php echo $product['product_no'];?>" style="word-break:break-all;"><?php echo $product['product_no']; ?></td>
            <td class="text-center" title="<?php echo $product['product_name']; ?>" style="word-break:break-all;"><?php echo $product['product_name']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $status_product_status[$product['status']]; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['supplier_name']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['place_name']; ?></td>
            <td class="text-center" title="<?php echo $product['type_name']; ?>" style="word-break:break-all;" ><?php echo $product['type_name']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['market_price']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['price']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['storeprice']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['credit']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['quantity']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['salesCount']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['shareCount']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['commentCount']; ?></td>
            <td class="text-center" style="word-break:break-all;"><?php echo $product['shareLevel']; ?></td>
            <td class="text-center">
              <button class="btn btn-xs lfx-btn" type="button" onclick="editProduct('<?php echo $product['product_id']; ?>')">编辑</button>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="15"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
<script type="text/javascript">
  function editProduct(productId){
    var url = '<?php echo html_entity_decode($product_edit_url); ?>';
    var page = GetQueryString("page");
    location.href = url + '&product_id='+ productId + '&page=' + page;
  }

</script>
