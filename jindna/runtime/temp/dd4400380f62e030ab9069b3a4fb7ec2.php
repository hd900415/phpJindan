<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/www/wwwroot/jindan11.lostinparadise.xyz/public/../application/index/view/index/shouye.html";i:1641907215;}*/ ?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    <title>虎年金蛋</title>
    <script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="/addons/hc_wish/template/mobile/js/flexible.min.js"></script>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <script type="text/javascript" src="/addons/hc_wish/template/mobile/js/layer/layer.js"></script>
    <link rel="stylesheet" href="/addons/hc_wish/template/mobile/css/css.css" />
    <link rel="stylesheet" href="/addons/hc_wish/template/mobile/css/vh.css" />
    <link rel="stylesheet" href="/addons/hc_wish/template/mobile/css/personal.css" />
    <link rel="stylesheet" href="/addons/hc_wish/template/mobile/css/mach.css">
    </link>
    <style>
        .noticeR {
            width: 1rem;
            height: 1rem;
            position: absolute;
            top: 0.5rem;
            display: -webkit-box;
            display: flex;
            align-items: center;
            -webkit-align-items: center;
            -webkit-justify-content: center;
            justify-content: center;
            right: 0.5rem;
            z-index: 12;
        }

        .noticeR img {
            width: 0.5rem;
            height: 0.5rem;
            display: block;
        }

        /*.gift {
    width: 7.7vh;
    height: 7.7vh;
    position: absolute;
    bottom: 55vh;
    left: 3vh;
    z-index: 1;
}*/

        .cute-barrage {
            width: 100%;
            height: 100vh;
            position: absolute;
            left: 0;
            top: 0;
        }

        .barrage-div {
            z-index: 15;
            position: absolute;
            top: 0;
            right: 10%;
            overflow: visible;
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            width: fit-content;
            width: -webkit-fit-content;
            width: -moz-fit-content;
        }

        .DMimg {
            width: 46px;
            z-index: 15;
        }

        .barrage-div>span {
            height: 32px;
            line-height: 32px;
            font-size: 13px;
            padding: 0 10px 0 30px;
            display: inline-block;
            margin-left: -28px;
            border-radius: 16px;
            box-sizing: border-box;
            box-shadow: inset 0 0 12px #F2DF7C;
            background: rgba(255, 255, 255, .9);

        }

        .barrage-div span text:nth-child(1) {
            color: #fe5541;
            margin-right: 8px;

        }

        .barrage-div span text:nth-child(2) {
            color: #B1B3B6;
            font-size: 10px;
        }
    </style>
    <style>
      .flex{
        display: flex;
      }
      .flex-center{
        display: flex;
        align-items: center;
      }
      .flex-center-center{
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .flex-end{
        display: flex;
        align-items: flex-end;
      }
      .flex-between{
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .flex-wrap{
        display: flex;
        flex-wrap: wrap;
      }
      .flex-direction{
        display: flex;
        flex-direction:column;
      }
      .ellipsis01{
        overflow: hidden;
        white-space: nowrap;
        text-overflow:ellipsis;
      }
      .ellipsis02{
        display: -webkit-box;
          -webkit-box-orient: vertical;
          -webkit-line-clamp: 2;
          overflow: hidden;
      }
      
      /*弹窗*/
      .tipmodel {
          position: fixed;
          width: 100%;
          background-color: rgba(0,0,0,.5);
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          z-index: 999;
          overflow: hidden;
          display: flex;
        align-items: center;
        justify-content: center;
      }
      /*支付弹窗*/
      .zhifu{
        width:5.8rem;
        /*height:3.8rem;*/
        background:rgba(255,255,255,1);
        box-shadow:0px 0.1rem 0.175rem 1px rgba(224,153,2,0.3);
        border-radius:0.25rem;
        position: absolute;
        /*top: 0;*/
        /*left: 0;*/
        /*right: 0;*/
        /*bottom:0;*/
        /*display: table;*/
        /*margin:auto;*/
        text-align:center;
      }
      .zhifu .title{
        color: #E80005;
        font-size: 0.3rem;
        padding-top: 0.57rem;
        padding-bottom: 0.8rem;
      }
      .zhifu h1{
        color: #E80005;
        font-size: 0.36rem;
      }
      .zhifu .twoBtn{
        display: flex;
      }
      .zhifu .twoBtn{
        margin-top:0.5rem;
        border-top: 0.5px solid rgba(147,135,135,0.25);
        position: relative;
      }
      .zhifu .twoBtn li{
        width: 50%;
        height: 1rem;
        line-height: 1rem;
        text-align: center;
        color: #7B6C6C;
        font-weight:500;
        font-size: 0.3rem;
      }
      .zhifu .twoBtn li:nth-child(2){
        color: #E80005;
      }
      .zhifu .twoBtn .line{
        width:1px;
        height:1.7rem;
        background:rgba(147,135,135,0.25);
        position: absolute;
        top: 0;
        bottom:0;
        right: 0;
        left: 0;
        display: table;
        margin:auto;
      }
      .zhifu .type{
          width: 2.5rem;
        color:#333;
        font-size: 0.35rem;
        background:white url(/static/wap/images/type02.png) left no-repeat;
        background-size: 0.44rem 0.44rem;
        /* background-position:0% 50%; */
      }
      .zhifu .db{
          width: 2.5rem;
        background:white url(/static/wap/images/type01.png) left no-repeat;
        background-size: 0.44rem 0.44rem;
        background-position:0% 50%;
      }
      /*支付弹窗*/
      .dw{
        width:5.49rem;	
          height: 5.1rem;
        /*position: absolute;*/
        /*top: 0;*/
        /*left: 0;*/
        /*right: 0;*/
        /*bottom:0;*/
        /*display: table;*/
        /*margin:auto;*/
      }
      .dw .close{
        width: 0.54rem;
        height: 0.8rem;
      }
      .fail_box{
        background:rgba(255,255,255,1);
        box-shadow:0px 0.1rem 0.175rem 1px rgba(224,153,2,0.3);
        border-radius:0.08rem;
        text-align:center;
        padding-bottom: 0.3rem;
      }
      .fail_box img{
        width: 1.1rem;
        height: 1.26rem;
        padding-top: 0.5rem;
      }
      .fail_box h1{
        color: #333333;
        font-size: 0.3rem;
        padding-top: 0.3rem;
      }
      .fail_box p{
        color: rgba(51,51,51,0.5);
        font-size: 0.22rem;
        padding-top: 0.26rem;
      }
      .fail_box a{
        display: block;
        margin:auto;
        margin-top:0.41rem;
        width:2.55rem;
        height:0.72rem;
        line-height:0.72rem;
        background:linear-gradient(127deg,rgba(255,200,113,1),rgba(255,171,50,1));
        border-radius:0.34rem;
        font-weight:400;
        font-size: 0.24rem;
        color: white;
      }
      .input{
        margin-left: 0.5rem;
        text-align: left;
      }
      .input input{
        width: 1.2rem;
      }
      /* 礼盒 */
      .wrap{
	width: 100%;
	/*height: 27.6rem;*/
	height: 100vh;
	background: url(../img/gift_bg.png)  center no-repeat;
	background-size: 100% 100%;
}
.gift_close{
	width: 3.18rem;
	height: 4.07rem;
	animation: bounceInDown 2s ease 1;
}
.gift_open{
	width: 4.72rem;
	height: 4.8rem;
}
.gift_open_box{
	position: relative;
	width: 4.72rem;
	height: 6rem;
}
.goods{
	width: 2rem;
	height: 1.6rem;
	position: absolute;
	top: 0.92rem;
	left: 0.78rem;
	animation: fadeInUp 1s ease;
}
.write{
	display: block;
	width: 3.1rem;
	height: 0.6rem;
	line-height: 0.6rem;
	margin:auto;
	margin-top: 0.75rem;
	text-align: center;
	color: #A54B0A;
	font-size: 0.3rem;
	background:#FFD865;
	border-radius:0.75rem;
}

.move{
	animation:move 1.5s ease 1;
}

@keyframes bounceInDown {
  0%, 60%, 75%, 90%, 100% {
    -webkit-transition-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
            transition-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
  }

  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -3000px, 0);
            transform: translate3d(0, -3000px, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(0, 25px, 0);
            transform: translate3d(0, 25px, 0);
  }

  75% {
    -webkit-transform: translate3d(0, -10px, 0);
            transform: translate3d(0, -10px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, 5px, 0);
            transform: translate3d(0, 5px, 0);
  }

  100% {
    -webkit-transform: none;
            transform: none;
  }
}

@keyframes move{
	0%, 65%{ 
	  -webkit-transform:rotate(0deg);
	  transform:rotate(0deg);
	}
	70% {  
	  -webkit-transform:rotate(6deg);
	  transform:rotate(6deg);
	}
	75% {  
	  -webkit-transform:rotate(-6deg);
	  transform:rotate(-6deg);
	}
	80% {  
	  -webkit-transform:rotate(6deg);
	  transform:rotate(6deg);
	}
	85% {  
	  -webkit-transform:rotate(-6deg);
	  transform:rotate(-6deg);
	}
	90% {  
	  -webkit-transform:rotate(6deg);
	  transform:rotate(6deg);
	}
	95% {  
	  -webkit-transform:rotate(-6deg);
	  transform:rotate(-6deg);
	}
	100% {  
	  -webkit-transform:rotate(0deg);
	  transform:rotate(0deg);
	}
}

@-webkit-keyframes fadeInUp {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
  }

  100% {
    opacity: 1;
    -webkit-transform: none;
            transform: none;
  }
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
  }

  100% {
    opacity: 1;
    -webkit-transform: none;
            transform: none;
  }
}
.liwu_name{
  position: absolute;
  top: 0;
  left: 1rem;
  color: #FFD865;
  font-size: 0.4rem;
}
      </style>
