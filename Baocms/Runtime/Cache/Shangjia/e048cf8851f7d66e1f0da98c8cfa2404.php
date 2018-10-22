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
<script src="__PUBLIC__/bs/js/bootstrap.min.js"></script>
<div class="sjgl_lead">
    <ul>
        <li><a href="#">系统设置</a> > <a href="">评价</a> > <a>评价管理</a></li>
    </ul>
</div>
<div class="tuan_content">
    <div class="radius5 tuan_top">
        <div class="tuan_top_t tuanfabu_top">
            <div class="left tuan_topser_l">如果收到恶意评价，可以联系网站客服：<?php echo ($CONFIG["site"]["tel"]); ?></div>
        </div>
    </div>
    <div class="tuanfabu_tab">
        <ul>
            <li class="tuanfabu_tabli"><a href="<?php echo U('index');?>">收到的评论</a></li>
            <li class="tuanfabu_tabli on"><a href="<?php echo U('noreply');?>">未回复的评论</a></li>
    </div> 
    <table class="tuan_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr style="background-color:#eee;">
            <td>编号</td>
            <td>用户</td>
            <td>评分</td>
            <td>商品名称</td>
            <td>评价时间</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        <?php if(is_array($data)): foreach($data as $key=>$var): ?><tr>
                <td><?php echo ($var["comment_id"]); ?></td>
                <td><?php echo ($var["nickname"]); ?></td>
                <td><?php echo ($var["comment_starts"]); ?></td>
                <td><?php echo ($var["title"]); ?></td>
                <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                <td>
                    <?php if(($var["reply_status"]) == "0"): ?>未回复<?php endif; ?>
                    <?php if(($var["reply_status"]) == "1"): ?>已回复<?php endif; ?>
                </td>
                <td>
                    <?php if(($var["status"]) == "0"): ?><code>已拒绝</code>
                        | <a href="<?php echo U('com_detail',array('com_id' => $var['comment_id']));?>">查看</a><?php endif; ?>
                    <?php if(($var["status"]) == "1"): ?><a href="javascript:;" onclick="refuse(<?php echo ($var["comment_id"]); ?>)">拒绝</a> | 
                        <a href="javascript:;" onclick="ok(<?php echo ($var["comment_id"]); ?>)">通过</a>
                        | <a href="<?php echo U('com_detail',array('com_id' => $var['comment_id']));?>">查看</a><?php endif; ?>
                    <?php if(($var["status"]) == "2"): if(($var["reply_status"]) == "0"): ?><a href="javascript:;" data-toggle="modal" data-target="#myModal<?php echo ($var["comment_id"]); ?>">回复</a> | <a href="<?php echo U('com_detail',array('com_id' => $var['comment_id']));?>">查看</a><?php endif; ?>
                        <?php if(($var["reply_status"]) == "1"): ?><a href="javascript:;">已回复</a> | <a href="<?php echo U('com_detail',array('com_id' => $var['comment_id']));?>">查看</a><?php endif; endif; ?>
                    </eq>
                </td>
                <div class="modal fade" id="myModal<?php echo ($var["comment_id"]); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">回复内容</h4>
                            </div>
                            <form action="<?php echo U('com_detail');?>" method="POST" target="baocms_frm">
                            <div class="modal-body">
                                <input type="hidden" name="comment_id" value="<?php echo ($var['comment_id']); ?>">
                                <textarea name="con" id="" cols="90" rows="10"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-default" data-dismiss="modal">关闭</button>
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal -->
                </div><?php endforeach; endif; ?>
    </table>
    <?php echo ($page); ?>
   
</div>
<script>
    function refuse(id){
        $.post("<?php echo U('ajax_refuse');?>",{id:id},function(){
            location.reload();
        })
    }
     function ok(id){
        $.post("<?php echo U('ajax_ok');?>",{id:id},function(){
            location.reload();
        })
    }
</script>
</body>
</html>