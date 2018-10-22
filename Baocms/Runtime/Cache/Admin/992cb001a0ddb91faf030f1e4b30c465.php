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
<style>
	table tr th,td{
		text-align: center;
	}
</style>
<link rel="stylesheet" href="__PUBLIC__/bs/css/bootstrap.min.css">
<script src="__PUBLIC__/bs/js/jquery.min.js"></script>
<script src="__PUBLIC__/bs/js/bootstrap.min.js"></script>
<ul class="breadcrumb">
    <li>
        <a href="#">会员管理</a></span>
    </li>
    <li>
        <a href="#">团队组成</a></span>
    </li>
</ul>
<style>
	.panel-body{
		padding:0px;
	}
	.panel a{
		text-decoration: none;
		font-size: 14px;
		font-family: "微软雅黑";
	}
</style>
<div class="row">
	<div class="panel panel-default">
		<div class="panel-body">
			<table class="table table-bordered table-striped">
				<tr>
					<th>昵称/手机号</th>
					<th>姓名</th>
					<th>级别</th>
					<th>其他</th>
				</tr>
				<?php if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$u): $mod = ($i % 2 );++$i;?><tr>
						<td><?php echo ($u["nickname"]); ?>【<?php echo (($u["mobile"])?($u["mobile"]):'--'); ?>】</td>
						<td><?php echo (($u["realname"])?($u["realname"]):'<code>未认证</code>'); ?></td>
						<td>
							<code>
								<?php if(($u["level_id"]) == "1"): ?>普通会员<?php endif; ?>
								<?php if(($u["level_id"]) == "2"): ?>黄金会员<?php endif; ?>
								<?php if(($u["level_id"]) == "3"): ?>钻石会员<?php endif; ?>
							</code>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
		</div>
	</div>
</div>