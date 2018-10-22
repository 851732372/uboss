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
<link href='__PUBLIC__/assets/stylesheets/bootstrap/bootstrap.css' media='all' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/webupload/globle.css" />
<script src="__PUBLIC__/webupload/webuploader.min.js"></script>
<script src="__PUBLIC__/webupload/diyUpload.js"></script>
<div class="panel penel-default">
    <div class="panel-heading">
       <ul class="breadcrumb">
            <li>
                <a href="#">图片管理</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="#">添加图片</a> <span class="divider">/</span>
            </li>
        </ul>
    </div>
    <div class="panel-body">
    	<form  action="<?php echo U('add');?>" method="post" class='form form-horizontal validate-form' target="baocms_frm">
            <div class='control-group'>
                <div class='control-label'>
                    <label>类型：</label>
                </div>
                <div class='controls'>
                    <select name="type" id="" >
                		<option value="1">轮播</option>
                		<option value="2">广告</option>
                	</select>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>链接：</label>
                <div class='controls'>
                    <input class="scAddTextName w210 sj" type="text" name="href" id="href"  value=""/>
                </div>
            </div>
            <div class='control-group'>

                <label class='control-label'>图片：</label>
                <input type="hidden" name="img" id="logo">
                <div class='controls'>
                    <ul class="upload-ul clearfix">
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload"></div>
                        </li>
                    </ul>
                	<code>宽750*高200</code>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>位置：</label>
                <div class='controls'>
                    <input type="text" name="pos" class="scAddTextName w210" />
                </div>
            </div>
		    <div class='form-actions' style='margin-bottom:0'>
                <button class='btn btn-primary' type='submit'>
                    <i class='icon-save'></i>
                    保存数据
                </button>
            </div>
		</form>
    </div>
</div>
<!-- 商家logo -->
<script>
    $(function(){
        //上传图片
        var $tgaUpload = $('#goodsUpload').diyUpload({
            url:'<?php echo U("app/upload/uploadify");?>',
            success:function( data ) {
               $("#logo").val(data._raw);
            },  
            error:function( err ) { },
            buttonText : '',
            accept: {
                title: "Images",
                extensions: 'gif,jpg,jpeg,bmp,png'
            },
            thumb:{
                width:120,
                height:90,
                quality:100,
                allowMagnify:false,
                crop:true,
                type:"image/jpeg, image/png, image/jpg, image/gif"
            }
        });

    });
</script>

</div>
</body>
</html>