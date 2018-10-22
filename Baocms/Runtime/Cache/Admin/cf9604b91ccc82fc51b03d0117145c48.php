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
            <a href="#">订单列表</a> <span class="divider"></span>
        </li>
        <li>
            <a href="#">查看订单</a> 
        </li>
    </ul>
	<div class="panel-default">
		<div class="panel-heading">
			<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
			<form action="" class="form-inline">
				<div class="form-group">
					<label for="">订单号：</label>
					<input type="text" class="form-control" name="orderno" value="<?php echo ($orderno); ?>" placeholder="订单号">
				</div>
				<div class="form-group">
					<label for="">商家名称：</label>
					<input type="text" class="form-control" name="shop_name" value="<?php echo ($shop_name); ?>" placeholder="商家名称">
				</div>
				<div class="form-group">
					<label for="">订单状态：</label>
					<select name="status" id="" class="form-control">
						<option value="">全部</option>
						<option value="1" <?php if(($status) == "1"): ?>selected<?php endif; ?>>待付款</option>
						<option value="4" <?php if(($status) == "4"): ?>selected<?php endif; ?>>已经付款</option>
						<option value="5" <?php if(($status) == "5"): ?>selected<?php endif; ?>>付款失败</option>
						<option value="9" <?php if(($status) == "9"): ?>selected<?php endif; ?>>退款中</option>
						<option value="11" <?php if(($status) == "11"): ?>selected<?php endif; ?>>已退款</option>
					</select>
				</div>
				<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
			</form> 
		</div> 
		<div class="panel-body">
			<table class="table table-bordered"> 
				<tr>
					<th>商家</th>
					<th>订单号</th>
					<th>商品名称</th>
					<th>数量</th>
					<th>合计</th>
					<th>使用时间</th>
					<th>订单状态</th>
					<th>创建时间</th>
					<th>操作</th>
				</tr>
				<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
						<td><a href=""><?php echo ($data["shop_name"]); ?></a></td>
						<td><?php echo ($data["orderno"]); ?></td>
						<td><?php echo ($data["title"]); ?></td>
						<td><?php echo ($data["num"]); ?> * <?php echo ($data['price']/100); ?>元</td>
						<td><?php echo ($data['total_price']/100); ?>元</td>
						<?php if(!empty($data['success_time'])){ ?>
						<td><?php echo (date("Y-m-d H:i:s",$data["success_time"])); ?></td>
						<?php }else{ ?>
						<td>--</td>
						<?php } ?>
						<td>
							<?php if(($data["status"]) == "1"): ?>待付款<?php endif; ?>
			                <?php if(($data["status"]) == "4"): ?>已付款<?php endif; ?>
			                <?php if(($data["status"]) == "3"): ?>已取消<?php endif; ?>
			                <?php if(($data["status"]) == "6"): ?>待使用<?php endif; ?>
			                <?php if(($data["status"]) == "7"): ?>待评价<?php endif; ?>
			                <?php if(($data["status"]) == "9"): ?>申请退款<?php endif; ?>
			                <?php if(($data["status"]) == "11"): ?>已退款<?php endif; ?>
						</td>
						<td><?php echo (date("Y-m-d H:i:s",$data["create_time"])); ?></td>
						<td><a href="<?php echo U('info',array('order_id' => $data['order_id']));?>">查看</a></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
		</div>
		<?php echo ($page); ?>
	</div>

</div>
</body>
</html>