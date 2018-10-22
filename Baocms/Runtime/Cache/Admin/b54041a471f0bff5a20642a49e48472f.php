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
  <style>
        body {
            margin: 0;
            padding: 0;
        }
        input {
            padding:5px;
            margin-right: 10px;
            border: 1px solid #d9d9d9;
            border-radius:4px;
            color: rgba(0, 0, 0, 0.65);
            transition: all .3s;
        }
        .first_input {
            margin-right: 7px;
        }
        input::-webkit-input-placeholder {
            color: #c1c1c1;
        }
        input::-moz-placeholder {
            color: #c1c1c1;
        }
        input::-ms-input-placeholder {
            color: #c1c1c1;
        }
        input:focus {
            border-color: #40a9ff;
            -webkit-box-shadow: 0 2px 0 #1890ff;
            box-shadow: 0 0 5px rgba(24, 144, 255, 0.5);
        }

        .btn_common {
            display: inline-block;
            background-color: #40a9ff;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
            -webkit-box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
            text-align: center;
            font-weight: 400;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 14px;
            color: #fff;
            padding: 2.5px 8px;
            margin: 2.5px;
        }
        .btn_common:active {
            background-color: #1890ff;
            transition: all 0.1s ease-in, color 0.1s ease-out;
        }

        .input_Parent, .add_Parent_btn {
            /*margin-left: 15px;*/
        }
        .input_Child {
            margin-left: 35px;
        }
        .first_addclass, .first_removeclass {
            margin-left: 1px;
            margin-right: 1px;
        }
    </style>
    <div class="panel penel-default">
        <div class="panel-heading">
           <ul class="breadcrumb">
                <li>
                    <a href="#">商城管理</a> <span class="divider">></span>
                </li>
                <li>
                    <a href="#">商品编辑</a> <span class="divider">></span>
                </li>
            </ul>
        </div>
        <div class="panel-body">
            <form class='form form-horizontal validate-form' style='margin-bottom: 0;' target="baocms_frm" action="<?php echo U('goods/edit');?>" method="post"/>
                <input type="hidden" name="goods_id" value="<?php echo ($_GET['goods_id']); ?>">
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_secret'>商品名称</label>
                    </div>
                    <div class='controls'>
                        <input data-rule-buga='true' name="title" data-rule-required='true' id='validation_secret' name='validation_secret' placeholder='商品名称' value="<?php echo ($detail["title"]); ?>" type='text' />
                    </div>
                </div>
                <div class='control-group'>
                    <label class='control-label' for='validation_iban'>商品标签</label>
                    <div class='controls'>
                        <input  name='instructions' value="<?php echo ($detail["instructions"]); ?>" placeholder='商品标签' type='text' />
                         <small class='muted'>以 | 隔开</small>
                    </div>
                </div>
                <!-- <div class='control-group'>
                    <label class='control-label' for='validation_select'>商品分类</label>
                    <div class='controls'>
                         <select id="cate_id" name="cate_id" class="manageSelect w200" style="width: 140px;">
                            <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option>                
                                    <?php if(is_array($cates)): foreach($cates as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><option value="<?php echo ($var2["cate_id"]); ?>"  <?php if(($var2["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($var2["cate_name"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
                        </select>
                    </div>
                </div> -->
                 <!-- <div class='control-group'> -->
                   <!--  <label class='control-label' for='validation_select'>选择商家</label>
                    <div class='controls'>
                         <select id="shop_id" name="shop_id" class="manageSelect w200" style="width: 140px;">
                            <option value="">选择商家</option>
                            <?php if(is_array($shop)): foreach($shop as $key=>$var): ?><option value="<?php echo ($var["shop_id"]); ?>" <?php if(($var["shop_id"]) == $detail["shop_id"]): ?>selected<?php endif; ?>><?php echo ($var["shop_name"]); ?>(<?php echo ($var["shop_id"]); ?>)</option><?php endforeach; endif; ?>
                        </select>
                    </div> -->
                <!-- </div> -->
                <div class='control-group'>
                    <label class='control-label' for='validation_select'>选择商家</label>
                    <div class='controls'>
                         <div class="lt">
                            <input type="hidden" id="shop_id" name="shop_id" value="<?php echo ($detail["shop_id"]); ?>"/>
                            <input type="text" id="shop_name" name="shop_name" class="manageInput" value="<?php echo ($detail["shop_name"]); ?>"/>
                        </div>
                        <a mini="select"  w="1000" h="600" href="<?php echo U('goods/select_shop');?>" class="remberBtn" style="margin-top: 0px;">选择商家</a>
                    </div>
                </div>
                <div class='control-group'>
                    <label class='control-label' for='validation_ip'>商品logo图片<br><code>图片大小<?php echo ($CONFIG["attachs"]["GoodsLogo"]["thumb"]); ?></code></label>
                    <input type="hidden" name="photo" id="logo" value="<?php echo ($detail["photo"]); ?>">
                    <div class='controls'>
                        <ul class="upload-ul clearfix">
                            <li style="position: relative;">
                                <img src="/attachs/<?php echo ($detail["photo"]); ?>" style="height:90px" width="120px">
                            </li>
                            <li class="upload-pick">
                                <div class="webuploader-container clearfix" id="goodsUpload1"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <script>
                    $(function(){
                        //上传图片
                        var $tgaUpload = $('#goodsUpload1').diyUpload({
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
                <div class='control-group'>
                    <label class='control-label' for='validation_ip'>商品图片列表 <code>图片大小<?php echo ($CONFIG["attachs"]["DetailBanner"]["thumb"]); ?></code></label>
                    <input type="hidden" name="photos" id="img" value="<?php echo ($detail["photos"]); ?>">
                    <div class='controls'>
                        <ul class="upload-ul clearfix">
                            <?php if(isset($detail['img'])){ ?>
                            <?php if(is_array($detail["img"])): $i = 0; $__LIST__ = $detail["img"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i; if(!empty($img)){ ?>
                                <li style="position: relative;">
                                    <img src="/attachs/<?php echo ($img); ?>" style="height:90px" width="120px" height="90px"> <span class="close" style="position: absolute;top:0px;right:0px;color:white" onclick="deli(this,<?php echo ($key); ?>,<?php echo ($detail["goods_id"]); ?>)">&times;</span>
                                </li>
                                <?php } endforeach; endif; else: echo "" ;endif; ?>
                            <?php } ?>
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
                                img = $("#img").val();
                               $("#img").val(img +','+ data._raw );
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
                        $.post('<?php echo U("ajax_del");?>',{key:key,id:id},function(data){
                            $(obj).parent().remove();
                            location.reload();
                        })
                    }
                </script>
                <div class='control-group'>
                    <div class='control-label'>
                        <label >商品价格</label>
                    </div>
                    <div class='controls'>
                        <input  name='mall_price' value="<?php echo ($detail['mall_price']/100); ?>" placeholder='商城价格' type='text' />
                        <span class='add-on'>元</span>
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_phone'>门店价格</label>
                    </div>
                    <div class='controls'>
                        <input name='price' value="<?php echo ($detail['price']/100); ?>" type='text' />
                        <span class='add-on'>元</span>
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'>
                        <label >商品库存</label>
                    </div>
                    <div class='controls'>
                        <input  name='stock' value="<?php echo ($detail["stock"]); ?>" placeholder='商品库存' type='text' />
                    </div>
                </div>
                
                <div class='control-group'>
                    <label class='control-label' for='validation_iban'>浏览量</label>
                    <div class='controls'>
                        <input  name='views' value="<?php echo ($detail["views"]); ?>" placeholder='浏览量' type='text' />
                    </div>
                </div>
                <div class='control-group'>
                    <label class='control-label' for='validation_iban'>出售量</label>
                    <div class='controls'>
                        <input  name='sold_num' value="<?php echo ($detail["sold_num"]); ?>" placeholder='出售量' type='text' />
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_vin'>套餐</label>
                    </div>
                    <div class='controls'>
                        <?php if(is_array($detail["standard"])): $i = 0; $__LIST__ = $detail["standard"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ds): $mod = ($i % 2 );++$i; if(is_array($ds)): $k = 0; $__LIST__ = $ds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($k % 2 );++$k;?><code><?php echo ($ds[$key]); ?></code><br><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_vin'>套餐重新编辑</label>
                    </div>
                    <div class='controls'>
                        <div class="standard">
                            <div class="inputsWrapper">
                                <div class="input_Parent standard[1]">
                                    <input type="text" name="standard[1][1]" class="first_input" placeholder=""/>
                                    <div class="btn_common addclass first_addclass">新增</div>
                                    <div class="btn_common removeclass first_removeclass">删除</div>
                                </div>
                            </div>
                            <div class="btn_common add_Parent_btn" class="btn btn-info">添加更多的input输入框</div>
                        </div>      
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_vin'>温馨提示</label>
                    </div>
                    <div class='controls'>
                        <?php if(is_array($detail["details"])): $i = 0; $__LIST__ = $detail["details"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ds): $mod = ($i % 2 );++$i; if(is_array($ds)): $k = 0; $__LIST__ = $ds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($k % 2 );++$k;?><code><?php echo ($ds[$key]); ?></code><br><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_vin'>温馨提示重新编辑</label>
                    </div>
                    <div class='controls'>
                        <div class="details">
                            <div class="inputsWrapper">
                                <div class="input_Parent details[1]">
                                    <input type="text" name="details[1][1]" class="first_input" placeholder=""/>
                                    <div class="btn_common addclass first_addclass">新增</div>
                                    <div class="btn_common removeclass first_removeclass">删除</div>
                                </div>
                            </div>
                            <div class="btn_common add_Parent_btn" class="btn btn-info">添加更多的input输入框</div>
                        </div>          
                    </div>
                </div>
                <script>
                   $(document).ready(function() {
                        let ParentCount = 1;
                        let ChildCount = 1;
                        // 新增父类操作
                        $("body").on("click", '.add_Parent_btn', function (e) {
                            ParentCount++;
                            const className =  $(this).parent('div').prop("class");
                            $(this).parent('div').children('.inputsWrapper').append(
                                '<div class="input_Parent '+ className + '[' + ParentCount + ']">' +
                                    '<input type="text" name="'+ className + '[' + ParentCount + '][1]" placeholder=" '+ ParentCount +'"/>' +
                                    '<div class="btn_common addclass">新增</div>' +
                                    '<div class="btn_common removeclass">删除</div>' +
                                '</div>'
                            );
                            return false;
                        });

                        // 新增子类操作
                        $("body").on("click", '.addclass', function(e) {
                            ChildCount++;
                            const className =  $(this).parent('div').prop("class");
                            const regex = /( )(\w+\[\d])$/;
                            const r = className.match(regex);
                            console.log('r', r);
                            if (r) {
                                const childClassName = r[2];
                                $(this).parent('div').append(
                                    '<div class="input_Child ' + childClassName + ChildCount + '">' +
                                        '<input type="text" name="' + childClassName + '['+ ChildCount +']" placeholder=" '+ ChildCount +'"/>' +
                                        '<div class="btn_common removeclass">删除</div>' +
                                    '</div>'
                                );
                            } else {
                                return false
                            }

                            return false;
                        });
                        // 删除操作
                        $("body").on("click",".removeclass", function(e) {
                                $(this).parent('div').remove();
                            return false;
                        });
                    });

                </script>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_vin'>图文详情</label>
                    </div>
                    <div class='controls'>
                        <script type="text/plain" id="data_instructions" name="extends_con" style="width:800px;height:360px;"><?php echo ($detail["extends_con"]); ?></script>
                        <link rel="stylesheet" href="__PUBLIC__/umeditor/themes/default/css/umeditor.min.css" type="text/css">
                        <script type="text/javascript" charset="utf-8" src="__PUBLIC__/umeditor/umeditor.config.js"></script>
                        <script type="text/javascript" charset="utf-8" src="__PUBLIC__/umeditor/umeditor.min.js"></script>
                        <script type="text/javascript" src="__PUBLIC__/umeditor/lang/zh-cn/zh-cn.js"></script>
                        <script>
                            um = UM.getEditor('data_instructions', {
                                imageUrl: "<?php echo U('app/upload/editor');?>",
                                imagePath: '__ROOT__/attachs/editor/',
                                lang: 'zh-cn',
                                langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
                                focus: false
                            });
                        </script>
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

</div>
</body>
</html>