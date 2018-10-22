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
<link href='__PUBLIC__/assets/stylesheets/light-theme.css' id='color-settings-body-color' media='all' rel='stylesheet' type='text/css' />
<style>
    .container-fluid{
        padding:0px;
        width:96.5%;
    }
    .span3 a:hover{
        text-decoration: none;
    }
</style>
    <div class='container-fluid'>
        <div class='row-fluid' style="margin-top: 15px;padding-left:20px;padding-right:20px;height: 97px;">
            <div class='span12 box box-transparent'>
                <div class='row-fluid'>
                    <div class='span3 box-quick-link blue-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-money'>
                                    <?php echo (($allmoney)?($allmoney): 0); ?>元
                                </div>
                            </div>
                            <div class='content'>总营收</div>
                        </a>
                    </div>
                    <div class='span3 box-quick-link green-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-star'>
                                    <?php echo (($totay_order)?($totay_order): 0); ?>
                                </div>
                            </div>
                            <div class='content'>今日订单</div>
                        </a>
                    </div>
                    <div class='span3 box-quick-link orange-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-magic'>
                                    <?php echo (($totay_shop)?($totay_shop): 0); ?>
                                </div>
                            </div>
                            <div class='content'>新增商家</div>
                        </a>
                    </div>
                    <div class='span3 box-quick-link purple-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-comments'>
                                    <?php echo (($totay_u)?($totay_u): 0); ?>
                                </div>
                            </div>
                            <div class='content'>新增U店</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <hr class='hr-drouble' />
        <div class='row-fluid' style="background:#eee;margin-top: 15px;padding-left:20px;padding-right:20px;">
            <div class='span12 box'>
                <h5>待处理项</h5>
                <div class='row-fluid'>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-info pull-right'><?php echo (($shop_apply)?($shop_apply): 0); ?></h3>
                            <small>商家入驻申请</small>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-success pull-right'><?php echo (($founder)?($founder): 0); ?></h3>
                            <small>U店入驻申请</small>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-muted pull-right'><?php echo (($goods)?($goods): 0); ?></h3>
                            <small>商品数量</small>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-primary pull-right'><?php echo (($shop_money_logs)?($shop_money_logs): 0); ?></h3>
                            <small>商家提现待审核</small>
                        </div>
                    </div>
                    <?php  if($_SESSION['admin']['admin_id'] == 1){?>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-warning pull-right'><?php echo (($user_money_logs)?($user_money_logs): 0); ?></h3>
                            <small>用户提现待审核</small>
                        </div>
                    </div>
                    
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-error pull-right'><?php echo (($city_money_logs)?($city_money_logs): 0); ?></h3>
                            <small>城市提现待审核</small>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class='row-fluid' style="background:#eee;margin-top: 15px;padding-left:20px;padding-right:20px;">
            <div class='span12 box'>
                <h5>常用功能</h5>
                <div class='row-fluid'>
                    <div class='span3'>
                        <a href="<?php echo U('bussiness/index');?>">
                            <div class='box-content box-statistic'>
                                <small>商家管理 </small>
                                <div class='text-warning icon-book align-right'></div>
                            </div>
                        </a>
                    </div>
                    <div class='span3'>
                        <a href="<?php echo U('ustore/index');?>">
                            <div class='box-content box-statistic'>
                                <small>U店管理</small>
                                <div class='text-success icon-flag align-right'></div>
                            </div>
                        </a>
                    </div>
                    <div class='span3'>
                        <a href="<?php echo U('order/index');?>">
                            <div class='box-content box-statistic'>
                                <small>订单管理</small>
                                <div class='text-error icon-user align-right'></div>
                            </div>
                        </a>
                    </div>
                    <div class='span3'>
                        <a href="<?php echo U('shopfinance/index');?>">
                            <div class='box-content box-statistic'>
                                <small>财务管理</small>
                                <div class='text-error icon-inbox align-right'></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php  if($_SESSION['admin']['admin_id'] == 1){?>
        <div class='row-fluid' style="background:#eee;margin-top: 15px;padding-left:20px;padding-right:20px;">
            <div class='span12 box'>
                <h5>会员总览</h5>
                <div class='row-fluid'>
                    <div class='span3'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-info pull-right'><?php echo (($vip)?($vip): 0); ?></h3>
                            <small>会员总人数</small>
                        </div>
                    </div>
                    <div class='span3'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-success pull-right'><?php echo (($dvip)?($dvip): 0); ?></h3>
                            <small>会员认证待审核</small>
                        </div>
                    </div>
                    <div class='span3'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-muted pull-right'><?php echo (($totay_l)?($totay_l): 0); ?></h3>
                            <small>今日注册会员</small>
                        </div>
                    </div>
                    <div class='span3'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-primary pull-right'><?php echo (($totay_v)?($totay_v): 0); ?></h3>
                            <small>今日新增会员</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <style>
            .lu{
                margin-left: 25px;
                float: left;
            }
            .lu li{
                float: left;
                margin-left: 15px;
                cursor: pointer;
            }
        </style>
        <div class="row-fluid" style="margin-top: 15px;padding-left:20px;padding-right:20px;">
            <h4 style="text-align:center;">30天订单流量</h4>
            <p style="display: inline-block;float: left;">
                订单总数: <span id="money"></span>
               <!--  <ul class="lu">
                    1 待付款 2 支付超时 3 取消订单 4 已付款 5 付款失败 6待使用 7待评价 8 已完成 9 退款中 10 退款失败 11 已退款 12 分红开始结算
                    <li><a href="javascript:;" onclick="ajax_select('13')">全部</a></li>
                    <li><a href="javascript:;" onclick="ajax_select('1')">待付款</a></li>
                    <li><a href="javascript:;" onclick="ajax_select('4')">已付款</a></li>
                    <li><a href="javascript:;" onclick="ajax_select('11')">已退款</a></li>
                    <li><a href="javascript:;" onclick="ajax_select('9')">申请退款</a></li>
                </ul> -->
            </p>
            <div class="content" style="margin-top: 50px;">
                <div id="mountNode"></div>
            </div>
            <script>/*Fixing iframe window.innerHeight 0 issue in Safari*/document.body.clientHeight;</script>
            <script src="https://gw.alipayobjects.com/os/antv/pkg/_antv.g2-3.2.8-beta.5/dist/g2.min.js"></script>
            <script src="https://gw.alipayobjects.com/os/antv/pkg/_antv.data-set-0.8.9/dist/data-set.min.js"></script>
        </div>
        <script>
            // $('.lu li').click(function(){
            //     $(this).css({'color':'black'})
            // })
        </script>
    </div>
    <script>
        var chart = new G2.Chart({
            container: 'mountNode',
            forceFit: true,
            height: 300,
        });
        $.post("<?php echo U('ajax_select_d');?>",{type:0},function(data){
            var money = money1 = 0;
            for(var i in data.data){
                const money_ = data.data[i].num;
                money += Number(money_);
            }
            if(data.data == null){
                $('#money').html(0+'个');
            }
            $('#money').html(money+'个');
            console.log('data.data', data.data);
            const _data = data.data.map(v => ({
                date: v.date,
                num: Number(v.num)
            }))
            // console.log('_data', _data)
            test(_data);
        },'json')
        
        // function ajax_select(type){
        //     $.post("<?php echo U('ajax_select_d');?>",{type:type},function(data){
        //         var money = money1 = 0;
        //         for(var i in data.data){
        //             const money_ = data.data[i].num;
        //             money += Number(money_);
        //         }
        //         if(data.data == null){
        //             $('#money').html(0+'个');
        //         }
        //         $('#money').html(money+'个');
        //         console.log('data.data', data.data);
        //         const _data = data.data.map(v => ({
        //             date: v.date,
        //             num: Number(v.num)
        //         }))
        //         // console.log('_data', _data)
        //         test(_data);
        //     },'json')
        // }

        function test(data){
            chart.source(data);
            chart.scale('num', {
            min: 0
            });
            chart.scale('date', {
                range: [0, 1]
            });
            chart.tooltip({
                crosshairs: {
                    type: 'line'
                }
            });
            chart.line().position('date*num');
            chart.point().position('date*num').size(4).shape('circle').style({
              stroke: '#fff',
              lineWidth: 1
            });
            chart.render();
        }
    </script>

</div>
</body>
</html>