<body>
    <!--/audio/111.mp3-->
        <audio src="/audio/111.mp3" id="bgms" controls="true" autoplay="true" loop="true" hidden="true"></audio>
    <audio src="" id="bgms" controls="true" autoplay="true" loop="true" hidden="true"></audio>
    <script type="text/javascript">
        $(document).ready(function () {
            document.body.addEventListener('touchmove', function (e) {
                e.preventDefault(); //阻止默认的处理方式(阻止下拉滑动的效果)
            }, { passive: false });
        });
        document.addEventListener('WeixinJSBridgeReady', function () {
            var audio = document.getElementById('bgms');
            if (localStorage.getItem('audio') == 2) {
                audio.pause();// 暂停
                $(".noticeR").html('<img src="/addons/hc_wish/template/mobile/image/closeV.png" alt="">');
            } else {
                audio.play();
            }
            $(".noticeR").click(function () {
                if (audio !== null) {
                    if (audio.paused) {
                        audio.play();// 播放 
                        localStorage.setItem('audio', 1);
                        $(".noticeR").html('<img src="/addons/hc_wish/template/mobile/image/openV.png" alt="">');
                    } else {
                        audio.pause();// 暂停
                        localStorage.setItem('audio', 2);
                        $(".noticeR").html('<img src="/addons/hc_wish/template/mobile/image/closeV.png" alt="">');
                    }
                }
            })
        });
    </script>
    <div class="cute-barrage">
    </div>
    <div class="main">

        <!-- 新年主题 -->
        <div class="container1" style="background:url(/addons/hc_wish/template/mobile/images/happy/bg.png) no-repeat; display: none;">
            <img class="mactop" src="/addons/hc_wish/template/mobile/images/happy/top.png">
            <div class="noticeR" style="right:0">
                <img src="/addons/hc_wish/template/mobile/image/openV.png" alt="">
            </div>
            <div class="mach_box">
                <img class="machImg" src="/addons/hc_wish/template/mobile/images/happy/mach.png">
                <img class="cloudImg" src="/addons/hc_wish/template/mobile/images/happy/cloud.png">
                <div class="boxlist">
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                    <div class="box_item">
                        <img class="box_gift" src="/addons/hc_wish/template/mobile/images/happy/box_2.png">
                    </div>
                </div>
                <div class="doorBox" style="perspective: 150;-webkit-perspective:90;">
                    <div class="door"></div>
                </div>

                <img class="buy_n" src="/addons/hc_wish/template/mobile/images/happy/buy_n.png">
                <img class="buy_ns" src="/addons/hc_wish/template/mobile/images/happy/buy_ns.png">
                <div class="buy_p">
                    <img src="/addons/hc_wish/template/mobile/images/happy/buy_p.png">
                    <span>0</span>
                </div>
                <a href="tel:点击按钮直接复制到拨号盘。" class="phone">
                    <img style="bottom:20vh" src="/addons/hc_wish/template/mobile/image/phone.png" alt="" />
                </a>
            </div>
        </div>
        <!-- 新年主题 -->


        <div class="vending" >
            <div class="noticeR"><img src="/addons/hc_wish/template/mobile/image/openV.png" alt=""></div>
            <img class="lipa" src="/addons/hc_wish/template/mobile/image/lipa.png" alt="">

            <div class="vending_box">
                <img class="huab" src="/addons/hc_wish/template/mobile/image/wenan.gif" alt="">
                <div class="vending_box_teo">
                    <img class="vending_boximg" src="/addons/hc_wish/template/mobile/image/mach.png" alt="">
                    <img class="gfuia" src="/addons/hc_wish/template/mobile/image/gfuia.png" alt="">
                    <img class="goshu" src="/addons/hc_wish/template/mobile/image/goshu.png" alt="">
                    <div class="meiau">

                        <p>¥<span><?php echo config('site.box_price'); ?></span></p>
                    </div>
                    <a href="tel:点击按钮直接复制到拨号盘。" class="phone">
                        <img src="/addons/hc_wish/template/mobile/image/phone.png" alt="" />
                    </a>
                    <div class="box_muiab">
                        <div class="box_muiab_poi">

                            <div class="vending_boximg_a butom1">
                                <img class="gift" data-num="1"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia1.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom2">
                                <img class="gift" data-num="2"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia2.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom3">
                                <img class="gift" data-num="3"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia3.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom4">
                                <img class="gift" data-num="4"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia4.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom5">
                                <img class="gift" data-num="5"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia5.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom6">
                                <img class="gift" data-num="6"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia6.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom7">
                                <img class="gift" data-num="7"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia7.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom8">
                                <img class="gift" data-num="8"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia8.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom9">
                                <img class="gift" data-num="9"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia9.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom10">
                                <img class="gift" data-num="10"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia10.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom11">
                                <img class="gift" data-num="11"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia11.png" alt="">
                            </div>
                            <div class="vending_boximg_a butom12">
                                <img class="gift" data-num="12"
                                    src="/addons/hc_wish/public/box.png" alt="">
                                <img class="gift_two" src="/addons/hc_wish/template/mobile/image/lia12.png" alt="">
                            </div>

                            <div class="Exit">
                                <div class="Exit_ona">
                                    <div class="Exit_ona_yua"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- 简陋版主题 -->
        <div class="vending_twoa" style="display:none">
            <div class="noticeR"><img src="/addons/hc_wish/template/mobile/image/openV.png" alt=""></div>
            <div class="shop_bag">
                <img class="shop_bagjhoau" src="/addons/hc_wish/template/mobile/image/gfuia.png" alt="">
                <img class="shop_bagjhoao" src="/addons/hc_wish/template/mobile/image/goshu.png" alt="">
                <div class="meiau avda">
                    <!-- <img class="shop_bagjhoaa" src="/addons/hc_wish/template/mobile/image/meiau.png" alt=""> -->
                    <p>¥<span>0</span></p>
                </div>

                <img class="shop_bagimg" src="/addons/hc_wish/template/mobile/image/shop_bag.png" alt="" />
                <div class="shop_bau">
                    <div class="fuaia fuaia1" data-num="1">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.1</p>
                    </div>
                    <div class="fuaia fuaia1" data-num="2">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.2</p>
                    </div>
                    <div class="fuaia fuaia1" data-num="3">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.3</p>
                    </div>
                    <div class="fuaia " data-num="4">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.4</p>
                    </div>
                    <div class="fuaia " data-num="5">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.5</p>
                    </div>
                    <div class="fuaia " data-num="6">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.6</p>
                    </div>
                    <div class="fuaia " data-num="7">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.7</p>
                    </div>
                    <div class="fuaia " data-num="8">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.8</p>
                    </div>
                    <div class="fuaia " data-num="9">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.9</p>
                    </div>
                    <div class="fuaia fuaia2" data-num="10">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.10</p>
                    </div>
                    <div class="fuaia fuaia2" data-num="11">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.11</p>
                    </div>
                    <div class="fuaia fuaia2" data-num="12">
                        <img src="/addons/hc_wish/public/box.png" alt="" />
                        <p>NO.12</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ypumi" style="display:none" id="huaia">

        <div class="specification">
            <h3>购买说明</h3>
            <div class="huai">
                <p class="instruction">
                    <img src="/addons/hc_wish/template/mobile/image/dian.png" alt="" />
                    <span>点击选择心仪的号码</span>
                </p>
                <p class="instruction">
                    <img src="/addons/hc_wish/template/mobile/image/jiauh.png" alt="" />
                    <span>选择数量，微信支付</span>
                </p>
                <p class="instruction">
                    <img src="/addons/hc_wish/template/mobile/image/naoifyh.png" alt="" />
                    <span>金蛋马上发货</span>
                </p>
                <p class="instruction">
                    <img src="/addons/hc_wish/template/mobile/image/daui.png" alt="" />
                    <span>收到惊喜礼物</span>
                </p>
                <div class="hsau"></div>

            </div>
            <img class="miau" src="/addons/hc_wish/template/mobile/image/f7b84c2d5771b56c3019b68f3c752833.png" alt="" />
            <img class="aiSAsaa aiSAsaaa" src="/addons/hc_wish/template/mobile/image/guanh.png" alt="" />
        </div>
    </div>
    <div class="ypumi" style="display:none;" id="huai">
        <div class="specification">
            <h3>购买须知</h3>
            <div class='cioa' style="overflow-y: auto;">
                <?php echo config('site.buy_desc'); ?>
            </div>
            <img class="aiSAsaa" src="/addons/hc_wish/template/mobile/image/guanh.png" alt="" />
        </div>
    </div>
    <div class="buttonm">
        <a href="<?php echo url('index/shouye'); ?>" class="vua">
            <img src="/addons/hc_wish/template/mobile/image/index_icon.png" alt="" />
            <p>首页</p>
        </a>
        <a href="<?php echo url('index/red_packet'); ?>" class="vua">
          <img src="/addons/hc_wish/template/mobile/image/hby.png" alt="" />
          <p>红包</p>
      </a>
        <a href="<?php echo url('index/goods'); ?>" class="vua">
            <img src="/addons/hc_wish/template/mobile/image/gift_icon.png" alt="" />
            <p>礼品</p>
        </a>
        <a href="<?php echo url('index/my'); ?>" class="vua">
            <img src="/addons/hc_wish/template/mobile/image/my_icon.png" alt="" />
            <p>我的</p>
        </a>
    </div>
