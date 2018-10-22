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
        <a href="#">用户财务</a> <span class="divider"></span>
    </li>
    <li>
        <a href="<?php echo U('index');?>">余额流水账单</a><span class="divider"></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<a href="<?php echo U('count_account');?>" class="btn btn-danger">查看统计</a>
        <div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline pull-right" method="POST">
			<div class="form-group">
				<label for="">类型：</label>
				<select name="type" id="" class="form-control">
					<option value="">全部</option>
					<option value="1" <?php if(($type) == "1"): ?>selected<?php endif; ?>>充值</option>
					<option value="2" <?php if(($type) == "2"): ?>selected<?php endif; ?>>余额支付</option>
					<option value="3" <?php if(($type) == "3"): ?>selected<?php endif; ?>>提现</option>
					<option value="4" <?php if(($type) == "4"): ?>selected<?php endif; ?>>U店分红</option>
					<option value="5" <?php if(($type) == "5"): ?>selected<?php endif; ?>>消费分成</option>
					<option value="6" <?php if(($type) == "6"): ?>selected<?php endif; ?>>资产变现</option>
					<option value="22" <?php if(($type) == "22"): ?>selected<?php endif; ?>>扫码支付</option>
				</select>
			</div>
			<div class="form-group">
				<label for="">手机号：</label>
				<input type="text" name="mobile" id="" value="<?php echo ($mobile); ?>" class="form-control">
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>用户名称</th>
				<th>类型</th>
				<th>收支</th>
				<th>日志</th>
				<th>时间</th>
			</tr>
			<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["realname"]); ?>【<?php echo ($lis["mobile"]); ?>】</td>
					<td>
						<code>
							<?php switch($lis["type"]): case "1": ?>充值<?php break;?>
								<?php case "2": ?>余额支付<?php break;?>
								<?php case "3": ?>提现<?php break;?>
								<?php case "4": ?>U店分红<?php break;?>
								<?php case "5": ?>消费分成<?php break;?>
								<?php case "6": ?>资产变现<?php break;?>
								<?php case "7": ?>余额退款<?php break;?>
								<?php case "22": ?>扫码支付<?php break;?>
								<?php default: ?>default<?php endswitch;?>
						</code>
					</td>
					<td><?php echo floor($lis['money'])/100;?>元</td>
					<td><?php echo ($lis["intro"]); ?></td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
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