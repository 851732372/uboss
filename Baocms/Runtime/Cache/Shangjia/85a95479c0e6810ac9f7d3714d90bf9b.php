<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($CONFIG["site"]["title"]); ?>商户中心</title>
<meta name="description" content="<?php echo ($CONFIG["site"]["title"]); ?>商户中心" />
<meta name="keywords" content="<?php echo ($CONFIG["site"]["title"]); ?>商户中心" />
<link href="__TMPL__statics/css/newstyle.css" rel="stylesheet" type="text/css" />
 <link href="__PUBLIC__/js/jquery-ui.css" rel="stylesheet" type="text/css" />
<script>
var BAO_PUBLIC = '__PUBLIC__'; var BAO_ROOT = '__ROOT__';
</script>
<script src="__PUBLIC__/js/jquery.js"></script>
<script src="__PUBLIC__/js/jquery-ui.min.js"></script>
<script src="__PUBLIC__/js/web.js"></script>
<script src="__PUBLIC__/js/layer/layer.js"></script>

</head>
<style>
	.tuan_content{
        padding:0px!important;
    }
   .sjgl_main{
   	height:700px!important;
   }
    </style>
<body>
<iframe id="baocms_frm" name="baocms_frm" style="display:none;"></iframe>
<link href='__PUBLIC__/assets/stylesheets/bootstrap/bootstrap.css' media='all' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/webupload/globle.css" />
<script src="__PUBLIC__/webupload/webuploader.min.js"></script>
<script src="__PUBLIC__/webupload/diyUpload.js"></script>
<div class="sjgl_lead">
    <ul>
        <li><a href="#">系统设置</a> > <a href="">店铺管理</a> > <a>店铺形象图</a></li>
    </ul>
</div>
<div class="tuan_content">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t">
            <div class="left tuan_topser_l">这里可以上传店铺或公司的形象图片</div>
        </div>
    </div> 
    <div class="tuanfabu_tab">
        <ul>
            <!-- <li class="tuanfabu_tabli tabli_change"><a href="<?php echo U('shop/about');?>">店铺文字资料</a></li> -->
            <li class="tuanfabu_tabli tabli_change on"><a href="<?php echo U('shop/image');?>">店铺形象图</a></li>
            <li class="tuanfabu_tabli tabli_change"><a href="<?php echo U('shop/logo');?>">店铺LOGO</a></li>
            <li class="tuanfabu_tabli tabli_change"><a href="<?php echo U('shop/photo');?>">店铺环境图</a></li>
        </ul>
    </div>
    <div class="tabnr_change  show">
        <form method="post" action="<?php echo U('shop/image');?>"  target="baocms_frm" class='form form-horizontal validate-form' style='margin-bottom: 0;' >
            <div class='control-group'>
                <label class='control-label' for='validation_ip'>店铺形象图</label>
                <input type="hidden" name="photo" value="<?php echo ($photo); ?>" id="data_img" />
                <div class='controls'>
                    <ul class="upload-ul clearfix">
                        <li style="position: relative;">
                                <img src="/attachs/<?php echo ($photo); ?>" style="height:90px" width="120px">
                                <span class="close" style="position: absolute;top:-5px;right:0px;color:#ccc" onclick="deli(this)">&times;</span>
                            </li>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <script>
                $(function(){
                    //上传图片
                    var $tgaUpload = $('#goodsUpload').diyUpload({
                        url:'<?php echo U("app/upload/uploadify");?>',
                        success:function( data ) {
                           $("#data_img").val(data._raw);
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
                 function deli(){
                    $.post('<?php echo U("ajax_del1");?>',{},function(data){
                        location.reload();
                    })
                }
            </script>
            <div class='form-actions' style='margin-bottom:0'>
                <button class='btn btn-primary' type='submit'>
                    <i class='icon-save'></i>
                    保存数据
                </button>
            </div>
        </form>
    </div> 
</div>
</body>
</html>