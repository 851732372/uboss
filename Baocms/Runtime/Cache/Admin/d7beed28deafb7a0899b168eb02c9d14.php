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
    <li>创始人管理</li>
    <li>创始人列表</li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<a class="btn btn-primary" href="<?php echo U('add');?>"><span class="glyphicon glyphicon-plus"></span>添加申请</a>
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline pull-right">
			<div class="form-group">
				<label for="">状态：</label>
				<select name="status" id="" class="form-control">
					<option value="0">全部</option>
					<option value="1"<?php if(($status) == "1"): ?>selected<?php endif; ?>>审核中</option>
					<option value="2"<?php if(($status) == "2"): ?>selected<?php endif; ?>>已审核</option>
					<option value="4"<?php if(($status) == "4"): ?>selected<?php endif; ?>>已拒绝</option>
					<option value="3"<?php if(($status) == "3"): ?>selected<?php endif; ?>>已生成U店</option>
				</select>
			</div>
			<div class="form-group">
				<label for="">店铺类型：</label>
				<select name="store_type" id="" class="form-control">
					<option value="">全部</option>
					<option value="1" <?php if(($store_type) == "1"): ?>selected<?php endif; ?>>旗舰店</option>
					<option value="3" <?php if(($store_type) == "3"): ?>selected<?php endif; ?>>人气店</option>
					<option value="2" <?php if(($store_type) == "2"): ?>selected<?php endif; ?>>核心店</option>
				</select>
			</div>
			<div class="form-group">
				<label for="">手机号：</label>
				<input type="text" class="form-control" name="mobile" value="<?php echo ($mobile); ?>">
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>编号</th>
				<th>创始人名称</th>
				<th>联系手机</th>
				<th>身份证号码</th>
				<th>身份证图片</th>
				<th>申请时间</th>
				<th>行业</th>
				<th>店铺类型</th>
				<th>状态</th>
			    <th>操作</th>	
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["founder_id"]); ?></td>
					<td><a href="<?php echo U('vip/info',array('user_id' => $lis['user_id']));?>"><?php echo ($lis["realname"]); ?></a></td>
					<td><?php echo ($lis["mobile"]); ?></td>
					<td><?php echo ($lis["idcardno"]); ?></td>
					<td>
						<div style="float: left;">
							<img src="/attachs/<?php echo (($lis["idcard_zimgs"])?($lis["idcard_zimgs"]):'default.png'); ?>" alt="" width="80px" class="img-responsive center-block pic" onclick = "select_img('<?php echo ($lis["user_id"]); ?>',1)">
						</div>
						<div style="float: left;margin-left: 15px;">
							<img src="/attachs/<?php echo (($lis["idcard_fimgs"])?($lis["idcard_fimgs"]):'default.png'); ?>" alt="" width="80px" class="img-responsive center-block pic" onclick = "select_img('<?php echo ($lis["user_id"]); ?>',2)">
						</div>
					</td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td><?php echo ($lis["cate_name"]); ?></td>
					<td>
						<?php if(($lis["store_type"]) == "1"): ?>旗舰店<?php endif; ?>
						<?php if(($lis["store_type"]) == "2"): ?>核心店<?php endif; ?>
						<?php if(($lis["store_type"]) == "3"): ?>人气店<?php endif; ?>
					</td>
					<td>
						<?php if(($lis["status"]) == "0"): ?>拒绝<?php endif; ?>
						<?php if(($lis["status"]) == "1"): ?>正在审核<?php endif; ?>
						<?php if(($lis["status"]) == "2"): ?>通过<?php endif; ?>
						<?php if(($lis["status"]) == "3"): ?>已经生成U店<?php endif; ?>
					</td>
					<td>
						<?php switch($lis["status"]): case "2": ?><a href="<?php echo U('add_u',array('id' => $lis['founder_id']));?>">去生成U店</a><?php break;?>
							<?php case "3": ?><a href="<?php echo U('look_info',array('id' => $lis['shop_id']));?>">查看U店</a><?php break;?>
							<?php case "0": echo BA('founder/refuse_cause',array("founder_id"=>$lis['founder_id']),'拒绝原因','load','',600,280); break;?>
							<?php default: ?>
							<?php echo BA('founder/refuse',array("founder_id"=>$lis['founder_id']),'拒绝','load','',600,280);?>
							| <a href="javascript:;" onclick="ok(<?php echo ($lis["founder_id"]); ?>)">通过</a><?php endswitch;?>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
		<?php echo ($page); ?>
	<div class="panel-footer">

	</div>
	<style>
		.simg{
			position: absolute;
			width:100%;
			height:100%;
			float: left;
			top:0px;
			display: none;
		}
		.rimg{
			position: absolute;
			top:30%;
			left:40%;
			text-align: center;
		}
		.closed{
			position: absolute;
			top:15%;
			right:38%;
			background: #ccc;
			height:30px;
			width:30px;
			text-align: center;
			font-size: 20px;
			border-radius: 30px;
			color:white;
			cursor: pointer;
		}
	</style>
	<div class="simg">
		<span class="closed">&times;</span>
		<img src="" alt="" class="rimg center-block" height="300px" width="180px">
	</div>
</div>
<script>
	function refuse(id){
		$.post('<?php echo U("ajax_refuse");?>',{id:id},function(data){
			location.reload();
		})
	}
	function ok(id){
		$.post('<?php echo U("ajax_ok");?>',{id:id},function(data){
			location.reload();
		})
	}
</script>
<script>
function select_img(id,type){
	$.post("<?php echo U('ajax_select_sfz');?>",{id:id,type:type},function(data){
		$('.simg').show();
		$('.rimg').attr({'src':''});
		$('.rimg').attr({'src':'/attachs/'+data});
	})
}
$('.closed').click(function(){
	$('.simg').hide();
})
</script>

</div>
</body>
</html>