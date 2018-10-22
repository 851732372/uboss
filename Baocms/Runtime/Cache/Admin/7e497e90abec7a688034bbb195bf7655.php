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
    .breadcrumb .active{
        background: #ccc;
        color:#fff;
    }
    .breadcrumb li a{
        padding:5px 10px;
    }
</style>
<link href='__PUBLIC__/assets/stylesheets/bootstrap/bootstrap.css' media='all' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/webupload/globle.css" />
<script src="__PUBLIC__/webupload/webuploader.min.js"></script>
<script src="__PUBLIC__/webupload/diyUpload.js"></script>
<div class="panel penel-default">
    <div class="panel-heading">
       <ul class="breadcrumb">
            <li><a href="<?php echo U('set',array('id' => $_GET['id']));?>">基本信息</a><span class="divider">/</span></li>
            <li><a href="<?php echo U('over_order',array('id' => $_GET['id']));?>">买单设置</a><span class="divider">/</span></li>
            <li><a href="<?php echo U('check_authen',array('id' => $_GET['id']));?>" class="active">认证资料</a><span class="divider">/</span></li>
           
            <li><a href="<?php echo U('index');?>">返回商户列表</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <form action="" method="POST" class='form form-horizontal validate-form' target="baocms_frm">
            <div class='control-group'>
                <div class='control-label'>
                    <label>真实姓名：</label>
                </div>
                <div class='controls'>
                    <input name="realname" type="text" class="form-control" value="<?php echo ($info["realname"]); ?>" />
                </div>
            </div>
            <div class='control-group'>
                <div class='control-label'>
                    <label>手机号：</label>
                </div>
                <div class='controls'>
                    <input name="telphone" type="text" class="form-control" value="<?php echo ($info["telphone"]); ?>"/>
                </div>
            </div>
            <div class='control-group'>
                <div class='control-label'>
                    <label>身份证号：</label>
                </div>
                <div class='controls'>
                    <input name="idcardno" type="text" class="form-control" value="<?php echo ($info["idcardno"]); ?>"/>
                </div>
            </div>    
            <div class='control-group'>
                <div class='control-label'>
                    <label>身份证照片：</label>
                </div>
                <div class='controls'>
                    <input type="hidden" name="idcardimgs" id="img" value="<?php echo ($info["idcardimgs"]); ?>">
                    <ul class="upload-ul clearfix">
                        <?php if(isset($info['idcardimgs1'])){ ?>
                        <?php if(is_array($info["idcardimgs1"])): $i = 0; $__LIST__ = $info["idcardimgs1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i; if(!empty($img)){ ?>
                            <li style="position: relative;">
                                <img src="/attachs/<?php echo ($img); ?>" style="height:90px" width="120px" height="90px"> <span class="close" style="position: absolute;top:0px;right:0px;color:white" onclick="deli(this,<?php echo ($key); ?>,<?php echo ($info["id"]); ?>,1)">&times;</span>
                            </li>
                            <?php } endforeach; endif; else: echo "" ;endif; ?>
                        <?php } ?>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload"></div>
                        </li>
                    </ul>
                </div>
            </div>   
            <div class='control-group'>
                <div class='control-label'>
                    <label>营业执照注册号：</label>
                </div>
                <div class='controls'>
                    <input type="text" name="licence" class="form-control" value="<?php echo ($info["licence"]); ?>"/>
                </div>
            </div>  
            <div class='control-group'>
                <div class='control-label'>
                    <label>营业执照照片：</label>
                </div>
                <div class='controls'>
                    <input type="hidden" name="licenceimgs" id="img2" value="<?php echo ($info["licenceimgs"]); ?>">
                    <ul class="upload-ul clearfix">
                        <?php if(isset($info['licenceimgs1'])){ ?>
                        <?php if(is_array($info["licenceimgs1"])): $i = 0; $__LIST__ = $info["licenceimgs1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i; if(!empty($img)){ ?>
                            <li style="position: relative;">
                                <img src="/attachs/<?php echo ($img); ?>" style="height:90px" width="120px" height="90px"> <span class="close" style="position: absolute;top:0px;right:0px;color:white" onclick="deli(this,<?php echo ($key); ?>,<?php echo ($info["id"]); ?>,2)">&times;</span>
                            </li>
                            <?php } endforeach; endif; else: echo "" ;endif; ?>
                        <?php } ?>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload2"></div>
                        </li>
                    </ul>
                </div>
            </div>      
            <div class='control-group'>
                <div class='control-label'>
                    <label>餐饮许可证编号：</label>
                </div>
                <div class='controls'>
                    <input type="text" name="meatlicence"  value="<?php echo ($info["meatlicence"]); ?>"/>
                </div>
            </div>  
            <div class='control-group'>
                <div class='control-label'>
                    <label>餐饮许可证图片：</label>
                </div>
                <div class='controls'>
                    <input type="hidden" name="meatlicenceimgs" id="img3" value="<?php echo ($info["meatlicenceimgs"]); ?>">
                    <ul class="upload-ul clearfix">
                        <?php if(isset($info['meatlicenceimgs1'])){ ?>
                        <?php if(is_array($info["meatlicenceimgs1"])): $i = 0; $__LIST__ = $info["meatlicenceimgs1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i; if(!empty($img)){ ?>
                            <li style="position: relative;">
                                <img src="/attachs/<?php echo ($img); ?>" style="height:90px" width="120px" height="90px"> <span class="close" style="position: absolute;top:0px;right:0px;color:white" onclick="deli(this,<?php echo ($key); ?>,<?php echo ($info["id"]); ?>,3)">&times;</span>
                            </li>
                            <?php } endforeach; endif; else: echo "" ;endif; ?>
                        <?php } ?>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload3"></div>
                        </li>
                    </ul>
                </div>
            </div>  
            <input type="hidden" name="shop_id" value="<?php echo ($_GET['id']); ?>">
            <div class='form-actions' style='margin-bottom:0'>
                <button class='btn btn-primary' type='submit'>
                    <i class='icon-save'></i>
                    保存资料
                </button>
            </div>
        </form>
    </div>
