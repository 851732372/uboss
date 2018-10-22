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
<link href='__PUBLIC__/assets/stylesheets/bootstrap/bootstrap.css' media='all' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="__PUBLIC__/zyupload/skins/zyupload-1.0.0.min.css " type="text/css">
<script type="text/javascript" src="__PUBLIC__/zyupload/zyupload-1.0.0.min.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/webupload/globle.css" />
<script src="__PUBLIC__/webupload/webuploader.min.js"></script>
<script src="__PUBLIC__/webupload/diyUpload.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        input {
            padding:5px;
            margin-right: 10px;
            border: 1px solid #d9d9d9;
            border-radius:4px;
            color: rgba(0, 0, 0, 0.65);
            transition: all .3s;
        }
        .first_input {
            margin-right: 7px;
        }
        input::-webkit-input-placeholder {
            color: #c1c1c1;
        }
        input::-moz-placeholder {
            color: #c1c1c1;
        }
        input::-ms-input-placeholder {
            color: #c1c1c1;
        }
        input:focus {
            border-color: #40a9ff;
            -webkit-box-shadow: 0 2px 0 #1890ff;
            box-shadow: 0 0 5px rgba(24, 144, 255, 0.5);
        }

        .btn_common {
            display: inline-block;
            background-color: #40a9ff;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
            -webkit-box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
            text-align: center;
            font-weight: 400;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 14px;
            color: #fff;
            padding: 2.5px 8px;
            margin: 2.5px;
        }
        .btn_common:active {
            background-color: #1890ff;
            transition: all 0.1s ease-in, color 0.1s ease-out;
        }

        .input_Parent, .add_Parent_btn {
            /*margin-left: 15px;*/
        }
        .input_Child {
            margin-left: 35px;
        }
        .first_addclass, .first_removeclass {
            margin-left: 1px;
            margin-right: 1px;
        }
    </style>
    <div class="panel penel-default">
        <div class="panel-heading">
           <ul class="breadcrumb">
                <li>
                    <a href="#">城市提现</a> <span class="divider">></span>
                </li>
                <li>
                    <a href="#">设置提现账号</a> <span class="divider">></span>
                </li>
            </ul>
        </div>
        <div class="panel-body">
            <form class='form form-horizontal validate-form' style='margin-bottom: 0;' target="baocms_frm" action="<?php echo U('cityfinance/account');?>" method="post"/>
                <div class='control-group'>
                    <div class='control-label'>
                        <label for='validation_secret'>开户行</label>
                    </div>
                    <div class='controls'>
                        <select name="bank_name"  >
                            <option value="1" <?php if(($detail["bank_name"]) == "1"): ?>selected<?php endif; ?>>中国工商银行</option>
                            <option value="2" <?php if(($detail["bank_name"]) == "2"): ?>selected<?php endif; ?>>中国建设银行</option>
                            <option value="3" <?php if(($detail["bank_name"]) == "3"): ?>selected<?php endif; ?>>中国农业银行</option>
                            <option value="4" <?php if(($detail["bank_name"]) == "4"): ?>selected<?php endif; ?>>中国交通银行</option>
                            <option value="5" <?php if(($detail["bank_name"]) == "5"): ?>selected<?php endif; ?>>中国银行</option>
                            <option value="6" <?php if(($detail["bank_name"]) == "6"): ?>selected<?php endif; ?>>支付宝</option>
                            <option value="7" <?php if(($detail["bank_name"]) == "7"): ?>selected<?php endif; ?>>微信</option>
                            <option value="8" <?php if(($detail["bank_name"]) == "8"): ?>selected<?php endif; ?>>中国招商银行</option>
                            <option value="9" <?php if(($detail["bank_name"]) == "9"): ?>selected<?php endif; ?>>中信银行</option>
                            <option value="10" <?php if(($detail["bank_name"]) == "10"): ?>selected<?php endif; ?>>民生银行</option>
                            <option value="11" <?php if(($detail["bank_name"]) == "11"): ?>selected<?php endif; ?>>信用社</option>
                        </select>
                    </div>
                </div>
                <div class='control-group'>
                    <label class='control-label' >开户人</label>
                    <div class='controls'>
                        <input  name='bank_realname' value="<?php echo ($detail["bank_realname"]); ?>" type='text' />
                    </div>
                </div>
                 <div class='control-group'>
                    <label class='control-label' >账号</label>
                    <div class='controls'>
                        <input  name='bank_num' value="<?php echo ($detail["bank_num"]); ?>" type='text' />
                    </div>
                </div>
                <div class='control-group'>
                    <label class='control-label' >手机号</label>
                    <div class='controls'>
                        <input  name='tel' value="<?php echo ($detail["tel"]); ?>" type='text' />
                    </div>
                </div>
                <div class='form-actions' style='margin-bottom:0'>
                    <button class='btn btn-primary' type='submit'>
                        <i class='icon-save'></i>
                        保存数据
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
</html>