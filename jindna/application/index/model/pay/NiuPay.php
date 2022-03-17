<?php
/**
 * 牛支付
 */

namespace app\index\model\pay;

use think\Db;

class NiuPay
{

    /**
     * 构建支付，返回发起支付url
     * @param $order_sn
     * @param float $price
     * @param string $openId
     * @return false|mixed|string
     */
    public function pay($order_sn, $price, $openId = '')
    {


        $appid = Db::name('config')->where('name', 'zfn_mchid')->value('value');
        $appsecret = Db::name('config')->where('name', 'zfn_key')->value('value');
        $url = Db::name('config')->where('name', 'zfn_url')->value('value');

        $Post['mid'] = $appid;
        $Post['type'] = $_REQUEST['type'];
        $Post['payId'] = $order_sn;
        $Post['notifyUrl'] = request()->domain() . url('index/pay/niunotify');
        $Post['returnUrl'] = request()->domain() . url('index/index/shouye');
        $Post['param'] = 'chongzhi';
        $Post['price'] = $price;
        $Post['isHtml'] = '1';
        //$Post['key'] = $appsecret;//提示传入秘钥的时候，不传mid不签名
        $sign = "{$Post['mid']}{$Post['payId']}{$Post['param']}{$Post['type']}{$Post['price']}{$appsecret}";
        $Post['sign'] = md5($sign);
        $Post['sign_type'] = 'MD5';

        //var_dump($Post);die;

        $str = '<form id="Form1" name="Form1" method="post" action="' . $url . '">';
        foreach ($Post as $key => $val) {
            $str .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $str .= '</form>';
        $str .= '<script>';
        $str .= 'document.Form1.submit();';
        $str .= '</script>';

        cache('niu' . $order_sn, $str);

        return request()->domain() . url('index/index/niuhtml', ['out_trade_no' => $order_sn]);

        echo $str;
        die;

    }

    /**
     * 异步回调(json格式字符串)
     * @return mixed|void
     * @throws \think\Exception
     */
    public function notify()
    {

        $key = Db::name('config')->where('name', 'zfn_key')->value('value');
        $order_sn = $_GET['payId'];//商户订单号

        $payId = $_GET['payId'];//商户订单号
        $param = $_GET['param'];//创建订单的时候传入的参数
        $type = $_GET['type'];//支付方式 ：微信支付为1 支付宝支付为2
        $price = $_GET['price'];//订单金额
        $reallyPrice = $_GET['reallyPrice'];//实际支付金额
        $sign = $_GET['sign'];//校验签名，计算方式 = md5(mid+payId + param + type + price + reallyPrice + 通讯密钥)
//开始校验签名
        if ($price < 0 || !isset($_GET['sign'])) {
            exit('error1');
        }
        $data = $_GET;

        $transaction_id = $data['payId'];//支付流水号
        $where['order_num'] = $order_sn;
        $order = db('recharge_order')->where($where)->find();


        if ($order['is_pay'] == 0) {//$order['is_pay'] == 0
            $update['return_num'] = $transaction_id;
            $update['updatetime'] = time();
            $update['is_pay'] = '1';
            $result = db('recharge_order')->where($where)->update($update);
            //充值记录
            db('user')->where('id', $order['user_id'])->setInc('jine', $order['jine']);
            $log_data = [
                'user_id' => $order['user_id'],
                'jine' => $order['jine'],
                'desc' => '充值获得',
                'createtime' => time(),
            ];
            db('jine_log')->insert($log_data);
            $user = db('user')->where('id', $order['user_id'])->find();
            if ($user['tuid1'] > 0) {
                $yongjin = $order['jine'] * config('site.yiji_bili');
                db('user')->where('id', $user['tuid1'])->setInc('yongjin', $yongjin);
            }
            if ($user['tuid2'] > 0) {
                $yongjin = $order['jine'] * config('site.erji_bili');
                db('user')->where('id', $user['tuid2'])->setInc('yongjin', $yongjin);
            }
            if ($user['tuid3'] > 0) {
                $yongjin = $order['jine'] * config('site.sanji_bili');
                db('user')->where('id', $user['tuid3'])->setInc('yongjin', $yongjin);
            }
        }
        echo 'success';
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