<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:95:"/www/wwwroot/jindan11.lostinparadise.xyz/public/../application/index/view/index/red_packet.html";i:1623984031;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>红包雨</title>
	<link rel="stylesheet" href="/static/wap/css/reset.css">
	<link rel="stylesheet" href="/static/wap/css/hongbao.css">
	<link rel="stylesheet" type="text/css" href="/static/wap/css/sweet-alert.css">
	<script type="text/javascript" src="/static/wap/js/pxtorem.js"></script>
	<script type="text/javascript" src="/static/wap/js/jquery-1.10.1.min.js"></script>
  <script type="text/javascript" src="/static/wap/layer/layer.js"></script>
</head>
<body>
<div class="w702 flex-between">
	<div class="tip flex-center" id="news" style="overflow: hidden">
	    <img src="/static/wap/images/tip.png" class="tip_img" alt="">
	    <ul style="height: 1.5rem;overflow: hidden;">
			<?php if(($notice)): foreach($notice as $v): ?>
	        <li><?php echo $v; ?></li>
			<?php endforeach; endif; ?>
	   </ul>
	</div> 
	<div class="djs">
    <span class="cishu"><i class=""><?php echo $user['game']; ?></i><i> 次</i></span>
		<span class="time" style="display: none;"><i class="second">50</i><i> 秒</i></span>
	</div>
</div> 
<script>
function AutoScroll(obj){
    $(obj).find("ul:first").animate({
        marginTop:"-2rem"
    },1000,function(){
        $(this).css({marginTop:"0px"},1000).find("li:first").appendTo(this);
    });
}
$(document).ready(function(){
    setInterval('AutoScroll("#news")',3000);
});

</script>
<ul class="couten">
  <img src="/static/wap/images/start.png" class="start" style="width:50%;margin:13rem auto 0 auto;display:block" alt="">
	<!--<li>
		<a href="#"><img src="img/hb_1.png"></a>
	</li>-->
</ul>
<div class="mo">
	<div class="sen">
		<img src="img/gx.png">
		<h3>获得红包3元</h3>
		<a href="#">确定</a>
	</div>
</div>
<!-- <div class="backward">
	<span></span>
</div>	 -->
<!-- 10S倒计时弹窗 -->
<div class="tipmodel flex-center-center" id="time" style="display:none;">
	<div>
		<p class="ts">游戏结束</p>
		<div class="time_box">
			<p class="zimu">S</p>
			<p class="shijian">10</p>
		</div>
		<!-- <img src="img/yazhu_clcose.png" class="time_close" alt=""> -->
	</div>

</div>
<script type="text/javascript">
$(document).ready(function() {
	var win = (parseInt($(".couten").css("width"))) - 60;
	$(".mo").css("height", $(document).height());
	$(".couten").css("height", $(document).height());
	$(".backward").css("height", $(document).height());
	$("li").css({});
	// 点击确认的时候关闭模态层
	$(".sen a").click(function(){
	  $(".mo").css("display", "none")
	});
	
	var del = function(){
		nums++;
//					console.info(nums);
//					console.log($(".li" + nums).css("left"));
		$(".li" + nums).remove();
		setTimeout(del,200)
	}
	var id;
	var add = function() {
		var hb = parseInt(Math.random() * (3 - 1) + 1);
		var Wh = parseInt(Math.random() * (70 - 30) + 20);
		var Left = parseInt(Math.random() * (win - 0) + 0);
		var rot = (parseInt(Math.random() * (45 - (-45)) - 45)) + "deg";
		//				console.log(rot)
		num++;
		if(num>20 && num<30){
			$(".couten").append("<li class='li" + num + "' id='fudai" + num + "'><a href='javascript:;'><img src='/static/wap/images/fd.png'></a></li>");
		}else{
			$(".couten").append("<li class='li" + num + "' id='hongbao" + num + "'><a href='javascript:;'><img src='/static/wap/images/hb_" + hb + ".png'></a></li>");
		}
		
		$(".li" + num).css({
			"left": Left,
		});
		$(".li" + num + " a img").css({
			"width": Wh,
			"transform": "rotate(" + rot + ")",
			"-webkit-transform": "rotate(" + rot + ")",
			"-ms-transform": "rotate(" + rot + ")", /* Internet Explorer */
			"-moz-transform": "rotate(" + rot + ")", /* Firefox */
			"-webkit-transform": "rotate(" + rot + ")",/* Safari 和 Chrome */
			"-o-transform": "rotate(" + rot + ")" /* Opera */
		});	
		$(".li" + num).animate({'top':$(window).height()+20},5000,function(){
			//删掉已经显示的红包
			this.remove()
		});
		//点击红包的时候弹出模态层
    $("#hongbao"+num).click(function(){
			// $(".mo").css("display", "block")
      $.post("<?php echo url('index/red_packet'); ?>",{type:1,game_id:id},function(data){
        layer.msg(data.msg)
      });
		});
    //点击福袋的时候弹出模态层
    $("#fudai"+num).click(function(){
			// $(".mo").css("display", "block")
      $.post("<?php echo url('index/red_packet'); ?>",{type:2,game_id:id},function(data){
        layer.msg(data.msg)
      });
		});
		setTimeout(add,200)
	}	
	
	//增加红包
	var num = 0;
  // setTimeout(add,100);
  // 开始游戏
  $('.start').click(function(){
    var cishu="<?php echo $user['game']; ?>";
    if(cishu<=0 ){
      layer.msg('次数不足')
    }else{
      $.post("<?php echo url('index/play_game'); ?>",{},function(data){
        if(data.code==1){
          id=data.data.game_id
          $('.start').hide()
          $('.cishu').hide()
          $('.time').show()
          setTimeout(add,100);
          //倒计时
          var miao = $(".djs .second"); //首页倒计时
          var miao01= $("#time .shijian"); //10s倒计时
          var miao02 = $("#shengxiao .second span");  //弹窗倒计时
          var count = 30;
          miao.text(count);
          var timer = null;
          timer = setInterval(function () {
            if (count > 0) {
              count --;
              if(count == 1){
                $("#time").show();  //显示10S弹窗
              }
              miao.text(count);
              miao01.text(count)
            }
            else {
              clearInterval(timer);
            }
          }, 1000);
        }
      })
    }
  })
  
})
</script>	
</body>
</html>
