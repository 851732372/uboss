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
<div class="mainBt">
    <ul>
        <li class="li2"><a href="<?php echo U('index');?>">图片管理</a></li>
        <li class="li2 li3">修改图片</li>
    </ul>
</div>
<form  action="<?php echo U('edit');?>" method="post">
	<input type="hidden" name="id" value="<?php echo ($detail["id"]); ?>">
    <div class="mainScAdd">
        <div class="tableBox">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            	<tr>
	                <td class="lfTdBt">类型：</td>
	                <td class="rgTdBt">
	                    <div class="lt">
	                    	<select name="type" id="" class="form-control">
	                    		<option value="1" <?php if(($detail["type"]) == "1"): ?>selected<?php endif; ?>>轮播</option>
	                    		<option value="2" <?php if(($detail["type"]) == "2"): ?>selected<?php endif; ?>>广告</option>
	                    	</select>
	                    </div>
	                </td>
	            </tr> 
	            <tr>
	                <td class="lfTdBt">链接：</td>
	                <td class="rgTdBt">
	                    <div class="lt">
	                        <input class="scAddTextName w210 sj" type="text" name="href" id="href"  value="<?php echo ($detail["href"]); ?>"/>
	                    </div>
	                </td>
	            </tr>  
	          	<tr>
	                <td class="lfTdBt">
	                	<script type="text/javascript" src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js"></script>
	                	<link rel="stylesheet" href="__PUBLIC__/js/uploadify/uploadify.css">
	                	图片:
	                </td>
	                <td class="rgTdBt">
	                    <div style="width: 300px;height: 100px; float: left;">
	                        <input type="hidden" name="img" value="<?php echo ($detail["img"]); ?>" id="data_logo" />
	                        <input id="logo_file" name="logo_file" type="file" multiple="true" value="" />
	                    </div>
	                    <div style="width: 300px;height: 100px; float: left;">
	                        <img id="logo_img" width="80" height="80"  src="__ROOT__/attachs/<?php echo (($detail["img"])?($detail["img"]):'default.jpg'); ?>" />
	                        <a href="<?php echo U('setting/attachs');?>">缩略图设置</a>
	                        建议尺寸:<?php echo ($CONFIG["attachs"]["shoplogo"]["thumb"]); ?>
	                    </div>
	                    <script>
	                        $("#logo_file").uploadify({
	                            'swf': '__PUBLIC__/js/uploadify/uploadify.swf?t=<?php echo ($nowtime); ?>',
	                            'uploader': '<?php echo U("app/upload/uploadify",array("model"=>"shoplogo"));?>',
	                            'cancelImg': '__PUBLIC__/js/uploadify/uploadify-cancel.png',
	                            'buttonText': '上传图片',
	                            'fileTypeExts': '*.gif;*.jpg;*.png',
	                            'queueSizeLimit': 1,
	                            'onUploadSuccess': function (file, data, response) {
	                                $("#data_logo").val(data);
	                                $("#logo_img").attr('src', '__ROOT__/attachs/' + data).show();
	                            }
	                        });
	                    </script>
	                </td>
	            </tr>
		        <tr>
		            <td class="lfTdBt">位置：</td>
		            <td class="rgTdBt"><input type="text" name="pos" class="scAddTextName w210" value="<?php echo ($detail["pos"]); ?>" />
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