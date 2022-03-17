<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayResults;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayApi;
use wxpay\WxPayConfig;
use wxpay\JsApiPay;
class Wxpay  extends Controller
{
    public function index($id)
    {   
        
        $order = db('recharge_order')->where('id',$id)->find();
        $openid = db('user')->where('id',session('user_id'))->value('openid');
        $paytype = [
            'wx_appid' => 'wx41c4cda903c2d3f2',
            'wx_mchid' => '1610141584',
            'wx_key' => 'awdaawAWDHkkwh213akdhkwajhdkawdh',
        ];
        $CANSHU = [  
            'appid'             => $paytype['wx_appid'] ,
            'mch_id'            => $paytype['wx_mchid'],
            'nonce_str'         => md5( $order['order_num']),
            'body'              => '充值',
            'attach'            => $order['order_num'],
            'out_trade_no'      => $order['order_num'],
            'total_fee'         => $order['jine']*100,
            // 'total_fee'         => 1,
            'spbill_create_ip'  => request()->ip(),
            'time_start'        => date('YmdHis'),
            'notify_url'        => request()->domain().'/index/wxpay/wxfan_url',
            'trade_type'        => 'JSAPI',
            'openid'            => $openid,
        ];
        $CANSHU = argSort( $CANSHU );
        $CANSH  = getarray( $CANSHU );
        $CANSHU['sign'] = strtoupper( md5( $CANSH . '&key='.$paytype['wx_key'] ));
        $xml ='<xml>';
        foreach( $CANSHU as $k =>$v ) $xml .= "<$k>$v</$k>";
        $xml .='</xml>';
        $fanhui = https_request('https://api.mch.weixin.qq.com/pay/unifiedorder?',$xml);
        $woqu = str_replace(array('<','>'),'',$fanhui);
        $p = xml_parser_create();
        xml_parse_into_struct($p, $fanhui, $vals, $index);
        xml_parser_free($p);
        foreach( $vals as $zhis)  $shuju[ strtolower( $zhis['tag'] ) ] = isset( $zhis['value']) ? $zhis['value'] :'';
        if( $shuju['return_code'] == 'SUCCESS'){
            $FHSIGN  =  [
                            'appId'     => $paytype['wx_appid'],
                            'timeStamp' => (string)time(),
                            'nonceStr'  => md5(time().rand(1,9999)),
                            'package'   => 'prepay_id='.$shuju['prepay_id'],
                            'signType'  => 'MD5',
                        ];
            $CANSHU = argSort($FHSIGN);
            $CANSH = getarray($CANSHU);
            $FHSIGN['paySign'] = strtoupper(md5($CANSH.'&key='.$paytype['wx_key']));
            return $FHSIGN;
        }else{
            return false;
        }
    }


    /**
     * 微信支付 回调逻辑处理
     * @return string
     */
    public function wxfan_url(){
        $wxData = file_get_contents("php://input");
        try{
            $resultObj = new WxPayResults();
            $wxData = $resultObj->Init($wxData);
        }catch (\Exception $e){
            $resultObj ->setData('return_code','FAIL');
            $resultObj ->setData('return_msg',$e->getMessage());
            return $resultObj->toXml();
        }

        if ($wxData['return_code']==='FAIL'||
            $wxData['return_code']!== 'SUCCESS'){
            $resultObj ->setData('return_code','FAIL');
            $resultObj ->setData('return_msg','error');
            return $resultObj->toXml();
        }
        $out_trade_no = $wxData['out_trade_no'];//订单编号
        $transaction_id = $wxData['transaction_id'];//微信返回的订单编号
        $return_jine = $wxData['total_fee']/100; //微信支付返回的金额
        //此处为举例
        $where['order_num']=$out_trade_no;
        $order=db('recharge_order')->where($where)->find();
        if($order['is_pay'] == 0){
            $update['return_num'] = $transaction_id;
            $update['updatetime'] = time();
            $update['is_pay'] = '1';
            $result = db('recharge_order')->where($where)->update($update);
            //充值记录
            db('user')->where('id',$order['user_id'])->setInc('jine',$order['jine']);
            $data = [
                        'user_id' => $order['user_id'],
                        'jine' => $order['jine'],
                        'desc' => '充值获得',
                        'createtime' => time(),
                    ];
            db('jine_log')->insert($data);
            $user = db('user')->where('id',$order['user_id'])->find();
            if($user['tuid1'] > 0){
                $tuser1 = db('user')->where('id',$user['tuid1'])->find();
                $bili = $tuser1['yiji_bili'] > 0 ? $tuser1['yiji_bili'] : config('site.yiji_bili');
                $yongjin = $order['jine'] * $bili;
                db('user')->where('id',$user['tuid1'])->setInc('yongjin',$yongjin);
            }
            if($user['tuid2'] > 0){
                $tuser2 = db('user')->where('id',$user['tuid2'])->find();
                $bili = $tuser2['erji_bili'] > 0 ? $tuser2['erji_bili'] : config('site.erji_bili');
                $yongjin = $order['jine'] * $bili;
                db('user')->where('id',$user['tuid2'])->setInc('yongjin',$yongjin);
            }
            if($user['tuid3'] > 0){
                $tuser3 = db('user')->where('id',$user['tuid3'])->find();
                $bili = $tuser3['sanji_bili'] > 0 ? $tuser3['sanji_bili'] : config('site.sanji_bili');
                $yongjin = $order['jine'] * $bili;
                db('user')->where('id',$user['tuid3'])->setInc('yongjin',$yongjin);
            }
            $resultObj ->setData('return_code','SUCCESS');
            $resultObj ->setData('return_msg','OK');
            return $resultObj->toXml();
        }
        $resultObj ->setData('return_code','ERROR');
        $resultObj ->setData('return_msg','NO');
        return $resultObj->toXml();
 
    }
}
