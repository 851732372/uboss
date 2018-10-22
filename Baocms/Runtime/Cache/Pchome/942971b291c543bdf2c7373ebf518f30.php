<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <!-- <?php echo ($CONFIG['site']['headinfo']); ?>-->
        <title><?php if($config['title'])echo $config['title'];else echo $seo_title; ?></title>
        <meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
        <meta name="description" content="<?php echo ($seo_description); ?>" />
        <link href="__TMPL__statics/css/style.css?v=20150718" rel="stylesheet" type="text/css">
        <script> var BAO_PUBLIC = '__PUBLIC__'; var BAO_ROOT = '__ROOT__';</script>
        <script src="__TMPL__statics/js/jquery.js?v=20150718"></script>
        <script src="__PUBLIC__/js/layer/layer.js?v=20150718"></script>
        <script src="__TMPL__statics/js/jquery.flexslider-min.js?v=20150718"></script>
        <script src="__TMPL__statics/js/js.js?v=20150718"></script>
        <script src="__PUBLIC__/js/web.js?v=20150718"></script>
        <script src="__TMPL__statics/js/baocms.js?v=20150718"></script>
    </head>
    <body>
        <iframe id="baocms_frm" name="baocms_frm" style="display:none;"></iframe>
<div class="topOne">

    <div class="nr">

        <?php if(empty($MEMBER)): ?><div class="left"><span class="welcome">您好，欢迎访问<?php echo ($CONFIG["site"]["sitename"]); ?></span><a href="<?php echo U('pchome/passport/login');?>">登陆</a>|<a href="<?php echo U('passport/register');?>">注册</a>

                <?php else: ?>

                <div class="left">欢迎 <b style="color: red;font-size:14px;"><?php echo ($MEMBER["nickname"]); ?></b> 来到<?php echo ($CONFIG["site"]["sitename"]); ?>&nbsp;&nbsp; <a href="<?php echo U('member/index/index');?>" >个人中心</a>|<a href="<?php echo U('pchome/passport/logout');?>" >退出登录</a><?php endif; ?>

                    <div class="topSm"> <span class="topSmt"><em>&nbsp;</em>手机CMS</span>

                        <div class="topSmnr"><img src="__PUBLIC__/img/wx.png" width="100" height="100" />

                            <p>扫描下载客户端</p>

                        </div>

                    </div>

                </div>

                <div class="right">                    

                    <ul>

                        <li class="liOne"><a class="liOneB" href="<?php echo U('member/order/index');?>" >我的订单</a><em>&nbsp;</em></li>

                        <li class="liOne"><a class="liOneA" href="javascript:void(0);">我的服务<em>&nbsp;</em></a>

                            <div class="list">

                                <ul>

                                    <li><a href="<?php echo U('member/order/index');?>">我的订单</a></li>

                                    <li><a href="<?php echo U('member/ele/index');?>">我的外卖</a></li>

                                    <li><a href="<?php echo U('member/yuyue/index');?>">我的预约</a></li>

                                    <li><a href="<?php echo U('member/dianping/index');?>">我的评价</a></li>

                                    <li><a href="<?php echo U('member/favorites/index');?>">我的收藏</a></li>                                    

                                    <li><a href="<?php echo U('member/myactivity/index');?>">我的活动</a></li>

                                    <li><a href="<?php echo U('member/life/index');?>">会员服务</a></li>

                                    <li><a href="<?php echo U('member/set/nickname');?>">帐号设置</a></li>

                                </ul>

                            </div>

                        </li>

                        <span>|</span>

                        <li class="liOne liOne_visit"><a class="liOneA" href="javascript:void(0);">最近浏览<em>&nbsp;</em></a>

                            <div class="list liOne_visit_pull">

                                <ul>

                                    <?php
 $views = unserialize(cookie('views')); $views = array_reverse($views, TRUE); if($views){ foreach($views as $v){ ?>

                                    <li class="liOne_visit_pull_li">

                                        <a href="<?php echo U('tuan/detail',array('tuan_id'=>$v['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($v["photo"]); ?>" width="80" height="50" /></a>

                                        <h5><a href="<?php echo U('tuan/detail',array('tuan_id'=>$v['tuan_id']));?>"><?php echo ($v["title"]); ?></a></h5>

                                        <div class="price_box"><a href="<?php echo U('tuan/detail',array('tuan_id'=>$v['tuan_id']));?>"><em class="price">￥<?php echo ($v["tuan_price"]); ?></em><span class="old_price">￥<?php echo ($v["price"]); ?></span></a></div>

                                    </li>

                                    <?php }?>

                                </ul>

                                <p class="empty"><a href="javascript:;" id="emptyhistory">清空最近浏览记录</a></p>

                                <?php }else{?>

                                <p class="empty">您还没有浏览记录</p>

                                <?php } ?>

                            </div>

                        </li>

                        <span>|</span>

                        <li class="liOne"> <a class="liOneA" href="javascript:void(0);">我是商家<em>&nbsp;</em></a>

                            <div class="list">

                                <ul>

                                    <li><a href="<?php echo U('shangjia/login/index');?>">商家登陆</a></li>

                                    <li><a href="<?php echo U('shangjia/index/index');?>">微信营销</a></li>

                                </ul>

                            </div>

                        </li>

                        <span>|</span>

                        <li class="liOne"> <a class="liOneA" href="javascript:void(0);" style="color:#F00; font-weight:bold;">好站长功能核心<em>&nbsp;</em></a>

                            <div class="list">

                                <ul>

                                    <li><a href="<?php echo U('pchome/news/index');?>">新闻快报</a></li>

                                    <li><a href="<?php echo U('pchome/tieba/index');?>">贴吧圈子</a></li>

                                    <li><a href="<?php echo U('pchome/seller/index');?>">商家新闻</a></li>

                                    <li><a href="<?php echo U('pchome/community/index');?>">独立小区</a></li>
                                    
                                    <li><a href="<?php echo U('pchome/housekeeping/index');?>">预约管理</a></li>
                                    
                                    <li><a href="<?php echo U('pchome/life/index');?>">分类信息</a></li>
                                    
                                    <li><a href="<?php echo U('Mobile/index/index');?>">手机版重要！</a></li>
                                    

                                </ul>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

    </div>

