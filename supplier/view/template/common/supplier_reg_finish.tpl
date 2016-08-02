<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-repeat"></i> <?php echo $heading_title; ?></h1>
          </div>
          <div class="panel-body" id="company-address">

              <div class="text-danger"><p id="text-warning" style="font-size:18px" ><strong>申请提交成功！我们将在三个工作日内与您联系。</strong></p>
                 <?php $cancel=HTTP_SERVER."index.php?route=common/login" ?>
              <a class="btn btn-default" title="" data-toggle="tooltip" href="<?php echo $cancel ?>" data-original-title="取消"><i class="fa fa-reply"></i></a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo $footer; ?>
