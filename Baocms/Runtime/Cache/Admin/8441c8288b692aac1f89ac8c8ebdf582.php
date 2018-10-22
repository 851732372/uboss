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
<div class="panel panel-default">
	<div class="panel-heading">累计分红:<code><?php echo ($money/100); ?>元</code></div>
	<div class="panel-body">
		<ol class="list-group">
		<?php if(is_array($phis)): $i = 0; $__LIST__ = $phis;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$his): $mod = ($i % 2 );++$i;?><li class="list-group-item"><?php echo ($key+1); ?>.<?php echo ($his["username"]); ?>在<?php echo (date("Y-m-d H:i:s",$his["create_time"])); ?>收到U店<a href=""><?php echo ($his["shop_name"]); ?></a>分红<code><?php echo ($his['money']/100); ?></code>元</li><?php endforeach; endif; else: echo "" ;endif; ?>
	    </ol>
	</div>
</div>

</div>
</body>
</html>