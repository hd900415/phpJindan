<?php

namespace app\index\controller;
use app\index\model\pay\ZPay;
use \think\Db;
use think\Exception;
use fast\Random;
use think\Config;
use wechat\wechatpay;
use app\index\model\pay\YiPay;
use app\index\model\pay\NiuPay;
use zpay\ZPaySdk;

class Index extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //引导页
    public function index(){
        return $this->redirect('index/shouye');
    }

    //首页
    public function shouye(){
        $user = db('user')->where('id',session('user_id'))->find();
        $box_price = config('site.box_price');
        $this->assign('user',$user);
        $this->assign('box_price',$box_price);
        return $this->fetch();
    }

    /* 
        弹幕
    */
    public function danmu(){
        $str = ['恭喜','抽中了','奖品','秒前'];
        $danmu = config('site.danmu');
        foreach($danmu as $k => $v){
            $shuju = explode('-',$v);
            $zhongjiang[] = [
                    'name' => $shuju[0],
                    'gift' =>  $shuju[1],
                    'time' =>  $shuju[2],
                ];
        }
       
        $data = [
            'data' => $zhongjiang,
            'str' => $str,
        ];
        return json(['code'=>0,'msg'=>'获取成功','data'=>$data]);
    }

    /* 
        充值页面
    */
    public function chongzhi1(){
        if(request()->isPost()){
            $jine = input('post.jine');
            $order_num = create_ordernum();
            $data = [
                'user_id' => session('user_id'),
                'order_num' => $order_num,
                'jine' => $jine,
                'is_pay' => '0',
                'createtime' => time(),
                'updatetime' => time(),
            ];
            $order_id = db('recharge_order')->insertGetId($data);
            $pay = new Wxpay();
            $result = $pay->index($order_id);
            return json(['code'=>1,'msg'=>'success','data'=>$result]);
        }
        $recharge_list = explode(',',config('site.recharge_jine'));
        $user = db('user')->where('id',session('user_id'))->find();
        $this->assign('user',$user);
        $this->assign('recharge_list',$recharge_list);
        return $this->fetch();
    }
    
    public function niuhtml(){
        $out_trade_no = input('out_trade_no');
        $html = cache('niu'.$out_trade_no);
        echo  $html;die;
    }
    
    
    /**
     * 新易支付
     */
    public function chongzhi(){
        if(request()->isPost()){
            $jine = input('post.jine');
            $order_num = create_ordernum();
            $data = [
                'user_id' => session('user_id'),
                'order_num' => $order_num,
                'jine' => $jine,
                'is_pay' => '0',
                'createtime' => time(),
                'updatetime' => time(),
            ];
            $order_id = db('recharge_order')->insertGetId($data);
            //$payUrl = (new YiPay())->pay($order_num, $jine, '');
            $payUrl = (new ZPay())->pay($order_num, $jine, '');
            return json(['code'=>1,'msg'=>'success','data'=>['pay_url'=>$payUrl]]);
        }
        $recharge_list = explode(',',config('site.recharge_jine'));
        $user = db('user')->where('id',session('user_id'))->find();
        $this->assign('user',$user);
        $this->assign('recharge_list',$recharge_list);
        return $this->fetch();
    }
    
    /* 
        开始游戏
    */
    public function play_game(){
        $user = db('user')->where('id',session('user_id'))->find();
        if($user['game'] <= 0){
            return json(['code'=>0,'msg'=>'游戏次数不足']);
        }
        Db::startTrans();
        try{
            db('user')->where('id',session('user_id'))->where('game','>=',1)->setDec('game',1);
            $data = [
                'user_id' => session('user_id'),
                'order_num' => create_ordernum(),
                'createtime' => time(),
            ];
            $game_id = db('game')->insertGetId($data);
            // 提交事务
            Db::commit();    
            return json(['code'=>1,'msg'=>'success','data'=>['game_id'=>$game_id]]);
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>0,'msg'=>'系统繁忙，请稍后再试']);
        }
    }

    /* 
        红包雨
    */
    public function red_packet(){
        if(request()->isPost()){
            $type = request()->post('type',1);//1 红包 2 福袋
            $game_id = request()->post('game_id',0);
            $game = db('game')->where('id',$game_id)->where('user_id',session('user_id'))->find();
            if(!$game){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $shicha = time()-$game['createtime'];
            if($shicha > config('site.game_time')){
                return json(['code'=>0,'msg'=>'游戏时间已结束']);
            }
            switch($type){
                case 1: //红包
                    $jine_list = explode(',',config('site.jine_list'));
                    $key = array_rand($jine_list,1);
                    $jine = $jine_list[$key];
                    if($jine <= 0){
                        return json(['code'=>1,'msg'=>'哈哈，这个红包是空的哦！','data'=>['jine'=>$jine]]);
                    }
                    Db::startTrans();
                    try{
                        db('user')->where('id',session('user_id'))->setInc('jine',$jine);
                        db('jine_log')->insert(['user_id'=>session('user_id'),'jine'=>$jine,'desc'=>'红包雨','createtime'=>time()]);
                        $data = [
                            'user_id' => session('user_id'),
                            'game_id' => $game_id,
                            'type' => '1',
                            'jine' => $jine,
                            'num' => 0,
                            'createtime' => time()
                        ];
                        db('receive_log')->insert($data);
                        // 提交事务
                        Db::commit();    
                        return json(['code'=>1,'msg'=>'恭喜您，抢到了'.$jine.'元红包','data'=>['jine'=>$jine]]);
                    } catch (Exception $e) {
                        // 回滚事务
                        Db::rollback();
                        return json(['code'=>1,'msg'=>'哈哈，这个红包是空的哦！','data'=>['jine'=>$jine]]);
                    }
                    break;
                case 2: 
                    $fudai_list = explode(',',config('site.fudai_list'));
                    $key = array_rand($fudai_list,1);
                    $fudai = $fudai_list[$key];
                    if($fudai <= 0){
                        return json(['code'=>1,'msg'=>'哈哈，这个福袋是空的哦！','data'=>['fudai'=>$fudai]]);
                    }
                    Db::startTrans();
                    try{
                        db('user')->where('id',session('user_id'))->setInc('fudai',$fudai);
                        $data = [
                            'user_id' => session('user_id'),
                            'game_id' => $game_id,
                            'type' => '1',
                            'jine' => 0,
                            'num' => $fudai,
                            'createtime' => time()
                        ];
                        db('receive_log')->insert($data);
                        // 提交事务
                        Db::commit();    
                        return json(['code'=>1,'msg'=>'恭喜您，抢到了'.$fudai.'个福袋','data'=>['fudai'=>$fudai]]);
                    } catch (Exception $e) {
                        // 回滚事务
                        Db::rollback();
                        return json(['code'=>1,'msg'=>'哈哈，这个红包是空的哦！','data'=>['fudai'=>$fudai]]);
                    }
                    break;
                    break;
                default: 
                    return json(['code'=>0,'msg'=>'参数错误']);
                    break;
            }
        }
        $notice = config('site.notice_list');
        $user = db('user')->where('id',session('user_id'))->find();
        $this->assign('user',$user);
        $this->assign('notice',$notice);
        return $this->fetch();
    }

    /* 
        分类列表
    */
    public function category(){
        $category = db('category')->order('id desc')->select();
        $gonggao = config('site.gonggao');
        return json(['code'=>1,'msg'=>'success','data'=>$category,'gonggao'=>$gonggao]);
    }

    /* 
        商品列表
    */
    public function goods(){
        if(request()->isPost()){
            $page = request()->post('page',1);
            $limit = request()->post('limit',10);
            $id = request()->post('id','');
            $where = [];
            if($id){
                $where['category_id'] = $id;
            }
            $goods = db('goods')->where($where)->where('is_show','1')->paginate($limit,false,['page'=>$page]);
            return json(['code'=>1,'msg'=>'success','data'=>$goods]);
        }
        return $this->fetch();
    }

    /* 
        商品详情
    */
    public function goods_detail(){
        
        $id = request()->param('id','');
        $goods = db('goods')->where('id',$id)->find();
        $goods['goods_images'] = explode(',',$goods['goods_images']);
        $address = db('address')->where('uid',session('user_id'))->find();
        $this->assign('address',$address);
        $this->assign('goods',$goods);
        return $this->fetch();
    }

    /* 
        晒单
    */
    public function shaidan(){
        if(request()->isPost()){
            $page = request()->post('page',1);
            $limit = request()->post('limit',10);
            $list = db('saidan')->field("*,FROM_UNIXTIME(addtime, '%Y-%m-%d') as time")->order('id desc')->paginate($limit,false,['page'=>$page])->toArray();
            if($list){
                foreach($list['data'] as $k => $v){
                    $list['data'][$k]['images'] = explode(',',$v['images']);
                }
            }
            return json(['code'=>1,'msg'=>'success','data'=>$list]);
        }
        return $this->fetch();
    }

    /* 
        添加晒单记录
    */
    public function add_shaidan(){
        if(request()->isPost()){
            $order = db('order')->where('uid',session('user_id'))->find();
            if(!$order){
                return json(['code'=>0,'msg'=>'您还没有购买礼盒']);
            }
            $gift = request()->post('gift','');
            $images = request()->post('images','');
            $desc = request()->post('desc','');
            if(!$gift || !$images || !$desc){
                return json(['code'=>0,'msg'=>'缺少参数']);
            }
            $user = db('user')->where('id',session('user_id'))->find();
            $data = [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'headimage' => $user['headimage'],
                'gift' => $gift,
                'desc' => $desc,
                'images' => $images,
                'addtime' => time(),
                'createtime' => time(),
                'updatetime' => time(),
            ];
            $result = db('saidan')->insert($data);
            if($result){
                return json(['code'=>1,'msg'=>'晒单成功']);
            }else{
                return json(['code'=>0,'msg'=>'晒单失败']);
            }
        }
        return $this->fetch();
    }

    /* 
        开金蛋
    */
    public function open_box(){
        $type = (int)request()->post('type',1);//type 1 试玩  2  购买
        $fudai = (int)request()->post('fudai',0);//福袋数量
        switch($type){
            case 1: //试玩
                $goods = db('goods')->where('status','big')->field('id,goods_name,goods_image')->select();
                $key = array_rand($goods,1);
                $jiang = $goods[$key];
                return json(['code'=>1,'msg'=>'success','data'=>$jiang]);
                break;
            case 2: //购买
                $order_count = db('order')->where('uid',session('user_id'))->whereTime('createtime','today')->count();
                if($order_count >= config('site.buy_max')){
                    return json(['code'=>0,'msg'=>'今日购买次数已用完']);
                }
                Db::startTrans();
                try{
                    $user = db('user')->where('id',session('user_id'))->find();
                    if($user['fudai'] < $fudai){
                        return json(['code'=>0,'msg'=>'福袋数量不足']);
                    }
                    if($fudai >= 10){
                        $goods = db('goods')->where('status','big')->field('id,goods_name,goods_image')->select();
                    }else{
                        $goods = db('goods')->where('status','small')->field('id,goods_name,goods_image')->select();
                    }
                    $box_price = config('site.box_price');
                    if($user['jine'] < $box_price){
                        return json(['code'=>-1,'msg'=>'余额不足']);
                    }
                    if($fudai > 0){
                        db('user')->where('id',session('user_id'))->where('fudai','>=',$fudai)->setDec('fudai',$fudai);
                    }
                    db('user')->where('id',session('user_id'))->where('jine','>=',$box_price)->setDec('jine',$box_price);
                    db('jine_log')->insert(['user_id'=>session('user_id'),'jine'=>-$box_price,'desc'=>'购买盲盒','createtime'=>time()]);
                    $key = array_rand($goods,1);
                    $jiang = $goods[$key];
                    $where = [
                        'uid' => session('user_id'),
                        'order_num' => create_ordernum(),
                        'goods_id' => $jiang['id'],
                        'status' => '1',
                        'createtime' => time(),
                        'updatetime' => time(),
                    ];
                    $order_id = db('order')->insertGetId($where);
                    // 提交事务
                    Db::commit();
                    return json(['code'=>1,'msg'=>'success','data'=>$jiang,'order_id'=>$order_id]);
                } catch (Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json(['code'=>0,'msg'=>'系统繁忙，请稍后再试']);
                }
                break;
            default: 
                return json(['code'=>0,'msg'=>'参数错误']);
                break;
        }
    }

    /* 
        选择
    */
    public function cart(){
        $order_id = request()->param('order_id');
        $order = db('order')->where('id',$order_id)->where('status','1')->find();
        if(!$order){
            return $this->redirect('index/order');
        }
        $goods = db('goods')->where('id',$order['goods_id'])->find();
        $address = Db::table('yk_address')->where(['uid'=>session('user_id')])->find();
        $this->assign('address',$address);
        $this->assign('goods',$goods);
        $this->assign('order',$order);
        return $this->fetch();
    }

    /* 
        发货
    */
    public function zhifu(){
        $order_id = request()->param('order_id','');
        $rece_name = request()->param('rece_name','');
        $rece_address = request()->param('rece_address','');
        $rece_phone = request()->param('rece_phone','');
        if(!$rece_name || !$rece_address || !$rece_phone){
            return json(['code'=>0,'msg'=>'收获地址错误']);
        }
        $order = db('order')->where('id',$order_id)->where('status','1')->where('uid',session('user_id'))->find();
        if(!$order){
            return json(['code'=>0,'msg'=>'订单不存在']);
        }
        $youfei = config('site.youfei');
        $user = db('user')->where('id',$order['uid'])->find();
        if($user['jine'] < $youfei){
            return json(['code'=>-1,'msg'=>'余额不足，请前往充值']);
        }
        Db::startTrans();
        try{
            
            db('user')->where('id',$order['uid'])->where('jine','>=',$youfei)->setDec('jine',$youfei);
            db('order')->where('id',$order['id'])->update(['status'=>'3','rece_name'=> $rece_name,'rece_address' => $rece_address,'rece_phone'=>$rece_phone,'updatetime'=>time()]);
            // 提交事务
            Db::commit();
            return json(['code'=>1,'msg'=>'支付成功']);
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>0,'msg'=>'系统繁忙，请稍后再试']);
        }
    }

    /* 
        商品兑换积分
    */
    public function duihuan_goods(){
        $order_id = request()->post('order_id','');
        $order = db('order')->where('id',$order_id)->where('status','1')->where('uid',session('user_id'))->find();
        if(!$order){
            return json(['code'=>0,'msg'=>'奖品不存在']);
        }
        Db::startTrans();
        try{
            db('order')->where('id',$order['id'])->update(['status'=>'2','updatetime'=>time()]);
            $goods = db('goods')->where('id',$order['goods_id'])->find();
            db('user')->where('id',$order['uid'])->setInc('jifen',$goods['jifen']);
            $jifen_log = [
                'user_id' => $order['uid'],
                'jifen' => $goods['jifen'],
                'desc' => '“'.$goods['goods_name'].'”兑换获得',
                'createtime' => time(),
            ];
            db('jifen_log')->insert($jifen_log);
            // 提交事务
            Db::commit();
            return json(['code'=>1,'msg'=>'兑换成功']);
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>0,'msg'=>'系统繁忙，请稍后再试']);
        }
    }

    /* 
        积分兑换商品
    */
    public function duihuan_jifen(){
        $goods_id = request()->post('goods_id');
        $rece_name = request()->post('rece_name');
        $rece_address = request()->post('rece_address');
        $rece_phone = request()->post('rece_phone');
        $goods = db('goods')->where('id',$goods_id)->find();
        if(!$rece_name || !$rece_address || !$rece_phone){
            return json(['code'=>0,'msg'=>'收获地址错误']);
        }
        if(!$goods){
            return json(['code'=>0,'msg'=>'商品不存在']);
        }
        $user = db('user')->where('id',session('user_id'))->find();
        if($user['jifen'] < $goods['jifen']){
            return json(['code'=>0,'msg'=>'积分不足']);
        }
        Db::startTrans();
        try{
            db('user')->where('id',session('user_id'))->where('jifen','>=',$goods['jifen'])->setDec('jifen',$goods['jifen']);
            $jifen_log = [
                'user_id' => session('user_id'),
                'jifen' => -$goods['jifen'],
                'desc' => '兑换“'.$goods['goods_name'].'”消耗',
                'createtime' => time(),
            ];
            db('jifen_log')->insert($jifen_log);
            $data = [
                'uid' => session('user_id'),
                'order_num' => create_ordernum(),
                'goods_id' => $goods['id'],
                'status' => '3',
                'rece_name' => $rece_name,
                'rece_address' => $rece_address,
                'rece_phone' => $rece_phone,
                'createtime' => time(),
                'updatetime' => time(),
            ];
            db('order')->insert($data);
            // 提交事务
            Db::commit();
            return json(['code'=>1,'msg'=>'兑换成功']);
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>0,'msg'=>'系统繁忙，请稍后再试']);
        }
    }

    /* 
        地址列表
    */
    public function address_list(){
        $address = Db::table('yk_address')->where(['uid'=>session('user_id')])->find();
        $appid = 'wx258b9c67e5bc8aa5';
        $appsecret = '9e1b02054ffd1c2ba3c5c71a1c3d2b30';
        //获取access_token
        $access_token = https_request('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
        $access_token = json_decode($access_token,1);
        // exit(var_export($access_token,1));
        $access_token = @$access_token['access_token'];
        $jsapi_ticket = https_request('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi');
        $jsapi_ticket = json_decode($jsapi_ticket,1);
        $jsapi_ticket = @$jsapi_ticket['ticket'];
        $noncestr = md5(time());
        $timestamp = time();
        $url = request()->domain().url('index/address_list');        
        $signature = sha1('jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url);
        // die;
        
        $wechat = [
            'appid' => $appid,
            'noncestr' => $noncestr,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ];
        $this->assign('address',$address);
        $this->assign('wechat',$wechat);
        return $this->fetch();
    }

    /* 
        添加收获地址
    */
    public function address_add(){
        if(request()->isAjax()){
            $params = request()->param();
            $address = Db::table('yk_address')->where(['uid'=>session('user_id')])->find();
            if($address){
                $where = [
                    'name' => $params['username'],
                    'phone' => $params['mobile'],
                    'address' => $params['address'],
                    'updatetime' => time(),
                ];
                $result = Db::table('yk_address')->where(['uid'=>session('user_id')])->update($where);
                if($result){
                    return json(['code'=>1,'msg'=>'更新成功']);
                }else{
                    return json(['code'=>0,'msg'=>'更新失败']);
                }
            }else{
                $where = [
                    'uid' => session('user_id'),
                    'name' => $params['username'],
                    'phone' => $params['mobile'],
                    'address' => $params['address'],
                    'createtime' => time(),
                    'updatetime' => time(),
                ];
                $result = Db::table('yk_address')->insert($where);
                if($result){
                    return json(['code'=>1,'msg'=>'添加成功']);
                }else{
                    return json(['code'=>0,'msg'=>'添加失败']);
                }
            }
            
            
        }
        return $this->fetch();
    }

    /* 
        编辑地址
    */
    public function address_edit(){
        if(request()->isAjax()){
            return json();
        }
        $address = Db::table('yk_address')->where(['uid'=>session('user_id'),'id'=>request()->param('id')])->find();
        if(!$address){
            $this->redirect('index/address_add');
            exit;
        }
        return $this->fetch();
    }

    /* 
        礼物
    */
    public function gift(){
        $gift_img = config('site.gift_img');
        $this->assign('gift_img',$gift_img);
        return $this->fetch();
    }

    /* 
        我的
    */
    public function my(){
        $openid = request()->param('openid');
        if(!$openid){
            $redirect_url = request()->domain().url('index/my');
            $appid = config('site.ydf_id');
            $key = config('site.ydf_key');
            $zpay = new ZPaySdk($appid, $key);
            $zpay->get_WeChat_info($redirect_url);
            exit;
        }
        $order_total = db('order')->where(['uid'=>session('user_id'),'status' => ['>',1]])->count();
        $user = db('user')->where('id',session('user_id'))->find();
        $this->assign('order_total',$order_total);
        $this->assign('user',$user);
        $this->assign('openid',$openid);
        return $this->fetch();
    }

    /* 
        我的订单
    */
    public function order(){
        if(request()->isAjax()){
            $page = request()->post('p');
            $status = request()->post('status');
            if($status == 0){
                $where = [
                    'uid' => session('user_id'),
                ];
            }else{
                $where = [
                    'uid' => session('user_id'),
                    'status' => $status,
                ];
            }
            $order = db('order')->where($where)->field('id,order_num,status,goods_id')->order('id desc')->paginate(['list_rows' =>10, 'page' => $page])->toArray();
            if($order['data']){
                $content = '';
                foreach($order['data'] as $k => $v){
                    $goods = db('goods')->where('id',$v['goods_id'])->find();
                    $content .= '<li>
                                    <a href="'.url('index/order_detail').'?id='.$v['id'].'" class="hd bd-b">
                                        <div class="flex-item fz-28">订单编号'.$v['order_num'].'</div>
                                        <span class="fz-26 croci">'.order_status($v['status']).'</span>
                                    </a>
                                    <div class="bd">
                                        <a href="'.url('index/order_detail').'?id='.$v['id'].'" class="pic">
                                            <img src="'.$goods['goods_image'].'">
                                        </a>
                                        <a href="'.url('index/order_detail').'?id='.$v['id'].'" class="info">
                                            <div class="title">'.$goods['goods_name'].'</div>
                                            <div class="fz-28">x1</div>
                                            <div class="price">'.$goods['jifen'].'积分</div>
                                        </a>
                                    </div>
                                </li>';
                   
                }
            }else{
                $content = '<div class="success">
                                <img src="/static/wap/images/suc_2.jpg" class="pic_2 mb-30">
                                <div class="title" style="color:#999;padding-bottom: .5rem;">暂无数据</div>
                            </div>';
            }
            

            $map = [
                'limit' => $order['per_page'],
                "total" => $order['total'],
                "page" => $order['current_page'],
                "totalpage" => $order['last_page']
            ];
            return json(['status' => true, 'content' => $content, 'map' => $map]);
        }
        return $this->fetch();
    }

    /* 
        确认订单
    */

    public function confirm_order(){
        $id = request()->param('id');
        $result = db('order')->where(['id'=>$id,'uid'=>session('user_id'),'status'=>'3'])->update(['status'=>'4']);
        if($result){
            return json(['code'=>1,'msg'=>'确认成功']);
        }else{
            return json(['code'=>0,'msg'=>'确认失败']);
        }
    }

    /* 
        订单详情
    */
    public function order_detail(){
        $id = request()->param('id');
        $order = db('order')->where(['id'=>$id,'uid'=>session('user_id')])->find();
        $goods = db('goods')->where('id',$order['goods_id'])->find();
        $this->assign('goods',$goods);
        $this->assign('order',$order);
        if($order['express_num']){
            $wuliu = kuaidi100($order['express_num']);
        }else{
            $wuliu = [];
        }
        $this->assign('wuliu',$wuliu);
        return $this->fetch();
    }

    /* 
        我的团队
    */
    public function tuandui(){
        if(request()->isAjax()){
            $level = request()->param('level');
            $page = request()->param('page');
            if($level == 1){
                $where = [
                    'tuid1' => session('user_id'),
                ];
            }elseif($level == 2){
                $where = [
                    'tuid2' => session('user_id'),
                ];
            }else{
                $where = [
                    'tuid3' => session('user_id'),
                ];
            }
            $users = db('user')->where($where)->field("nickname as nick_name,headimage as head_pic,from_unixtime(createtime,'%Y-%m-%d %H:%i') as ctime")->order('id desc')->page($page,20)->select();
            return json(['code'=> 1,'msg'=>'success','data' => $users]);
        }
        $data = [
            'yiji' => db('user')->where('tuid1',session('user_id'))->count(),
            'erji' => db('user')->where('tuid2',session('user_id'))->count(),
            'sanji' => db('user')->where('tuid3',session('user_id'))->count(),
        ];
        $this->assign('data',$data);
        return $this->fetch();
    }

    /* 
        提现
    */
    public function tixian(){
        $openid = request()->param('openid','');
        if(!$openid){
            return json(['code'=>0,'msg'=>'缺少参数']);
        }
        $user = db('user')->find(session('user_id'));
        if($user['yongjin'] < config('site.min_tixian')){
            return json(['code'=>0,'msg'=>'单笔提现收益不能少于'.config('site.min_tixian').'元']);
        }

        Db::startTrans();
        try{
            $data = [
                'uid' => session('user_id'),
                'nickname' => $user['nickname'],
                'headimage' => $user['headimage'],
                'openid' => $openid,
                'jine' => $user['yongjin'],
                'charge_jine' => $user['yongjin']*config('site.tixian_bili'),
                'status' => '1',
                'createtime' => time(),
                'updatetime' => time(),
            ];
            $shiji_jine = $user['yongjin'] - $data['charge_jine'];
            db('tixian')->insert($data);
            db('user')->where(['id'=>session('user_id'),'yongjin' => ['>=',config('site.min_tixian')]])->update(['yongjin' => 0]);
            $return = wechat($data);
            if ($return['code'] == 40011) {
                Db::commit();
                return json(['code' => 1, 'msg' => '提现成功']);
            } else {
                Db::rollback();
                return json(['code' => 0, 'msg' => $return['errmsg']]);
            }
            Db::commit();    
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code' => 0, 'msg' => '系统繁忙~']);
        }
    }

    /* 
        提现明细
    */
    public function tixian_list(){
        if(request()->isAjax()){
            $page = request()->param('page');
            $where = [
                'uid' => session('user_id'),
                'status' => '1',
            ];
            $tixian = db('tixian')->where($where)->field("nickname as nick_name,headimage as head_pic,from_unixtime(createtime,'%Y-%m-%d %H:%i') as createtime,jine as money")->order('id desc')->page($page,20)->select();
            return json(['code'=> 1,'msg'=>'success','data' => $tixian]);
        }
        return $this->fetch();
    }

    /* 
        分销详情
    */
    public function fenxiao(){
        if(request()->isAjax()){
            $page = input('page');
            $level = input('level');
            $where = [
                'uid' => session('user_id'),
                'level' => $level,
            ];
            $data = $users = db('yongjinlog')->where($where)->field("xj_nickname as nick_name,xj_headimage as head_pic,from_unixtime(createtime,'%Y-%m-%d %H:%i') as createtime,price,yj_bili as rate,yongjin as profit ,order_num as trade_no")->order('id desc')->page($page,20)->select();
            return json(['code'=> 1,'msg'=>'success','data' => $data]);
        }
        $data = [
            'yiji' => db('yongjinlog')->where(['uid'=>session('user_id'),'level'=>1])->count(),
            'erji' => db('yongjinlog')->where(['uid'=>session('user_id'),'level'=>2])->count(),
            'sanji' => db('yongjinlog')->where(['uid'=>session('user_id'),'level'=>3])->count(),
        ];
        $this->assign('data',$data);
        return $this->fetch();
    }

    /* 
        获取二维码
    */
    public function get_qrcode()
    {
        $url = request()->domain().url('login/login');
        if ($url) {
            $value = $url . '?p_id=' . session('user_id'); //二维码内容 
        }
        //生成二维码图片 
        \think\Loader::import('qrcode.qrcode');
        return \QRcode::png($value, false, 'L', 3, 3, false);
    }

    public function ceshi(){
        
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        Config::set('default_return_type', 'json');
        $file = request()->file('file');
        if (empty($file)) {
            return json(['code'=>0,'msg'=>'未上传文件或超出服务器上传限制']);
        }

        //判断是否已经存在附件
        $sha1 = $file->hash();
        $extparam = $this->request->post();

        $upload = Config::get('upload');

        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';

        $mimetypeArr = explode(',', strtolower($upload['mimetype']));
        $typeArr = explode('/', $fileInfo['type']);

        //禁止上传PHP和HTML文件
        if (in_array($fileInfo['type'], ['text/x-php', 'text/html']) || in_array($suffix, ['php', 'html', 'htm'])) {
            return json(['code'=>0,'msg'=>'上传文件格式受限制']);
        }
        //验证文件后缀
        if ($upload['mimetype'] !== '*' &&
            (
                !in_array($suffix, $mimetypeArr)
                || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
            )
        ) {
            return json(['code'=>0,'msg'=>'上传文件格式受限制']);
        }
        //验证是否为图片文件
        $imagewidth = $imageheight = 0;
        if (in_array($fileInfo['type'], ['image/gif', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/png', 'image/webp']) || in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'webp'])) {
            $imgInfo = getimagesize($fileInfo['tmp_name']);
            if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                return json(['code'=>0,'msg'=>'上传文件不是有效的图片文件']);
            }
            $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
            $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
        }
        $replaceArr = [
            '{year}'     => date("Y"),
            '{mon}'      => date("m"),
            '{day}'      => date("d"),
            '{hour}'     => date("H"),
            '{min}'      => date("i"),
            '{sec}'      => date("s"),
            '{random}'   => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}'   => $suffix,
            '{.suffix}'  => $suffix ? '.' . $suffix : '',
            '{filemd5}'  => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        //
        $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
        if ($splInfo) {
            $params = array(
                'user_id'     => session('user_id'),
                'filesize'    => $fileInfo['size'],
                'imagewidth'  => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype'   => $suffix,
                'imageframes' => 0,
                'mimetype'    => $fileInfo['type'],
                'url'         => $uploadDir . $splInfo->getSaveName(),
                'uploadtime'  => time(),
                'storage'     => 'local',
                'sha1'        => $sha1,
                'extparam'    => json_encode($extparam),
            );
            $attachment = model("attachment");
            $attachment->data(array_filter($params));
            $attachment->save();
            \think\Hook::listen("upload_after", $attachment);
            return json(['code'=>1,'msg'=>'上传成功','data'=>[
                'url' => $uploadDir . $splInfo->getSaveName()
            ]]);
        } else {
            // 上传失败获取错误信息
            return json(['code'=>0,'msg'=>$file->getError()]);
        }
    }

}
