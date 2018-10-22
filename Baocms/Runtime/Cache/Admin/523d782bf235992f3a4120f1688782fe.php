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
        <a href="#">会员管理</a></span>
    </li>
    <li>
        <a href="#">会员列表</a></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline">
			<div class="form-group">
				<label for="">昵称：</label>
				<input type="text" class="form-control" name="nickname" placeholder="昵称" value="<?php echo ($nickname); ?>">
			</div>
			<div class="form-group">
				<label for="">名称：</label>
				<input type="text" class="form-control" name="realname" placeholder="名称" value="<?php echo ($realname); ?>">
			</div>
			<div class="form-group">
				<label for="">会员级别</label>
				<select name="level_id" id="" class="form-control">
					<option value="">全部</option>
					<option value="1" <?php if(($level_id) == "1"): ?>selected<?php endif; ?>>
						普通会员
					</option>
					<option value="2" <?php if(($level_id) == "2"): ?>selected<?php endif; ?>>
						黄金会员
					</option>
					<option value="3" <?php if(($level_id) == "3"): ?>selected<?php endif; ?>>
						钻石会员
					</option>
				</select>
			</div>
			<div class="form-group">
				<label for="">认证状态</label>
				<select name="is_reg" id="" class="form-control">
					<option value="">全部</option>
					<option value="1" <?php if(($is_reg) == "1"): ?>selected<?php endif; ?>>
						未认证
					</option>
					<option value="2" <?php if(($is_reg) == "2"): ?>selected<?php endif; ?>>
						认证中
					</option>
					<option value="3" <?php if(($is_reg) == "3"): ?>selected<?php endif; ?>>
						通过认证
					</option>
					<option value="4" <?php if(($is_reg) == "4"): ?>selected<?php endif; ?>>
						认证未通过
					</option>
				</select>
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>编号</th>
				<th>昵称</th>
				<th>头像</th>
				<th>会员等级</th>
				<th>账户余额</th>
				<th>资产</th>
				<th>注册IP</th>
				<th>状态</th>
				<th>审核状态</th>
			    <th>操作</th>	
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["user_id"]); ?></td>
					<td><?php echo ($lis["nickname"]); ?></td>
					<td><img src="<?php echo ($lis["face"]); ?>" alt="" height="30px" class="img-circle pic" id="<?php echo ($lis["user_id"]); ?>"></td>
					<td>
						<?php if(($lis["level_id"]) == "1"): ?>普通会员<?php endif; ?>
						<?php if(($lis["level_id"]) == "2"): ?>黄金会员<?php endif; ?>
						<?php if(($lis["level_id"]) == "3"): ?>钻石会员<?php endif; ?>
					</td>
					<td><?php echo ($lis['money']/100); ?>元</td>
					<td><?php echo ($lis['asset']/100); ?>元</td>
					<td><?php echo ($lis["reg_ip"]); ?></td>
					<td>
						<?php if(($lis["closed"]) == "1"): ?>禁用<?php endif; ?>
                        <?php if(($lis["closed"]) == "0"): ?>正常<?php endif; ?>
					</td>
					<td>
						<?php if(($lis["is_reg"]) == "-1"): ?>未认证<?php endif; ?>
						<?php if(($lis["is_reg"]) == "1"): ?>已认证<?php endif; ?>
						<?php if(($lis["is_reg"]) == "0"): ?>认证中<?php endif; ?>
						<?php if(($lis["is_reg"]) == "2"): ?>不通过<?php endif; ?>
					</td>
					<td>
						<?php if(($lis["closed"]) == "1"): ?><a href="<?php echo U('Vip/closed',array('user_id'=>$lis['user_id']));?>" target="baocms_frm">激活</a><?php endif; ?>
                        <?php if(($lis["closed"]) == "0"): ?><a href="<?php echo U('Vip/unclosed',array('user_id'=>$lis['user_id']));?>" target="baocms_frm">禁用</a> |
                            <a href="<?php echo U('info',array('user_id' => $lis['user_id']));?>">查看</a> | 
                            <a href="<?php echo U('down_level',array('user_id' => $lis['user_id']));?>">邀请历史</a><?php endif; ?>
						
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
		<?php echo ($page); ?>
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
		<img src="" alt="" class="rimg img-responsive center-block" >
	</div>
</div>
<script>
$('.pic').click(function(){
	id = $(this).attr('id');
	$.post("<?php echo U('ajax_select_img');?>",{id:id},function(data){
		$('.simg').show();
		$('.rimg').attr({'src':''});
		$('.rimg').attr({'src':data});
	})
})
$('.closed').click(function(){
	$('.simg').hide();
})
</script>

</div>
</body>
</html>