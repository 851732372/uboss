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
<div class="mainBt">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">支付设置</li>
        <li class="li2 li3">支付方式</li>
    </ul>
</div>
<div class="main-jsgl">
    <p class="attention"><span>注意：</span>支付方式配置，目前先开通支付宝，后期会增加很多其他的支付方式</p>
    <div class="jsglNr">
        <div class="title" style="margin-bottom: 10px;">支付方式</div>
        <div class="tableBox">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td>ID</td>
                    <td>支付方式</td>
                    <td>说明</td>
                    <td>操作</td>   
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td class="w50"><?php echo ($var["payment_id"]); ?></td>
                        <td width="100"><?php echo ($var["name"]); ?></td>
                        <td style="text-align: left;"><?php echo ($var["contents"]); ?></td>
                        <td class="w80">
                            <?php if(($var["is_open"]) == "1"): echo BA('payment/uninstall',array("payment_id"=>$var["payment_id"]),'卸载','act','remberBtn');?>
                             <?php echo BA('payment/install',array("payment_id"=>$var["payment_id"]),'编辑','','remberBtn');?>
                    <?php else: ?>
                    <?php echo BA('payment/install',array("payment_id"=>$var["payment_id"]),'安装','','remberBtn'); endif; ?>
                    </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
    </div>
</div>

</div>
</body>
</html>