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
<style>
	.remberBtn{
		margin-top: 0px!important;
	}
</style>
<ul class="breadcrumb">
    <li>
        <a href="#">创始人管理</a> <span class="divider"></span>
    </li>
    <li>
        <a href="#">添加创始人</a> <span class="divider"></span>
    </li>
</ul>
<form action="" method="post">
    <div class="mainScAdd">
        <div class="tableBox">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;">
            	 <tr>
                    <td class="lfTdBt">选择用户：</td>
                    <td class="rgTdBt">
                        <div class="lt">
                            <input type="hidden" id="user_id" name="user_id"/>
                            <input type="text" id="mobile" name="mobile" value="" class="manageInput" />
                        </div>
                        <a mini="select"  w="1000" h="600" href="<?php echo U('founder/userinfo');?>" class="remberBtn">选择用户</a>
                    </td>
                </tr>
	            <tr>
	                <td class="lfTdBt">行业类型：</td>
	                <td class="rgTdBt">
	                    <select id="cate_id" name="shop_cate_id" class="seleFl w210">
	                        <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option>
	                            <?php if(is_array($cates)): foreach($cates as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><option value="<?php echo ($var2["cate_id"]); ?>"  <?php if(($var2["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($var2["cate_name"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
	                    </select>
	                </td>
	            </tr>    
	            <tr>
	                <td class="lfTdBt">店铺类型：</td>
	                <td class="rgTdBt">
	                    <select id="cate_id" name="store_type" class="seleFl w210">
	                    	<option value="1">旗舰店</option>
	                    	<option value="2">核心店</option>
	                    	<option value="3">人气店</option>
	                    </select>
	                </td>
	            </tr>    
	    	</table>
       		<input type="submit" value="确认添加" class="smtQrIpt" />
		</div>
	</div>
</form>

</div>
</body>
</html>