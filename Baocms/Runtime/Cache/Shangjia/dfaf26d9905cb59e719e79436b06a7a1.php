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
<div class="sjgl_lead">
    <ul>
        <li><a href="#">商家管理</a> > <a href="">客户</a> > <a>我的客户</a></li>
    </ul>
</div>
<div class="tuan_content">
    <form method="post" action="<?php echo U('custom/index');?>">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t">
            <div class="left tuan_topser_l">
            客户昵称：<input type="text" class="radius3 tuan_topser"  name="nickname" value="<?php echo ($nickname); ?>" />
            </div>
            <div class="left tuan_topser_l">
            手机号：<input type="text" class="radius3 tuan_topser"  name="tel" value="<?php echo ($tel); ?>" />
            </div>
            <div class="left tuan_topser_l">
                下单时间： <input type="text" name="start_date" value="<?php echo (($start_date)?($start_date):''); ?>" onfocus="WdatePicker();" class="tuanfabu_int tuanfabu_intw2" autocomplete="off"/> 至 <input type="text" name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker();" class="tuanfabu_int tuanfabu_intw2" autocomplete="off"/>
            <input type="submit" style="margin-left:10px;" class="radius3 sjgl_an tuan_topbt" value="搜 索"/>
            </div>
        </div>
    </div>
    </form>
    <div class="tuanfabu_tab">
        <ul>
            <li class="tuanfabu_tabli"><a href="javascript:;">数据列表</a></li>
        </ul>
    </div> 
    <table class="tuan_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr style="background-color:#eee;">
            <td>客户ID</td>
            <td>手机号</td>
            <td>昵称</td>
            <td>图像</td>
            <td>消费金额 </td>
            <td>订单数量</td>
            <td>最后下单时间</td>
            <td>操作</td>
        </tr>
        <?php if(is_array($data)): foreach($data as $key=>$var): ?><tr>
                <td><?php echo ($var["user_id"]); ?></td>
                <td><?php echo ($var['mobile']); ?></td>
                <td><?php echo ($var["nickname"]); ?></td>
                <td style="height: 50px;"><img src="<?php echo ($var["face"]); ?>"  style="width: 50px;  margin: 0px auto;" /></td>
                <td><?php echo ($var['total_price']/100); ?>元</td>
                <td><?php echo ($var["num"]); ?></td>
                <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                <td>
                    <a href="">赠送优惠券</a> | 
                    <a class="radius3 sy_c1an" href="<?php echo U('custom/look_info',array('user_id'=>$var['user_id']));?>">查看</a>
                </td>
            </tr><?php endforeach; endif; ?>
    </table>
    <?php echo ($page); ?>
</div>
</body>
</html>