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
<style>
    tr th,tr td{
        text-align: center;
        vertical-align: middle;
    }
</style>
<ul class="breadcrumb">
    <li>
        <a href="#">商家管理</a></span>
    </li>
    <li>
        <a href="#">商品列表</a></span>
    </li>
</ul>

<div class="panel panel-default">
    <div class="panel-heading">
        <a href="<?php echo U('goods/create');?>" class="btn btn-info btn-sm">添加商品</a>
        <form method="post" action="<?php echo U('goods/index');?>" class="pull-right form-inline">
            <div class="form-group">
                <select name="shop_id" id="" class="form-control">
                    <option value="">请选择商家</option>
                    <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shop): $mod = ($i % 2 );++$i;?><option value="<?php echo ($shop["shop_id"]); ?>" <?php if(($shop["shop_id"]) == $shop_id): ?>selected="selected"<?php endif; ?> ><?php echo ($shop["shop_name"]); ?>(<?php echo ($shop["shop_id"]); ?>)</option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
               
            </div>
            <div class="form-group">
                <input type="text" name="shop_name" id="" value="<?php echo ($shop_name); ?>" class="form-control" placeholder="商家名称：">
            </div>
          <!--   <div class="form-group">
                <select class="form-control" name="audit">
                    <option>全部</option>
                    <option value="-1" <?php if(($audit) == "-1"): ?>selected="selected"<?php endif; ?> >等待审核</option>
                    <option value="1" <?php if(($audit) == "1"): ?>selected="selected"<?php endif; ?> >正常</option>
                </select>
            </div> -->
            <div class="form-group">
                <select class="form-control" name="is_shelf">
                    <option value="">全部</option>
                    <option value="1" <?php if(($is_shelf) == "1"): ?>selected="selected"<?php endif; ?> >上架</option>
                    <option value="2" <?php if(($is_shelf) == "2"): ?>selected="selected"<?php endif; ?> >下架</option>
                </select>
            </div>
             <div class="form-group">
                <select class="form-control" name="closed">
                    <option value="">状态</option>
                    <option value="1" <?php if(($closed) == "1"): ?>selected="selected"<?php endif; ?> >已删除</option>
                    <option value="2" <?php if(($closed) == "2"): ?>selected="selected"<?php endif; ?> >正常</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="title" value="<?php echo ($title); ?>" class="form-control" placeholder="商品名称"/>
            </div>
            <input type="submit" class="btn btn-info" value="  搜索" />
        </form>
        <span>共有<?php echo ($count); ?>条数据</span>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-hover">
            <tr>
                <td class="w50"><input type="checkbox" class="checkAll" rel="goods_id" /></td>
                <td class="w50">ID</td>
                <td>产品名称</td>
                <td>商家</td>
                <td>logo</td>
                <td>商城价格</td>
                <td>门店价格</td>
                <td>卖出数量</td>
                <td>浏览量</td>
                <td>创建时间</td>
                <td>状态</td>
                <!-- <td>所属分类</td> -->
                <!-- <td>是否审核</td> -->
                <td>上下架</td>
                <td>操作</td>
            </tr>
            <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                    <td><input class="child_goods_id" type="checkbox" name="goods_id[]" value="<?php echo ($var["goods_id"]); ?>" /> </td>
                    <td><?php echo ($var["goods_id"]); ?></td>
                    <td title="<?php echo ($var["title"]); ?>"><?php echo (mb_substr($var["title"],0,8,'utf-8')); ?>...</td>
                    <td title="<?php echo ($var["shop_name"]); ?>"><?php echo (mb_substr($var["shop_name"],0,8,'utf-8')); ?>...</td>
                    <td><img src="__ROOT__/attachs/<?php echo ($var["photo"]); ?>" class="img-circle" height="50px" width="50px" /></td>
                    <td><?php echo ($var['mall_price']/100); ?>元</td>
                    <td><?php echo ($var['price']/100); ?>元</td>
                    <td><?php echo ($var["sold_num"]); ?></td>
                    <td><?php echo ($var["views"]); ?></td>
                    <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                    <td>
                        <?php if(($var["closed"]) == "1"): ?><code>已删除</code><?php endif; ?>
                        <?php if(($var["closed"]) == "0"): ?>正常<?php endif; ?>
                    </td>
                    <!-- <td><?php echo ($var["cate_name"]); ?></td> -->
                   <!--  <?php if(($var["closed"]) == "0"): ?><td>
                        <?php if(($var["audit"]) == "0"): echo BA('goods/audit',array("goods_id"=>$var["goods_id"]),'通过','act','a2');?> 
                            <?php echo BA('goods/unaudit',array("goods_id"=>$var["goods_id"]),'拒绝','act','a2'); endif; ?>
                        <?php if(($var["audit"]) == "-1"): ?><code>拒绝</code><?php endif; ?>
                        <?php if(($var["audit"]) == "1"): ?>通过<?php endif; ?>
                    </td>
                    <td>
                    <?php echo BA('goods/edit',array("goods_id"=>$var["goods_id"]),'编辑','','a2');?> | 
                    <?php echo BA('goods/delete',array("goods_id"=>$var["goods_id"]),'删除','act','a2');?>
                    </td><?php endif; ?> -->
                   
                    <?php if(($var["closed"]) == "0"): ?><td>
                        <?php if(($var["is_shelf"]) == "1"): ?>已上架<?php endif; ?>
                        <?php if(($var["is_shelf"]) == "0"): ?><code>已下架</code><?php endif; ?>
                    </td>
                    <td>
                        <?php if(($var["is_shelf"]) == "0"): echo BA('goods/shelf',array("goods_id"=>$var["goods_id"]),'上架','act','a2');?> |<?php endif; ?>
                        <?php if(($var["is_shelf"]) == "1"): echo BA('goods/unshelf',array("goods_id"=>$var["goods_id"]),'下架','act','a2');?> |<?php endif; ?>
                    <?php echo BA('goods/edit',array("goods_id"=>$var["goods_id"]),'编辑','','a2');?> | 
                    <?php echo BA('goods/delete',array("goods_id"=>$var["goods_id"]),'删除','act','a2');?>
                    </td><?php endif; ?>
                </tr><?php endforeach; endif; ?>
        </table>
        <?php echo ($page); ?>
    </div>
    
</div>

</div>
</body>
</html>