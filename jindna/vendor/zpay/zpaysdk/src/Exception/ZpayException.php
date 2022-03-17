<?php


namespace zpay\Exception;


class ZpayException extends \Exception
{
    protected $code;

    protected $version;

    public function __construct($code =0, $message = "",$version ="2.1.0")
    {
        parent::__construct($message, 0);
        $this->code = $code;
        $this->version = $version;
    }

    public function __toString()
    {
        return "[".__CLASS__."]"." {code:".$this->code.
            " , message:".$this->getMessage().
            " , Z支付sdk版本:".$this->version.'}';
    }

}