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
        <a href="<?php echo U('index');?>">用户提现</a><span class="divider"></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
        <div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline" method="POST">
			<div class="form-group">
				<label for="">审核状态：</label>
				<select name="status" id="" class="form-control">
					<option value="">全部</option>
					<option value="1" <?php if(($status) == "1"): ?>selected<?php endif; ?>>正在审核</option>
					<option value="3" <?php if(($status) == "3"): ?>selected<?php endif; ?>>拒绝</option>
					<option value="2" <?php if(($status) == "2"): ?>selected<?php endif; ?>>通过</option>
				</select>
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>管理者名称</th>
				<th>现有余额</th>
				<th>提现金额</th>
				<th>提现账号</th>
				<th>提现手续费</th>
				<th>实到金额</th>
				<th>申请时间</th>
				<th>审核时间</th>
				<th>审核</th>
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["username"]); ?></td>
					<td><?php echo floor($lis['money'])/100;?>元</td>
					<td><?php echo floor($lis['com_money'])/100;?>元</td>
					<td><?php echo ($lis["bank_num"]); ?></td>
					<td><?php echo C('SET_REMIND');?>%</td>
					<td><?php echo floor($lis['com_money']-$lis['com_money']*C('SET_REMIND')/100)/100;?>元</td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<?php if(isset($lis['ok_time'])){ ?>
					<td><?php echo (date("Y-m-d H:i:s",$lis["ok_time"])); ?></td>
					<?php }else{ ?>
					<td>--</td>
					<?php } ?>
					<td>
						<?php if($_SESSION['admin']['admin_id'] == 1){ ?>
						<?php if(($lis["status"]) == "1"): echo BA('cityfinance/refuse_cause',array("city_log_id"=>$lis["city_log_id"]),'拒绝原因','load','',600,280); endif; ?>
						<?php if(($lis["status"]) == "2"): ?><code>审核通过</code><?php endif; ?>
						<?php if(($lis["status"]) == "0"): echo BA('cityfinance/ok',array("city_log_id"=>$lis["city_log_id"]),'通过','act','');?>
							| <?php echo BA('cityfinance/refuse',array("city_log_id"=>$lis["city_log_id"]),'拒绝','load','',600,280); endif; ?>
						<?php }else{ ?>
							<?php if(($lis["status"]) == "1"): echo BA('cityfinance/refuse_cause',array("city_log_id"=>$lis["city_log_id"]),'拒绝原因','load','',600,280); endif; ?>
							<?php if(($lis["status"]) == "2"): ?><code>审核通过</code><?php endif; ?>
							<?php if(($lis["status"]) == "0"): ?><code>正在审核</code><?php endif; ?>
						<?php } ?>
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