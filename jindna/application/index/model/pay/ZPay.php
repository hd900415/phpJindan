<?php
/**
 * Z支付
 */

namespace app\index\model\pay;

use think\Db;
use zpay\Common\CommonFunction;
use zpay\Exception\ZpayException;
use zpay\Models\CreateOrderRequest;
use zpay\ZPaySdk;

class ZPay
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


        $appid = Db::name('config')->where('name', 'ydf_id')->value('value');
        $appsecret = Db::name('config')->where('name', 'ydf_key')->value('value');


        //实例化创建订单的请求参数
        $order = new CreateOrderRequest();

//支付类型 1-->微信,2-->支付宝
        $order->type = $_REQUEST['type'];
//金额
        $order->amount = $price;
//订单附加参数
        $order->attach = '砸金蛋';
//商户订单号
        $order->out_trade_no = $order_sn;
//返回数据类型,1-->使用form表单提交订单,0-->返回json数据
        $order->isHtml = 0;
//订单过期时间,单位分钟
        $order->time_expire = 5;
//同步地址,订单成功后页面跳转的地址
        $order->return_url = request()->domain() . url('index/index/shouye');
//异步地址,订单支付成功后请求的地址
        $order->notify_url = request()->domain() . url('index/pay/znotify');

//实例化sdk
        $sdk = new ZPaySdk($appid, $appsecret);
        try {
            //输出创建订单的html数据
            $zpayOrderInfo = $sdk->createOrder($order);
            return $sdk->getPayUrl($zpayOrderInfo);
        } catch (ZpayException $e) {
            //输出异常信息
            print_r($e->getMessage());
        }


        die;

    }

    /**
     * 异步回调(json格式字符串)
     * @return mixed|void
     * @throws \think\Exception
     */
    public function notify()
    {
        $appid = Db::name('config')->where('name', 'ydf_id')->value('value');
        $appsecret = Db::name('config')->where('name', 'ydf_key')->value('value');
        $sdk = new ZPaySdk($appid, $appsecret);
        if ($sdk->isSign()) {
            //签名验证成功,订单验证成功
            //---------开始业务逻辑----------------
            $request_data = CommonFunction::get_notify_parameter();
            $order_sn = $request_data['out_trade_no'];
            $transaction_id = $order_sn;//支付流水号
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
            //----------业务逻辑结束---------------
            //告诉服务器已经收到通知
            echo 'success';
        } else {
            exit('fail');
        }

    }


}