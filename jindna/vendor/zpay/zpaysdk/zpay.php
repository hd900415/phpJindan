<?php


/*
 * Z支付创建订单示例程序
 */


use zpay\Common\CommonFunction;
use zpay\Exception\ZpayException;
use zpay\Models\CreateOrderRequest;
use zpay\ZPaySdk;

require_once 'src/Models/CreateOrderRequest.php';
require_once 'src/ZPaySdk.php';

//实例化创建订单的请求参数
$order = new CreateOrderRequest();

//支付类型 1-->微信,2-->支付宝
$order->type =1;
//金额
$order->amount =1;
//订单附加参数
$order->attach ='订单测试';
//商户订单号
$order->out_trade_no = CommonFunction::build_order_no();
//返回数据类型,1-->使用form表单提交订单,0-->返回json数据
$order->isHtml =1;
//订单过期时间,单位分钟
$order->time_expire =5;
//同步地址,订单成功后页面跳转的地址
$order->return_url ='https://www.baidu.com';
//异步地址,订单支付成功后请求的地址
$order->notify_url ='https://www.baidu.com';

//实例化sdk
$sdk = new ZPaySdk();
try {
    //输出创建订单的html数据
    echo $sdk->createOrder($order);
} catch (ZpayException $e) {
    //输出异常信息
    print_r($e->getMessage());
}





