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
        <li class="li1">支付设置</li>
        <li class="li2">支付方式</li>
        <li class="li2 li3">安装</li>
    </ul>
</div>
<form target="baocms_frm" action="<?php echo U('payment/install',array('payment_id'=>$detail['payment_id']));?>" method="post">
    <div class="mainScAdd">
        <div class="tableBox">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                
                
                <tr>
                    <td class="lfTdBt">支付方式:</td>
                     <td class="rgTdBt">
                        <img src="__PUBLIC__/images/<?php echo ($detail["logo"]); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="lfTdBt">支付方式介绍:</td>
                     <td class="rgTdBt" style="font-size: 14px;">
                        <?php echo ($detail["contents"]); ?>
                    </td>
                </tr>
                <tr>
                    <td class="lfTdBt">支付接口类型：</td>
                     <td class="rgTdBt">
                        <select  name="data[service]" class="seleFl w200">
                            <option <?php if(($detail["setting"]["service"]) == "0"): ?>selected="selected"<?php endif; ?> value="0">标准双接口</option>
                            <option <?php if(($detail["setting"]["service"]) == "1"): ?>selected="selected"<?php endif; ?> value="1">担保交易</option>
                            <option <?php if(($detail["setting"]["service"]) == "2"): ?>selected="selected"<?php endif; ?> value="2">即时到帐</option>
                        </select>
                    </td>
                </tr>  
                <tr>
                    <td class="lfTdBt">商户ID：</td>
                     <td class="rgTdBt">
                        <input type="text" name="data[alipay_partner]" value="<?php echo ($detail["setting"]["alipay_partner"]); ?>" class="scAddTextName w400" />
                    </td>
                </tr>  
                <tr>
                    <td class="lfTdBt">商户KEY：</td>
                     <td class="rgTdBt">
                          <input type="text" name="data[alipay_key]" value="<?php echo ($detail["setting"]["alipay_key"]); ?>"  class="scAddTextName w400" />
                    </td>
                </tr>  
                <tr>
                    <td class="lfTdBt">商户账号：</td>
                     <td class="rgTdBt">
                          <input type="text" name="data[alipay_account]" value="<?php echo ($detail["setting"]["alipay_account"]); ?>"  class="scAddTextName w400" />
                    </td>
                </tr>
                
                
                
                <tr>
                    <td class="lfTdBt" style="text-align:left;" colspan="2">&nbsp;&nbsp;支付宝手机App配置</td>
                </tr>
                 <tr>
                    <td class="lfTdBt">合作者身份ID：</td>
                     <td class="rgTdBt">
                        <input type="text" name="data[alipay_app_partner]" value="<?php echo ($detail["setting"]["alipay_app_partner"]); ?>" class="scAddTextName w400" />
                    </td>
                </tr>  
                <tr>
                    <td class="lfTdBt">商户账号：</td>
                     <td class="rgTdBt">
                          <input type="text" name="data[alipay_app_seller]" value="<?php echo ($detail["setting"]["alipay_app_seller"]); ?>"  class="scAddTextName w400" />
                    </td>
                </tr>  
                <tr>
                    <td class="lfTdBt">合作者私钥：</td>
                     <td class="rgTdBt">
                          <input type="text" name="data[alipay_app_private]" value="<?php echo ($detail["setting"]["alipay_app_private"]); ?>"  class="scAddTextName w400" />
                    </td>
                </tr>
                <tr>
                    <td class="lfTdBt">支付宝公钥：</td>
                     <td class="rgTdBt">
                          <input type="text" name="data[alipay_app_public]" value="<?php echo ($detail["setting"]["alipay_app_public"]); ?>"  class="scAddTextName w400" />
                    </td>
                </tr>






            </table>
        </div>
        <div class="smtQr"><input type="submit" value="确定安装" class="smtQrIpt" /></div>
    </div>
</form>

</div>
</body>
</html>