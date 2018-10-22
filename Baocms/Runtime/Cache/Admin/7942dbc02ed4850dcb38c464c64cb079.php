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
    <li>
        <a href="#">商家管理</a></span>
    </li>
    <li>
        <a href="#">商家分类</a></span>
    </li>
    <li>
        <code><span>注意：</span>暂时2级分类</code>
    </li>
</ul>
<style>
    .x a{
        color:white;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <button class="btn btn-danger x"><?php echo BA('shopcate/create','','添加一级分类','load','',600,550);?></button>
        <code>优先级从小到大</code>
    </div>
    <div class="panel-body">
        <form  target="baocms_frm" method="post">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>分类</th>
                    <th>排序</th>
                    <th>图标</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): if(($var["parent_id"]) == "0"): ?><tr style="background:#eee">
                        <td><?php echo ($var["cate_name"]); ?>(<?php echo ($var["cate_id"]); ?>)</td>
                        <td><input name="orderby[<?php echo ($var["cate_id"]); ?>]" value="<?php echo ($var["orderby"]); ?>" type="text" /></td>
                         <td><img src="/attachs/<?php echo ($var["icon"]); ?>" alt="" width="50px" height="50px" class="img-circle"></td>
                        <td>
                            <?php echo BA('shopcate/create',array("parent_id"=>$var['cate_id']),'添加子分类','load','remberBtn',200,550);?>
                            <?php if(($var["is_hot"]) == "0"): echo BA('shopcate/hots',array("cate_id"=>$var["cate_id"]),'设为热门','act','remberBtn');?>
                    <?php else: ?>
                    <?php echo BA('shopcate/hots',array("cate_id"=>$var["cate_id"]),'取消热门','act','remberBtn'); endif; ?>
                    <?php echo BA('shopcate/edit',array("cate_id"=>$var["cate_id"]),'编辑','load','remberBtn',200,550);?>
                    <?php echo BA('shopcate/delete',array("cate_id"=>$var["cate_id"]),'删除','act','remberBtn');?>
                    <?php echo BA('shopcate/carousel',array("cate_id"=>$var["cate_id"]),'设置轮播图','load','remberBtn',200,550);?>
                    </td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><tr>
                            <td>|----<?php echo ($var2["cate_name"]); ?>(<?php echo ($var2["cate_id"]); ?>)</td>
                            <td><input name="orderby[<?php echo ($var2["cate_id"]); ?>]" value="<?php echo ($var2["orderby"]); ?>" type="text"  /></td>
                            <td><img src="/attachs/<?php echo ($var2["icon"]); ?>" alt="" width="50px" height="50px" class="img-circle"></td>
                            <td>
                                <?php if(($var2["is_hot"]) == "0"): echo BA('shopcate/hots',array("cate_id"=>$var2["cate_id"]),'设为热门','act','remberBtn');?>
                        <?php else: ?>
                        <?php echo BA('shopcate/hots',array("cate_id"=>$var2["cate_id"]),'取消热门','act','remberBtn'); endif; ?>
                        <?php echo BA('shopcate/edit',array("cate_id"=>$var2["cate_id"]),'编辑','load','remberBtn',600,550);?>
                        <?php echo BA('shopcate/delete',array("cate_id"=>$var2["cate_id"]),'删除','act','remberBtn');?>
                        </td>
                        </tr><?php endif; endforeach; endif; endif; endforeach; endif; ?>     
            </table>
            <div class="selectNr">
                <div class="left">
                    <?php echo BA('shopcate/update','','更新','list','remberBtn');?>
                </div>
            </div>
        </form>
    </div>
</div>

</div>
</body>
</html>