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
        <a href="#">图片管理</a></span>
    </li>
    <li>
        <a href="#">图片列表</a></span>
    </li>
</ul>
<div class="panel panel-default">
	<div class="panel-heading">
		<a class="btn btn-primary" href="<?php echo U('add');?>"><span class="glyphicon glyphicon-plus"></span>添加图片</a>
		<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
		<form action="" class="form-inline pull-right">
			<div class="form-group">
				<label for="">分类：</label>
				<select name="type" class="form-control">
					<option value="">请选择</option>
					<option value="1" <?php if(($type) == "1"): ?>selected<?php endif; ?>>轮播</option>
					<option value="2" <?php if(($type) == "2"): ?>selected<?php endif; ?>>广告</option>
                </select>
			</div>
			<?php if($_SESSION['admin']['admin_id'] == 1){ ?>
			<div class="form-group">
				<label for="">城市：</label>
				<select name="city_id" class="form-control">
					<option value="">请选择</option>
					<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><option value="<?php echo ($c["city_id"]); ?>" <?php if(($c["city_id"]) == $city_id): ?>selected<?php endif; ?>><?php echo ($c["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
			</div>
			<?php } ?>
			<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
		</form> 
	</div> 
	<div class="panel-body">
		<table class="table userIndexTable table-bordered">
			<tr>
				<th>编号</th>
				<th>图片</th>
				<th>链接</th>
				<th>类型</th>
				<th>城市</th>
				<th>位置/顺序</th>
			    <th>操作</th>	
			</tr>
			<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($lis["id"]); ?></td>
					<td><img src="/attachs/<?php echo ($lis["img"]); ?>" alt="" height="50px" width="150px"></td>
					<td><?php echo ($lis["href"]); ?></td>
					<td>
						<?php if(($lis["type"]) == "1"): ?>轮播<?php endif; ?>
						<?php if(($lis["type"]) == "2"): ?>广告<?php endif; ?>
					</td>
					<td><?php echo ($lis["name"]); ?></td>
					<td><?php echo ($lis["pos"]); ?></td>
					<td>
						<a href="<?php echo U('edit',array('id'=>$lis['id']));?>">修改</a> | <a href="<?php echo U('del',array('id'=>$lis['id']));?>">删除</a>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	</div>
		<?php echo ($page); ?>
	<div class="panel-footer">

	</div>
</div>
<script>
// 批量选择
 $('#checkAll').click(function(){
 	$('.check').click();
 });

function delAll(){
	datas=$('.check:checked');
	arr=new Array();
	for(i=0;i<datas.length;i++){
		arr[i]=datas.eq(i).val();
	}
	// 转字符串
	str=arr.join(',',arr);
	$.post('{:url("ajax_delAll")}',{str:str},function(data){
		if(arr.length==data){
			for(i=0;i<datas.length;i++){
				$('#tr'+arr[i]).remove();
				tot=parseInt($('#tot').html());
				  $('#tot').html(--tot);
			}
		}
	})
}
</script>

</div>
</body>
</html>