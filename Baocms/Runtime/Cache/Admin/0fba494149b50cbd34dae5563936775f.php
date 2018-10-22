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
<div class="panel-primary">
    <div class="panel-body">
        <form  action="<?php echo U('founder/refuse');?>" method="post" class='form form-horizontal validate-form' target="baocms_frm">
             <div class='control-group'>
                <div class='control-label'>
                    <label>拒绝原因：</label>
                </div>
                <input type="hidden" name="founder_id" value="<?php echo ($_GET['founder_id']); ?>">
                <div class='controls'>
                    <textarea name="reason" id="" cols="20" rows="5"></textarea>
                </div>
            </div>
            <div class='form-actions' style='margin-bottom:0'>
                <button class='btn btn-primary' type='submit'>
                    <i class='icon-save'></i>
                    保存数据
                </button>
            </div>
        </form>
    </div>
</div>


</div>
</body>
</html>