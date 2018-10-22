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
        <li class="li1">管理员管理</li>
        <li class="li2">角色管理</li>
        <li class="li2 li3">角色权限</li>
    </ul>
</div>
<p class="attention"><span>当前用户组：</span><?php echo ($detail["role_name"]); ?></p>
<form  target="baocms_frm" action="<?php echo U('role/auth',array('role_id'=>$role_id));?>" method="post">
    <div class="mainScAdd">
        <div class="tableBox">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >

                <?php if(is_array($menus)): foreach($menus as $key=>$var): if(($var["parent_id"] == 0) and ($var["is_show"] == 1) ): if(is_array($menus)): foreach($menus as $key=>$var2): if(($var2["parent_id"]) == $var["menu_id"]): if($var2["is_show"] == 1): ?><tr>
                                    <td style="text-align: left;padding-left: 10px; font-size: 14px;"> <input type="checkbox" class="checkAll" rel="<?php echo ($var2["menu_id"]); ?>" value="" /><b><?php echo ($var["menu_name"]); ?></b> > <?php echo ($var2["menu_name"]); ?> </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;padding-left: 10px;">
                                <?php if(is_array($menus)): foreach($menus as $key=>$var3): if(($var3["parent_id"]) == $var2["menu_id"]): ?><label style="font-size: 14px;"> <input class="child_<?php echo ($var2["menu_id"]); ?>" type="checkbox" name="menu_id[]" <?php if(in_array(($var3["menu_id"]), is_array($menuIds)?$menuIds:explode(',',$menuIds))): ?>checked="checked"<?php endif; ?> value="<?php echo ($var3["menu_id"]); ?>" /> <?php echo ($var3["menu_name"]); ?></label><?php endif; endforeach; endif; ?>   
                                </td>  
                                </tr><?php endif; endif; endforeach; endif; endif; endforeach; endif; ?> 
            </table>
        </div>
        <div class="smtQr"><input type="submit" value="确定授权" class="smtQrIpt" /></div>
    </div>
</form>

</div>
</body>
</html>