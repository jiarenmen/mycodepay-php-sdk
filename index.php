<?php
//下单案例

include 'config.php';
include 'myCodePay.class.php';
$myCodePay=new myCodePay();

$out_trade_no=date("YmdHis");//生成订单号
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST']; //获取当前网站地址

//请求参数
$data=array(
	'out_trade_no'=>$out_trade_no,//你平台的订单号
	'price'=>0.02,//支付金额
	'pay_type'=>1,//支付方式，1微信，2支付宝
	'notify_url'=>$siteurl.'/notify.php',//异步回调跳转地址
);

//生成签名并加入到请求参数里
$data['sign']=$myCodePay->getSign($codepay_config['key'],$data);

$res=json_decode($myCodePay->post($codepay_config['url']."/api/api/createOrder",$data),true);
if(isset($res['code'])){
	if($res['code']==1){
		//下单成功
		
		/**  以下为$res的结构
		Array
		(
			[code] => 1
			[message] => 下单成功
			[data] => Array
				(
					[code_url] => https://mycodepay.giao.cc/index/qr_code/create?content=wxp%3A%2F%2Ff2f0LiC6BtMGwxmkp_TJrOQDU_2YyWnq0SoP1tqyjgj7lfLaIvVu99wWGUGvpQNicobV
					[qrcode] => wxp://f2f0LiC6BtMGwxmkp_TJrOQDU_2YyWnq0SoP1tqyjgj7lfLaIvVu99wWGUGvpQNicobV
					[price] => 0.01
					[original_price] => 0.01
					[pay_type] => 1
					[trade_no] => 2022070222362010822934
					[order_id] => 37
					[timeout_time] => 1656772880
					[create_time] => 1656772820
				)

		)
		*/
		//print_r($res);
		
		//以下输出html代码进行页面支付显示
		$order=$res['data'];
		$qrCode=$order['qrcode'];
		if($order['pay_type']==1){
			$payTypeName='微信';
		}else if($order['pay_type']==2){
			$payTypeName='支付宝';
		}else if($order['pay_type']==3){
			$payTypeName='QQ';
		}
		$orderName='订单名称';
		$returnUrl='https://www.baidu.com';//付款成功跳转的链接
		include 'codepay.php';
	}else{
		//下单失败 $res['message'] 是下单失败返回的内容
		echo $res['message'];
	}
}else{
	echo "下单失败";
	//下单失败，请求出错
}