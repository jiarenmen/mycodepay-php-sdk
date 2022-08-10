<?php
//订单付款状态查询，给进行付款的html页面查询付款状态使用(页面通过ajax请求查询如果订单付款成功则跳转到支付成功回调地址)

if(!isset($_GET['out_trade_no']) || $_GET['out_trade_no']==''){
	echo json_encode(array('code'=>-1,'msg'=>'请传递订单号'));
	exit;
}
$out_trade_no=$_GET['out_trade_no'];
if(is_file('./orderCompleteLog/'.$out_trade_no)){
	//如果该订单号名字的文件存在orderCompleteLog目录下，则是订单付款成功
	//至于付款成功的订单号为什么会在orderCompleteLog目录下存在，可以看支付回调的逻辑(notify.php)
	
	//实际情况应该是实时去数据库查询订单信息(这里请自行根据自己的业务需求进行处理查询)
	
	echo json_encode(array('code'=>10000,'msg'=>'支付成功'));
	exit;
}else{
	echo json_encode(array('code'=>-1,'msg'=>'订单未付款'));
	exit;
}