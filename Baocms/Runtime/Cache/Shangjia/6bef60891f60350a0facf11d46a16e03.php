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
<script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<link rel="stylesheet" href="__PUBLIC__/bs/css/bootstrap.min.css">
<div class="sjgl_lead">
    <ul>
        <li><a href="#">商家管理</a> > <a href="">客户</a> > <a>客户详情</a></li>
    </ul>
</div>
<style>
    tr td,th{
        text-align: center;
        vertical-align: middle!important;
    }
    table p{
        margin:0px!important;
    }
</style>
<table class="table table-bordered">
    <tr>
        <td rowspan=4>
            <img src="<?php echo ($user["face"]); ?>" alt="" height="80px" width="80px" class="img-circle">
            <p class="help-block">手机号：<?php echo ($user["mobile"]); ?></p>
        </td>
        <th>会员级别</th>
        <td colspan="3">
            <?php if(($user["level_id"]) == "1"): ?>普通消费者<?php endif; ?>
            <?php if(($user["level_id"]) == "2"): ?>黄金会员<?php endif; ?>
            <?php if(($user["level_id"]) == "3"): ?>钻石会员<?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>用户ID</th>
        <td><?php echo ($user["user_id"]); ?></td>
        <th>用户昵称</th>
        <td><?php echo ($user["nickname"]); ?></td>
    </tr>
     <tr>
        <th>最后登录时间</th>
        <td><?php echo (date('Y-m-d H:i:s',$user['last_time'])); ?></td>
        <th>最后登录IP</th>
        <td><?php echo ($user["last_ip"]); ?></td>
    </tr>
</table>
<h5 style="margin-left: 20px;">统计信息</h5>
<table class="table table-bordered">
    <tr>
        <th>本店消费金额</th>
        <th>本店订单数量</th>
        <th>本店评价</th>
        <th>退货记录</th>
    </tr>
    <tr>
        <td><?php echo ($money); ?>元</td>
        <td><?php echo ($onum); ?></td>
        <td><?php echo ($com); ?></td>
        <td><?php echo ($back); ?></td>
    </tr>
</table>
<h5 style="margin-left: 20px;">订单记录</h5>
<table class="table table-bordered">
    <tr>
        <th>订单编号</th>
        <th>订单金额</th>
        <th>订单状态</th>
        <th>下单时间</th>
        <th>查看订单</th>
    </tr>
    <?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><tr>
            <td><?php echo ($o["order_id"]); ?></td>
            <td><?php echo round($o['total_price']/100,2);?>元</td>
            <td>
                <!-- 1 待付款 2 支付超时 3 取消订单 4已付款 5 付款失败 6待使用 7待评价 8 已完成 9 退款中 10 退款失败 11 已退款 -->
                <?php if(($o["status"]) == "1"): ?>待付款<?php endif; ?>
                <?php if(($o["status"]) == "2"): ?>支付超时<?php endif; ?>
                <?php if(($o["status"]) == "3"): ?>取消订单<?php endif; ?>
                <?php if(($o["status"]) == "4"): ?>已付款<?php endif; ?>
                <?php if(($o["status"]) == "5"): ?>付款失败<?php endif; ?>
                <?php if(($o["status"]) == "6"): ?>待使用<?php endif; ?>
                <?php if(($o["status"]) == "7"): ?>待评价<?php endif; ?>
                <?php if(($o["status"]) == "8"): ?>已完成<?php endif; ?>
                <?php if(($o["status"]) == "9"): ?>退款中<?php endif; ?>
                <?php if(($o["status"]) == "10"): ?>退款失败<?php endif; ?>
                <?php if(($o["status"]) == "11"): ?>已退款<?php endif; ?>
            </td>
            <td><?php echo (date('Y-m-d H:i:s',$o["create_time"])); ?></td>
            <td><a href="<?php echo U('goods_info',array('order_id' => $o['order_id']));?>">查看订单</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo ($page); ?>
</body>
</html>