<!-- 支付弹窗 -->
<div class="tipmodel" id="pay_success" style="display:none">
    <section class="zhifu">
        <p class="title"></p>
        <div class="flex-center-center">
            <!--<div class="type db" data-type="1">试玩模式</div>-->
            <div class="type" data-type="2">立即支付</div>
        </div>
        <div class="input" style="display: none;">
          <div style="margin: 0.5rem 0 0.2rem 0;">使用福袋数量：<input type="number" value="0" id="fudai_num"></div>
          <div>当前福袋数量：<?php echo $user['fudai']; ?> 个</div>
        </div>
        <ul class="twoBtn">
            <li class="cancel" style="border-right:0.5px solid rgba(147,135,135,0.25)">取消</li>
            <li class="true">确定</li>
            <p class="line"></p>
        </ul>
    </section>
</div>
<!-- 余额不足弹窗 -->
<div class="tipmodel" id="fail" style="display:none">
    <div class="dw">
        <div style="display: flex;justify-content: flex-end;">
            <img src="/static/wap/images/close.png" class="close" alt="">
        </div>
        <section class="fail_box">
            <img src="/static/wap/images/fail_ico.png" alt="">
            <h1>余额不足</h1>
            <p>当前余额不足，赶快去充值吧~</p>
            <a href="chongzhi.html">立即充值</a>
        </section>
    </div>
