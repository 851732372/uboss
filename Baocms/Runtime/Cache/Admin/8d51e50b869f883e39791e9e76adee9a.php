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
        <a href="#">申请人管理</a> <span class="divider"></span>
    </li>
    <li>
        <a href="#">审核列表</a> <span class="divider"></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<a class="btn btn-primary" href="<?php echo U('add');?>"><span class="glyphicon glyphicon-plus"></span>添加申请</a>
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline pull-right">
			<input type="text" class="form-control" name="search">
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>编号</th>
				<th>申请人名称</th>
				<th>联系手机</th>
				<th>申请店铺</th>
				<th>申请职位</th>
				<th>申请时间</th>
				<th>状态</th>
			    <th>操作</th>	
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["apply_id"]); ?></td>
					<td><a href="<?php echo U('vip/info',array('user_id' => $lis['user_id']));?>"><?php echo ($lis["realname"]); ?></a></td>
					<td><?php echo ($lis["mobile"]); ?></td>
					<td><a href="<?php echo U('founder/look_info',array('id' => $lis['shop_id']));?>"><?php echo ($lis["shop_name"]); ?></a></td>
					<td>
						<?php if(($lis["apply_position"]) == "1"): ?>总监<?php endif; ?>
						<?php if(($lis["apply_position"]) == "2"): ?>经理<?php endif; ?>
						<?php if(($lis["apply_position"]) == "3"): ?>主管<?php endif; ?>
					</td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td>
						<?php if(($lis["status"]) == "0"): ?>拒绝<?php endif; ?>
						<?php if(($lis["status"]) == "1"): ?>正在审核<?php endif; ?>
						<?php if(($lis["status"]) == "2"): ?>通过<?php endif; ?>
					</td>
					<td>
						<?php if(($lis["status"]) == "1"): echo BA('apply/refuse',array("apply_id"=>$lis['apply_id']),'拒绝','load','',600,280);?>
						| <a href="javascript:;" onclick="ok(<?php echo ($lis["apply_id"]); ?>)">通过</a><?php endif; ?>
						<?php if(($lis["status"]) == "0"): echo BA('apply/refuse_cause',array("apply_id"=>$lis['apply_id']),'拒绝原因','load','',600,280); endif; ?>
						<?php if(($lis["status"]) == "2"): echo BA('apply/divide_his',array("user_id"=>$lis['user_id']),'查看分红记录','load','',600,280); endif; ?>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
		<?php echo ($page); ?>
	<div class="panel-footer">
	</div>
</div>

<script>
	function ok(id){
		$.post('<?php echo U("ajax_ok");?>',{id:id},function(data){
			location.reload();
		})
	}
</script>

</div>
</body>
</html>