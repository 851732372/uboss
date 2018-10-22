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
<div class="mainBt">
    <ul>
        <li class="li1">系统</li>
        <li class="li2">后台菜单设置</li>
        <li class="li2 li3">菜单列表</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>这里主要管理后台的菜单，通常后台的基本权限是和这类菜单关联的！</p>
    <div class="jsglNr">
        <form id="cate_action" action="<?php echo U('menu/update');?>" target="baocms_frm" method="post">
            <div class="selectNr" style="border-top: 1px solid #e1e6eb;">
                <div class="left">
                    <?php echo BA('menu/create','','添加菜单','load','',600,280);?>
                </div>
                <div class="right">
                     <input type="submit" class="sBtn" value="更新"  />
                </div>
            </div>
            <div class="tableBox">
                <table class="table table-bordered">
                    <tr>
                        <td>分类</td>
                        <td>排序</td>
                        <td>类型</td>
                        <td>状态</td>
                        <td>action</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($datas)): foreach($datas as $key=>$var): ?><tr bgcolor="#f1f1f1">
                            <td ><?php echo ($var["menu_name"]); ?></td>
                            <td ><input name="orderby[<?php echo ($var["menu_id"]); ?>]" value="<?php echo ($var["orderby"]); ?>" type="text" /></td>
                            <td>
                                <?php if(($var["menu_type"]) == "1"): ?>模块<?php endif; ?>
                                <?php if(($var["menu_type"]) == "0"): ?>操作<?php endif; ?>
                                <?php if(($var["menu_type"]) == "2"): ?>节点<?php endif; ?>
                            </td>
                            <td>
                                <?php if(($var["is_show"]) == "1"): ?>显示<?php endif; ?>
                                <?php if(($var["is_show"]) == "0"): ?>隐藏<?php endif; ?>
                            </td>
                            <td><?php echo ($var["menu_action"]); ?></td>
                            <td>
                                <?php echo BA('menu/create',array("parent_id"=>$var['menu_id']),'添加','load','remberBtn',600,280);?>
                                <?php echo BA('menu/edit',array("menu_id"=>$var['menu_id']),'编辑','load','remberBtn',600,280);?>
                                <?php echo BA('menu/delete',array("menu_id"=>$var['menu_id']),'删除','act','remberBtn');?>
                            </td>
                        </tr>
                        <?php if(is_array($var["child"])): foreach($var["child"] as $key=>$var2): ?><tr >
                                <td>|---<?php echo ($var2["menu_name"]); ?></td>
                                <td><input name="orderby[<?php echo ($var2["menu_id"]); ?>]" value="<?php echo ($var2["orderby"]); ?>" type="text" /></td>
                                <td>
                                    <?php if(($var2["menu_type"]) == "1"): ?>模块<?php endif; ?>
                                    <?php if(($var2["menu_type"]) == "0"): ?>操作<?php endif; ?>
                                    <?php if(($var2["menu_type"]) == "2"): ?>节点<?php endif; ?>
                                </td>
                                <td>
                                    <?php if(($var2["is_show"]) == "1"): ?>显示<?php endif; ?>
                                    <?php if(($var2["is_show"]) == "0"): ?>隐藏<?php endif; ?>
                                </td>
                                <td><?php echo ($var2["menu_action"]); ?></td>
                                <td>
                                    <?php echo BA('menu/action',array("parent_id"=>$var2['menu_id']),'添加','load','remberBtn',800,500);?>
                                    <?php echo BA('menu/edit',array("menu_id"=>$var2['menu_id']),'编辑','load','remberBtn',600,280);?>
                                    <?php echo BA('menu/delete',array("menu_id"=>$var2['menu_id']),'删除','act','remberBtn');?>
                                </td>
                            </tr>
                            <?php if(is_array($var2["child"])): foreach($var2["child"] as $key=>$var3): ?><tr>
                                <td>|-------<?php echo ($var3["menu_name"]); ?></td>
                                <td><input name="orderby[<?php echo ($var3["menu_id"]); ?>]" value="<?php echo ($var3["orderby"]); ?>" type="text" /></td>
                                <td>
                                    <?php if(($var3["menu_type"]) == "1"): ?>模块<?php endif; ?>
                                    <?php if(($var3["menu_type"]) == "0"): ?>操作<?php endif; ?>
                                    <?php if(($var3["menu_type"]) == "2"): ?>节点<?php endif; ?>
                                </td>
                                <td>
                                    <?php if(($var3["is_show"]) == "1"): ?>显示<?php endif; ?>
                                    <?php if(($var3["is_show"]) == "0"): ?>隐藏<?php endif; ?>
                                </td>
                                <td><?php echo ($var3["menu_action"]); ?></td>
                                <td>
                                    <?php echo BA('menu/action',array("parent_id"=>$var3['menu_id']),'添加','load','remberBtn',800,500);?>
                                    <?php echo BA('menu/edit',array("menu_id"=>$var3['menu_id']),'编辑','load','remberBtn',600,280);?>
                                    <?php echo BA('menu/delete',array("menu_id"=>$var3['menu_id']),'删除','act','remberBtn');?>
                                </td>
                            </tr><?php endforeach; endif; endforeach; endif; endforeach; endif; ?>     
                </table>
            </div>
        </form>
    </div>
</div>

</div>
</body>
</html>