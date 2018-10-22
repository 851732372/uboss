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
        <li><a href="#">结算</a> > <a href="">资金记录</a> > <a>对账单</a></li>
    </ul>
</div>
<div class="tuan_content">
    <form method="post" action="<?php echo U('mone/account');?>" class="form-inline">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t">
            <div class="left tuan_topser_l">
                 开始时间：<input type="text" class="radius3 tuan_topser"  name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" autocomplete="off"/>
                结束时间：<input type="text" class="radius3 tuan_topser"  name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" autocomplete="off"/>       
                <!-- <div class="form-group">
                    <select name="acc" id="" class="form-control">
                        <option value="">全部</option>
                        <option value="1" <?php if(($status) == "1"): ?>selected<?php endif; ?>>已入账</option>
                        <option value="2" <?php if(($status) == "2"): ?>selected<?php endif; ?>>未入账</option>
                    </select>
                </div> -->
                <input type="submit" style="margin-left:10px;" class="radius3 sjgl_an tuan_topbt" value="搜 索"/>
            </div>
        </div>
    </div>
    </form>
   <!--  <div class="tuanfabu_tab">
        <ul>
            <li class="tuanfabu_tabli"><a href="<?php echo U('account');?>">团购对账管理</a></li>
            <li class="tuanfabu_tabli"><a href="<?php echo U('account',array('type' => 2));?>">收银对账单</a></li>
        </ul>
    </div> 
    <div class="panel-default">
        <div class="panel-heading">
            <h5>
                <?php if(($_GET['type']) == ""): ?>团购对账管理<?php endif; ?>
                <?php if(($_GET['type']) == "2"): ?>收银对账单<?php endif; ?>
            </h5>
        </div>
    </div> -->
    <table class="tuan_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr style="background-color:#eee;">
            <td>说明</td>
            <td>账单日期</td>
            <td>商家应得</td>
            <td>状态</td>
        </tr>
        <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                <td><?php echo ($var["intro"]); ?></td>
                <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                <td><?php echo floor($var['money'])/100;?>元</td>
                <td>
                    <?php if(($var["audit"]) == "1"): ?>已入账<?php endif; ?>
                    <?php if(($var["audit"]) == "0"): ?>未入账<?php endif; ?>
                </td>
            </tr><?php endforeach; endif; ?>
    </table>
    <div class="paging">
        <?php echo ($page); ?>
    </div>
</div>

</body>
</html>