<?php

namespace app\index\controller;
use think\Controller;

class Common extends Controller
{
    public function _initialize()
    {
        // session('user_id',24);
        // if(session('user_id') == 1){
        //     session('user_id',null);
        // }
        if(config('site.weihu') == 1){
            die('系统维护中...');
        }
        if(request()->param('yk')){
            session('user_id',5002);
        }
        $user_id = session('user_id');
        $p_id = request()->param('p_id');
        $p_id = $p_id ? $p_id : 0;
        if (!$user_id) {
            $this->redirect('login/login?p_id=' . $p_id);
            // $this->testlogin($p_id);
        }
        $user = db('user')->where(['id'=>$user_id])->find();
        if (!$user) {
            session('user_id', null);
            $this->redirect('login/login?p_id=' . $p_id);
        }
    }

    
}
