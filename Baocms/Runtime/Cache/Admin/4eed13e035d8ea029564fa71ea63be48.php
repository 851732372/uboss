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
<script src="__PUBLIC__/bs/js/bootstrap.min.js"></script>
    <ul class="breadcrumb">
        <li><a href="">评价</a> ></li>
        <li> <a>评价管理</a></li>
    </ul>
<div class="panel panel-default">
    <div class="panel-heading">
    	<div class="pull-right" style="margin-top:5px;margin-left: 25px;">共有<b id="tot"><?php echo ($count); ?></b>条数据</div>
    	<form action="" class="form-inline" method="POST">
    		<div class="form-group">
    			<label for="">状态：</label>
    			<select name="status" id="" class="form-control">
                    <option value="">全部</option>
	    			<option value="1">已回复</option>
	    			<option value="2">未回复</option>
	    		</select>
	    		<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search">搜索</span></button>
    		</div>
    	</form>
    </div>
    <table class="table table-hover table-bordered">
        <tr style="background-color:#eee;">
            <td>编号</td>
            <td>用户</td>
            <td>评分</td>
            <td>商品名称</td>
            <td>评价时间</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        <?php if(is_array($data)): foreach($data as $key=>$var): ?><tr>
                <td><?php echo ($var["comment_id"]); ?></td>
                <td><?php echo ($var["nickname"]); ?></td>
                <td><?php echo ($var["comment_starts"]); ?></td>
                <td><?php echo ($var["title"]); ?></td>
                <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                <td>
                    <?php if(($var["reply_status"]) == "0"): ?>未回复(<?php if(($var["status"]) == "0"): ?><code>审核不通过</code><?php endif; ?>
                    <?php if(($var["status"]) == "1"): ?><code>正在审核</code><?php endif; ?>
                    <?php if(($var["status"]) == "2"): ?><code>审核通过</code><?php endif; ?>)<?php endif; ?>
                    <?php if(($var["reply_status"]) == "1"): ?>已回复(<?php if(($var["status"]) == "0"): ?><code>审核不通过</code><?php endif; ?>
                    <?php if(($var["status"]) == "1"): ?><code>正在审核</code><?php endif; ?>
                    <?php if(($var["status"]) == "2"): ?><code>审核通过</code><?php endif; ?>)<?php endif; ?>
                    
                </td>
                <td>
                    <a href="<?php echo U('order/com_detail',array('com_id' => $var['comment_id']));?>">查看</a>
                </td><?php endforeach; endif; ?>
    </table>
    <?php echo ($page); ?>
   
</div>

</div>
</body>
</html>