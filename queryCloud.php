<?php
//查询订单案例

include 'config.php';
include 'myCodePay.class.php';
$myCodePay=new myCodePay();

//请求参数
$data=array(
	'out_trade_no'=>'20220810104113',//你平台的订单号
	'timestamp'=>time(),
);

//生成签名并加入到请求参数里
$data['sign']=$myCodePay->getSign($codepay_config['key'],$data);

$res=json_decode($myCodePay->post($codepay_config['url']."/api/api/queryOrder",$data),true);
if(isset($res['code'])){
	if($res['code']==1){
		//查询成功

		/**  以下为$res的结构
		Array
		(
			[code] => 1
			[message] => 查询成功
			[data] => Array
				(
					[order_no] => 2022070222182860580173
					[out_trade_no] => 20220702141819
					[price] => 0.01
					[original_price] => 0.01
					[pay_type] => 1
					[create_time] => 1656771508
					[payment_time] => 0
					[timeout_time] => 1656771808
					[status] => 0
				)

		)
		*/
		print_r($res);
	}else{
		//出现失败 $res['message'] 是失败返回的内容
		echo $res['message'];
	}
}else{
	echo "查询失败";
	//查询失败，请求出错
}
