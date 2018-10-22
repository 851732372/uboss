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
            <li><a href="<?php echo U('set',array('id' => $_GET['id']));?>"  class="active">基本信息</a><span class="divider">/</span></li>
	        <li><a href="<?php echo U('over_order',array('id' => $_GET['id']));?>">买单设置</a><span class="divider">/</span></li>
	        <li><a href="<?php echo U('check_authen',array('id' => $_GET['id']));?>">认证资料</a><span class="divider">/</span></li>
	        <li><a href="<?php echo U('index');?>">返回商户列表</a></li>
        </ul>
    </div>
    <div class="panel-body">
    	<form  action=""method="post" class='form form-horizontal validate-form' target="baocms_frm">
    		<div class='control-group'>
                <div class='control-label'>
                    <label>商家名称：</label>
                </div>
                <div class='controls'>
                    <input type="text" name="shop_name" value="<?php echo ($detail["shop_name"]); ?>"/>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>商户标识：</label>
                <div class='controls'>
                   <input type="text" name="shop_mark" value="<?php echo (($detail["shop_mark"])?($detail["shop_mark"]):''); ?>"/>
                   <code>格式如：含早/含晚/含早餐</code>
                </div>
            </div>
            <div class='control-group'>
                <div class='control-label'>
                    <label>账号：</label>
                </div>
                <div class='controls'>
                    <input type="text" name="account"  value="<?php echo ($detail["account"]); ?>" />
                </div>
            </div>
            <div class='control-group'>
                <div class='control-label'>
                    <label>密码：</label>
                </div>
                <div class='controls'>
                    <input type="text" name="password" />
                </div>
            </div>
            <div class='control-group'>
                <div class='control-label'>
                    <label>所在区域：</label>
                </div>
                <div class='controls'>
                    <select name="city_id" id="city_id" class="seleFl w210"></select>
					<select name="area_id" id="area_id" class="seleFl w210"></select>
                </div>
            </div>
            <div class='control-group'>
                <div class='control-label'>
                    <label>商户分类：</label>
                </div>
                <div class='controls'>
                    <select id="cate_id" name="cate_id" class="seleFl w210">
                        <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>" style="background:#eee" <?php if(($var["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option>                
                            <?php if(is_array($cates)): foreach($cates as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><option value="<?php echo ($var2["cate_id"]); ?>"  <?php if(($var2["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($var2["cate_name"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
                    </select>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>商铺LOGO：<br><code>图片大小<?php echo ($CONFIG["attachs"]["ShopLogo"]["thumb"]); ?></code></label>
                <input type="hidden" name="logo" id="logo" value="<?php echo ($detail["logo"]); ?>">
                <div class='controls'>
                    <ul class="upload-ul clearfix">
                        <li style="position: relative;">
                            <img src="/attachs/<?php echo ($detail["logo"]); ?>" style="height:90px" width="120px">
                        </li>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label' for='validation_ip'>店铺形象图：<br><code>图片大小<?php echo ($CONFIG["attachs"]["ShopFigure"]["thumb"]); ?></code></label>
                <input type="hidden" name="photo" value="<?php echo ($detail["photo"]); ?>" id="data_imgi" />
                <div class='controls'>
                    <ul class="upload-ul clearfix">
                        <li style="position: relative;">
                                <img src="/attachs/<?php echo ($detail["photo"]); ?>" style="height:90px" width="120px">
                            </li>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUploadi"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <script>
                $(function(){
                    //上传图片
                    var $tgaUpload = $('#goodsUploadi').diyUpload({
                        url:'<?php echo U("app/upload/uploadify");?>',
                        success:function( data ) {
                           $("#data_imgi").val(data._raw);
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
            <div class='control-group'>
                <label class='control-label'>酒店环境图：<br><code>图片大小<?php echo ($CONFIG["attachs"]["HotelBanner"]["thumb"]); ?></code><br><code>录入酒店信息时候添加</code></label>
                <input type="hidden" name="otherimgs" id="imgp" value="<?php echo ($imgs); ?>">
                <div class='controls'>
                    <ul class="upload-ul clearfix">
                        <?php if(isset($otherimgs)){ ?>
                        <?php if(is_array($otherimgs)): $i = 0; $__LIST__ = $otherimgs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i; if(!empty($otherimgs)){ ?>
                            <li style="position: relative;">
                                <img src="/attachs/<?php echo ($img); ?>" style="height:90px" width="120px" height="90px"> <span class="close" style="position: absolute;top:0px;right:0px;color:black" onclick="deli(this,<?php echo ($key); ?>,<?php echo ($_GET['id']); ?>)">&times;</span>
                            </li>
                            <?php } endforeach; endif; else: echo "" ;endif; ?>
                        <?php } ?>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUploadp"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <script>
                $(function(){
                    //上传图片
                    var $tgaUpload = $('#goodsUploadp').diyUpload({
                        url:'<?php echo U("app/upload/uploadify");?>',
                        success:function( data ) {
                           img = $("#imgp").val();
                           $("#imgp").val(img + ',' + data._raw );
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
                function deli(obj,key,id){
                    $.post('<?php echo U("ajax_del1");?>',{key:key,id:id},function(data){
                        $(obj).parent().remove();
                        location.reload();
                    })
                }
            </script>
            <div class='control-group'>
                <label class='control-label'>推荐图片：<br><code>图片大小<?php echo ($CONFIG["attachs"]["RecommImg"]["thumb"]); ?></code><br><code>推荐商家时候添加</code></label>
                <input type="hidden" name="recomimgs" id="logo1" value="<?php echo ($detail["recomimgs"]); ?>">
                <div class='controls'>
                    <ul class="upload-ul clearfix">
                        <li style="position: relative;">
                            <img src="/attachs/<?php echo ($detail["recomimgs"]); ?>" style="height:90px" width="120px">
                        </li>
                        <li class="upload-pick">
                            <div class="webuploader-container clearfix" id="goodsUpload1"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>店铺电话：</label>
                <div class='controls'>
                    <input type="text" name="tel"  value="<?php echo ($detail["tel"]); ?>"/>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>人均消费：</label>
                <div class='controls'>
                    <input type="text" name="price"  value="<?php echo floor($detail['price'])/100;?>"/>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>商家坐标：</label>
                <div class='controls'>
                    <input type="text" name="lng" id="data_lng" value="<?php echo (($detail["lng"])?($detail["lng"]):''); ?>" placeholder="经度"/>
		            <input type="text" name="lat" id="data_lat" value="<?php echo (($detail["lat"])?($detail["lat"]):''); ?>" placeholder="纬度"/>
		            <a style="margin-left: 10px;" mini="select"  w="600" h="600" href="<?php echo U('public/maps');?>" class="seleSj">百度地图</a>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>店铺具体地址：</label>
                <div class='controls'>
                    <input type="text" name="addr" value="<?php echo (($detail["addr"])?($detail["addr"]):''); ?>"/>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>提成比例：</label>
                <div class='controls'>
                   <input type="text" name="proportions" value="<?php echo (($detail["proportions"])?($detail["proportions"]):''); ?>" />%
                   <code>填写0~100之间的数字(例如:填写95则表示提现100元实际得到100X95%=95元)</code>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>固定排名：</label>
                <div class='controls'>
                   <input type="text" name="orderby" value="<?php echo (($detail["orderby"])?($detail["orderby"]):''); ?>"/>
                   <code>就是固定排名在第一位，一般建议不需要设置这个值！</code>
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label'>营业时间：</label>
                <div class='controls'>
                   <input type="text" name="bussiness_time" value="<?php echo (($detail["bussiness_time"])?($detail["bussiness_time"]):''); ?>"/>
                   <code>格式如：6:00-18:00</code>
                </div>
            </div>
            
            <input type="hidden" name="shop_id" value="<?php echo ($detail["shop_id"]); ?>">
            <div class='form-actions' style='margin-bottom:0'>
                <button class='btn btn-primary' type='submit'>
                    <i class='icon-save'></i>
                    保存数据
                </button>
            </div>
    	</form>
    </div>
</div>
<!-- 城市 -->
<script src="<?php echo U('app/datas/cityarea');?>"></script>
<script>
    var city_id = <?php echo (int)$detail['city_id'];?>;
    var area_id = <?php echo (int)$detail['area_id'];?>;
    function changeCity(cid){
        var area_str = '<option value="0">请选择.....</option>';
        for(a in cityareas.area){
           if(cityareas.area[a].city_id ==cid){
                if(area_id == cityareas.area[a].area_id){
                    area_str += '<option selected="selected" value="'+cityareas.area[a].area_id+'">'+cityareas.area[a].area_name+'</option>';
                }else{
                     area_str += '<option value="'+cityareas.area[a].area_id+'">'+cityareas.area[a].area_name+'</option>';
                }  
            }
        }
        $("#area_id").html(area_str);
    }
    $(document).ready(function(){
        var city_str = '<option value="0">请选择.....</option>';
        for(a in cityareas.city){
           if(city_id == cityareas.city[a].city_id){
               city_str += '<option selected="selected" value="'+cityareas.city[a].city_id+'">'+cityareas.city[a].name+'</option>';
           }else{
                city_str += '<option value="'+cityareas.city[a].city_id+'">'+cityareas.city[a].name+'</option>';
           }  
        }
        $("#city_id").html(city_str);
        if(city_id){
            changeCity(city_id);
        }
        $("#city_id").change(function(){
            city_id = $(this).val();
            changeCity($(this).val());
        });
        
        $("#area_id").change(function () {
            var url = '<?php echo U("business/child",array("area_id"=>"0000"));?>';
            if ($(this).val() > 0) {
                var url2 = url.replace('0000', $(this).val());
                $.get(url2, function (data) {
                    $("#business_id").html(data);
                }, 'html');
            }

        });
    });
</script>
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
<script>
    $(function(){
        //上传图片
        var $tgaUpload = $('#goodsUpload1').diyUpload({
            url:'<?php echo U("app/upload/uploadify");?>',
            success:function( data ) {
               $("#logo1").val(data._raw);
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