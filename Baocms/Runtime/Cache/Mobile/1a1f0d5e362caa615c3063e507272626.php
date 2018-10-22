<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<title><?php if(!empty($seo_title)): echo ($seo_title); ?>_<?php endif; echo ($CONFIG["site"]["sitename"]); ?></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
		<?php if($CONFIG[site][concat] != 1): ?><link rel="stylesheet" href="/static/default/wap/css/base.css">
		<link rel="stylesheet" href="/static/default/wap/css/<?php echo ($ctl); ?>.css"/>
		<script src="/static/default/wap/js/jquery.js"></script>
		<script src="/static/default/wap/js/base.js"></script>
		<script src="/static/default/wap/other/layer.js"></script>
		<script src="/static/default/wap/other/roll.js"></script>
		<script src="/static/default/wap/js/public.js"></script>
		<?php else: ?>
		<link rel="stylesheet" href="/static/default/wap/css/??base.css,<?php echo ($ctl); ?>.css" />
		<script src="/static/default/wap/js/??jquery.js,base.js,roll.js,layer.js,public.js"></script><?php endif; ?>
        
        
		 <script>
            function bd_encrypt(gg_lat, gg_lon)   // 百度地图测距偏差 问题修复
            {
				var page =  "/mobile/near/dingwei/lat/"+gg_lat+"/lng/"+gg_lon+".html";
				$.get(page, function (data) {
					if(data == '1'){
						$("#local").html("附近");
					}
				}, 'html');
            }
            navigator.geolocation.getCurrentPosition(function (position) {
                bd_encrypt(position.coords.latitude, position.coords.longitude);
            });
        </script>
	</head>
	<body>
	<header class="top-fixed bg-yellow bg-inverse">
		<div class="top-local">
			<a href="<?php echo U('city/index');?>" class="top-addr"><?php echo bao_msubstr($city_name,0,2,false);?> ∨</a>
		</div>
		<div class="top-search">
			<form method="post" action="<?php echo U('shop/index');?>">
				<input name="keyword" placeholder="输入商家的关键字"  />
				<button type="submit" class="icon-search"></button> 
			</form>
		</div>
		<div class="top-signed">
			<a href="<?php echo U('near/index');?>" class="top-addr"><i class="icon-map-marker"></i> <em id="local"><?php if(!empty($local['lat'])): ?>附近<?php else: ?>定位<?php endif; ?></em></a>
		</div>
	</header>
	
	<div class="line">
		<div id="roll" class="roller">
			<div class="bd">
				<ul>                     
                        
			 <?php  $cache = cache(array('type'=>'File','expire'=> 7200)); $token = md5("Ad, closed=0 AND site_id=57 AND city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ,0,2,7200,orderby asc,,"); if(!$items= $cache->get($token)){ $items = D("Ad")->where(" closed=0 AND site_id=57 AND city_id IN ({$city_ids}) and bg_date <= '{$today}' AND end_date >= '{$today}' ")->order("orderby asc")->limit("0,2")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><li><a href="<?php echo ($item["link_url"]); ?>" target="_blank" ><img src="__ROOT__/attachs/<?php echo ($item["photo"]); ?>" /></a></li> <?php endforeach; ?>
				</ul>
			</div>
			<div class="hd">
				<ul></ul>
			</div>
		</div>
	</div>
	<script type="text/javascript">	
		TouchSlide({ 
			slideCell:"#roll",
			titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
			mainCell:".bd ul", 
			effect:"leftLoop", 
			autoPage:true //自动分页
		});
	</script>
    
    
    

	<div class="index-guide margin-top">
		<div class="line">
			<span>
				<a class="col" href="<?php echo U('shop/main');?>">	
					<img src="/static/default/wap/image/index/app-shop.png" />	
					<p>商家</p>	
				</a>
			</span>
			<span>
				<a class="col" href="<?php echo U('tuan/index');?>">	
					<img src="/static/default/wap/image/index/app-tuan.png" />
					<p>抢购</p>	
				</a>
			</span>
			<span>
				<a class="col" href="<?php echo U('coupon/main');?>">	
					<img src="/static/default/wap/image/index/app-coupon.png" />
					<p>优惠券</p>	
				</a>
			</span>
			<span>
				<a class="col" href="<?php echo U('tieba/index');?>">	
					<img src="/static/default/wap/image/index/app-tieba.png" />
					<p>贴吧</p>	
				</a>
			</span>
			<span>
				<a class="col" href="<?php echo U('nearwork/index');?>">	
					<img src="/static/default/wap/image/index/app-nearwork.png" />
					<p>招聘</p>	
				</a>
			</span>
		</div>
		<div class="line">
			<span>
				<a class="col" href="<?php echo U('mall/main');?>">	
					<img src="/static/default/wap/image/index/app-mall.png" />
					<p>商城</p>	
				</a>	
			</span>
			<span>
				<a class="col" href="<?php echo U('housekeeping/main');?>">	
					<img src="/static/default/wap/image/index/app-housekeeping.png" />
					<p>家政</p>	
				</a>
			</span>
			<span>
				<a class="col" href="<?php echo U('news/index');?>">	
					<img src="/static/default/wap/image/index/app-news.png" />
					<p>资讯</p>	
				</a>	
			</span>
			<span>
				<a class="col" href="<?php echo U('life/index');?>">	
					<img src="/static/default/wap/image/index/app-life.png" />
					<p>分类</p>	
				</a>	
			</span>
			<span>
				<a class="col" href="<?php echo U('index/more');?>">	
					<img src="/static/default/wap/image/index/app-more.png" />
					<p>更多</p>	
				</a>
			</span>
		</div>
	</div>
	
	<div class="container index-tieba border-bottom">
		<hr />
		<div class="line">
			<div class="x2">
				<img src="/static/default/wap/image/index/index-tieba.png">
			</div>
			<div class="x10">
				<?php  $cache = cache(array('type'=>'File','expire'=> 43200)); $token = md5("Post,0,1,43200,post_id desc,,,"); if(!$items= $cache->get($token)){ $items = D("Post")->where("")->order("post_id desc")->limit("0,1")->select(); $cache->set($token,$items); } ; $index=0; foreach($items as $item): $index++; ?><a href="<?php echo U('tieba/detail',array('post_id'=>$item['post_id']));?>"><?php echo ($item["title"]); ?></a> <?php endforeach; ?>
			</div>
		</div>
	</div>
	
	<div class="blank-10 bg"></div>
	
	<!--首页广告-->
	<div class="index-ads">
		<div class="line border-bottom border-top">
			<div class="x5 ad-1">
				<a href="#"><img src="/static/default/wap/image/index/ads-1.png"></a>
			</div>
			<div class="x7 border-left">
				<div class="line">
					<div class="x12 border-bottom ad-2">
						<a href="#"><img src="/static/default/wap/image/index/ads-2.png"></a>
					</div>
					<div class="x6 border-right ad-3">
						<a href="#"><img src="/static/default/wap/image/index/ads-3.png"></a>
					</div>
					<div class="x6 ad-3">
						<a href="#"><img src="/static/default/wap/image/index/ads-4.png"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--/首页广告-->
	
	<div class="blank-10 bg"></div>
	
	<div class="tab index-tab" data-toggle="click">
		<div class="tab-head">
			<ul class="tab-nav line">
				<li class="x4 active"><a href="#tab-shop">附近商家</a></li>
				<li class="x4"><a href="#tab-coupon">附近优惠</a></li>
				<li class="x4"><a href="#tab-active">附近活动</a></li>
			</ul>
		</div>
		<div class="tab-body">
			<div class="tab-panel active" id="tab-shop">
				<ul class="line">
					<?php if(is_array($shoplist)): $index = 0; $__LIST__ = $shoplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($index % 2 );++$index; if($item['city_id'] == $city_id){ ?>
                        <li class="x4">
							<a href="<?php echo U('shop/detail',array('shop_id'=>$item['shop_id']));?>">
								<img src="__ROOT__/attachs/<?php echo (($item["photo"])?($item["photo"]):'default.jpg'); ?>" >
							</a>
						</li>
                        <?php } endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="tab-panel" id="tab-coupon">
				<ul>
					<?php if(is_array($couponlist)): $index = 0; $__LIST__ = $couponlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($index % 2 );++$index; if($item['city_id'] == $city_id){ ?>
					<li class="x4">
						<a href="<?php echo U('coupon/detail',array('coupon_id'=>$item['coupon_id']));?>" >	
							<img class="pic" src="__ROOT__/attachs/<?php echo (($item["photo"])?($item["photo"]):'default.jpg'); ?>">	
						</a>
					</li>
                    <?php } endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="tab-panel" id="tab-active">
                <ul>
                    <?php if(is_array($huodong)): $index = 0; $__LIST__ = $huodong;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($index % 2 );++$index; if($item['city_id'] == $city_id){ ?>
					<li class="x4">
						<a href="<?php echo U('mobile/huodong/detail',array('activity_id'=>$item['activity_id']));?>">
							<img src="__ROOT__/attachs/<?php echo (($item["photo"])?($item["photo"]):'default.jpg'); ?>">
						</a>
					</li>
                    <?php } endforeach; endif; else: echo "" ;endif; ?>
                </ul>
			</div>
		</div>
	</div>
	
	<div class="blank-10 bg margin-top"></div>
    <!-- 
	<div class="index-title">
		<h4>今日优惠</h4>
	</div>
	<div class="container">
		<div class="line index-coupon">
			<ul>
				<?php if(is_array($couponlist)): $index = 0; $__LIST__ = $couponlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($index % 2 );++$index; if($item['city_id'] == $city_id){ ?>
				<li class="x12">
					<a class="line" href="<?php echo U('coupon/detail',array('coupon_id'=>$item['coupon_id']));?>" >	
						<img class="x4" src="__ROOT__/attachs/<?php echo (($item["photo"])?($item["photo"]):'default.jpg'); ?>">
						<div class="des x8">
							<h5><?php echo ($item["title"]); ?></h5>
							<p class="intro">
								<?php echo ($item["intro"]); ?>
							</p>
							<p class="info">
								<span>下载：<?php echo ($item["downloads"]); ?> 人</span> <em>有效期：<?php echo ($item["expire_date"]); ?></em>
							</p>
						</div>
					</a>
				</li>
                 <?php } endforeach; endif; else: echo "" ;endif; ?>
                
			</ul>
		</div>
	</div>
	
	<div class="blank-10 bg margin-top"></div>
    -->
	<div class="index-title">
		<h4>火爆团购</h4>
	</div>
	<div class="line index-tuan">
		<ul id="index-tuan">
			<script>
				$(document).ready(function () {
					loaddata('<?php echo U("tuan/loadindex",array("t"=>$nowtime,"p"=>"0000"));?>', $("#index-tuan"),true);
				});
			</script>
		</ul>
	</div>

	<style>
	.foot-fixed .foot-item { width: 25%;}
	</style>
    
    <footer class="foot-fixed">		
    <a class="foot-item <?php if(($ctl == 'index') AND ($act != 'more')): ?>active<?php endif; ?>" href="<?php echo U('index/index');?>">		
    	<span class="icon icon-home"></span>		
        	<span class="foot-label">首页</span>		
            </a>		
            
          <!--  <a class="foot-item <?php if(($ctl) == "near"): ?>active<?php endif; ?>" href="<?php echo U('near/index');?>">			<span class="icon icon-map-marker"></span>			<span class="foot-label">附近</span>		</a>	-->	
            
            <a class="foot-item <?php if(($ctl) == "favorites"): ?>active<?php endif; ?>" href="<?php echo U('favorites/index');?>">			<span class="icon icon-heart"></span>			<span class="foot-label">关注</span>		</a>		
            
            <a class="foot-item <?php if(($ctl) == "mcenter"): ?>active<?php endif; ?>" href="<?php echo U('mcenter/index/index');?>">			<span class="icon icon-user"></span>			<span class="foot-label">我的</span>		</a>		
            
            <a class="foot-item <?php if(($ctl == 'index') AND ($act == 'more')): ?>active<?php endif; ?>" href="<?php echo U('index/more');?>">			<span class="icon icon-ellipsis-h"></span>			<span class="foot-label">更多</span>		</a>	
            
            </footer>	
            
            	<iframe id="x-frame" name="x-frame" style="display:none;"></iframe>
                </body>
                
                </html>