</div>

<div class="tipmodel lihe_box flex-center-center" style="display: none;">
    <!-- 礼盒关 -->
    <img src="/static/wap/images/gift_close.png" class="gift_close" alt="">
    <!-- 礼盒开 -->
    <div class="gift_open_box" style="display: none">
        <img src="/static/wap/images/gift_open.png" class="gift_open" alt="">
        <p class="liwu_name"></p>
        <img src="" class="goods" alt="">
        <p class="write">填写收货地址</p>
    </div>
</div>
</body>

<script type="text/javascript">
// 礼盒开关
 $(".gift_close").click(function(event) {
    $(this).addClass('move');
    setTimeout(function(){  
        $(".gift_close").hide();
        $(".gift_open_box").fadeIn();
    },1500);
}); 
$('.lihe_box').on('click', function(event){
    if($(event.target).is('.lihe_box') ) {
        event.preventDefault();
        $(this).fadeOut('fast');
        location.reload()
    }
});

// 支付方式
$(".tipmodel .type").click(function() {
    $(".tipmodel .type").removeClass('db');
    $(this).addClass('db');
    var type=$('#pay_success .db').attr('data-type');
    if(type==2){
      $('.tipmodel .input').show()
    }else{
      $('.tipmodel .input').hide()
    }
});
// 取消按钮
$(".twoBtn .cancel").click(function(){
        $("#pay_success").fadeOut('fast');
    })
    
 // 关闭按钮
 $("#fail .close").click(function(){
    $("#fail").fadeOut('fast');
})
//判断余额是否充足
function money_state(){
    var my_jinbi="<?php echo $user['jine']; ?>";  //我拥有的金币数量
    var pay_jinbi="<?php echo $box_price; ?>";  //游戏支付金币数量
    if((my_jinbi*1)<(pay_jinbi*1)){
      $("#fail").fadeIn('fast');
      return false;
    }
}
    $(document).ready(function () {

        var danmukg = '1';
        if (danmukg == 1) {
            var p = Promise.all([barrage(), window.lunbo()]);
            Promise.all(p).then(function (posts) { }).catch(function (reason) { });
        }
        $(".vending_box .gift").click(function () {
            drop($(this))
        });
        var order_id;
        // 确定按钮
        $(".twoBtn .true").click(function(){
            $("#pay_success").fadeOut('fast');
            var type=$('#pay_success .db').attr('data-type');
            if(type==1){
              $.post("<?php echo url('index/open_box'); ?>",{type:type},function(data){
                $('.lihe_box').show();
                $('.write').hide()
                $('.lihe_box .goods').attr('src',data.data.goods_image)
                $('.lihe_box .liwu_name').text(data.data.goods_name)
              })
            }else{
              money_state()
              $.post("<?php echo url('index/open_box'); ?>",{type:type,fudai:$('#fudai_num').val()},function(data){
                if(data.code==1){
                  $('.lihe_box').show();
                  $('.lihe_box .goods').attr('src',data.data.goods_image)
                  $('.lihe_box .liwu_name').text(data.data.goods_name)
                  order_id=data.order_id
                }
              })
            }
        })
        // 填写收货地址
        $(".write").click(function(){
          $(location).attr('href', "<?php echo url('index/cart'); ?>?order_id=" + order_id);
        })

    });
    // $(".box_item").on("click", drop_apple);
    function drop_apple() {
        var index = $(this).index()
        var box_num = parseInt(index) + 1
        $(".box_item").unbind("click");
        var lineNum = Math.abs(4 - Math.ceil((index + 1) / (3)))
        $(".box_item:eq(" + index + ")>.box_gift").animate({
            bottom: '8vh',
        }, 300).animate({
            bottom: -10.47 - lineNum * 13 + "vh",
        }, 500);
        setTimeout(function () {
            $(".door").css({
                transform: "rotateX(40deg)",
                "-webkit-transform": "rotateX(-40deg)"
            })
        }, 800)
        setTimeout(function () {
          $("#pay_success").show()
            // $(location).attr('href', "<?php echo url('index/cart'); ?>?box_num=" + box_num);

            $(".box_item").click(drop_apple);
        }, 1600);
        setTimeout(function () {
            $(".box_item:eq(" + index + ")>.box_gift").animate({
                bottom: '5vh',
            })
            $(".door").css({
                transform: "rotateX(0deg)",
                "-webkit-transform": "rotateX(0deg)"
            })
        }, 1800);
    }
    function drop($this) {
        var num = $this.data("num")
        $this.addClass("current");
        setTimeout(function () {
            $this.css({
                'bottom': '0',
                'transition': '0.5s',
                'transform': 'scale(0.8)'
            });
        }, 1000);
        switch (num) {
            case 1:
            case 4:
            case 7:
            case 10:
                setTimeout(function () {
                    $this.css({
                        'transform': 'rotate(90deg) scale(0.8)',
                        ' opacity ': '0.5'

                    });
                }, 1500);
                setTimeout(function () {
                    $('.Exit_ona_yua').addClass("xuya");
                }, 2200);
                setTimeout(function () {
                    $("#pay_success").show()
                    // $(location).attr('href', "<?php echo url('index/cart'); ?>?box_num=" + num);
                }, 2600);
                break;
            case 3:
            case 6:
            case 9:
            case 12:
                setTimeout(function () {
                    $this.css({
                        'transform': 'rotate(-90deg) scale(0.8)'
                    });
                }, 1500);
                setTimeout(function () {
                    $('.Exit_ona_yua').addClass("xuya");
                }, 2200);
                setTimeout(function () {
                  $("#pay_success").show()
                    // $(location).attr('href', "<?php echo url('index/cart'); ?>?box_num=" + num);
                }, 2600);
                break;
            case 2:
            case 5:
            case 8:
            case 11:
                setTimeout(function () {
                    $this.css({
                        'transform': 'rotate(-20deg) scale(0.8)',
                        'transition': '0.1s',
                    });
                }, 1400);
                setTimeout(function () {
                    $this.css({
                        'transform': 'rotate(0deg) scale(0.8)'
                    });
                }, 1900);
                setTimeout(function () {
                    $('.Exit_ona_yua').addClass("xuya");
                }, 2500);
                setTimeout(function () {
                  $("#pay_success").show()
                    // $(location).attr('href', "<?php echo url('index/cart'); ?>?box_num=" + num);
                }, 2900);
        }
    }
    $(".shop_bagjhoau").click(function () {
        $('#huai').show();
    })
    $(".gfuia").click(function () {
        $('#huai').show();
    })
    $(".aiSAsaa").click(function () {
        $('#huai').hide();
    })
    $(".shop_bagjhoao").click(function () {
        $('#huaia').show();
    })
    $(".goshu").click(function () {
        $('#huaia').show();
    })



    $(".buy_n").click(function () {
        $('#huaia').show();
    })
    $(".buy_ns").click(function () {
        $('#huai').show();
    })



    $(".aiSAsaaa").click(function () {
        $('#huaia').hide();
    })
    $('.shop_bau .fuaia').click(function () {
        var $this = $(this)
        var num = $this.data("num")

        // $(location).attr('href', "<?php echo url('index/cart'); ?>?box_num=" + num);

    })
    //弹幕
    function lunbo() {
        clearInterval(timer)
        var timer = setInterval(function () {
            barrage()
            clearInterval(timer)
        }, 10000)
        styles()
    }
    function barrage() {
        $.ajax({
            type: "GET",
            url: "<?php echo url('index/danmu'); ?>",
            dataType: "json",
            success: function (data) {
                var danmulist = data.data.data
                var str = data.data.str
                $(".cute-barrage").html("");
                for (var i in danmulist) {
                    $(".cute-barrage").append(`
					<div class="barrage-div">
        			<img class="DMimg" src="/addons/hc_wish/template/mobile/images/DMleft.png"/>
        				<span>`+ str[0] + danmulist[i].name + str[1] + `<text>` + danmulist[i].gift + str[2] + `</text><text>` + danmulist[i].time + str[3] + `</text></span>
				    </div>
	    		`)
                }
                window.lunbo()
            }
        });
    }
    function styles() {
        var winWidth = $(window).width();  //获取屏幕宽度
        $(".barrage-div").each(function (index, value) {   //遍历每条弹幕
            var width = $(value).width();   //获取当前弹幕的宽度
            var topRandom = Math.floor(Math.random() * 80) + 'px';  //获取0,1,2的随机数  可根据情况改变
            $(value).css({ "right": -width, "top": topRandom });  //将弹幕移动到屏幕外面，正好超出的位置
            //拼写动画帧函数，记得每个ani要进行区分，宽度从自己的负宽度移动一整个屏幕的距离    
            var keyframes = `   
		        @keyframes ani${index}{   
		            form{
		                right:${-width}px;
		            }
		            to{
		                right:${winWidth}px;
		            }
		        }   
		        @-webkit-keyframes ani${index}{
		            form{
		                right:${-width}px;
		            }
		            to{
		                right:${winWidth}px;
		            }
		        }`;
            //添加到页面的head标签里面
            $("<style>").attr("type", "text/css").html(keyframes).appendTo($("head"));
            //定义动画速度列表
            //取数组的随机数，0,1,2,10,4
            var aniTime = Math.floor(Math.random() * 5);
            //给当全前弹幕添加animation的css
            //延迟的时间用每个的*1.5倍，这个可变
            $(value).css({
                "animation": `ani${index} 8s linear ${index * 1.5}s none`,
                "-webkit-animation": `ani${index} 8s linear ${index * 1.5}s none`
            }
            );
        })
    }
</script>

</html>