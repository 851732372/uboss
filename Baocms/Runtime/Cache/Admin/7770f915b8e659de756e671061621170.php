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
<ul class="breadcrumb">
    <li>
        <a href="#">会员管理</a></span>
    </li>
    <li>
        <a href="#">会员详情</a></span>
    </li>
</ul>
<style>
	td p{
		margin-top:5px;
		margin-bottom: 0px!important;
	}
</style>
	<table class="table table-bordered">
		<tr>
			<td rowspan="3" width="100px">
				<img src="<?php echo (($users["face"])?($users["face"]):'--'); ?>" alt="" width="70px" class="img-circle img-responsive center-block" > 
				<p><code>
					<?php if(($users["is_reg"]) == "-1"): ?>未认证<?php endif; ?>
					<?php if(($users["is_reg"]) == "1"): ?>已认证<?php endif; ?>
					<?php if(($users["is_reg"]) == "0"): ?>认证中<?php endif; ?>
					<?php if(($users["is_reg"]) == "2"): ?>认证未通过<?php endif; ?>
				</code></p>
				<p><?php echo (($users["mobile"])?($users["mobile"]):'--'); ?></p>
			</td>
			<th>用户ID</th>
			<td><?php echo ($users["user_id"]); ?></td>
			<th>用户昵称</th>
			<td><?php echo ($users["nickname"]); ?></td>
			<th>真实姓名</th>
		    <td><?php echo (($users["realname"])?($users["realname"]):'--'); ?></td>
		</tr>
		<tr>
		    <th>资产</th>
		    <td><?php echo ($users['asset']/100); ?>元</td>
		    <th>余额</th>
		    <td><?php echo ($users['money']/100); ?>元</td>
		    <th>最后下单时间</th>	
		    <?php if(!empty($create_time)){ ?>
		    <td><?php echo (date("Y-m-d H:i:s",$create_time)); ?></td>
		    <?php }else{ ?>
		    <td>--</td>
		    <?php } ?>
		</tr>
		<tr>
			<th>身份证号</th>
			<td><?php echo (($users["idcardno"])?($users["idcardno"]):'--'); ?></td>
			<th>身份证照片</th>
			<td>
				<div style="float: left;">
					<img src="<?php echo (($users["idcard_zimgs"])?($users["idcard_zimgs"]):'default.png'); ?>" alt="" width="80px" class="img-responsive center-block pic" onclick = "select_img('<?php echo ($users["user_id"]); ?>',1)">
				</div>
				<div style="float: left;margin-left: 15px;">
					<img src="<?php echo (($users["idcard_fimgs"])?($users["idcard_fimgs"]):'default.png'); ?>" alt="" width="80px" class="img-responsive center-block pic" onclick = "select_img('<?php echo ($users["user_id"]); ?>',2)">
				</div>
			</td>
			<th>会员级别</th>
			<td>
				<form action="<?php echo U('change_level');?>" class="form-inline" method="POST" target="baocms_frm">
					<div class="form-group">
						<input type="hidden" name="user_id" value="<?php echo ($_GET['user_id']); ?>">
						<select name="level_id" id="" class="form-control">
							<option value="1" <?php if(($users["level_id"]) == "1"): ?>selected<?php endif; ?>>普通会员</option>
							<option value="2" <?php if(($users["level_id"]) == "2"): ?>selected<?php endif; ?>>黄金会员</option>
							<option value="3" <?php if(($users["level_id"]) == "3"): ?>selected<?php endif; ?>>钻石会员</option>
						</select>
					</div>
					<button class="btn btn-sm btn-info" type="submit">修改级别</button>
				</form>
			</td>
		</tr>
		<?php if(($users["is_reg"]) == "0"): ?><tr>
			<td>
				<?php echo BA('Vip/is_reg',array("user_id"=>$users["user_id"]),'通过','act','a2');?> | 
				<?php echo BA('Vip/is_ureg',array("user_id"=>$users["user_id"]),'拒绝','act','a2');?>
			</td>
		</tr><?php endif; ?>
	</table>
	<ul class="breadcrumb">
	    <li>
	        <a href="#">会员管理</a></span>
	    </li>
	    <li>
	        <a href="#">余额记录</a></span>
	    </li>
	</ul>
	<table class="table table-bordered">
		<tr>
			<!-- <th>来源</th> -->
			<th>类型</th>
			<th>收支</th>
			<th>日志</th>
			<th>时间</th>
		</tr>
		<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
				<td>
					<code>
						<?php switch($lis["type"]): case "1": ?>充值<?php break;?>
							<?php case "2": ?>余额支付<?php break;?>
							<?php case "3": ?>提现<?php break;?>
							<?php case "4": ?>U店分红<?php break;?>
							<?php case "5": ?>消费分成<?php break;?>
							<?php case "6": ?>资产变现<?php break;?>
							<?php case "22": ?>扫码支付<?php break;?>
							<?php default: ?>default<?php endswitch;?>
					</code>
				</td>
				<td><?php echo floor($lis['money'])/100;?>元</td>
				<td><?php echo ($lis["intro"]); ?></td>
				<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</table>
	<?php echo ($page); ?>
	<ul class="breadcrumb">
	    <li>
	        <a href="#">会员管理</a></span>
	    </li>
	    <li>
	        <a href="#">近7天消费</a></span>
	    </li>
	</ul>
	 <table class="table">
        <tr style="background-color:#eee;">
            <td>订单ID</td>
            <td>订单编号</td>
            <td>商品名称</td>
            <td>数量</td>
            <td>合计</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                <td><?php echo ($var["order_id"]); ?></td>
                <td><?php echo ($var["orderno"]); ?></td>
                <td><?php echo ($var["goodsname"]); ?></td>
                <td><?php echo ($var["num"]); ?></td>
                <td><?php echo round($var['total']/100,2);?>元</td>
                <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                <td><a href="<?php echo U('order/info',array('order_id' => $var['order_id']));?>">查看</a></td>
            </tr><?php endforeach; endif; ?>
    </table>
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
		<img src="" alt="" class="rimg img-responsive center-block" height="180px" width="180px">
	</div>
<script>
function select_img(id,type){
	$.post("<?php echo U('ajax_select_sfz');?>",{id:id,type:type},function(data){
		$('.simg').show();
		$('.rimg').attr({'src':''});
		$('.rimg').attr({'src':data});
	})
}
$('.closed').click(function(){
	$('.simg').hide();
})
</script>

</div>
</body>
</html>