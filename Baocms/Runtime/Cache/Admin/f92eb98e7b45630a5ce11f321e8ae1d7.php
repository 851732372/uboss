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
<link rel="stylesheet" href="__PUBLIC__/bs/css/bootstrap.min.css">
<ul class="breadcrumb">
    <li>商家管理</li>
    <li>订单详情</li>
</ul>
<div class="panel panel-default">
    <table class="table table-hover table-striped table-bordered">
        <tr>
            <th>订单ID</th>
            <td><?php echo ($list["order_id"]); ?></td>
            <th>用户</th>
            <td><?php echo ($list["nickname"]); ?></td>
        </tr>
        <tr>
            <th>商品数量</th>
            <td><?php echo ($list["num"]); ?></td>
            <th>手机号</th>
            <td><?php echo ($list["mobile"]); ?></td>
        </tr>
        <tr>
            <th>订单状态</th>
            <td>
                <?php if(($list["status"]) == "1"): ?>待付款<?php endif; ?>
                <?php if(($list["status"]) == "4"): ?>已付款<?php endif; ?>
                <?php if(($list["status"]) == "3"): ?>已取消<?php endif; ?>
                <?php if(($list["status"]) == "6"): ?>待使用<?php endif; ?>
                <?php if(($list["status"]) == "7"): ?>待评价<?php endif; ?>
                <?php if(($list["status"]) == "9"): ?>申请退款<?php endif; ?>
                <?php if(($list["status"]) == "11"): ?>已退款<?php endif; ?>
            </td>
            <th>下单时间</th>
            <td><?php echo (date('Y-m-d H:i:s',$list["create_time"])); ?></td>
        </tr>
        <tr>
            <th>商品名称</th>
            <td><?php echo ($list["title"]); ?></td>
            <th>订单金额</th>
            <td><?php echo round($list['total']/100,2);?>元</td>
        </tr>
        <tr>
            <th>支付方式</th>
            <td>
                <?php if(($list["trade_style"]) == "1"): ?>支付宝<?php endif; ?>
                <?php if(($list["trade_style"]) == "2"): ?>微信<?php endif; ?>
                <?php if(($list["trade_style"]) == "3"): ?>余额<?php endif; ?>
                <?php if(($list["trade_style"]) == "4"): ?>支付宝+余额<?php endif; ?>
                <?php if(($list["trade_style"]) == "5"): ?>微信+余额<?php endif; ?>
            </td>
            <th>会员级别</th>
            <td>
                <?php if(($list["level_id"]) == "1"): ?><code>普通</code><?php endif; ?>
                <?php if(($list["level_id"]) == "2"): ?><code>黄金</code><?php endif; ?>
                <?php if(($list["level_id"]) == "3"): ?><code>钻石</code><?php endif; ?>
            </td>
        </tr>
    </table>
    <hr>
     <table class="table table-hover table-striped">
        <tr>
            <th>日志</th>
            <th>时间</th>
        </tr>
        <tr>
            <td>订单提交成功</td>
            <td><?php echo (date('Y-m-d H:i:s',$list["create_time"])); ?></td>
        </tr>
        <tr>
            <td>订单支付成功</td>
            <?php if(!empty($list['pay_time'])){ ?>
             <td><?php echo (date('Y-m-d H:i:s',$list["pay_time"])); ?></td>
            <?php }else{ ?>
            <td>--</td>
            <?php } ?>
        </tr>
        <tr>
            <td>用户确认订单完成</td>
            <?php if(!empty($list['success_time'])){ ?>
              <td><?php echo (date('Y-m-d H:i:s',$list["success_time"])); ?></td>
            <?php }else{ ?>
            <td>--</td>
            <?php } ?>
        </tr>
    </table>
</div>

</div>
</body>
</html>