<script>

    $(document).ready(function(){

        $("#emptyhistory").click(function(){

            $.get("<?php echo U('tuan/emptyviews');?>",function(data){

                if(data.status == 'success'){

                    $(".liOne_visit_pull ul li").remove();

                    $(".liOne_visit_pull p.empty").html("您还没有浏览记录");

                }else{

                    layer.msg(data.msg,{icon:2});

                }

            },'json')



            //$.cookie('views', '', { expires: -1 }); 

            //$(".liOne_visit_pull ul li").remove();

            //$(".liOne_visit_pull p.empty").html("您还没有浏览记录");

        })

    });

</script>     
    <div class="topTwo">
        <div class="left">
            <?php if(!empty($CONFIG['site']['logo'])): ?><h1><a href="<?php echo U('pchome/index/index');?>"><img width="214" height="53" src="__ROOT__/attachs/<?php echo ($CONFIG["site"]["logo"]); ?>" /></a></h1>
                <?php else: ?>
                <h1><a href="<?php echo U('pchome/index/index');?>"><img width="214" height="53" src="__PUBLIC__/img/logo_03.png" /></a></h1><?php endif; ?> 
            <div class="changeCity"><?php echo ($city_name); ?><a href="<?php echo U('pchome/city/index');?>" class="change">[切换城市]</a></div>
        </div>
        <div class="left center">
            <div class="searchBox">
                <script>
                    $(document).ready(function () {
                        $(".selectList li a").click(function () {
                            $("#search_form").attr('action', $(this).attr('rel'));
                            $("#selectBoxInput").html($(this).html());
                            $('.selectList').hide();
                        });
                        $(".selectList a").each(function(){
                            if($(this).attr("cur")){
                                $("#search_form").attr('action', $(this).attr('rel'));
                                $("#selectBoxInput").html($(this).html());                                
                            }
                        })
                    });
                </script>
                <form id="search_form"  method="post" action="<?php echo U('pchome/shop/index');?>">
                    <div class="selectBox">
                        <span class="select" id="selectBoxInput">商家</span>
                        <div  class="selectList">
                            <ul>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'shop'){?> cur='true'<?php }?> rel="<?php echo U('pchome/shop/index');?>">商家</a></li>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'tuan'){?> cur='true'<?php }?>rel="<?php echo U('pchome/tuan/index');?>">抢购</a></li>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'life'){?> cur='true'<?php }?>rel="<?php echo U('pchome/life/index');?>">生活</a></li>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'mall'){?> cur='true'<?php }?>rel="<?php echo U('pchome/mall/index');?>">商品</a></li>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'tieba'){?> cur='true'<?php }?>rel="<?php echo U('pchome/tieba/index');?>">贴吧</a></li>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'news'){?> cur='true'<?php }?>rel="<?php echo U('pchome/news/index');?>">旅游</a></li>
                                <li><a href="javascript:void(0);" <?php if($ctl == 'community'){?> cur='true'<?php }?>rel="<?php echo U('pchome/community/index');?>">小区</a></li>
                            </ul>
                        </div>
                    </div>
                    <input type="text" class="text" <?php if($ctl != ding): ?>name="keyword" value="<?php echo (($keyword)?($keyword):'输入您要搜索的内容'); ?>"<?php endif; ?> onclick="if (value == defaultValue) {
                                value = '';
                                this.style.color = '#000'
                            }"  onBlur="if (!value) {
                                        value = defaultValue;
                                        this.style.color = '#999'
                                    }" />
                    <input type="submit" class="submit" value="搜索" />
                </form>
            </div>



            <div class="hotSearch">
                <?php $a =1; ?>
                <?php  $cache = cache(array('type'=>'File','expire'=> 43200)); $token = md5("Keyword,,0,7,43200,key_id desc,,"); if(!$items= $cache->get($token)){ $items = D("Keyword")->where("")->order("key_id desc")->limit("0,7")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; if($item['type'] == 0 or $item['type'] == 1): ?><a href="<?php echo U('pchome/shop/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                    <?php elseif($item['type'] == 2): ?>
                        <a href="<?php echo U('pchome/tuan/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                    <?php elseif($item['type'] == 3): ?>
                        <a href="<?php echo U('pchome/life/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                    <?php elseif($item['type'] == 4): ?>
                        <a href="<?php echo U('pchome/mall/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                        <?php elseif($item['type'] == 5): ?>
                        <a href="<?php echo U('pchome/tieba/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                        <?php elseif($item['type'] == 6): ?>
                        <a href="<?php echo U('pchome/news/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a>
                        <?php elseif($item['type'] == 7): ?>
                        <a href="<?php echo U('pchome/community/index',array('keyword'=>$item['keyword']));?>"><?php echo ($item["keyword"]); ?></a><?php endif; ?> <?php endforeach; ?>
            </div>
        </div>
        <div class="right topTwo_b">
<!--			<div class="topTwo_tel">
				服务热线：<big>055-456879852</big>
			</div>
-->			<div class="clear"></div>
            <div class="protect">
                <ul>
                    <li><em>&nbsp;</em><a href="javascript:void(0);">随时退</a></li>
                    <li class="protectLi2"><em>&nbsp;</em><a href="javascript:void(0);">不满意免单</a></li>
                    <li class="protectLi3"><em>&nbsp;</em><a href="javascript:void(0);">过期退款</a></li>
                </ul>
            </div>
        </div>
    </div>
<div class="nav">
        <div class="navList">

            <ul>
                <li class="navListAll"><!-- <img src="__TMPL__statics/images/ico_1.png" class="left"/> --><!--<i class="nav-bz left"></i>--><span class="navListAllt">全部抢购分类</span>
                    <!--<div class="shadowy navAll">此处显示  class "navAll" 的内容</div>-->
                </li>
                <li class="navLi"><a <?php if($ctl == 'index'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="首页" href="<?php echo U('pchome/index/index');?>" >首页</a></li>
                <li class="navLi"><a <?php if($ctl == 'tuan'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="抢购" href="<?php echo U('pchome/tuan/nearby');?>" >抢购</a></li>
                <li class="navLi"><a <?php if($ctl == 'huodong'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="活动" href="<?php echo U('pchome/huodong/index');?>" >活动</a></li>
                <li class="navLi"><a <?php if($ctl == 'shop'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="商家" href="<?php echo U('shop/index');?>" >商家</a></li>
                <li class="navLi"><a <?php if($ctl == 'mall'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="商城" href="<?php echo U('pchome/mall/main');?>" >商城</a></li>
                <li class="navLi"><a <?php if($ctl == 'ele'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="外卖" href="<?php echo U('pchome/ele/index');?>" >外卖</a></li>
                <li class="navLi"><a <?php if($ctl == 'ding'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="订座" href="<?php echo U('pchome/ding/index');?>" >订座</a></li>
                <li class="navLi"><a <?php if($ctl == 'life'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="分类" href="<?php echo U('pchome/life/main');?>" >分类</a></li>
              
         
                   <li class="navLi"><a <?php if($ctl == 'coupon'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="券" href="<?php echo U('pchome/coupon/index');?>" >领劵</a></li>
                           <li class="navLi"><a <?php if($ctl == 'coupon'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="新闻" href="<?php echo U('news/index');?>" >新闻</a></li>
                   <li class="navLi"><a <?php if($ctl == 'community'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="券" href="<?php echo U('community/index');?>" >小区</a></li>
                   <li class="navLi"><a <?php if($ctl == 'coupon'): ?>class="navA  on"<?php else: ?>class="navA"<?php endif; ?> title="券" href="<?php echo U('housekeeping/index');?>" >预约</a></li>
                
            </ul>
        </div>
</div>
<div class="clear"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.sy_flexslider').flexslider({
            directionNav: false,
            pauseOnAction: false
        });//首页大幻灯片
        $('.syfq_flexslider').flexslider({
            directionNav: true,
            pauseOnAction: false
        });
        $('.hottj_flexslider').flexslider({
            directionNav: true,
            pauseOnAction: false
        });
		
				
		$(".sy_flsx .all_locate").mouseover(function(){
			$(this).parent().find(".all_location_list").addClass("hover");
			$(".sy_flsx .all_location_list").mouseleave(function(){
				$(this).removeClass("hover");
			});
		});//首页全部区域显示js
		
		
		/*$(".sy_Floor_box .sy_FloorBt .center a").each(function(e){
			$(this).parents(".sy_Floor_box").find(".sy_FloorNr ul").each(function(i){
				if(e==i){
					$(this).parent().find("ul");
					$(this).show();
				}
				else{
					$(this);
				}
			});
		});*/
		
    });
</script>
<div class="content" id="index-gun">
    <div class="sy_content1">
        <div class="left sy_content1_l">
            <div class="menu_fllist2">
    <?php $i=0; ?>             
    <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item): if(($item["parent_id"]) == "0"): $i++;if($i <= 10){ ?>
        <div <?php if($i == 1): ?>class="item2 bo"<?php else: ?>class="item2"<?php endif; ?> >
            <h3>
                <div class="left"><span>&nbsp;</span><a class="menu_flt" title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id']));?>"><?php echo msubstr($item['cate_name'],0,2,'utf-8',false);?></a></div>
                <div class="right">
                    <?php $i2=0; ?>
                    <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item2): if(($item2["parent_id"]) == $item["cate_id"]): $i2++;if($i2 <= 2){ ?>
                        <a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo msubstr($item2['cate_name'],0,4,'utf-8',false);?></a>
                        <?php } endif; endforeach; endif; ?>
                    &gt;</div>
            </h3>
            <div style="height: 458px;" class="menu_flklist2">
                <div class="menu_fl2t"><a title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id']));?>"><?php echo ($item["cate_name"]); ?></a></div>
                <div class="menu_fl2nr">
                    <ul>
                        <?php $k=0; ?>
                        <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item2): if(($item2["parent_id"]) == $item["cate_id"]): $k++; ?>
                            <?php if($k%16 == 1): ?><li class="menu_fl2nrli">
                                    <ul> 
                                        <li><a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo ($item2['cate_name']); ?></a></li>
                                        <?php elseif($k%16 == 0): ?>
                                        <li><a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo ($item2['cate_name']); ?></a></li>
                                    </ul>
                                </li>
                                <?php else: ?>
                                <li><a title="<?php echo ($item2["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id'],'cate_id'=>$item2['cate_id']));?>"><?php echo ($item2['cate_name']); ?></a></li><?php endif; endif; endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php } endif; endforeach; endif; ?>
</div>

        </div>
        <div class="center sy_content1_c">
        	<div class="sy_hotgz">
                <div class="sy_hotgzNr">
                    <div class="sy_flexslider">
                        <ul class="slides">
                            <?php  $cache = cache(array('type'=>'File','expire'=> 43200)); $token = md5("Ad, closed=0 AND site_id=1 AND  city_id IN ({$city_ids})  AND bg_date <= '{$today}' AND end_date >= '{$today}' ,0,9,43200,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=1 AND  city_id IN ({$city_ids})  AND bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,9")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; if($index%3 == 1): ?><li class="sy_hotgzLi">
                                        <a target="_blank" href="<?php echo ($item["link_url"]); ?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="748" height="310" /></a>
                                    </li>
                                    <?php else: ?>
                                    <li><a target="_blank" href="<?php echo ($item["link_url"]); ?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="748" height="310" /></a></li><?php endif; ?> <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sy_flsx">
            	<div>
                	<div class="title">热门抢购</div>
                    <ul class="mid_list">
                        <?php $i=0; ?>
                        <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item): if(($item["is_hot"]) == "1"): $i++;if($i<12){ ?>
                            <?php if($item['parent_id'] == 0): ?><li><a title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['cate_id']));?>"><?php echo ($item['cate_name']); ?></a></li>
                                <?php else: ?>
                                <li><a title="<?php echo ($item["cate_name"]); ?>" target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item['parent_id'],'cate_id'=>$item['cate_id']));?>"><?php echo ($item['cate_name']); ?></a></li><?php endif; ?>
                            <?php } endif; endforeach; endif; ?>
                    </ul>
                </div>
                <div>
                	<div class="title">全部区域</div>
                    <ul class="mid_list">
                        <?php $i=0; ?>
                        <?php if(is_array($areas)): foreach($areas as $key=>$item): $i++;if($i<=15&&$item['city_id'] == $city_id){ ?>
                            <li><a href="<?php echo U('tuan/index',array('area'=>$item['area_id']));?>"><?php echo ($areas[$item['area_id']]['area_name']); ?></a></li>
                            <?php } endforeach; endif; ?>
                    </ul>
                    <div class="all_locate"></div>
                    <div class="all_location_list">
                    	<div class="title">全部区域</div>
                        <ul class="mid_list">
                        	<?php if(is_array($areas)): foreach($areas as $key=>$item): if($item['city_id'] == $city_id){ ?>
                                <li><a href="<?php echo U('tuan/index',array('area'=>$item['area_id']));?>"><?php echo ($areas[$item['area_id']]['area_name']); ?></a></li>
                                <?php } endforeach; endif; ?>
                        </ul>
                    </div>
                </div>
                <div>
                	<div class="title">热门商圈</div>
                    <ul class="mid_list">
                        <?php $i=0; ?>
                            <?php if(is_array($bizs)): foreach($bizs as $key=>$item): if(in_array($item['area_id'],$limit_area)&&$i<=10&&$item['is_hot']=='1'){ ?>
                                <li><a href="<?php echo U('tuan/index',array('area'=>$item['area_id'],'business'=>$item['business_id']));?>"><?php echo ($bizs[$item['business_id']]['business_name']); ?></a></li>
                                <?php $i++;} endforeach; endif; ?>
                    </ul>
                </div>
            </div>     
        </div>
        <div class="right sy_content1_r">
            <div class="sy_hotfq">
                <div class="syfq_flexslider">
                    <ul class="slides">
                        <?php  $cache = cache(array('type'=>'File','expire'=> 21600)); $token = md5("Ad, closed=0 AND site_id=2 AND city_id IN ({$city_ids}) AND bg_date <= '{$today}' AND end_date >= '{$today}' ,0,3,21600,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=2 AND city_id IN ({$city_ids}) AND bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,3")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li class="sy_hotgzLi"><a target="_blank" href="<?php echo ($item["link_url"]); ?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="200" height="212" /></a></li> <?php endforeach; ?>
                    </ul>
                </div>
                <div class="sy_hotfqT"><img src="__TMPL__statics/images/hott_03.jpg" /></div>
            </div>
            <div class="sy_c1wx">
                <p><img src="__PUBLIC__/img/wx.png" width="140" height="140" /></p>
                <p class="wz">扫一扫关注微信</p>
                <p><img src="__ROOT__/themes/default/Pchome/statics/images/sywx_03.png" width="64" height="45" /></p>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="sy_content2">
        <div class="sy_hottjTab">
            <ul>
                <li class="on">今日新单</li>
                <li>最热抢购</li>
            </ul>
        </div>
        <div class="sy_hottj" style="display:block;">
            <div class="hottj_flexslider">
                <ul class="slides">
                    <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Tuan,create_time desc, bg_date <= '{$today}' AND end_date >= '{$today}' AND audit=1 AND closed=0 AND city_id='{$city_id}',1,600,0,25,"); if(!$items= $cache->get($token)){ $items = D("Tuan")->where(" bg_date <= '{$today}' AND end_date >= '{$today}' AND audit=1 AND closed=0 AND city_id='{$city_id}'")->order("create_time desc")->limit("0,25")->select(); $items = D("Tuan")->CallDataForMat($items); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; if($index%5 == 1): ?><li class="sy_hotgzLi">
                                <ul>
                                    <li>
                                        <div class="sy_hottjList"><a title="<?php echo ($item['title']); ?>" target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="216" height="130" />
                                                <p class="sy_hottjbt"><?php echo ($item['title']); ?></p>
                                                <p class="sy_hottjp"><?php echo ($item['intro']); ?></p>
                                                <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                                <p class="sy_hottjJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right">已售<?php echo ($item["sold_num"]); ?></span></p>
                                            </a></div>
                                    </li>
                                    <?php elseif($index%5 == 0): ?>
                                    <li>
                                        <div class="sy_hottjList"><a title="<?php echo ($item['title']); ?>" target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="216" height="130" />
                                                <p class="sy_hottjbt"><?php echo ($item['title']); ?></p>
                                                <p class="sy_hottjp"><?php echo ($item['intro']); ?></p>
                                                <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                                <p class="sy_hottjJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right">已售<?php echo ($item["sold_num"]); ?></span></p>
                                            </a></div>
                                    </li>
                                </ul>
                            </li>
                            <?php else: ?>
                            <li>
                                <div class="sy_hottjList"><a title="<?php echo ($item['title']); ?>" target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="216" height="130" />
                                        <p class="sy_hottjbt"><?php echo ($item['title']); ?></p>
                                        <p class="sy_hottjp"><?php echo ($item['intro']); ?></p>
                                        <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                        <p class="sy_hottjJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right">已售<?php echo ($item["sold_num"]); ?></span></p>
                                    </a></div>
                            </li><?php endif; ?> <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="sy_hottj">
            <div class="hottj_flexslider">
                <ul class="slides">
                    <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Tuan,sold_num desc, bg_date <= '{$today}' AND end_date >= '{$today}' AND audit=1 AND closed=0 AND city_id='{$city_id}',1,600,0,25,"); if(!$items= $cache->get($token)){ $items = D("Tuan")->where(" bg_date <= '{$today}' AND end_date >= '{$today}' AND audit=1 AND closed=0 AND city_id='{$city_id}'")->order("sold_num desc")->limit("0,25")->select(); $items = D("Tuan")->CallDataForMat($items); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; if($index%5 == 1): ?><li class="sy_hotgzLi">
                                <ul>
                                    <li>
                                        <div class="sy_hottjList"><a title="<?php echo ($item['title']); ?>" target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="216" height="130" />
                                                <p class="sy_hottjbt"><?php echo ($item['title']); ?></p>
                                                <p class="sy_hottjp"><?php echo ($item['intro']); ?></p>
                                                <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                                <p class="sy_hottjJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right">已售<?php echo ($item["sold_num"]); ?></span></p>
                                            </a></div>
                                    </li>
                                    <?php elseif($index%5 == 0): ?>
                                    <li>
                                        <div class="sy_hottjList"><a title="<?php echo ($item['title']); ?>" target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="216" height="130" />
                                                <p class="sy_hottjbt"><?php echo ($item['title']); ?></p>
                                                <p class="sy_hottjp"><?php echo ($item['intro']); ?></p>
                                                <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                                <p class="sy_hottjJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right">已售<?php echo ($item["sold_num"]); ?></span></p>
                                            </a></div>
                                    </li>
                                </ul>
                            </li>
                            <?php else: ?>
                            <li>
                                <div class="sy_hottjList"><a title="<?php echo ($item['title']); ?>" target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" width="216" height="130" />
                                        <p class="sy_hottjbt"><?php echo ($item['title']); ?></p>
                                        <p class="sy_hottjp"><?php echo ($item['intro']); ?></p>
                                        <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                        <p class="sy_hottjJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right">已售<?php echo ($item["sold_num"]); ?></span></p>
                                    </a></div>
                            </li><?php endif; ?> <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    


    
    <script type="text/javascript" language="javascript">
    
		$(document).ready(function(){
			
			$('.center a').click(function(){
				var val = $(this).attr('val');
				var f = $(this).parent().parent().parent().attr('ajax');
				$.post('<?php echo U("Pchome/index/get_arr");?>',{val:val},function(result){
					if(result.status == 'success'){
						var arr = result.arr;
						var res = '';
						var url = '';
						var price = 0;
						var tuan_price = 0;
						$.each(arr, function (n, value) {
							url = "<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>";
							price = (value.price/100).toFixed(2);
							tuan_price = (value.tuan_price/100).toFixed(2);
							res += '<ul>';
							res += '<li class="sy_FloorLi"><div class="sy_sjcpLi">';
							res += '<a target="_blank" href="'+url+'"><img src="__ROOT__/attachs/'+value.photo+'"  width="275" height="166" /></a>';
							res += '<p class="sy_hottjbt">'+value.title+'</p><p class="sy_hottjp">'+value.intro+'</p><hr style="border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />';
							res += '<p class="sy_hottjJg sy_sjcpJg"><span class="left">¥'+tuan_price+'<del>¥'+price+'</del></span><span class="right"><a target="_blank" class="sy_hottjJd" href="'+url+'">立即抢购</a></span></p>';
							res += '</div></li>';
							res += '</ul>';

						});
						$('#ajaxc'+f).html(res);
						
					}else{
						$('#ajaxc'+f).html('');
					}
				},'json')
			})
			
		})
	
    </script>
    
    <?php $i=0; ?>
    <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item1): if(($item1["parent_id"]) == "0"): $i++;if($i <= 10){ ?>
        <div class="sy_Floor_box">
            <div class="radius3 sy_FloorBt" data="top_<?php echo ($i); ?>" id="floor<?php echo ($i); ?>"  ajax="<?php echo ($i); ?>">
                <div class="left sy_FloorBtz"><span><?php echo ($i); ?>F</span><span class="sy_FloorBtl"><?php echo msubstr($item1['cate_name'],0,2,'utf-8',false);?></span></div>
                <div class="fr">
                    <div class="center">
                        <?php $i2=0; ?>
                        <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item2): if(($item2["parent_id"]) == $item1["cate_id"]): $i2++;if($i2 <= 10){ ?>
                                <a title="<?php echo ($item2["cate_name"]); ?>" href="javascript:void(0);" val="<?php echo ($item2["cate_id"]); ?>"><?php echo ($item2["cate_name"]); ?></a> <!-- <?php echo U('tuan/index',array('cat'=>$item1['cate_id'],'cate_id'=>$item2['cate_id']));?> -->
                                <?php } endif; endforeach; endif; ?>
                        </div>
                    <div class="right"><a target="_blank" href="<?php echo U('tuan/index',array('cat'=>$item1['cate_id']));?>">查看全部>></a></div>
                    <div class="clear"></div>
                    </div>
                <div class="clear"></div>
            </div>
            <?php $cate_id = $item1['cate_id']; $catarray = D('Tuancate')->getChildren($cate_id); $cateids = join(',',$catarray); ?>
            
            <div class="sy_FloorNr"  id="ajaxc<?php echo ($i); ?>">
                <ul>
                    <?php  $cache = cache(array('type'=>'File','expire'=> 600)); $token = md5("Tuan,orderby asc, bg_date <= '{$today}' AND end_date >= '{$today}' AND audit=1 AND closed=0 AND city_id='{$city_id}' AND cate_id IN ({$cateids}),1,600,0,8,"); if(!$items= $cache->get($token)){ $items = D("Tuan")->where(" bg_date <= '{$today}' AND end_date >= '{$today}' AND audit=1 AND closed=0 AND city_id='{$city_id}' AND cate_id IN ({$cateids})")->order("orderby asc")->limit("0,8")->select(); $items = D("Tuan")->CallDataForMat($items); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li class="sy_FloorLi">
                            <div class="sy_sjcpLi">
                                <a target="_blank" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>"><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>"  width="275" height="166" /></a>
                                <p class="sy_hottjbt"><?php echo ($item["title"]); ?></p>
                                <p class="sy_hottjp"><?php echo ($item["intro"]); ?></p>
                                <hr style=" border:none 0px; border-bottom: 1px solid #e0e0e0; margin-top:6px;" />
                                <p class="sy_hottjJg sy_sjcpJg"><span class="left">¥<?php echo round($item['tuan_price']/100,2);?><del>¥<?php echo round($item['price']/100,2);?></del></span><span class="right"><a target="_blank" class="sy_hottjJd" href="<?php echo U('tuan/detail',array('tuan_id'=>$item['tuan_id']));?>">立即抢购</a></span></p>
                                <div class="sy_sjcpBq"><?php if(($item["freebook"]) == "1"): ?><span class="sy_sjcpBq1">免预约</span><?php endif; if(($item["is_new"]) == "1"): ?><span class="sy_sjcpBq2">新单</span><?php endif; if(($item["is_hot"]) == "1"): ?><span class="sy_sjcpBq3">热门</span><?php endif; if(($item["is_chose"]) == "1"): ?><span class="sy_sjcpBq4">精选</span><?php endif; ?></div>

                                </div>
                            </li> <?php endforeach; ?>
                    </ul>
            </div>
        </div>
        <?php } endif; endforeach; endif; ?>
