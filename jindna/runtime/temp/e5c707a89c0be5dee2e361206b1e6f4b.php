<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"/www/wwwroot/jindan11.lostinparadise.xyz/public/../application/index/view/index/my.html";i:1641901475;}*/ ?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    <title>่ๅนด้่</title>
    <script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="/addons/hc_wish/template/mobile/js/flexible.min.js"></script>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <script type="text/javascript" src="/addons/hc_wish/template/mobile/js/layer/layer.js"></script>
    <link rel="stylesheet" href="/addons/hc_wish/template/mobile/css/personal.css" />
    <link rel="stylesheet" href="/addons/hc_wish/template/mobile/css/css.css" />
    <style>
        a {
            text-decoration: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
            -webkit-user-select: none;
        }

        .shadow,
        .shadows {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
        }

        .fenhua_uyas p {
            color: #000;
        }

        .content {
            overflow-y: auto;
            max-height: 20rem;
        }

        .addresslogo {
            position: absolute;
            right: 0;
            top: 10vw;
            display: inline-block;
            width: 26vw;
        }

        .qrcodes {
            width: 90vw;
            display: block;
            margin: 20px auto 0;
            padding-bottom: 10vh;
        }

        .shadowqrcode {
            width: 77vw;
            /* height: 100vw; */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }
        .gzh_qrcode{
            width: 77vw;
            height: 77vw;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        .avatar {
            position: relative;
        }

        .weiahua {
            position: absolute;
            bottom: -5px;
        }

        .between {
            font-size: 14px;
            color: #fff;
            margin-top: 20px;
        }
    </style>

<body>
    <!-- ๆ็ฐๆ็คบๅฑ -->
    <div class="shadow" style="display:none" onclick="guanbi()"></div>
    <div class="notices" id="cashs" style="display: none;z-index:99;">
        <div class="closeNotices" onclick="closeCash(this)" id="cash">โ</div>
        <div class="content a">
            <div style="text-align:center;margin-top:3vw;">ๆ็ฐ้้ข<?php echo $user['yongjin']; ?>ๅ๏ผๆ็ปญ่ดน<?php echo config('site.tixian_bili')*$user['yongjin']; ?>ๅ</div>
            <div style="background:#FB1F7F;width:40vw;height:10vw;line-height:10vw;border-radius: 5vw;margin:6vw  auto 3vw;text-align:center;color:#ffffff;"
                id="cashmoney" cashtype="1">ๆ็ฐ</div>
        </div>
    </div>
    <!-- ๆ็ฐๆ็คบๅฑ -->

    <!-- <img src="<?php echo config('site.ewm_bg'); ?>" alt="" style="display: none;z-index:99;position: absolute;width: 75%;left: 12.5%;top: 10%;" class="fengxiang"> -->

    <link href="/static/wap/css/main.css" rel="stylesheet" type="text/css">
    <link href="/static/wap/css/style.css" rel="stylesheet" type="text/css">
    <link href="/static/wap/css/dialog-ui.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/static/wap/font/iconfont.css">
    <section id="container" class="bg-f">
        <div class="page">
            <div class="member-toppar">
                <div class="bg"></div>
                <!-- <img src="/static/wap/images/5.jpg" class="bg"> -->
                <div class="content">
                    <div class="hd">
                        <img src="<?php echo $user['headimage']; ?>" class="avatar">
                        <div class="info">
                            <div class="title"><?php echo $user['nickname']; ?></div>
                            <div class="sub"><span>ID๏ผ<?php echo $user['id']; ?></span></div>
                        </div>
                        <a href="javascript:;" class="link"><i class="iconfont">&#xe60a;</i>ๆถ็ๆ็ฐ</a>
                    </div>
                    <div class="bd">
                        <div class="item">
                            <div class="title"><i>๏ฟฅ</i><?php echo $user['jine']; ?></div>
                            <p class="sub">ๆ็ไฝ้ข</p>
                        </div>
                        <div class="item">
                            <div class="title"><i></i><?php echo $user['jifen']; ?></div>
                            <p class="sub">ๆ็็งฏๅ</p>
                        </div>
                        <div class="item">
                            <div class="title"><?php echo $user['fudai']; ?><i> ไธช</i></div>
                            <p class="sub">ๆ็็ฆ่ข</p>
                        </div>
                        <!-- <div class="item">
                            <div class="title">0<i> ไธช</i></div>
                            <p class="sub">ๆ็ๅฅๅ</p>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="member-common-title">ๆ็ๆๅก</div>
            <div class="grids">
                <a href="<?php echo url('index/order'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe645;</i>
                    <p class="grid-label">ๆ็่ฎขๅ</p>
                </a>
                <a href="<?php echo url('index/tuandui'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe654;</i>
                    <p class="grid-label">ๆ็ๅข้</p>
                </a>
                <a href="<?php echo url('index/address_list'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe649;</i>
                    <p class="grid-label">ๆ็ๅฐๅ</p>
                </a>
                <a href="javascript:;" class="grid" onclick="ewm()">
                    <i class="iconfont grid-icon">&#xe646;</i>
                    <p class="grid-label">ๆจๅนฟไบ็ปด็?</p>
                </a>
                <a href="<?php echo url('index/tixian_list'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe60a;</i>
                    <p class="grid-label">ๆ็ฐๆ็ป</p>
                </a>
                <a href="<?php echo url('index/fenxiao'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe6a0;</i>
                    <p class="grid-label">ๅ้่ฏฆๆ</p>
                </a>
                <a href="" class="grid">
                    <i class="iconfont grid-icon">&#xe645;</i>
                    <p class="grid-label">ๆ็ๅๅ</p>
                </a>
                <a href="<?php echo url('index/shaidan'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe645;</i>
                    <p class="grid-label">ๆๅๅบๅ</p>
                </a>
                <a href="<?php echo url('index/add_shaidan'); ?>" class="grid">
                    <i class="iconfont grid-icon">&#xe645;</i>
                    <p class="grid-label">ๆทปๅ?ๆๅ</p>
                </a>
                <!-- <a href="javascript:;" class="grid" id="kefu">
                    <i class="iconfont grid-icon">&#xe613;</i>
                    <p class="grid-label">่็ณปๅฎขๆ</p>
                </a> -->
                
            </div>
            <a href="javascript:;" class="member-ad"><img src="/static/wap/images/6.png"></a>
        </div>
    </section>


    <div class="buttonm">
      <a href="<?php echo url('index/shouye'); ?>" class="vua">
          <img src="/addons/hc_wish/template/mobile/image/index_icon.png" alt="" />
          <p>้ฆ้กต</p>
      </a>
      <a href="<?php echo url('index/red_packet'); ?>" class="vua">
        <img src="/addons/hc_wish/template/mobile/image/hby.png" alt="" />
        <p>็บขๅ</p>
    </a>
      <a href="<?php echo url('index/goods'); ?>" class="vua">
          <img src="/addons/hc_wish/template/mobile/image/gift_icon.png" alt="" />
          <p>็คผๅ</p>
      </a>
      <a href="<?php echo url('index/my'); ?>" class="vua">
          <img src="/addons/hc_wish/template/mobile/image/my_icon.png" alt="" />
          <p>ๆ็</p>
      </a>
  </div>
<!-- ้ฎ็ฝฉๅฑไปฅๅไบ็ปด็? -->
    <div class="shadow" onclick="close()" style="display: none;"></div>
    <div class="shadows" style="display: none;"></div>
    <img class="shadowqrcode" src="<?php echo config('site.ewm_bg'); ?>">
    <img class="gzh_qrcode" src="<?php echo config('site.gzh_img'); ?>">


    <div class="notices" id="buy" style="display:none;max-height: none;">

        <div class="closeNotices" onclick="closeNotice(this)" id="closebuy">โ</div>

        <div class="pricetitle">ๆถ้ถๅฐ</div>

        <div style="color:#000000;margin:20px 0 -20px;text-align: center;">

        </div>

        <div class="flex coins">

            <text>ๅฝๅไฝ้ข๏ผ</text>

            <img class="coin" src="/addons/hc_wish/public/coin.png" alt="">

            <text>0.00ๅ</text>

        </div>

        <div class="pricelist between" style="color:#EC6243;">
            <div class="priceitem flex" money="" onclick="recharge(this)">
                <text></text>
                <text>ๅ</text>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="/static/wap/js/mcanvas.js"></script>
<script>
    $(".member-ad").click(function () {
        $(".shadow").show();
        $(".gzh_qrcode").show();
    })
    $(".shadow").click(function () {
        $(".shadow").hide();
        $(".shadowqrcode").hide();
        $(".gzh_qrcode").hide();
    })

    $(".link").click(function () {
        $(".shadows").css("display", "block");
        $("#cashs").css("display", "block");
        $("#container").addClass("blur");
        $(".footer").addClass("blur");
    })
    function closeCash(obj) {
        $(".shadows").css("display", "none");
        $("#cashs").css("display", "none");
        $("#container").removeClass("blur");
        $(".footer").removeClass("blur");
    }
    $("#cashmoney").click(function () {
        var yongjin = "<?php echo $user['yongjin']; ?>";
        var min_tixian = "<?php echo config('site.min_tixian'); ?>";
        var openid = "<?php echo $openid; ?>";
        if(yongjin < min_tixian){
            layer.msg('ๅ็ฌๆ็ฐๆถ็ไธ่ฝๅฐไบ'+min_tixian+'ๅ');
            return;
        }
        $.post("<?php echo url('index/tixian'); ?>",{openid:openid},function(data){
            if(data.code == 1){
                layer.msg(data.msg,function(){
                    window.closeCash()
                    location.reload();
                });
            }else{
                $(".shadow").css("display", "none");
                $("#cashs").css("display", "none");
                layer.msg(data.msg);
                return;
            }
        });
    })

    function closeNotice(obj) {
        $("#buy").hide()
        $(".shadows").hide()
    }
    
    function ewm(){
        var mc = new MCanvas({
            width: 662,
            height: 960
            // backgroundColor: 'black',
        });
        // mc.background('/public/static/wap/share/share_bg1.jpg', {
        mc.background("<?php echo config('site.ewm_bg'); ?>", {
            type: 'origin', 
            left: '50%',
            top: '50%'
        });
        var ops = [
            {
                image: "<?php echo url('index/get_qrcode'); ?>",
                options: {
                    width: 140,
                    pos: {
                        x: 500,
                        y: 500,
                        scale: 1,
                        rotate: 0
                    }
                }
            },
        ];
        mc.add(ops);
        mc.draw(function (b64) {
            console.log(b64)
            $('.shadowqrcode').attr('src', b64);
        })
        $(".shadow").show();
        $(".shadowqrcode").show();
    }
</script>

</html>