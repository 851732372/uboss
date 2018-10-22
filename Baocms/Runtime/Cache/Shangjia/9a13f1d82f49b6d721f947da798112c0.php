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
        <li><a href="#">结算</a> > <a href="">资金记录</a> > <a>余额日志</a></li>
    </ul>
</div>
<div class="tuan_content">
    <form method="post" action="<?php echo U('mone/index');?>">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t">
            <div class="left tuan_topser_l">
             开始时间：<input type="text" class="radius3 tuan_topser"  name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" autocomplete="off"/>
            结束时间：<input type="text" class="radius3 tuan_topser"  name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" autocomplete="off"/>       
            <input type="submit" style="margin-left:10px;" class="radius3 sjgl_an tuan_topbt" value="搜 索"/>
            </div>
        </div>
    </div>
    </form>
    <div class="tuanfabu_tab">
        <ul>
            <li class="tuanfabu_tabli on"><a href="<?php echo U('mone/index');?>">余额管理</a></li>
            <li class="tuanfabu_tabli"><a href="<?php echo U('mone/tixianlog');?>">提现日志</a></li>
            <li class="tuanfabu_tabli"><a href="<?php echo U('mone/tixian');?>">提现</a></li>
        </ul>
    </div> 
    <div class="tuanfabu_nr">
       未入账：<font color="red"><?php echo round($mone3/100,2);?></font>元！已入账：<font color="red"><?php echo round($mone2/100,2);?></font>元！已提现：<font color="red"><?php echo round($mone1/100,2);?></font>元！
    </div>
    <table class="tuan_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr style="background-color:#eee;">
            <td>类型</td>
            <td>收支</td>
            <td>日志</td>
            <td>时间</td>
        </tr>
        <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                <td>
                   <?php echo ($var["type"]); ?>
                </td>
                <td><?php echo ($var['money']); ?></td>
                <td><?php echo ($var["intro"]); ?></td>
                <td><?php echo ($var["create_time"]); ?></td>
            </tr><?php endforeach; endif; ?>
    </table>
    <div class="paging">
        <?php echo ($page); ?>
    </div>
</div>

</body>
</html>