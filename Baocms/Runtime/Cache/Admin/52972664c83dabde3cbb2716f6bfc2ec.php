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
<div class="main-jsgl main-sc">
    <div class="jsglNr">
        <div class="selectNr" style="margin-top: 0px; border-top:none;">
            <div class="right">    
                <form class="search_form" method="post" action="<?php echo U('Founder/userinfo');?>">
                    <div class="seleHidden" id="seleHidden">
                        <span>手机号：</span>
                        <input type="text" name="mobile" value="<?php echo ($mobile); ?>" class="inptText" /><input type="submit" value="   搜索"  class="inptButton" />
                    </div> 
                </form>
            </div>
        </div>
    </div>
    <form  target="baocms_frm" method="post">
        <div class="tableBox">
            <table class="table table-hover">
                <tr>
                	<th></th>
                    <th>编号</th>
                    <th>昵称</th>
                    <th>姓名</th>
                    <th>注册IP</th>
                    <th>手机号</th>
                    <th>状态</th>
                    <th>认证状态</th>
                </tr>
                <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lis): $mod = ($i % 2 );++$i;?><tr>
                    <td><input rel="<?php echo ($lis["mobile"]); ?>" type="radio" name="user_id" value="<?php echo ($lis["user_id"]); ?>" /></td>
                    <td><?php echo ($lis["user_id"]); ?></td>
                    <td><?php echo ($lis["nickname"]); ?></td>
                    <td><?php echo ($lis["realname"]); ?></td>
                    <td><?php echo ($lis["create_ip"]); ?></td>
                    <td><?php echo ($lis["mobile"]); ?></td>
                    <td>
                        <?php if(($lis["closed"]) == "-1"): ?>待激活<?php endif; ?>
                        <?php if(($lis["closed"]) == "0"): ?>正常<?php endif; ?>
                        <?php if(($lis["closed"]) == "1"): ?><code>已删除</code><?php endif; ?>
                    </td>
                    <td>
                        <?php if(($lis["is_reg"]) == "0"): ?>认证中<?php endif; ?>
                        <?php if(($lis["is_reg"]) == "1"): ?>通过认证<?php endif; ?>
                        <?php if(($lis["is_reg"]) == "2"): ?>认证未通过<?php endif; ?>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="selectNr" style="margin-bottom: 0px; border-bottom: none;">
            <div class="left">
                <input style="border:1px solid #dbdbdb; width: 100px; height: 38px; line-height: 38px; text-align: center;" type="button" id="select_btn" class="remberBtn" value="确定选择" />
            </div>
        </div>
    </form>
</div>
</div>

<script>
    $(document).ready(function (e) {
        $("#select_btn").click(function () {
            $("input[name='user_id']").each(function (a) {
                if ($(this).prop("checked") == true) {
                    parent.selectCallBack('user_id', 'mobile', $(this).val(), $(this).attr('rel'));
                }
            });
        });

    });
</script>

</div>
</body>
</html>