</div>


<div class="indexpop" id="fox-food">
    <ul>
        <?php $i=0; ?>
        <?php if(is_array($tuancates)): foreach($tuancates as $key=>$item): if(($item["parent_id"]) == "0"): $i++; ?>
            <li><a href="#floor<?php echo ($i); ?>"><div class="floorico">&nbsp;</div><?php echo bao_msubstr($item['cate_name'],0,2,false);?></a></li><?php endif; endforeach; endif; ?>
    </ul>
</div>
<script>


$(function(){
    $("#fox-food li").each(function(e){
        $(this).click(function(event){
            $(".radius3").each(function(i){
                if(e==i)
                {
                   $("html,body").animate({scrollTop:$(this).offset().top},500);
                   event.preventDefault();                  
                }
            });
        });
    });
});


$(document).ready(function(){
    $(window).scroll(function(){
        var top = $(document).scrollTop();          //定义变量，获取滚动条的高度
        var menu = $("#fox-food");                      //定义变量，抓取#menu
        var items = $("#index-gun").find(".sy_FloorBt");    //定义变量，查找.item

        var curId = "";                             //定义变量，当前所在的楼层item #id 

        items.each(function(){
            var m = $(this);                        //定义变量，获取当前类
            var itemsTop = m.offset().top;        //定义变量，获取当前类的top偏移量
            if(top > itemsTop-92){
                curId = "#" + m.attr("id");
            }else{
                return false;
            }
        });

        //给相应的楼层设置cur,取消其他楼层的cur
        var curLink = menu.find(".cur");
        if( curId && curLink.attr("href") != curId ){
            curLink.removeClass("cur");
            menu.find( "[href=" + curId + "]" ).addClass("cur");
        }
        // console.log(top);
    });
	/*控制*/
	$(window).scroll(function () {
		if ($(window).scrollTop() <220) {
			$(".indexpop").css("top","220px");
			$(".indexpop").css("bottom","auto");
		}
		else{
			$(".indexpop").css("top","40px");
			$(".indexpop").css("bottom","auto");
		}
	});
	
	
});


