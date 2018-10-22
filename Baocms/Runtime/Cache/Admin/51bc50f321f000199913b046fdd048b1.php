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
    <li>U店详情</li>
</ul>
<div class="panel panel-default">
	<div class="panel-body">
		<table class="table userIndexTable table-bordered table-hover">
				<tr>
					<th>编号</th>
					<td><?php echo ($shop["user_id"]); ?></td>
					<th>创始人</th>
					<td><?php echo ($shop["realname"]); ?></td>
					<th>联系方式</th>
					<td><?php echo ($shop["tel"]); ?></td>
					<th>店铺分类</th>
					<td><?php echo ($shop["cate_name"]); ?></td>
				</tr>
				<tr>
					<th>资产</th>
					<td><?php echo ($shop['asset']/100); ?>元</td>
					<th>余额</th>
					<td><?php echo floor($shop['money'])/100;?>元</td>
					<th>提成</th>
					<td><?php echo ($shop["proportions"]); ?>%</td>
				</tr>
				<tr>
					<th>店铺级别</th>
					<td>
						<?php if(($shop["store_type"]) == "1"): ?>旗舰店<?php endif; ?>
						<?php if(($shop["store_type"]) == "2"): ?>核心店<?php endif; ?>
						<?php if(($shop["store_type"]) == "3"): ?>人气店<?php endif; ?>
					</td>
					<th>店铺位置</th>
					<td><?php echo ($shop["city"]); ?> / <?php echo ($shop["area"]); ?> / <?php echo ($shop["addr"]); ?></td>
					<th>店铺名称</th>
					<td><?php echo ($shop["shop_name"]); ?></td>
					<th>店铺创建时间</th>
					<td><?php echo (date("Y-m-d H:i:s",$shop["create_time"])); ?></td>
				</tr>
				<tr>
					<th>总监人数</th>
					<td><?php echo ($shop["zj"]); ?></td>
					<th>经理人数</th>
					<td><?php echo ($shop["jl"]); ?></td>
					<th>主管人数</th>
					<td><?php echo ($shop["zg"]); ?></td>
					<th>状态</th>
					<td>
						<?php if(($shop["status"]) == "0"): ?><code>未运营</code><?php endif; ?>
						<?php if(($shop["status"]) == "1"): ?><code>已运营</code><?php endif; ?>
					</td>
				</tr>
				<tr>
					<th colspan="7">操作<code>注意：分红发放之前U店需要运营</code></th>
					<td>
						<?php if(($shop["status"]) == "0"): echo BA('founder/start_business',array('shop_id' => $shop['shop_id']),'开始运营','act','');?>
						<?php else: ?>
							<?php echo BA('founder/end_business',array('shop_id' => $shop['shop_id']),'停止运营','act','');?> | <a href="javascript:;"  data-toggle="modal" data-target="#myModal">发放分红</a><?php endif; ?>
						| <a href="<?php echo U('shop/login',array('shop_id' => $shop['shop_id']));?>" target="_blank">管理U店</a> | <a href="javascript:;" data-toggle="modal" data-target="#myModal1">分红历史</a>
					</td>
				</tr>
		</table>
		<ul class="breadcrumb">
		    <li>U店详情</li>
		    <li>人员组成</li>
		</ul>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a type="button" href="<?php echo U('look_info',array('id' => $_GET['id'],'pos' => 0));?>" class="btn btn-default <?php if(($type) == "0"): ?>active<?php endif; ?>">全部</a>
			</div>
			<div class="btn-group" role="group">
				<a type="button" href="<?php echo U('look_info',array('id' => $_GET['id'],'pos' => 1));?>" class="btn btn-default <?php if(($type) == "1"): ?>active<?php endif; ?>">总监</a>
			</div>
			<div class="btn-group" role="group">
				<a type="button" href="<?php echo U('look_info',array('id' => $_GET['id'],'pos' => 2));?>" class="btn btn-default <?php if(($type) == "2"): ?>active<?php endif; ?>">经理</a>
			</div>
			<div class="btn-group" role="group">
				<a type="button" href="<?php echo U('look_info',array('id' => $_GET['id'],'pos' => 3));?>" class="btn btn-default <?php if(($type) == "3"): ?>active<?php endif; ?>">主管</a>
			</div>
		</div>
		<hr>	
		<table class="table tabel-hover table-bordered">
			<tr>
				<th>用户名称</th>
				<th>手机号</th>
				<th>资产</th>
				<th>余额</th>
				<th>是否认证</th>
			</tr>
			<?php if(is_array($user)): $i = 0; $__LIST__ = $user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$u): $mod = ($i % 2 );++$i;?><tr>
					<td><a href=""><?php echo ($u["realname"]); ?></a></td>
					<td><?php echo ($u["mobile"]); ?></td>
					<td><?php echo ($u['asset']/100); ?>元</td>
					<td><?php echo ($u['money']/100); ?>元</td>
					<td>
						<?php if(($u["is_reg"]) == "1"): ?><code>已认证</code><?php endif; ?>
						<?php if(($u["is_reg"]) == "0"): ?><code>未认证</code><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
</div>
<!-- 发放金额 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">发放金额</h4>
            </div>
            <form  action="<?php echo U('give_money');?>" method="post" class='form-inline' target="baocms_frm">
            <div class="modal-body">
	            <div class='form-group'>
	                <div class='control-label'>
	                   发放金额： <input type="text" name="money" class="form-control"/>
	                   <input type="hidden" value="<?php echo ($shop["shop_id"]); ?>" name="shop_id">
	                </div>
	            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary">提交更改</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!-- 分红历史 -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">分红历史</h4>
            </div>
            <div class="modal-body">
            	<?php if(is_array($his)): $i = 0; $__LIST__ = $his;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hiss): $mod = ($i % 2 );++$i;?><code>操作者:<?php echo ($hiss["name"]); ?>在<?php echo (date("Y-m-d H:i:s",$hiss["create_time"])); ?>发放金额<?php echo floor($hiss['total_money'])/100;?>元</code>
            	<table class="table table-bordered table-striped">
            		<tr>
            			<th>获得者</th>
            			<th>分红金额</th>
            			<th>发放信息</th>
            		</tr>
            		
            		<?php if(is_array($hiss["child"])): $i = 0; $__LIST__ = $hiss["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hist): $mod = ($i % 2 );++$i;?><tr>
            				<td><?php echo ($hist["username"]); ?></td>
            				<td><?php echo floor($hist['money'])/100;?></td>
            				<td><?php echo ($hist["intro"]); ?></td>
            			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
            	
            	</table><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
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

</div>
</body>
</html>