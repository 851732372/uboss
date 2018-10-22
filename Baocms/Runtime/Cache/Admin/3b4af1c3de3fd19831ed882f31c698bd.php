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
	.breadcrumb .active{
		background: #ccc;
		color:#fff;
	}
	.breadcrumb li a{
		padding:5px 10px;
	}
	.btn-danger a{
		color:white;
	}
</style>
<link href='__PUBLIC__/bs/css/bootstrap.min.css' media='all' rel='stylesheet' type='text/css' />
 <ul class="breadcrumb">
    <li><a href="<?php echo U('set',array('id' => $_GET['id']));?>">基本信息</a><span class="divider"></span></li>
    <li><a href="<?php echo U('over_order',array('id' => $_GET['id']));?>" class="active">买单设置</a><span class="divider"></span></li>
    <li><a href="<?php echo U('check_authen',array('id' => $_GET['id']));?>">认证资料</a><span class="divider"></span></li>
    <li><a href="<?php echo U('index');?>">返回商户列表</a></li>
</ul>
<div class="panel penel-default">
	<div class="panel-heading">
		<button class="btn btn-danger"><?php echo BA('bussiness/edit_over',array("type" => 3,'shop_id' => $_GET['id']),'添加优惠','load','',600,240);?></button>
	</div>
	<div class="panel-body" id="pb">
		<div>
			<table class="table table-bordered">
				<tr>
					<th>类型</th>
					<th>优惠</th>
					<th>活动时间</th>
					<th>操作</th>
				</tr>
				<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dat): $mod = ($i % 2 );++$i;?><tr>
						<td>
							<?php if(($dat["type"]) == "1"): ?>折扣<?php endif; ?>
							<?php if(($dat["type"]) == "2"): ?>满减<?php endif; ?>
						</td>
						<td>
							<?php if(($dat["type"]) == "1"): echo ($dat["rate"]); ?>%<?php endif; ?>
							<?php if(($dat["type"]) == "2"): $arr = explode('/', $dat['rate']); echo '满'.$arr[0].'元减'.$arr[1].'元'; endif; ?></td>
						<td><?php echo ($dat["start_time"]); ?> -- <?php echo ($dat["end_time"]); ?></td>
						<td>
							<?php echo BA('bussiness/edit_over',array("type" => $dat['type'],'id' => $dat['id'],'shop_id' => $_GET['id']),'修改','load','',600,240);?>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
		</div>
	</div>
</div>

</div>
</body>
</html>