$(function () {
	if (document.all) {
		$('.indexpop ul li:nth-child(1) .floorico').css('background-position', 'center -2px')
		$('.indexpop ul li:nth-child(2) .floorico').css('background-position', 'center -69px')
		$('.indexpop ul li:nth-child(3) .floorico').css('background-position', 'center -131px')
		$('.indexpop ul li:nth-child(4) .floorico').css('background-position', 'center -266px')
		$('.indexpop ul li:nth-child(5) .floorico').css('background-position', 'center -470px')
		$('.indexpop ul li:nth-child(6) .floorico').css('background-position', 'center -330px')
		$('.indexpop ul li:nth-child(7) .floorico').css('background-position', 'center -601px')
		$('.indexpop ul li:nth-child(8) .floorico').css('background-position', 'center -399px')
		$('.indexpop ul li:nth-child(9) .floorico').css('background-position', 'center -532px')
		$('.indexpop ul li:nth-child(10) .floorico').css('background-position', 'center -198px')


		$('.menu_fllist2 .item2:nth-child(1) span').css('background-position', 'center 10px')
		$('.menu_fllist2 .item2:nth-child(2) span').css('background-position', 'center -35px')
		$('.menu_fllist2 .item2:nth-child(3) span').css('background-position', 'center -83px')
		$('.menu_fllist2 .item2:nth-child(4) span').css('background-position', 'center -179px')
		$('.menu_fllist2 .item2:nth-child(5) span').css('background-position', 'center -324px')
		$('.menu_fllist2 .item2:nth-child(6) span').css('background-position', 'center -227px')
		$('.menu_fllist2 .item2:nth-child(7) span').css('background-position', 'center -416px')
		$('.menu_fllist2 .item2:nth-child(8) span').css('background-position', 'center -273px')
		$('.menu_fllist2 .item2:nth-child(9) span').css('background-position', 'center -369px')
		$('.menu_fllist2 .item2:nth-child(10) span').css('background-position', 'center -135px')
	}
});
</script>




