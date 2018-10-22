<?php
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Origin: http://wx.uboss.net.cn");
header("Access-Control-Allow-Headers", "access-control-allow-origin,content-type");
//php插件下载地址： https://files.cnblogs.com/files/fan-bk/jssdk_php.rar
//建立一个php文件
require_once "jssdk.php";  //引入下载的PHP插件
$jssdk = new JSSDK("wxece7fccd7def1e55","919a2c069b07190863f15ec96a2e4985",'http://m.uboss.net.cn/location.php');//填写公众号 密匙
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>
<body>
 <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
 <script>
 wx.config({
   debug: false,  //调式模式，设置为ture后会直接在网页上弹出调试信息，用于排查问题
   appId: '<?php echo $signPackage["appId"];?>',
   timestamp: <?php echo $signPackage["timestamp"];?>,
   nonceStr: '<?php echo $signPackage["nonceStr"];?>',
   signature: '<?php echo $signPackage["signature"];?>',
   jsApiList: [ // 所有要调用的 API 都要加到这个列表中
       'checkJsApi',
       'openLocation',
       'getLocation'
 
   ]
 });
 wx.ready(function () {  
 
wx.checkJsApi({
    jsApiList: [
        'getLocation'
    ],
    success: function (res) {
        // alert(JSON.stringify(res));
        // alert(JSON.stringify(res.checkResult.getLocation));
        if (res.checkResult.getLocation == false) {
//            alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
            return;
        }
    }
});
wx.getLocation({
    success: function (res) {
        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
        var speed = res.speed; // 速度，以米/每秒计
        var accuracy = res.accuracy; // 位置精度
//        alert('纬度：'+latitude+'，经度:'+longitude+'，位置精度：'+accuracy);
    },
    cancel: function (res) {
//        alert('用户拒绝授权获取地理位置');
    }
});
 
});       
wx.error(function (res) {
 //alert(res.errMsg);  //打印错误消息。及把 debug:false,设置为debug:ture就可以直接在网页上看到弹出的错误提示
});
 </script>
</body>
</html>
<?php
/*
$url = "http://api.map.baidu.com/geocoder?location=“+latitude+","+longitude+”&output=json&key=28bcdd84fae25699606ffad27f8da77b";
$content = file_get_contents($url);
$res = json_decode($content, true);
print_r($res);*/
