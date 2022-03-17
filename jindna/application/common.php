<?php

// 公共助手函数

if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array  $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return \think\Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int    $size      大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int    $time   时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }
}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time  时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }
}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        $url = preg_match($regex, $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
        if ($domain && !preg_match($regex, $url)) {
            $domain = is_bool($domain) ? request()->domain() : $domain;
            $url = $domain . $url;
        }
        return $url;
    }
}


if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param    string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return true;
        } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);
        return true;
    }
}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname  目录
     * @param bool   $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }
}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest   目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }
}

if (!function_exists('addtion')) {

    /**
     * 附加关联字段数据
     * @param array $items  数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields)
    {
        if (!$items || !$fields) {
            return $items;
        }
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }
}

if (!function_exists('var_export_short')) {

    /**
     * 返回打印数组结构
     * @param string $var    数组
     * @param string $indent 缩进字符
     * @return string
     */
    function var_export_short($var, $indent = "")
    {
        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : var_export_short($key) . " => ")
                        . var_export_short($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, true);
        }
    }
}

if (!function_exists('letter_avatar')) {
    /**
     * 首字母头像
     * @param $text
     * @return string
     */
    function letter_avatar($text)
    {
        $total = unpack('L', hash('adler32', $text, true))[1];
        $hue = $total % 360;
        list($r, $g, $b) = hsv2rgb($hue / 360, 0.3, 0.9);

        $bg = "rgb({$r},{$g},{$b})";
        $color = "#ffffff";
        $first = mb_strtoupper(mb_substr($text, 0, 1));
        $src = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="' . $bg . '" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="' . $color . '" text-anchor="middle" text-rights="admin" alignment-baseline="central">' . $first . '</text></svg>');
        $value = 'data:image/svg+xml;base64,' . $src;
        return $value;
    }
}

if (!function_exists('hsv2rgb')) {
    function hsv2rgb($h, $s, $v)
    {
        $r = $g = $b = 0;

        $i = floor($h * 6);
        $f = $h * 6 - $i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($i % 6) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;
            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;
            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;
            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;
            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
        }

        return [
            floor($r * 255),
            floor($g * 255),
            floor($b * 255)
        ];
    }
}

/**
 * 生成订单号
 */
function create_ordernum(){
    return date('YmdHis').\fast\Random::numeric(6);
}

/**
 * 打印方法
 */
function p($data){
    $data = func_get_args();
    if (count($data) < 1) {
        echo ("<font color='red'> Debug");
        return;
    }
    echo '<div style="width:100%;text-align:left"><pre>';
    foreach ($data as $v) {
        if (is_array($v)) {
            print_r($v);
            echo '<br>';
        } else if (is_string($v)) {
            echo $v . '<br>';
        } else {
            var_dump($v);
            echo '<br>';
        }
    }
    echo '</pre></div>';
}

/**
 * curl 请求
 */
function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

//获取订单状态
function order_status($status){
    switch ($status)
    {
        case 1:
            $result = '待选择';
            break;  
        case 2:
            $result = '已兑换';
            break;  
        case 3:
            $result = '待发货';
            break;  
        case 4:
            $result = '已发货';
            break;  
    }
    return $result;
}

//快递100 查询快递
function kuaidi100($postcode)
{
    $url = 'http://www.kuaidi100.com/autonumber/autoComNum?resultv2=1&text=' . $postcode;
    $result = https_request($url);
    $result = json_decode($result, 1);
    if ($result['auto']) {
        $comCode = $result['auto'][0]['comCode'];
        $url = 'http://www.kuaidi100.com/query?type=' . $comCode . '&postid=' . $postcode . '&temp=0.7324204283508002';
        $data = https_request($url);
        $data = json_decode($data, 1);
        if ($data['message'] == 'ok') {
            $data = $data['data'];
            return ['code' => 1, 'msg' => '获取成功', 'data' => $data];
        } else {
            return ['code' => 0, 'msg' => '暂无数据'];
        }
    } else {
        return ['code' => 0, 'msg' => '暂无数据'];
    }
}

function argSort($para)
{
    ksort($para);
    reset($para);
    return $para;
}


function getarray($para)
{
    $arg = "";
    // while (list($key, $val) = each($para)) $arg .= $key . "=" . $val . "&";
    foreach ($para as $key => $val) {
        $arg .= $key . "=" . $val . "&";
    }
    $arg = substr($arg, 0, strlen($arg) - 1);
    if (get_magic_quotes_gpc()) $arg = stripslashes($arg);
    return $arg;
}

function huancun($name, $data)
{
    file_put_contents('./' . $name . '.php', '<?php' . "\n\nreturn " . print_r($data, true) . ";");
}


/**
 * 云代付 提现
 */
function sign($data, $key)
{
    $data = argSort($data);
    $data = createLinkstring($data);
    $sign = strtoupper(md5($data . '&key=' . $key));
    return $sign;
}

function createLinkstring($para)
{
    $arg  = "";
    foreach ($para as $key => $val) {
        $arg .= $key . "=" . $val . "&";
    }
    $arg = substr($arg, 0, strlen($arg) - 1);
    if (get_magic_quotes_gpc()) {
        $arg = stripslashes($arg);
    }
    return $arg;
}

function wechat($data){
      $url = Db::name('config')->where('name','yun_url')->value('value');

    // $url = 'http://api.zs222.cn/api/api/withdraw';
    $appid = config('site.ydf_id');
    $secret = config('site.ydf_key');
    $_data = [
        "appid" => $appid,
        "channel"=> "wx",
        "order_no"=> md5(time()),
        "amount"=> $data['jine'] - $data['charge_jine'],
        "recipient_openid" => $data['openid'],
        "description" => '提现',
    ];
    $_data['sign'] = sign($_data,$secret);
    $result = https_request($url,$_data);
    $result = json_decode($result,1);
    return $result;
}



function sixteenToascii($data){
    switch ($data)
    {
        case '41':
            return 'A';
            break;  
        case '42':
            return 'B';
            break;  
        case '43':
            return 'C';
            break;
        case '44':
            return 'D';
            break;  
        case '45':
            return 'E';
            break;  
        case '46':
            return 'F';
            break;  
        case '47':
            return 'G';
            break;  
        case '48':
            return 'H';
            break;  
        case '49':
            return 'I';
            break;
        case '4A':
            return 'J';
            break;  
        case '4B':
            return 'K';
            break;  
        case '4C':
            return 'L';
            break; 
        case '4D':
            return 'M';
            break;  
        case '4E':
            return 'N';
            break;  
        case '4F':
            return 'O';
            break; 
        case '50':
            return 'M';
            break;  
        case '51':
            return 'N';
            break;  
        case '52':
            return 'O';
            break; 
        case '50':
            return 'P';
            break;  
        case '51':
            return 'P';
            break;  
        case '52':
            return 'Q';
            break; 
        case '53':
            return 'R';
            break;  
        case '54':
            return 'S';
            break;  
        case '55':
            return 'T';
            break; 
        case '56':
            return 'U';
            break;  
        case '57':
            return 'V';
            break;  
        case '58':
            return 'W';
            break; 
        case '59':
            return 'X';
            break;  
        case '5A':
            return 'Y';
            break;  
        case '55':
            return 'Z';
            break; 
        default:
            return null;
    }
}