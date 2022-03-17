<?php
namespace wechat;
Class wechatpay{
    //微信开放平台的应用appid
    private $appid = '';
    //商户号（注册商户平台时，发置注册邮箱的商户id）
    private $mchid = '';
    //商户平台api支付处设置的key
    private $key = '';
    //支付成功回调地址
    private $notify_url = '';
    //支付请求地址
    const URL='https://api.mch.weixin.qq.com/pay/unifiedorder';
    function __construct()
    {
        $this->appid= 'wx41c4cda903c2d3f2';
        $this->mchid= '1610141584';
        $this->notify_url= '';
        $this->key= 'awdaawAWDHkkwh213akdhkwajhdkawdh';
    }
    //生成订单
    public function wechat_pay($body, $out_trade_no, $total_fee, $notify_url){
        $data["appid"] = $this->appid;
        $data["body"] = $body;
        $data["mch_id"] = $this->mchid;
        $data["nonce_str"] = $this->getRandChar(32);
        $data["notify_url"] = $notify_url;
        $data["out_trade_no"] = $out_trade_no;
        $data["spbill_create_ip"] = $this->get_client_ip();
        //金额
        $data["total_fee"] = $total_fee;
        // $data["total_fee"] = 1;
        $data["trade_type"] = "APP";
        //按照参数名ASCII字典序排序并且拼接API密钥生成签名
        $s = $this->getSign($data);
        $data["sign"] = $s;
        //配置xml最终得到最终发送的数据
        p($data);
        $xml = $this->arrayToXml($data);
        // file_put_contents("xml.txt",json_encode($xml));
        // $file = fopen("log.txt","w");
        $response = $this->postXmlCurl($xml,self::URL);
        p($response);
        // file_put_contents("response.txt",json_encode($response));
        //将微信返回的结果xml转成数组
        $array = $this->xmlstr_to_array($response);
        $payss = [
            'appid' => $array['appid'],
            'partnerid' => $array['mch_id'],
            'prepayid' => $array['prepay_id'],
            'package' => 'Sign=WXPay',
            'noncestr' => md5(time().rand(1,9999999) ),
            'timestamp' => time(),
        ];
        $payss['sign'] = $this->getSign($payss);
        $payss['return_code'] = $array['return_code'];
        return $payss;
    }
    /*
    *   企业付款到零钱
    *   $data = ['openid' => '','money'  => '']
    */
    public function transfer($data){
        //支付信息
    	$wxchat['appid'] = $this->appid;
        $wxchat['mchid'] = $this->mchid;
        // p($wxchat);
    	$webdata = array(
    			'mch_appid' => $wxchat['appid'],//商户账号appid
                'mchid'     => $wxchat['mchid'],//商户号
    			'nonce_str' => md5(time()),//随机字符串
                'partner_trade_no'=> date('YmdHis'), //商户订单号，需要唯一
    			'openid' => $data['openid'],//转账用户的openid
    			'check_name'=> 'NO_CHECK', //OPTION_CHECK不强制校验真实姓名, FORCE_CHECK：强制 NO_CHECK：
    			'amount' => $data['money']*100, //付款金额单位为分
    			'desc'   => '(加入代理 日赚千元)',//企业付款描述信息
    			'spbill_create_ip' => request()->ip(),//获取IP
        );
    	foreach ($webdata as $k => $v) {
    		$tarr[] =$k.'='.$v;
    	}
    	sort($tarr);
    	$sign = implode($tarr, '&');
    	$sign .= '&key='.$this->key;
    	$webdata['sign']=strtoupper(md5($sign));
    	$wget = $this->ArrToXml($webdata);//数组转XML
    	$pay_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';//api地址
        $res = $this->postData($pay_url,$wget);//发送数据
        // p($res);die; 
    	if(!$res){
    		return array('status'=>-1, 'msg'=>"Can't connect the server" );
    	}
        $content = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
    	if(strval($content->return_code) == 'FAIL'){
    		return array('status'=>-1, 'msg'=>strval($content->return_msg));
    	}
    	if(strval($content->result_code) == 'FAIL'){
    		return array('status'=>-1, 'msg'=>strval($content->err_code),':'.strval($content->err_code_des));
    	}
    	$rdata = array(
                'status'           => 1,
    			'mch_appid'        => strval($content->mch_appid),
    			'mchid'            => strval($content->mchid),
    			'device_info'      => strval($content->device_info),
    			'nonce_str'        => strval($content->nonce_str),
    			'result_code'      => strval($content->result_code),
    			'partner_trade_no' => strval($content->partner_trade_no),
    			'payment_no'       => strval($content->payment_no),
    			'payment_time'     => strval($content->payment_time),
    	);
    	return $rdata;
    }

    //数组转XML
    function ArrToXml($arr){
        if(!is_array($arr) || count($arr) == 0) return '';
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    //发送数据
    function postData($url,$postfields){
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $postfields;
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        //以下是证书相关代码
        $params[CURLOPT_SSLCERTTYPE] = 'PEM';
        $params[CURLOPT_SSLCERT] = getcwd().'/cert/apiclient_cert.pem';//绝对路径
        $params[CURLOPT_SSLKEYTYPE] = 'PEM';
        $params[CURLOPT_SSLKEY] = getcwd().'/cert/apiclient_key.pem';//绝对路径
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }
    //进行签名
    function getSign($Obj)
    {
        foreach ($Obj as $k => $v)
        {
            $Parameters[strtolower($k)] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo "【string】 =".$String."</br>";
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$this->key;
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }
    //获取指定长度的随机字符串
    private function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
    //获取当前服务器的IP
    function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }
    //将数组转成uri字符串
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        $reqPar='';
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
    //数组转xml
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }
    //post https请求，CURLOPT_POSTFIELDS xml格式
    function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data)
        {
            curl_close($ch);
            return $data;
        }
        else
        {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
    /**
    xml转成数组
     */
    public function xmlstr_to_array($xmlstr) {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlstr);
        return $this->domnode_to_array($doc->documentElement);
    }
    public function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v) {
                        $output = (string) $v;
                    }
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
    //微信支付成功以后的回调
    public function notify($arr)
    {
        $arr_sign = $arr['sign'];
        unset($arr['sign']);
        $sign = $this->getSign($arr);
        return $sign;
    }
}