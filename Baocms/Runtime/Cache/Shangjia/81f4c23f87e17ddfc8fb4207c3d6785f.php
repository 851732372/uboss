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
                                    <?php echo (($all_money)?($all_money): 0); ?>元
                                </div>
                            </div>
                            <div class='content'>总营收</div>
                        </a>
                    </div>
                    <div class='span3 box-quick-link green-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-star'>
                                    <?php echo (($today_num)?($today_num): 0); ?>
                                </div>
                            </div>
                            <div class='content'>今日总销量</div>
                        </a>
                    </div>
                    <div class='span3 box-quick-link orange-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-magic'>
                                    <?php echo (($comment_num)?($comment_num): 0); ?>
                                </div>
                            </div>
                            <div class='content'>收到评论</div>
                        </a>
                    </div>
                    <div class='span3 box-quick-link purple-background'>
                        <a>
                            <div class='header'>
                                <div class='icon-comments'>
                                    <?php echo (($custorm)?($custorm): 0); ?>
                                </div>
                            </div>
                            <div class='content'>我的客户</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <hr class='hr-drouble' />
        <div class='row-fluid' style="background:#eee;margin-top: 15px;padding-left:20px;padding-right:20px;">
            <div class='span12 box'>
                <h5>待处理订单</h5>
                <div class='row-fluid'>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-info'><?php echo (($consumed)?($consumed): 0); ?></h3>
                            <small>待消费</small>
                            <div class='text-info icon-inbox align-right'></div>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-success'><?php echo (($obligation)?($obligation): 0); ?></h3>
                            <small>待付款</small>
                            <div class='text-success icon-time align-right'></div>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-primary'><?php echo (($paid)?($paid): 0); ?></h3>
                            <small>已付款</small>
                            <div class='text-primary icon-truck align-right'></div>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-warning'><?php echo (($evaluated)?($evaluated): 0); ?></h3>
                            <small>待评价</small>
                            <div class='text-warning icon-ok align-right'></div>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-muted'><?php echo (($completed)?($completed): 0); ?></h3>
                            <small>已完成</small>
                            <div class='muted icon-remove align-right'></div>
                        </div>
                    </div>
                    <div class='span2'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-error'><?php echo (($refunding)?($refunding): 0); ?></h3>
                            <small>退款中</small>
                            <div class='text-error icon-user align-right'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='row-fluid' style="background:#eee;margin-top: 15px;padding-left:20px;padding-right:20px;">
            <div class='span12 box'>
                <h5>常用功能</h5>
                <div class='row-fluid'>
                    <div class='span3'>
                        <a href="<?php echo U('mone/index');?>">
                            <div class='box-content box-statistic'>
                                <h3 class='title text-warning'><?php echo (($money['asset']/100)?($money['asset']/100): 0); ?>元</h3>
                                <small>资金管理</small>
                                <div class='text-warning icon-book align-right'></div>
                            </div>
                        </a>
                    </div>
                    <div class='span3'>
                        <a href="<?php echo U('orders/index');?>">
                            <div class='box-content box-statistic'>
                                <h3 class='title text-success'><?php echo (($order_num)?($order_num): 0); ?></h3>
                                <small>订单管理</small>
                                <div class='text-success icon-flag align-right'></div>
                            </div>
                        </a>
                    </div>
                    <div class='span3'>
                        <div class='box-content box-statistic'>
                            <h3 class='title text-error'> <?php echo (($custorm)?($custorm): 0); ?></h3>
                            <small>客户管理</small>
                            <div class='text-error icon-user align-right'></div>
                        </div>
                    </div>
                    <div class='span3' style="">
                        <div class='box-content box-statistic'>
                            <h3 class='title text-primary'><?php echo (($comment_num)?($comment_num): 0); ?></h3>
                            <small>评价管理</small>
                            <div class='text-error icon-inbox align-right'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid" style="margin-top: 15px;padding-left:20px;padding-right:20px;">
            <h4 style="text-align:center;">30天营业走势</h4>
            <p>订单总数: <span id="money"></span></p>
            <div class="content">
                <div id="mountNode"></div>
            </div>
            <script>/*Fixing iframe window.innerHeight 0 issue in Safari*/document.body.clientHeight;</script>
            <script src="https://gw.alipayobjects.com/os/antv/pkg/_antv.g2-3.2.8-beta.5/dist/g2.min.js"></script>
            <script src="https://gw.alipayobjects.com/os/antv/pkg/_antv.data-set-0.8.9/dist/data-set.min.js"></script>
        </div>
    </div>
    <script>
        var chart = new G2.Chart({
            container: 'mountNode',
            forceFit: true,
            height: 300,
        });
        $.post("<?php echo U('ajax_select_d');?>",{type:0,user_id:"<?php echo ($_GET['user_id']); ?>"},function(data){
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
            const data1 = data.data && data.data.map(v => {
                v.num = Number(v.num);
                return v;
            })
            test(data.data);
        },'json')
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
</body>
</html>