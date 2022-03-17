<?php


namespace zpay\Models;
use zpay\Common\AbstractModel;

class CreateOrderRequest extends AbstractModel
{

    /**
     *  订单类型
     */
    public $type;
    /**
     *  订单号
     */
    public $out_trade_no;
    /**
     *  订单附加信息
     */
    public $attach;
    /**
     *  金额
     */
    public $amount;
    /**
     *  订单过期时间
     */
    public $time_expire;
    /**
     *  同步地址
     */
    public $return_url;
    /**
     *  异步地址
     */
    public $notify_url;
    /**
     *  数据返回类型
     */
    public $isHtml;

    function __construct()
    {

    }

}