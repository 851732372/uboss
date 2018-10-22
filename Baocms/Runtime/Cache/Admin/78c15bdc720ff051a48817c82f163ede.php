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
<ul class="breadcrumb">
    <li>
        <a href="#">U店管理</a> <span class="divider">/</span>
    </li>
    <li>
        <a href="<?php echo U('index');?>">U店列表</a><span class="divider">/</span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
        <div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline">
			<div class="form-group">
				<label for="">店铺名称：</label>
				<input type="text" class="form-control" name="shop_name" value="<?php echo ($shop_name); ?>">
			</div>
			<div class="form-group">
				<label for="">店铺类型：</label>
				<select name="type" id="" class="form-control">
					<option value="">全部</option>
					<option value="3" <?php if(($type) == "3"): ?>selected<?php endif; ?>>人气店</option>
					<option value="2" <?php if(($type) == "2"): ?>selected<?php endif; ?>>核心店</option>
					<option value="1" <?php if(($type) == "1"): ?>selected<?php endif; ?>>旗舰店</option>
				</select>
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>店铺名称</th>
				<th>总监人数</th>
				<th>经理人数</th>
				<th>主管人数</th>
				<th>所属行业</th>
				<th>店铺类型</th>
				<th>店铺地址</th>
				<th>店铺提成</th>
				<th>创建时间</th>
				<th>状态</th>
			    <th>操作</th>
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["shop_name"]); ?></td>
					<td><?php echo ($lis["zj"]); ?></td>
					<td><?php echo ($lis["jl"]); ?></td>
					<td><?php echo ($lis["zg"]); ?></td>
					
					<td><?php echo ($lis["cate_name"]); ?></td>
	                <td>
						<?php if(($lis["store_type"]) == "1"): ?>旗舰店<?php endif; ?>
						<?php if(($lis["store_type"]) == "2"): ?>核心店<?php endif; ?>
						<?php if(($lis["store_type"]) == "3"): ?>人气店<?php endif; ?>
					</td>
					<td><?php echo ($lis["city"]); ?> / <?php echo ($lis["area"]); ?>/ <?php echo ($lis["address"]); ?></td>
					<td><?php echo ($lis["proportions"]); ?>%</td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td>
						<?php if(($lis["status"]) == "0"): ?>未运营<?php endif; ?>
						<?php if(($lis["status"]) == "1"): ?>运营<?php endif; ?>
					</td>
					<td><a href="<?php echo U('bussiness/set',array('id' => $lis['shop_id']));?>">设置</a> | <a href="<?php echo U('shop/login',array('shop_id' => $lis['shop_id']));?>" target="_blank">管理</a> | <a href="<?php echo U('founder/look_info',array('id' => $lis['shop_id']));?>">查看详情</a></td>
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