<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="container-fluid">
                <div class="row">
                    <?php if ($success) { ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php } ?>
                    <?php if ($error_warning) { ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php } ?>

                    <div class="row" style="border: 1px solid #e4e4e4;padding: 5px;">
                    <div class="col-sm-5" style="height: 206px;">
                        <div style="width: 100%;height: 100%;background-color: #666;">
                            <img src="../image/supplier_login.jpg" width="100%" height="100%" alt="USER" />
                        </div>
                    </div>
                    <div class="col-sm-7">

                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="input-username"><?php echo $entry_username; ?></label>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-password"><?php echo $entry_password; ?></label>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                </div>
                            </div>

                            <div style="display:block;float:left;">
                                <span><a href="<?php echo $supplier_reg; ?>"><?php echo $text_register; ?></a></span>
                            </div>
                            <div style="display:block;float:right;">
                                <span><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
                            </div>

                            <div class="text-center" style="clear:both">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> <?php echo $button_login; ?></button>
                            </div>
                            <?php if ($redirect) { ?>
                            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                            <?php } ?>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
  </div>
</div>
<?php echo $footer; ?>
