<?php

require_once 'src/ZPaySdk.php';

use zpay\Common\CommonFunction;
use zpay\ZPaySdk;

$pay = new ZPaySdk();
//验证签名
if($pay->isSign())
{
        //签名验证成功,订单验证成功
        //---------开始业务逻辑----------------
        $request_data = CommonFunction::get_notify_parameter();


        //----------业务逻辑结束---------------
        //告诉服务器已经收到通知
        echo 'success';
}
else
{
    exit('fail');
}
