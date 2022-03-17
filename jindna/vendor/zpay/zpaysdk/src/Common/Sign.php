<?php


namespace zpay\Common;


class Sign
{

    public static function sign($out_trade_no, $attach, $type, $amount, $timestamp, $mch_id, $key)
    {
        return md5($out_trade_no . $attach . $type . $amount . $timestamp . $mch_id . $key);
    }

    public static function wechatSign($mch_id, $host, $key, $time)
    {
        return md5($mch_id . $host . $key . $time);
    }

}