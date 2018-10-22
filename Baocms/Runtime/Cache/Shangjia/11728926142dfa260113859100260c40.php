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
<style>
    .sjgl_leftmenu,.leftMenu_li,.sjgl_leftmenu ul{
        width:148px !important;
        
    }
    .sjgl_leftmenu a{
        font-size: 12px !important;
    }
    
</style>
<script type="text/javascript">

	$(document).ready(function(){
		$(".leftMenu_a").click(function(){ 
            if($(this).hasClass("on")){
                console.log("!!!");
                $(".leftMenu_a").removeClass("on");
                $(".leftNav2").hide();
            }else{
                console.log("###");
                $(".leftMenu_a").removeClass("on");
                $(this).addClass("on");
                $(".leftNav2").hide();
                $(this).parent().find(".leftNav2").show();  
            }
		});

		$(".leftNav2 li").click(function(){

			$(".leftNav2 li a").removeClass('on');

			$(this).find('a').addClass('on');

		});

	});

</script>
<div class="sjgl_top">
    <div class="left sjgl_toplogo">商家后台</div>
    <div class="right sjgl_top_r">
        <ul>
            <?php if(($hotel) == "1"): if(($is_enough) == "0"): ?><li class="left sjgl_topli sjgl_toplixl"><a href="<?php echo U('shangjia/Index/hotel');?>" target="baocms_frm">设置满房状态</a></li><?php endif; ?>
                <?php if(($is_enough) == "1"): ?><li class="left sjgl_topli sjgl_toplixl"><a href="<?php echo U('shangjia/Index/hotels');?>" target="baocms_frm">清除满房状态</a></li><?php endif; endif; ?>
            <li class="left sjgl_topli sjgl_toplixl"><a href="<?php echo U('shangjia/index/index');?>"><?php echo ($SHOP["account"]); ?>，您好！</a>
            	<div class="sjgl_topxl">
                    <div class="sjgl_topcz">
                        <div class="left"><p class="sjgl_topyet">帐户余额</p></div>
                        <div class="right"><a class="radius5 sjgl_topczA" target="main_frm" href="<?php echo U('mone/tixian');?>">立即提现</a></div>
                    </div>
                </div>
            </li>
            <li class="left sjgl_topli"><a href="<?php echo U('login/logout');?>"><em class="toptc"><img src="__TMPL__statics/images/toptc_03.png" /></em>退出</a></li>
        </ul>
    </div>
