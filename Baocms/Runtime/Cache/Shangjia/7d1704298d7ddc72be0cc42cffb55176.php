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
        <li><a href="#">商家管理</a> > <a href="">消费券</a> > <a>消费券列表</a></li>
    </ul>
</div>
<style>
    .tuan_top_t input[type="text"]{
        width:150px;
    }
</style>
<div class="tuan_content">
     <form method="post" action="<?php echo U('coupon/index');?>">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t">
            <div class="left tuan_topser_l">
            开始时间：<input type="text" class="radius3 tuan_topser"  name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"/>
            结束时间：<input type="text" class="radius3 tuan_topser"  name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"/>
            手机号：<input type="text" name="mobile" value="<?php echo ($mobile); ?>" class="radius3 tuan_topser" />
            
            消费密码：<input type="text" name="coupon" value="<?php echo ($coupon); ?>" class="radius3 tuan_topser" />
            <input type="submit" style="margin-left:10px;" class="radius3 sjgl_an tuan_topbt" value="搜 索"/>
            </div>
        </div>
    </div>
    </form>
    <div class="tuanfabu_tab">
        <ul>
            <li class="tuanfabu_tabli on"><a href="<?php echo U('coupon/index');?>">消费券列表</a></li>
        </ul>
    </div> 
     <table class="tuan_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr style="background-color:#eee;">
            <td>订单ID</td>
            <td>订单编号</td>
            <td>商品名称</td>
            <td>数量</td>
            <td>合计</td>
            <td>昵称</td>
            <td>手机号</td>
            <td>消费券码</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                <td><?php echo ($var["order_id"]); ?></td>
                <td><?php echo ($var["orderno"]); ?></td>
                <td><?php echo ($var["goodsname"]); ?></td>
                <td><?php echo ($var["num"]); ?></td>
                <td><?php echo round($var['total']/100,2);?>元</td>
                <td><?php echo ($var["nickname"]); ?></td>
                <td><?php echo ($var["mobile"]); ?></td>
                <td><?php echo ($var["coupon"]); ?></td>
                <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                <td>
                    <?php if($var["status"] == 7): ?><a href="javascript:;">已核销</a> 
                    <?php else: ?>
                        <a href="javascript:;"  onclick="ok(this,<?php echo ($var["order_id"]); ?>)">核销</a><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; ?>
    </table>
    <?php echo ($page); ?>
</div>
<script>
    function ok(obj,id){
        $.post('<?php echo U("ajax_verify");?>',{id:id},function(data){
            $(obj).parent().html('已核销')
        })
    }
</script>
</body>
</html>