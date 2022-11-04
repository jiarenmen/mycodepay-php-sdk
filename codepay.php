<?php
//订单付款展示页面html

/* 示例参数
$order=array(
	'code_url' => 'https://mycodepay.giao.cc/index/qr_code/create?content=wxp%3A%2F%2Ff2f0LiC6BtMGwxmkp_TJrOQDU_2YyWnq0SoP1tqyjgj7lfLaIvVu99wWGUGvpQNicobV',
	'qrcode' => 'wxp://f2f0LiC6BtMGwxmkp_TJrOQDU_2YyWnq0SoP1tqyjgj7lfLaIvVu99wWGUGvpQNicobV',
	'price' => 0.01,
	'original_price' => 0.01,
	'pay_type' => 1,
	'order_no' => '2022070222362010822934',
	'order_id' => '37',
	'timeout_time' => 1656772880,
	'create_time' => 1656772820,
);
$qrCode=$order['qrcode'];
if($order['pay_type']==1){
	$payTypeName='微信';
}else if($order['pay_type']==2){
	$payTypeName='支付宝';
}else if($order['pay_type']==3){
	$payTypeName='QQ';
}
$orderName='订单名称';
$out_trade_no='订单号';
$returnUrl='https://www.baidu.com';
*/
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no,email=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?php echo $payTypeName;?>扫码支付</title>
    <?php
    if($order['pay_type']==1){
        echo '<link href="/static/pay/css/wechat_pay.css" rel="stylesheet" media="screen">';
    }else if($order['pay_type']==2){
        echo '<link href="/static/pay/css/alipay_pay.css" rel="stylesheet" media="screen">';
    }else if($order['pay_type']==3){
        echo '<link href="/static/pay/css/qqpay_pay.css" rel="stylesheet" media="screen">';
    }
    ?>

</head>

<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text"><?php echo $payTypeName;?>支付</span>
    </h1>
    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount">￥<?php echo $order['price'];?></div>
		<?php if($order['original_price']!=$order['price']){?>
            <div style="display: flex; justify-content: center; align-items: center">
                (由于金额正在被其他用户占用,原实付金额<?php echo $order['original_price'];?>元已变更为<?php echo $order['price'];?>元,请务必付款<small style='color:red; font-size:26px'><?php echo $order['price'];?></small>元)
            </div>
        <?php }?>
        <div class="qr-image" id="qrcode">
        </div>
        <div style="margin-top: 20px;" id="divTime"></div>

        <div class="detail" id="orderDetail">
            <dl class="detail-ct" style="display: none;">
                <dt>购买物品</dt>
                <dd id="productName"><?php echo $orderName;?></dd>
                <dt>订单号</dt>
                <dd id="billId"><?php echo $out_trade_no;?></dd>
                <dt>创建时间</dt>
                <dd id="createTime"><?php echo date('Y-m-d H:i:s',$order['create_time']);?></dd>
            </dl>
            <a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan"></div>
            <div class="tip-text">
                <p>请使用<?php echo $payTypeName;?>扫一扫</p>
                <p>扫描二维码完成支付</p>
            </div>
        </div>
        <div class="tip-text">
        </div>
    </div>
    <div class="foot">
        <div class="inner">
            <p>手机用户可保存上方二维码到手机中</p>
            <p>在<?php echo $payTypeName;?>扫一扫中选择“相册”即可</p>
        </div>
    </div>
</div>
<script src="/static/pay/js/qrcode.min.js"></script>
<script src="/static/pay/js/qcloud_util.js"></script>
<script src="/static/pay/js/layer.js"></script>
<script>
    var qrcode = new QRCode("qrcode", {
        text: "<?php echo $qrCode;?>",
        width: 230,
        height: 230,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    // 订单详情
    $('#orderDetail .arrow').click(function (event) {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function () {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function () {
                $('#orderDetail').addClass('detail-open');
            });
        }
    });
    // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/query.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {out_trade_no: "<?php echo $out_trade_no;?>"}, //参数数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 10000) {
                    layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.01,time: 15000});
                    setTimeout(window.location.href='<?php echo $returnUrl;?>', 1000);
                }else{
                    setTimeout("loadmsg()", 4000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = loadmsg();

    var intDiff = parseInt('<?php echo $order['timeout_time']-$order['create_time'];?>');//倒计时总秒数量
    function timer(intDiff){
        window.setInterval(function(){
            var day=0,
                hour=0,
                minute=0,
                second=0;//时间默认值
            if(intDiff > 0){
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            if (hour <= 0 && minute <= 0 && second <= 0) {
                $("#divTime").html("<small style='color:red; font-size:26px'>订单二维码已过期</small>");
                $("#qrcode").html('<img id="qrcode" src="/static/pay/image/qrcode_timeout.png">');//输出过期二维码提示图片
            }else{
                $("#divTime").html("<h3>二维码有效时间:<small style='color:red; font-size:26px'>" + minute + "</small>分<small style='color:red; font-size:26px'>" + second + "</small>秒,若超出时间请勿支付否则可能不会到账</h3>");
            }
            intDiff--
        }, 1000);
    }

    $(function(){
        timer(intDiff);
    });
</script>
</body>
</html>
