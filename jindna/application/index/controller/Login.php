<?php

namespace app\index\controller;

use think\Controller;
use zpay\ZPaySdk;

class Login extends Controller
{
    public function login()
    {
        $appid = config('site.ydf_id');
        $key = config('site.ydf_key');
        if (request()->param('openid')) {
            $param = request()->param();
            $p_id = isset($param['p_id']) ? $param['p_id'] : 0;
            $openid = request()->param('openid');
            $user = db('user')->where('openid', $openid)->find();
            if ($user) {
                session('user_id', $user['id']);
                return $this->redirect('index/index');
            } else {
                if (request()->param('openid')) {
                    $data = [
                        'openid' => $openid,
                        'nickname' => request()->param('nickname'),
                        'headimage' => request()->param('headimgurl'),
                        'createtime' => time(),
                        'updatetime' => time(),
                        'game' => 1,
                    ];
                    if ($p_id) {
                        $p_user = db('user')->where(['id' => $p_id])->find();
                        if ($p_user) {
                            $data['tuid1'] = $p_id;
                            $data['tuid2'] = $p_user['tuid1'];
                            $data['tuid3'] = $p_user['tuid2'];
                        }
                        db('user')->where('id', $p_user['id'])->setInc('game', 1);
                    }
                    $user_id = db('user')->insertGetId($data);
                    session('user_id', $user_id);
                    return $this->redirect('index/index');
                }
            }
        } else {
            $p_id = request()->param('p_id');
            $zpay = new ZPaySdk($appid, $key);
            $zpay->get_WeChat_info(urlencode(request()->domain() . url('login/login') . '?p_id=' . $p_id) . '&state=' . \fast\Random::numeric(8));
            exit();
        }
    }
}
