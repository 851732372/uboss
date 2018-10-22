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
<link rel="stylesheet" href="__PUBLIC__/bs/css/bootstrap.min.css">
<script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<div class="sjgl_lead">
    <ul>
        <li><a href="#">商家管理</a> > <a href="">商城</a> > <a>我的订单</a></li>
    </ul>
</div>
<div class="tuan_content">
    <form method="post" action="<?php echo U('orders/index');?>" class="form-inline">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t">
            <div class="left tuan_topser_l">
            开始时间：<input type="text" class="radius3 tuan_topser"  name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"/>
            结束时间：<input type="text" class="radius3 tuan_topser"  name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"/>
            订单编号：<input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="radius3 tuan_topser" />
            <input type="submit" style="margin-left:10px;" class="radius3 sjgl_an tuan_topbt" value="搜 索"/>
            </div>
        </div>
    </div>
    </form>
    <!-- 1 待付款 2 支付超时 3 取消订单 4 已付款 5 付款失败 6待使用 7待评价 8 已完成 9 退款中 10 退款失败 -->
    <div class="tuanfabu_tab">
        <ul>
            <li class="tuanfabu_tabli <?php if(($status) == "1"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index');?>">待付款</a></li>
            <li class="tuanfabu_tabli <?php if(($status) == "4"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index',array('status' => 4));?>">已付款</a></li>
            <li class="tuanfabu_tabli <?php if(($status) == "7"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index',array('status' => 7));?>">待评价</a></li>
             <li class="tuanfabu_tabli <?php if(($status) == "8"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index',array('status' => 8));?>">已完成</a></li>
            <li class="tuanfabu_tabli <?php if(($status) == "9"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index',array('status' => 9));?>">申请退款</a></li>
            <li class="tuanfabu_tabli <?php if(($status) == "11"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index',array('status' => 11));?>">已退款</a></li>
            <li class="tuanfabu_tabli <?php if(($status) == "10"): ?>on<?php endif; ?>"><a href="<?php echo U('orders/index',array('status' => 10));?>">退款失败</a></li>
        </ul>
        <p class="pull-right">共有<?php echo ($count); ?>条数据</p>
    </div> 
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="tuan_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr style="background-color:#eee;">
                    <td>订单ID</td>
                    <td>订单编号</td>
                    <td>商品名称</td>
                    <td>数量</td>
                    <td>合计</td>
                    <td>状态</td>
                    <td>创建时间</td>
                    <td>来源</td>
                    <td>操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><?php echo ($var["order_id"]); ?></td>
                        <td><?php echo ($var["orderno"]); ?></td>
                        <td><?php echo ($var["goodsname"]); ?></td>
                        <td><?php echo ($var["num"]); ?></td>
                        <td><?php echo floor($var['total_price'])/100;?>元</td>
                        <td>
                            <?php if(($var["status"]) == "1"): ?>待付款<?php endif; ?>
                            <?php if(($var["status"]) == "4"): ?>已付款【待使用】<?php endif; ?>
                            <?php if(($var["status"]) == "3"): ?>已取消<?php endif; ?>
                            <?php if(($var["status"]) == "7"): ?>待评价<?php endif; ?>
                            <?php if(($var["status"]) == "8"): ?>已完成<?php endif; ?>
                            <?php if(($var["status"]) == "9"): ?>申请退款<?php endif; ?>
                            <?php if(($var["status"]) == "11"): ?>已退款<?php endif; ?>
                            <?php if(($var["status"]) == "10"): ?>退款失败<?php endif; ?>
                            <?php if(($var["status"]) == "12"): ?>分红开始结算<?php endif; ?>
                        </td>
                        <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                        <td>
                            <?php if(($var["type"]) == "1"): ?>商城支付<?php endif; ?>
                            <?php if(($var["type"]) == "2"): ?>扫码支付<?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo U('info',array('order_id' => $var['order_id'],'status' => $var['status']));?>">查看</a>
                        </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
    </div>
</div>

</body>
</html>