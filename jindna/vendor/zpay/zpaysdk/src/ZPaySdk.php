<?php


namespace zpay;

use zpay\Common\CommonFunction;
use zpay\Common\Sign;
use zpay\Config\ZpayConfig;
use zpay\Exception\ZpayException;
use zpay\Models\CreateOrderRequest;

require_once 'Config/ZpayConfig.php';
require_once 'Common/CommonFunction.php';
require_once 'Common/Sign.php';
require_once 'Exception/ZpayException.php';

class ZPaySdk
{

    private $config;

    //支付页面地址
    private $payUrl = "https://pay.bvaas.net/payPage/pay.php?out_trade_no=";
    //支付网关
    private $gateway = "https://pay.rxkku.net/v1/common/";
    //获取微信用户信息地址
    private $getWeChatInfoApiUrl = "https://pay.rxkku.net/v1/wechat/getWechatUserUrl";
    //创建订单接口
    private $createOrderUrl = 'createOrder';
    //获取订单接口
    private $getOrderApiUrl ='getOrder';
    //关闭订单接口
    private $closeOrderApiUrl ='closeOrder';
    //sdk版本号
    private $version = 'v2.1.0';


    function __construct($mch_id=null,$key=null)
    {
        $this->config = new ZpayConfig();
        if ($mch_id!=null)
        {
            $this->config->mch_id=$mch_id;
        }
        if ($key != null)
        {
            $this->config->secret_key=$key;
        }
    }

    //创建订单
    public function createOrder(CreateOrderRequest $createOrderRequest)
    {
        //验证传入的参数
        if ($createOrderRequest == null) {
            throw new ZpayException("500","参数列表为空",$this->version);
        } else if ($createOrderRequest->out_trade_no == null || $createOrderRequest->out_trade_no == '') {
            throw new ZpayException("500","订单号为空",$this->version);
        } else if ($createOrderRequest->attach == null || $createOrderRequest->attach == '') {
            throw new ZpayException("500","附加参数为空",$this->version);
        } else if ($createOrderRequest->amount == null || $createOrderRequest->amount == '') {
            throw new ZpayException("500","金额为空",$this->version);
        } else if ($createOrderRequest->type == null || $createOrderRequest->type == '') {
            throw new ZpayException("500","支付类型为空",$this->version);
        }
        $parameter['mch_id'] = $this->config->mch_id;
        $parameter['timestamp'] = CommonFunction::millisecondWay();
        //补齐参数
        $parameter['sign'] = Sign::sign($createOrderRequest->out_trade_no, $createOrderRequest->attach, $createOrderRequest->type
            , $createOrderRequest->amount, $parameter['timestamp'], $this->config->mch_id, $this->config->secret_key);
        //如过传递过来的同步地址为空就获取配置文件里的
        if (empty($createOrderRequest->return_url)) {
                throw new ZpayException("500","未传入同步地址",$this->version);
        }
        if (empty($createOrderRequest->notify_url)) {
                throw new ZpayException("500","未传入异步地址",$this->version);
        }

        $request_url = $this->gateway.$this->createOrderUrl;

        $ordq = $createOrderRequest->serialize();
        $parameter = array_merge($parameter,$ordq);
        if ($createOrderRequest->isHtml == 0) {
            $content = CommonFunction::get_cur($request_url, $parameter, 'POST');
            //判断是否是url
            $getOrderInfo = json_decode($content, true);
            if ($getOrderInfo['code'] == 200) {
                return $getOrderInfo;
            } else {
               throw new ZpayException($getOrderInfo['code'],$getOrderInfo['msg'],$this->version);
            }
        } else if ($createOrderRequest->isHtml == 1) {
            $sHtml = "<form id='submit' name='submit' action='" . $request_url . "' method='post'>";
            foreach ($parameter as $key => $val) {
                $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
            }
            $sHtml = $sHtml . "<input type='submit' value='提交订单'></form>";
            $sHtml = $sHtml . "<script>document.forms['submit'].submit();</script>";
            return $sHtml;
        }
    }

    public function getPayUrl($getOrderInfo)
    {
        return $this->payUrl.$getOrderInfo['data']['outTradeNo'];
    }

    //判断订单是否支付成功
    public function checkOrder($out_trade_no)
    {
        $parameter['out_trade_no'] = $out_trade_no;
        $request_url = $this->gateway.$this->getOrderApiUrl;
        $parameter = json_decode(CommonFunction::get_cur($request_url, $parameter), true);
        if ($parameter['code'] == 200) {
            //订单状态：-1:订单过期 0:等待支付 1:完成 2:支付完成但通知失败
            if ($parameter['data']['state'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //关闭订单
    public function closeOrder($out_trade_no)
    {
        $parameter['out_trade_no'] = $out_trade_no;
        $parameter['sign'] = md5($out_trade_no, $this->config->secret_key);
        $request_url = $this->gateway.$this->closeOrderApiUrl;
        $parameter = json_decode(CommonFunction::get_cur($request_url, $parameter), true);
        if ($parameter['code'] == 200) {
            //关闭成功
            return true;
        } else {
            //关闭失败
            return false;
        }
    }

    //附加服务获取微信用户信息，传入用户ID，传入要接收数据的地址
    public function get_WeChat_info($url)
    {
        $request_url = $this->getWeChatInfoApiUrl;

        $http_request_parameter['mch_id'] = $this->config->mch_id;
        $http_request_parameter['redirect_uri'] = $url;
        $http_request_parameter['t'] = CommonFunction::millisecondWay();
        $http_request_parameter['sign'] = Sign::wechatSign($this->config->mch_id, $http_request_parameter['redirect_uri']
            , $this->config->secret_key, $http_request_parameter['t']);

        $parameter =  json_decode(CommonFunction::get_cur($request_url, $http_request_parameter),true);
        if($parameter['code']==200)
        {
            $request_url = $parameter['data'] . '/getWechatUserInfo.php?' . http_build_query($http_request_parameter);
            Header("Location:".$request_url);

        }else
        {
            throw new ZpayException($parameter['code'],$parameter['msg'],$this->version);
        }
    }


    //异步验证签名
    public function isSign()
    {
        //异步回来的参数表
        $notify_parameter = CommonFunction::get_notify_parameter();
        $_sign = md5($notify_parameter['out_trade_no']
            . $notify_parameter['attach']
            . $notify_parameter['type']
            . $notify_parameter['amount']
            . $notify_parameter['really_amount']
            . $this->config->mch_id
            . $this->config->secret_key);
        if ($notify_parameter['sign'] == $_sign) {
            return true;
        } else {
            return false;
        }
    }
}