<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo ($CONFIG["site"]["title"]); ?>管理后台</title>
        <meta name="description" content="<?php echo ($CONFIG["site"]["title"]); ?>管理后台" />
        <meta name="keywords" content="<?php echo ($CONFIG["site"]["title"]); ?>管理后台" />
        <!-- <link href="__TMPL__statics/css/index.css" rel="stylesheet" type="text/css" /> -->
        <link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css" />
        <link href="__TMPL__statics/css/land.css" rel="stylesheet" type="text/css" />
        <link href="__TMPL__statics/css/pub.css" rel="stylesheet" type="text/css" />
        <link href="__TMPL__statics/css/main.css" rel="stylesheet" type="text/css" />
        <link href="__PUBLIC__/js/jquery-ui.css" rel="stylesheet" type="text/css" />
        <script> var BAO_PUBLIC = '__PUBLIC__'; var BAO_ROOT = '__ROOT__'; </script>
        <script src="__PUBLIC__/js/jquery.js"></script>
        <script src="__PUBLIC__/js/jquery-ui.min.js"></script>
        <script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
        <script src="__PUBLIC__/js/admin.js?v=20150409"></script>
    </head>
   
    <body>
         <iframe id="baocms_frm" name="baocms_frm" style="display:none; height:1600px"></iframe>
   <div class="main">
<link rel="stylesheet" href="__PUBLIC__/bs/css/bootstrap.min.css">
<div class="panel-primary" style="width:500px;">
    <div class="panel-body">
        <?php if(($type) == "1"): ?><form action="<?php echo U('bussiness/edit_over');?>" method="POST"  class='form' target="baocms_frm">
            <div class="form-inline">
                <label for="">折扣率%：</label>
                <input type="text" name="rate" id="" class="form-control" placeholder="折扣" value="<?php echo ($dat["rate"]); ?>">
                <code>格式：10%</code>
            </div>
            <br>
            <div class="form-inline">
                <label for="">活动时间：</label>
                <input type="date" name="start_time" class="form-control" value="<?php echo ($dat["start_time"]); ?>"> --<input type="date" name="end_time" class="form-control" value="<?php echo ($dat["end_time"]); ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo ($id); ?>">
            <input type="hidden" name="shop_id" value="<?php echo ($shop_id); ?>">
            <input type="hidden" name="type" value="1">
            <input type="submit" value="提交" class="btn btn-primary">
        </form><?php endif; ?>
        <?php if(($type) == "2"): ?><form action="<?php echo U('bussiness/edit_over');?>" method="POST" class='form' target="baocms_frm">
            <div class="form-inline">
                <label for="">满减规则：</label>
                <input type="text" name="rate" id="" class="form-control" placeholder="例如：满足金额/减少金额" value="<?php echo ($dat["rate"]); ?>">
                <code>100/20意味着满100元减20元</code>
            </div>
            <br>
            <div class="form-inline">
                <label for="">活动时间：</label>
                 <input type="date" name="start_time" class="form-control" value="<?php echo ($dat["start_time"]); ?>"> --<input type="date" name="end_time" class="form-control" value="<?php echo ($dat["end_time"]); ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo ($id); ?>">
            <input type="hidden" name="shop_id" value="<?php echo ($shop_id); ?>">
            <input type="hidden" name="type" value="2">
            <input type="submit" value="提交" class="btn btn-primary" style="margin-left: 20px; margin-top: 20px;">
        </form><?php endif; ?>
        <?php if(($type) == "3"): ?><form action="<?php echo U('bussiness/edit_over');?>" method="POST" class='form' target="baocms_frm">
                <div id="cons1">
                    <div class="form-inline">
                        <label for="">满减规则：</label>
                        <input type="text" name="rate" class="form-control" placeholder="例如：满足金额/减少金额" >
                        <code>100/20意味着满100元减20元</code>
                    </div>
                </div>
                <br>
                <div class="form-inline">
                    <label for="">活动时间：</label>
                    <input type="date" name="start_time" class="form-control"> --<input type="date" name="end_time" class="form-control">
                </div>
                <br>
                <div class="form-inline">
                    <label for="">选择类型：</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="type" class="ch1" value="2" checked>满减
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="type" class="ch2" value="1">折扣
                        </label>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo ($id); ?>">
                <input type="hidden" name="shop_id" value="<?php echo ($shop_id); ?>">
                <input type="submit" value="提交" class="btn btn-primary" style="margin-left: 20px; margin-top: 20px;">
            </form><?php endif; ?>
    </div>
</div>
<script>
    $('.ch1').click(function(){
        $('#cons1').html('<div class="form-inline"><labe>满减规则：</label> <input type="text" name="rate" class="form-control" placeholder="例如：满足金额/减少金额"><code>100/20意味着满100元减20元</code></div>');
    })
     $('.ch2').click(function(){
        $('#cons1').html('<div class="form-inline"><label>折扣率%：</label><input type="text" name="rate" class="form-control" placeholder="折扣"><code>格式：10%</code></div>');
    })
</script>

</div>
</body>
</html>