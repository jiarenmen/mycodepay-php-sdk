<?php
//签名生成与请求发送工具类
class myCodePay{
	//参数序列化拼接
    public function kSortStr($paramArr)
    {
        if (empty($paramArr)) return '';
        ksort($paramArr);
        $str = '';
        foreach ($paramArr as $k => $v) {
            if ($v!='' && $k != 'sign') {
                $str .= $k . '=' . $v . '&';
            }

        }
        return rtrim($str, '&');
    }

	//获取签名
    public function getSign($key,$paramArr){
        return md5($this->kSortStr($paramArr).$key);
    }

    public function post($url, $data) {
        //初使化init方法
        $ch = curl_init();

        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);

        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);

        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //发送请求
        $output = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        //返回数据
        return $output;
    }
}