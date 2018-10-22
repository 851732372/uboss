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
        <a href="#">城市财务</a> <span class="divider"></span>
    </li>
    <li>
        <a href="<?php echo U('index');?>">对账单</a><span class="divider"></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
        <div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline" method="POST">
			<div class="form-group">
				<label for="">类型：</label>
				<select name="audit" id="" class="form-control">
					<option value="">全部</option>
					<option value="1" <?php if(($audit) == "1"): ?>selected<?php endif; ?>>已入账</option>
					<option value="2" <?php if(($audit) == "2"): ?>selected<?php endif; ?>>未入账</option>
				</select>
			</div>
			<div class="form-group">
				<label for="">选择城市：</label>
				<select name="admin_id" id="" class="form-control">
					<option value="">全部</option>
					<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$admin): $mod = ($i % 2 );++$i;?><option value="<?php echo ($admin["admin_id"]); ?>" <?php if(($admin_id) == $admin["admin_id"]): ?>selected<?php endif; ?>><?php echo ($admin["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>城市管理者</th>
				<th>详情</th>
				<th>账单日期</th>
				<th>管理者应得</th>
				<th>状态</th>
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["username"]); ?>【<?php echo ($lis["mobile"]); ?>】</td>
					<td><?php echo ($lis["intro"]); ?></td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td><?php echo floor($lis['com_money'])/100;?>元</td>
	                <td>
	                    <?php if(($lis["audit"]) == "1"): ?><code>已入账</code><?php endif; ?>
	                    <?php if(($lis["audit"]) == "0"): ?><code>未入账</code><?php endif; ?>
	                </td>
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