<div class="footer-content">
<div id="footer" class="footer">
<div class="footer-inner clearfix flexible">
<div class="footer-size">
<h3>公司信息</h3>
<ul>
 <li><a target="_blank" title="关于我们" href="<?php echo U('article/system',array('content_id'=>1));?>">关于我们</a></li>
<li><a target="_blank" title="联系我们" href="<?php echo U('article/system',array('content_id'=>3));?>">联系我们</a></li>
<li><a target="_blank" title="人才招聘" href="<?php echo U('article/system',array('content_id'=>2));?>">人才招聘</a></li>
<li><a target="_blank" title="免责声明" href="<?php echo U('article/system',array('content_id'=>6));?>">免责声明</a></li>
</ul>
</div>
<div class="footer-size-2">
<h3>商户合作</h3>
<ul>
<li><a href="<?php echo U('shop/apply');?>">商家入驻</a></li>
<li><a target="_blank" title="广告合作" href="<?php echo U('article/system',array('content_id'=>5));?>">广告合作</a></li>
<li><a href="<?php echo U('news/index');?>">商家新闻</a></li>
</ul>
</div>
<div class="footer-size-2">
<h3>用户帮助</h3>
<ul>
<li><a target="_blank" title="服务协议" href="<?php echo U('article/system',array('content_id'=>7));?>">服务协议</a></li>
<li><a target="_blank" title="退款承诺" href="<?php echo U('news/index');?>">退款承诺</a></li>
</ul>
</div>
<div class="footer-size-2">
<h3>关于我们</h3>
<ul>
 <li><a target="_blank" title="关于我们" href="<?php echo U('community/index');?>">智慧小区</a></li>
