<div class="" style="margin-bottom: 20px;">
  <div class="row"  style="margin-bottom: 0px;">
    <form id="<?php echo $form_id; ?>" action="<?php echo $base_url; ?>">
        <input type="hidden" name="route" value="<?php echo $route; ?>" />
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
      <?php for($i = 0; $i < $entry_column_count; $i++){ ?>
        <div class="col-sm-<?php echo $entry_column_size; ?>">
        <?php
        foreach($entries[$i] as $entry){
            if(is_array($entry) && is_array($entry[0])){
                $index = 0;
                foreach($entry as $e){
                    if($index++ == 1){ ?>
                        <span style="display: inline-block;width: 2%;">-</span>
                    <?php }
                    if($e['type']=='select'){
                          $options = $e['options'];
                        ?>
                    <div class="form-group" style="display: inline-block;width: 45%;">
                        <label class="control-label" ><?php echo $e['entry_name']; ?></label>
                        <select name="<?php echo $e['filter_name']; ?>" class="lfx-select w-10">
                            <?php
                                foreach($options as $key=>$value){
                                    if($key.'' === ''.$e['filter_value']){ ?>
                                        <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                                    <?php }
                                }?>
                        </select>
                    </div>
                    <?php }else if($e['type']=='date'){ ?>
                        <div class="form-group" style="display: inline-block;width: 45%;">
                            <label class="control-label" ><?php echo $e['entry_name']; ?></label>
                            <div class="date" style="display: inline-block;">
                                <input style="display: inline-block;width: 70%;" type="text" name="<?php echo $e['filter_name']; ?>"
                                       value="<?php echo $e['filter_value']; ?>"
                                       data-date-format="YYYY-MM-DD" class="lfx-text" />
                                    <span class="" style="width: 30%;">
                                    <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                            </div>
                        </div>
                    <?php }else if($e['type']=='submit'){ ?>
                        <div class="form-group" style="display: inline-block;width: 45%;">
                            <button class="<?php echo $e['entry']; ?> lfx-btn"><?php echo $e['entry_text']; ?></button>
                        </div>
                    <?php }else if($e['type']=='checkbox'){ ?>
                        <div class="form-group" style="display: inline-block;width: 45%;">
                            <label class="control-label" ><?php echo $e['entry_name']; ?></label>
                            <?php
                            if($e['filter_value']===true || $e['filter_value']===1 || $e['filter_value'] === '1'){ ?>
                                <input type="<?php echo $e['type'] ?>"
                                       name="<?php echo $e['filter_name']; ?>" checked class="form-control" />
                            <?php }else{ ?>
                                <input type="<?php echo $e['type'] ?>"
                                       name="<?php echo $e['filter_name']; ?>" class="form-control" />
                            <?php }
                             ?>
                        </div>
                    <?php }else{ ?>
                        <div class="form-group" style="display: inline-block;width: 45%;">
                            <label class="control-label" ><?php echo $e['entry_name']; ?></label>
                            <input type="<?php echo $e['type'] ?>"
                                   name="<?php echo $e['filter_name']; ?>" value="<?php echo $e['filter_value']; ?>" class="lfx-text w-10" />
                        </div>
                    <?php }
                }?>

            <?php }else{ ?>
                <?php if($entry['type']=='select'){
                  $options = $entry['options'];
                ?>
                <div class="form-group">
                    <label class="control-label" ><?php echo $entry['entry_name']; ?></label>
                    <select name="<?php echo $entry['filter_name']; ?>" class="lfx-select w-10">
                        <?php
                        foreach($options as $key=>$value){
                        if($key.'' === ''.$entry['filter_value']){ ?>
                        <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                        <?php }else{ ?>
                        <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                        <?php }
                        }?>
                    </select>
                </div>
                <?php }else if($entry['type']=='date'){ ?>
                <div class="form-group">
                    <label class="control-label" ><?php echo $entry['entry_name']; ?></label>
                    <div class="date" style="display: inline-block;">
                        <input style="display: inline-block;width: 70%;" type="text" name="<?php echo $entry['filter_name']; ?>"
                               value="<?php echo $entry['filter_value']; ?>"
                               data-date-format="YYYY-MM-DD" class="lfx-text" />
                        <span class="" style="width: 30%;">
                        <button class="lfx-btn" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <?php }else if($entry['type']=='submit'){ ?>
                    <div class="form-group">
                        <button class="<?php echo $entry['entry']; ?> lfx-btn"><?php echo $entry['entry_text']; ?></button>
                    </div>
                <?php }else if($entry['type']=='checkbox'){ ?>
                <div class="form-group" style="display: inline-block;width: 45%;">
                    <label class="control-label" ><?php echo $entry['entry_name']; ?></label>
                    <?php
                    if($entry['filter_value']===true || $entry['filter_value']===1 || $entry['filter_value'] === '1'){ ?>
                        <input type="<?php echo $entry['type'] ?>"
                               name="<?php echo $entry['filter_name']; ?>" checked class="form-control" />
                    <?php }else{ ?>
                        <input type="<?php echo $entry['type'] ?>"
                               name="<?php echo $entry['filter_name']; ?>" class="form-control" />
                    <?php }
                     ?>
                </div>
                <?php }else{ ?>
                <div class="form-group">
                    <label class="control-label" ><?php echo $entry['entry_name']; ?></label>
                    <input type="<?php echo $entry['type'] ?>" placeholder="<?php echo $entry['entry_name']; ?>"
                           name="<?php echo $entry['filter_name']; ?>"
                           value="<?php echo $entry['filter_value']; ?>" class="lfx-text w-10" />
                </div>
                <?php } ?>



            <?php } ?>

          <?php  } ?>
        </div>
      <?php } ?>
      <?php if(count($btns) > 0){ ?>
        <div class="col-sm-12" style="text-align: center;">
            <?php
                foreach($btns as $btn){ ?>
                <button class="<?php echo $btn['name'].' '.$btn['class_name']; ?> btn"><?php echo $btn['text']; ?></button>
                <?php }
            ?>
        </div>
      <?php } ?>
    </form>
  </div>
</div>
<script type="text/javascript">
    $('.date').datetimepicker({
        pickTime: false
    });

    $('.time').datetimepicker({
        pickDate: false
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });

    $(function () {
        $('.main-search-btn').on('click',function(e){
            e.preventDefault();
            var form = '#<?php echo $form_id; ?>';
            $(form).submit();
        });
        $('.main-clear-btn').on('click',function(e){
            e.preventDefault();
            var form = '#<?php echo $form_id; ?>';
            $(form+' input[type!="hidden"]').val('');
            $(form+' textarea').val('');
            $(form+' select option[selected]').removeAttr('selected');
            $(form+' input[type!="hidden"][checked]').removeAttr('checked');
        });
        $('.main-export-btn').on('click', function (e) {
            e.preventDefault();
            var url = '<?php echo $export_url; ?>';
            var params = $('#<?php echo $form_id; ?>').formJSON();
            ajaxDownload(url,params);
        });
    });
</script>