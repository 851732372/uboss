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
        <a href="#">商家管理</a></span>
    </li>
    <li>
        <a href="#">商家认证</a></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline" method="POST">
			<!-- 商户名称 -->
			<div class="form-group">
				<label for="">商户名称</label>
				<input type="text" class="form-control" name="shop_name" value="<?php echo ($shop_name); ?>">
			</div>
			<!-- 分类 -->
			<div class="form-group">
				<label for="">状态</label>
				<select name="audit" id="" class="form-control">
					<option value="4">请选择</option>
                    <option value="2" <?php if(($audit) == "2"): ?>selected<?php endif; ?>>通过</option>
                    <option value="0" <?php if(($audit) == "0"): ?>selected<?php endif; ?>>正在审核</option>
                    <option value="1" <?php if(($audit) == "1"): ?>selected<?php endif; ?>>拒绝</option>
                </select>
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>编号</th>
				<th>商户名称</th>
				<th>店主名称</th>
				<th>联系手机</th>
				<th>店主身份证</th>
				<th>营业执照号</th>
				<th>餐饮执照号</th>
				<th>创建时间</th>
				<th>审核状态</th>
				<th>状态</th>
			    <th>操作</th>	
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr <?php if(($_GET['shop_id']) == $lis["shop_id"]): ?>style="background:#ccc"<?php endif; ?>>
					<td><?php echo ($lis["id"]); ?></td>
					<td><?php echo ($lis["shop_name"]); ?></td>
					<td><?php echo ($lis["realname"]); ?></td>
					<td><?php echo ($lis["telphone"]); ?></td>
					<td><?php echo ($lis["idcardno"]); ?></td>
					<td><?php echo ($lis["licence"]); ?></td>
					<td><?php echo ($lis["meatlicence"]); ?></td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td>
						<?php if(($lis["audit"]) == "2"): ?>审核通过<?php endif; ?>
						<?php if(($lis["audit"]) == "0"): ?>正在审核<?php endif; ?>
						<?php if(($lis["audit"]) == "1"): ?><code>已拒绝</code><?php endif; ?>
					</td>
					<td>
						<?php if(($lis["delstatus"]) == "1"): ?><code>已删除</code>
						<?php else: ?>
							正常<?php endif; ?>
					</td>
					<?php if(($lis["delstatus"]) == "0"): ?><td>
							<?php if(($lis["audit"]) == "0"): ?><a href="javascript:;" onclick="go(<?php echo ($lis["shop_id"]); ?>)">通过</a> |
								<a href="javascript:;" data-toggle="modal" data-target="#myModala<?php echo ($key); ?>">拒绝</a> |
								<div class="modal fade" id="myModala<?php echo ($key); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								    <div class="modal-dialog">
								        <div class="modal-content">
								            <div class="modal-header">
								                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								                <h4 class="modal-title" id="myModalLabel">拒绝原因</h4>
								            </div>
								            <form action="" onsubmit="return false" class="reason">
								            <div class="modal-body">
								            	<textarea name="reason" cols="90" rows="10"></textarea>
								            	<input type="hidden" name="shop_id" value="<?php echo ($lis["shop_id"]); ?>">
								            </div>
								            <div class="modal-footer">
								                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
								                <button type="button" class="btn btn-primary" onclick="down()">提交更改</button>
								            </div>
								            </form>
								        </div>
								    </div>
								</div><?php endif; ?>
							<?php if(($lis["audit"]) == "1"): ?><a href="javascript:;" data-toggle="modal" data-target="#myModal<?php echo ($key); ?>">拒绝原因</a> |
								<div class="modal fade" id="myModal<?php echo ($key); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								    <div class="modal-dialog">
								        <div class="modal-content">
								            <div class="modal-header">
								                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								                <h4 class="modal-title" id="myModalLabel">拒绝原因</h4>
								            </div>
								            <form action="" onsubmit="return false" id="reason">
								            <div class="modal-body">
								            	<textarea name="reason" cols="90" rows="10"><?php echo ($lis["reason"]); ?></textarea>
								            </div>
								            <div class="modal-footer">
								            </div>
								            </form>
								        </div>
								    </div>
								</div><?php endif; ?>
							<a href="<?php echo U('check_authen',array('id' => $lis['shop_id']));?>">查看</a> | <a href="javascript:;" onclick="dela(<?php echo ($lis["shop_id"]); ?>)">删除</a>
						</td><?php endif; ?>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
		<?php echo ($page); ?>

</div>
<script>
function go(id)
{
	$.post("<?php echo U('ok');?>",{id:id},function(data){
		alert('通过审核');
		location.reload();
	})
}

function down()
{
	str = $('.reason').serialize();
	$.post("<?php echo U('refuse');?>",{str:str},function(data){
		location.reload();
	})
}
function dela(id)
{
	$.post("<?php echo U('del_authen');?>",{shop_id:id},function(data){
		location.reload();
	})
}
</script>

</div>
</body>
</html>