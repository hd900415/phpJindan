<?php
namespace app\index\controller;
use app\index\model\pay\ZPay;
use think\Controller;
use app\index\model\pay\YiPay;
class Pay  extends Controller
{
    public function index(){
        $id = request()->param('order_id');
        $type = request()->param('type');
        $order = db('order')->where(['id'=>$id])->find();
        $return_url = request()->domain().url('index/order');
        $pay_memberid = "200529091";
        $pay_orderid = $order['order_num'];    //订单号
        $pay_amount = $order['total_price'];    //交易金额
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        $pay_notifyurl = request()->domain().url('pay/notify');   //服务端返回地址
        $pay_callbackurl = $return_url;  //页面跳转返回地址
        $Md5key = "86js3yb0ib1witi4a7vwlwprvst4pgg6";   //商户后台API管理获取
        $tjurl = "https://www.xgymwl.cn/Pay_Index.html";   //提交地址
        if($type == 'weixin'){
            $pay_bankcode = "922"; 
        }elseif($type == 'zhifubao'){
            $pay_bankcode = "921"; 
        }else{
            header("location:https://www.baidu.com/");
            exit;
        }
        $native = array(
            "pay_memberid" => $pay_memberid,
            "pay_orderid" => $pay_orderid,
            "pay_amount" => $pay_amount,
            "pay_applydate" => $pay_applydate,
            "pay_bankcode" => $pay_bankcode,
            "pay_notifyurl" => $pay_notifyurl,
            "pay_callbackurl" => $pay_callbackurl,
        );
        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));
        $native["pay_md5sign"] = $sign;
        $native['pay_attach'] = "1234|456";
        $native['pay_productname'] =$order['box_num'].'号金蛋';
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$tjurl."' method='post'>";
        foreach($native as $key => $val){
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        echo $sHtml;
    }

    public function notify(){
        $post = request()->post();
        $returnArray = array( // 返回字段
            "memberid" => $post["memberid"], // 商户ID
            "orderid" =>  $post["orderid"], // 订单号
            "amount" =>  $post["amount"], // 交易金额
            "datetime" =>  $post["datetime"], // 交易时间
            "transaction_id" =>  $post["transaction_id"], // 支付流水号
            "returncode" => $post["returncode"],
        );
        $md5key = "86js3yb0ib1witi4a7vwlwprvst4pgg6";
        ksort($returnArray);
        reset($returnArray);
        $md5str = "";
        foreach ($returnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        if ($sign == $post["sign"]) {
            if ($post["returncode"] == "00") {
                $where['order_num'] = $post['orderid'];
                $order = db('order')->where($where)->find();
                if($order['status'] == '1'){
                    $data = [
                        'status' => '2',
                        'transaction_id' => $post['transaction_id'],
                        'updatetime' => time(),
                    ];
                    db('order')->where('id',$order['id'])->update($data);
                    $user = db('user')->where('id',$order['uid'])->field('id,nickname,headimage,tuid1,tuid2,tuid3')->find();
                    if($user['tuid1'] && $order['total_price']*config('site.yiji_bili') >= 0.01){
                        $data = [
                            'uid' => $user['tuid1'],
                            'level' => 1,
                            'xj_id' => $user['id'],
                            'xj_nickname' => $user['nickname'],
                            'xj_headimage' => $user['headimage'],
                            'price' => $order['total_price'],
                            'yj_bili' => config('site.yiji_bili'),
                            'yongjin' => $order['total_price']*config('site.yiji_bili'),
                            'order_num' => $order['order_num'],
                            'createtime' => time(),
                            'updatetime' => time(),
                        ];
                        db('yongjinlog')->insertGetId($data);
                    }

                    if($user['tuid2'] && $order['total_price']*config('site.erji_bili') >= 0.01){
                        $data = [
                            'uid' => $user['tuid2'],
                            'level' => 2,
                            'xj_id' => $user['id'],
                            'xj_nickname' => $user['nickname'],
                            'xj_headimage' => $user['headimage'],
                            'price' => $order['total_price'],
                            'yj_bili' => config('site.erji_bili'),
                            'yongjin' => $order['total_price']*config('site.erji_bili'),
                            'order_num' => $order['order_num'],
                            'createtime' => time(),
                            'updatetime' => time(),
                        ];
                        db('yongjinlog')->insertGetId($data);
                    }

                    if($user['tuid3'] && $order['total_price']*config('site.sanji_bili') >= 0.01){
                        $data = [
                            'uid' => $user['tuid3'],
                            'level' => 3,
                            'xj_id' => $user['id'],
                            'xj_nickname' => $user['nickname'],
                            'xj_headimage' => $user['headimage'],
                            'price' => $order['total_price'],
                            'yj_bili' => config('site.sanji_bili'),
                            'yongjin' => $order['total_price']*config('site.sanji_bili'),
                            'order_num' => $order['order_num'],
                            'createtime' => time(),
                            'updatetime' => time(),
                        ];
                        db('yongjinlog')->insertGetId($data);
                    }
                }
                exit("OK");
            }
        }
    }
    
     /**
     * 支付回调
     * @param $platform
     */
    public function znotify()
    {
        (new ZPay())->notify();
    }
    
      /**
     * 支付回调
     * @param $platform
     */
    public function yinotify()
    {
        (new YiPay())->notify();
    }


}

