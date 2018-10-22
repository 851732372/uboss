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
<div class="sjgl_lead">
    <ul>
        <li><a href="#">系统设置</a> > <a href="">评价</a> > <a>评价详情</a></li>
    </ul>
</div>

<form action="" method="POST" target="baocms_frm">
    <table class="table table-hover table-striped">
    <tr>
        <td></td>
        <th>订单号：<?php echo ($data["order_id"]); ?></th>
    </tr>
    <tr>
        <td></td>
        <th>商品信息：<?php echo ($data["title"]); ?></th>
    </tr>
    <tr>
        <td></td>
        <th>用户信息：<?php echo ($data["nickname"]); ?> / <?php echo ($data["mobile"]); ?></th>
    </tr>
    <tr>
        <td></td>
        <th>评分：<?php echo ($data["comment_starts"]); ?></th>
    </tr>
    <tr>
        <td></td>
        <th>评论内容：<?php echo ($data["content"]); ?></th>
    </tr>
     <tr>
        <td></td>
        <th>评论图片：
            <?php if(is_array($data['comment_img'])): $i = 0; $__LIST__ = $data['comment_img'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><img src="/attachs/<?php echo ($img); ?>" alt="" height="80px"><?php endforeach; endif; else: echo "" ;endif; ?>
        </th>
    </tr>
    <tr>
        <td></td>
        <th>评论时间：<?php echo (date('Y-m-d H:i:s',$data["create_time"])); ?></th>
    </tr>
    <?php if(($data["status"]) == "2"): if(($data["reply_status"]) == "0"): ?><tr>
                <td></td>
                <th>回复：<code>暂未回复</code></th>
            </tr><?php endif; ?>
    <?php else: ?>
        <tr>
            <td></td>
            <td><code>审核未通过或者未审核</code></td>
        </tr><?php endif; ?>
    <?php if(($data["reply_status"]) == "1"): ?><tr>
            <td></td>
            <th>回复内容：<?php echo ($data["con"]); ?></th>
        </tr><?php endif; ?>
   
</table>
</form>


</div>
</body>
</html>