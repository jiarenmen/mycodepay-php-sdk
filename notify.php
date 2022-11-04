<?php
/*

异步回调通知处理案例

1.检查参数
2.校验签名
3.处理业务
4.响应结果
*/

include 'config.php';
include 'myCodePay.class.php';
$myCodePay=new myCodePay();

//获取签名
$sign=$myCodePay->getSign($codepay_config['key'],$_POST);
if(isset($_POST['sign'])){
	if($sign==$_POST['sign']){
		//签名验证成功，开始处理你的业务

		$out_trade_no=$_POST['out_trade_no'];//你平台的订单号
		$order_no=$_POST['order_no'];//支付平台订单号
		$price=$_POST['price'];//本次付款的金额
		$original_price=$_POST['original_price'];//订单原本金额
		$type=$_POST['pay_type'];//支付方式，1微信，2支付宝

		//将订单当做文件存储在本地，用于查询订单的接口判断是否付款完成(实际情况则是将订单数据更新数据库，然后根据数据库查询信息)
		if(!is_dir('./orderCompleteLog')){
			@mkdir('./orderCompleteLog', 0777);
		}
		file_put_contents('./orderCompleteLog/'.$out_trade_no,'');


		echo 'success'; //最终需要输出success告知本次回调通知处理成功了
	}else{
		//签名验证失败
		echo 'fial';
	}
}else{
	//参数缺少
	echo 'fial';
}
