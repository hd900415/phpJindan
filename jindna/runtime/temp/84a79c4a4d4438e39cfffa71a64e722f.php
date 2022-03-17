<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"/www/wwwroot/jindan11.lostinparadise.xyz/public/../application/index/view/index/chongzhi.html";i:1625226106;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta charset="UTF-8">
    <title>充值</title>
    <link rel="stylesheet" href="/static/wap/css/reset.css" />
    <link rel="stylesheet" href="/static/wap/css/chongzhi.css" />
    <script type="text/javascript" src="/static/wap/js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="/static/wap/js/pxtorem.js"></script>
    <script type="text/javascript" src="/static/wap/layer/layer.js"></script>

</head>

<body>
<div class="wallet">
    <p class="my-balance">我的余额</p>
    <div class="balance">
        <p class="balance-number"><span>￥<?php echo $user['jine']; ?></span></p>
    </div>
</div>
<section>
    <div class="Cashier">
        <p class="choose_txt">请选择充值金额</p>
        <ul class="tabs flex-wrap">
          <?php if($recharge_list): foreach($recharge_list as $k => $v): ?>
            <li style="height: 2.75rem;" value="<?php echo $v; ?>">
                <p class="number-1"><span><?php echo $v; ?></span> <span>元</span></p>
            </li>
          <?php endforeach; endif; ?>
        </ul>
    </div>
    
    <div class="but" data-type= '1'>
        <button>微信充值</button>
    </div>
    <div class="but" data-type= '2'>
        <button>支付宝充值</button>
    </div>
</section>
<script>
var jine = 0;
$(document).ready(function() {
    $(".tabs li").click(function() {
        $(".tabs li").removeClass("active");
        $(this).addClass("active");
        jine = $(this).val();
    });
});
$('.but').click(function(){
    if(jine <= 0){
        layer.msg('请选择充值金额');
        return;
    }
    var type = $(this).data('type');
    $.post("<?php echo url('index/chongzhi'); ?>",{jine:jine,type:type},function(res){
        if(res.code == 1){
            location.href=res.data.pay_url;
        }
    },'json')
})
</script>
</body>
</html>