<li><a target="_blank" title="联系我们" href="<?php echo U('news/index');?>">旅游信息</a></li>
<li><a target="_blank" title="人才招聘" href="<?php echo U('pchome/life/main');?>">分类信息</a></li>
</ul>
</div>
<div class="footer-size-3">
<h3><?php echo ($CONFIG["site"]["tel"]); ?></h3>
<ul>
<li>周一至周日&nbsp;9:00-22:00</li>
<li>客服电话&nbsp;免长途费</li>
</ul>
<a href="##" class="mobile-btn">手机专享优惠</a>
</div>
</div>
</div>
<div class="clear"></div>
<div id="copyright-info">
<div class="site-info">
<span class="copyright">
©</span>2018&nbsp;<?php echo ($_SERVER['HTTP_HOST']); ?>&nbsp;<!--<?php echo ($CONFIG["site"]["sitename"]); ?>版权所有 - --> <?php echo ($CONFIG["site"]["icp"]); ?>
<?php echo ($CONFIG["site"]["tongji"]); ?>
</div>

</div>
</div>






<div class="topUp">
    <ul>
        <li class="topBack"><div class="topBackOn">回到<br />顶部</div></li>
        <li class="topUpWx"><div class="topUpWxk"><img src="__PUBLIC__/img/wx.png" width="149" height="149" /><p>扫描二维码关注微信</p></div></li>
    </ul>
