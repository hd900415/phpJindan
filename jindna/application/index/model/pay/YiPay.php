<?php
/**
 * 易支付
 */

namespace app\index\model\pay;

use think\Db;

class YiPay
{
    protected $payUrl = 'http://1t2c.cn/submit.php';
    protected $mercId="888427";
    protected $mercKey="B4D4B60A24A55A0AF943824C80BCADDA";

    /**
     * 构建支付，返回发起支付url
     * @param $order_sn
     * @param float $price
     * @param string $openId
     * @return false|mixed|string
     */
    public function pay($order_sn, $price, $openId = '')
    {
        $notify = request()->domain().url('index/pay/yinotify');
        $return_url = request()->domain().url('index/index/chongzhi');
        $name = '商品';
        $params = [
            'out_trade_no' => $order_sn,
            'pid' => $this->mercId,
            'type' => "wxpay",//alipay:支付宝,tenpay:财付通, qqpay:QQ钱包,wxpay:微信支付
            'notify_url' => $notify,//支付结果回调地址
            'return_url' => $return_url,//支付成功回跳地址
            'name' => $name,
            'money' => $price,//支付金额，元
            'sign_type' => "MD5",
        ];
        $params['sign'] = self::MakeSign($params, $this->mercKey);
        return  $this->payUrl . "?" . http_build_query($params);
    }

    /**
     * 异步回调(json格式字符串)
     * @return mixed|void
     * @throws \think\Exception
     */
    public function notify()
    {
        $data = input("get.");
        error_log(date("Y-m-d H:i:s ") . var_export($data, true) . "\n", 3, 'pay_notify.log');
        if (!empty($data)) {
            unset($data['platform']);
            $order_sn = $data['out_trade_no'];//商户订单号

            if (isset($data['sign']) && $data['sign'] != '' && self::MakeSign($data, $this->mercKey) == $data['sign']) {
                $transaction_id = $data['trade_no'];//支付流水号
                $where['order_num']=$order_sn;
                $order=db('recharge_order')->where($where)->find();
                if($order['is_pay'] == 0){
                    $update['return_num'] = $transaction_id;
                    $update['updatetime'] = time();
                    $update['is_pay'] = '1';
                    $result = db('recharge_order')->where($where)->update($update);
                    //充值记录
                    db('user')->where('id',$order['user_id'])->setInc('jine',$order['jine']);
                    $log_data = [
                                'user_id' => $order['user_id'],
                                'jine' => $order['jine'],
                                'desc' => '充值获得',
                                'createtime' => time(),
                            ];
                    db('jine_log')->insert($log_data);
                    $user = db('user')->where('id',$order['user_id'])->find();
                    if($user['tuid1'] > 0){
                        $yongjin = $order['jine'] * config('site.yiji_bili');
                        db('user')->where('id',$user['tuid1'])->setInc('yongjin',$yongjin);
                    }
                    if($user['tuid2'] > 0){
                        $yongjin = $order['jine'] * config('site.erji_bili');
                        db('user')->where('id',$user['tuid2'])->setInc('yongjin',$yongjin);
                    }
                    if($user['tuid3'] > 0){
                        $yongjin = $order['jine'] * config('site.sanji_bili');
                        db('user')->where('id',$user['tuid3'])->setInc('yongjin',$yongjin);
                    }
                }
                echo 'success';
                exit();
            }
        }
        echo 'fail';
        exit();
    }


    /**
     * 生成签名
     * @param string $sign_type 签名方式
     * @return string
     */
    protected function MakeSign($param, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($param);
        $string = $this->ToUrlParams($param);
        //签名步骤二：在string后加入KEY
        $string = $string . $key;
        //签名步骤三：MD5加密
        return md5($string);
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams($param)
    {
        $buff = "";
        foreach ($param as $k => $v) {
            if ($k == "sign" || $k == "sign_type" || $v == "") {
                continue;
            } else {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
}