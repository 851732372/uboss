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
        <a href="#">商家列表</a></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<a class="btn btn-primary" href="<?php echo U('add');?>"><span class="glyphicon glyphicon-plus"></span>添加申请</a>
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline pull-right">
			<!-- 商户名称 -->
			<div class="form-group">
				<label for="">商户名称</label>
				<input type="text" class="form-control" name="shop_name" value="<?php echo ($shop_name); ?>">
			</div>
			<!-- 分类 -->
			<div class="form-group">
				<label for="">商户分类</label>
				<select name="cate_id" id="" class="form-control">
					<option value="">请选择</option>
                    <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $cate_id): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option>                
                        <?php if(is_array($cates)): foreach($cates as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><option value="<?php echo ($var2["cate_id"]); ?>"<?php if(($var2["cate_id"]) == $cate_id): ?>selected="selected"<?php endif; ?>>|---<?php echo ($var2["cate_name"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
                </select>
			</div>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>编号</th>
				<th>LOGO</th>
				<th>商户名称</th>
				<th>分类名称</th>
				<th>联系手机</th>
				<th>总营收</th>
				<th>余额</th>
				<th>人均消费</th>
				<th>生成二维码</th>
				<th>参数标识</th>
				<th>创建时间</th>
			    <th>操作</th>	
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["shop_id"]); ?></td>
					<td><img src="/attachs/<?php echo ($lis["logo"]); ?>" alt="" height="50px" width="50px" onclick = "select_img('<?php echo ($lis["shop_id"]); ?>')"></td>
					<td><?php echo ($lis["shop_name"]); ?></td>
					<td><?php echo ($lis["cate_name"]); ?></td>
					<td><?php echo ($lis["tel"]); ?></td>
					<td><?php echo ($lis['sy']/100); ?>元</td>
					<td><?php echo ($lis['money']/100); ?>元</td>
					<td><?php echo ($lis['price']/100); ?>元</td>
					<td><img src="/attachs/<?php echo ($lis["qrcode"]); ?>" alt="" height="50px" width="50px"><br><?php echo BA('bussiness/short_url',array("shop_id"=>$lis["shop_id"]),'生成二维码','load','',600,280);?></td>
					<td><?php echo ($lis["code"]); ?></td>
					<td><?php echo (date("Y-m-d H:i:s",$lis["create_time"])); ?></td>
					<td>
						<?php if(($lis["closed"]) == "0"): if(($lis["is_recom"]) == "1"): ?><code><?php echo BA('bussiness/norecom',array("shop_id"=>$lis["shop_id"]),'取消推荐','act','a2');?> </code><?php endif; ?>
						<?php if(($lis["is_recom"]) == "0"): echo BA('bussiness/recom',array("shop_id"=>$lis["shop_id"]),'推荐','act','a2'); endif; ?>
						| <a href="<?php echo U('set',array('id' => $lis['shop_id']));?>">设置</a> | <a href="<?php echo U('shop/login',array('shop_id'=>$lis['shop_id']));?>" target="_blank">管理</a> | <?php echo BA('bussiness/del_shop',array("shop_id"=>$lis["shop_id"]),'删除','act','a2');?>
						<?php else: ?>
						<code>已经删除</code><?php endif; ?>
						<?php if(in_array($lis['cate_id'],$hotel_id)){ ?> | 
							<?php if(($lis["is_enough"]) == "0"): echo BA('bussiness/hotel',array("shop_id"=>$lis["shop_id"]),'未满房','act','a2'); endif; ?>
							<?php if(($lis["is_enough"]) == "1"): echo BA('bussiness/hotels',array("shop_id"=>$lis["shop_id"]),'已满房','act','a2'); endif; ?>
						<?php } ?>
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
		<img src="" alt="" class="rimg img-responsive center-block" height="180px" width="180px">
	</div>
<script>
function select_img(id){
	$.post("<?php echo U('ajax_select_simg');?>",{id:id},function(data){
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

</div>
</body>
</html>