</div>

<script>
    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(window).scrollTop() > 100) {
                $(".topUp").show();
                $(".indexpop").show();
            } else {
                $(".topUp").hide();
                $(".indexpop").hide();
            }
            var ctl = "<?php echo ($ctl); ?>";
            if(ctl == 'coupon'){
                if ($(window).scrollTop() > 665) {
                    $(".spxq_xqT2").show();
                } else {
                    $(".spxq_xqT2").hide();
                }
            }else{
                if ($(window).scrollTop() > '<?php echo ($height_num); ?>') {
                    $(".spxq_xqT2").show();
                } else {
                    $(".spxq_xqT2").hide();
                }
            }
        });

        $(".topBack").click(function () {
           $("html,body").animate({scrollTop: 0}, 200);
        });
		$(window).scroll(function(){
			var top = $(document).scrollTop();          //定义变量，获取滚动条的高度
			var menu = $(".topUp");                      //定义变量，抓取topUp
			var items = $(".footerOut");    //定义变量，查找footerOut 
			items.each(function(){
				var m=$(this);
				var itemsTop = m.offset().top;      //定义变量，获取当前类的top偏移量
				if(itemsTop-360 <= top-360){
					menu.css('bottom','360px');
					menu.css('top','auto');
				}else{
					menu.css('bottom','0px');
					menu.css('top','auto');
				}
			});
		});		
    });
</script>

</body>

</html>