</div>
<script>
// 身份证
    $(function(){
        //上传图片
        var $tgaUpload = $('#goodsUpload').diyUpload({
            url:'<?php echo U("app/upload/uploadify");?>',
            success:function( data ) {
                img = $("#img").val();
               $("#img").val(img + ','+ data._raw );
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
                allowMagnify:true,
                crop:true,
                type:"image/jpeg, image/png, image/jpg, image/gif"
            }
        });

    });
    // 营业执照照片
     $(function(){
        //上传图片
        var $tgaUpload = $('#goodsUpload2').diyUpload({
            url:'<?php echo U("app/upload/uploadify");?>',
            success:function( data ) {
                img = $("#img2").val();
               $("#img2").val(img + ','+ data._raw );
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
                allowMagnify:true,
                crop:true,
                type:"image/jpeg, image/png, image/jpg, image/gif"
            }
        });

    });
     // 餐饮许可证图片
      $(function(){
        //上传图片
        var $tgaUpload = $('#goodsUpload3').diyUpload({
            url:'<?php echo U("app/upload/uploadify");?>',
            success:function( data ) {
                img = $("#img3").val();
               $("#img3").val(img + ','+ data._raw );
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
                allowMagnify:true,
                crop:true,
                type:"image/jpeg, image/png, image/jpg, image/gif"
            }
        });

    });
    function deli(obj,key,id,type){
        $.post('<?php echo U("ajax_del");?>',{key:key,id:id,type:type},function(data){
            arr = $(obj).parent().parent().prev().val().split(',');
            arr.splice(key,1);
            $(obj).parent().parent().prev().val(arr);
            $(obj).parent().remove();
        })
    }

</script>

</div>
</body>
</html>