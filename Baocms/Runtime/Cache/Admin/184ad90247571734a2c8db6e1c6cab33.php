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
        <a href="#">商家管理</a></span>
    </li>
    <li>
        <a href="#">商家列表</a></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline">
			<!-- 商户名称 -->
			<div class="form-group">
				<label for="">手机号：</label>
				<input type="text" class="form-control" name="tel" value="<?php echo ($tel); ?>">
			</div>
			<!-- 分类 -->
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>联系方式</th>
				<th>姓名</th>
				<th>创建时间</th>
				<th>行业</th>
				<th>城市</th>
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td></td>
					<td><?php echo ($lis["sapplytel"]); ?></td>
					<td><?php echo ($lis["sapplyname"]); ?></td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td><?php echo ($lis["cate_name"]); ?></td>
					<td><?php echo ($lis["city"]); ?></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
		<?php echo ($page); ?>
	<div class="panel-footer">

	</div>
</div>

</div>
</body>
</html>