</div>
<div class="sjglBox">
    <div class="left sjgl_leftmenu">
        <ul>
            <li class="leftMenu_li"><a class="leftMenu_a on" target="main_frm" href="<?php echo U('index/main');?>"><em>&nbsp;</em>首页</a></li>
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a3" target="main_frm" href="javascript:;"><em>&nbsp;</em>商品</a>
            	<div class="leftNav2">
                    <ul>
                        <li><a target="main_frm" href="<?php echo U('goods/index');?>"><em>&nbsp;</em>商品管理</a></li>
                        <li><a target="main_frm" href="<?php echo U('orders/index');?>"><em>&nbsp;</em>订单管理</a></li>
                        <li><a target="main_frm" href="<?php echo U('coupon/index');?>"><em>&nbsp;</em>消费券</a></li>
                    </ul>
                </div>
            </li>
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a5" target="main_frm" href="javascript:;"><em>&nbsp;</em>客户</a>
            	<div class="leftNav2">
                    <ul>
                        <li><a target="main_frm" href="<?php echo U('custom/index');?>"><em>&nbsp;</em>我的客户</a></li>
                        <li><a class="on"  target="main_frm" href="<?php echo U('fans/index');?>"><em>&nbsp;</em>粉丝列表</a></li>
                    </ul>
                </div>
            </li>
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a12" target="main_frm" href="javascript:;"><em>&nbsp;</em>评价</a>
                <div class="leftNav2">
                    <ul>  
                        <li><a target="main_frm" href="<?php echo U('comment/index');?>"><em>&nbsp;</em>评价列表</a></li>
                    </ul>
                </div>
            </li>
           <!--  <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a11" target="main_frm" href="<?php echo U('ding/setting');?>"><em>&nbsp;</em>订座</a>
            	<div class="leftNav2">
                    <ul>
                        <li><a class="on" target="main_frm" href="javascript:;"><em>&nbsp;</em>订座配置</a></li>
                        <li><a target="main_frm" href="<?php echo U('dingorder/index');?>"><em>&nbsp;</em>订座列表</a></li>
                        <li><a target="main_frm" href="<?php echo U('ding/room');?>"><em>&nbsp;</em>包厢设置</a></li>
                        <li><a target="main_frm" href="<?php echo U('dingcate/index');?>"><em>&nbsp;</em>菜品分类</a></li>
                        <li><a target="main_frm" href="<?php echo U('dingmenu/index');?>"><em>&nbsp;</em>菜品配置</a></li>
                    </ul>
                </div>
            </li> -->
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a13" target="main_frm" href="javascript:;"><em>&nbsp;</em>收银</a>
                <div class="leftNav2">
                    <ul>  
                        <li><a target="main_frm" href="<?php echo U('unline/index');?>"><em>&nbsp;</em>台卡管理</a></li>
                        <li><a target="main_frm" href="<?php echo U('unline/orders');?>"><em>&nbsp;</em>收款记录</a></li>
                        <!-- <li><a target="main_frm" href="<?php echo U('unline/favor');?>"><em>&nbsp;</em>优惠设置</a></li> -->
                    </ul>
                </div>
            </li>
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a13" target="main_frm" href="javascript:;"><em>&nbsp;</em>结算</a>
            	<div class="leftNav2">
                    <ul>  
                        <li><a target="main_frm" href="<?php echo U('mone/index');?>"><em>&nbsp;</em>资金管理</a></li>
                        <li><a target="main_frm" href="<?php echo U('mone/account');?>"><em>&nbsp;</em>对账单</a></li>
                    </ul>
                </div>
            </li>
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a4" target="main_frm" href="javascript:;"><em>&nbsp;</em>统计</a>
                <div class="leftNav2">
                    <ul>
                        <li><a target="main_frm" href="<?php echo U('census/index');?>"><em>&nbsp;</em>营业统计</a></li>
                        <li><a target="main_frm" href="<?php echo U('census/trade');?>"><em>&nbsp;</em>交易分析</a></li>
                    </ul>
                </div>
            </li>
            <!-- <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a10" target="main_frm" href="javascript:;"><em>&nbsp;</em>营销</a>
            	<div class="leftNav2">
                    <ul>
                        <li><a target="main_frm" href="<?php echo U('award/index');?>"><em>&nbsp;</em>抽奖</a></li>
                        <li><a target="main_frm" href="<?php echo U('vote/index');?>"><em>&nbsp;</em>投票功能</a></li>
                        <li><a target="main_frm" href="<?php echo U('weixin/index');?>"><em>&nbsp;</em>微信营销</a></li>
                        <li><a target="main_frm" href="<?php echo U('news/index');?>"><em>&nbsp;</em>文章群发</a></li>
                    </ul>
                </div>
            </li> -->
            <li class="leftMenu_li"><a class="leftMenu_a leftMenu_a2"  target="main_frm" href="javascript:;"><em>&nbsp;</em>设置</a>
                <div class="leftNav2">
                    <ul>
                        <li><a class="on" target="main_frm" href="<?php echo U('info/password');?>"><em>&nbsp;</em>修改密码</a></li>
                         <li><a target="main_frm" href="<?php echo U('shop/image');?>"><em>&nbsp;</em>店铺管理</a></li>
                         <li><a target="main_frm" href="<?php echo U('bussiness/index');?>"><em>&nbsp;</em>认证信息</a></li>
                         <li><a target="main_frm" href="<?php echo U('bussiness/account');?>"><em>&nbsp;</em>提现账号</a></li>
                        <!-- <li><a  target="main_frm" href="<?php echo U('branch/index');?>"><em>&nbsp;</em>分店地址</a></li> -->
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <div id="sjgl_right" style="padding-left:152px; height:100%;">
        <div class="sjgl_main" style="height:1000px;">
            <iframe src="<?php echo U('index/main');?>" marginheight="0" marginwidth="0" frameborder="0" width="100%" height=100% id="main_frm" name="main_frm"></iframe>
        </div>
    </div>
</div>
</body>
</html>