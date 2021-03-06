<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"/www/wwwroot/jindan11.lostinparadise.xyz/public/../application/index/view/index/goods.html";i:1623984031;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>商品列表</title>
	<link rel="stylesheet" href="/static/wap/css/reset.css">
	<link rel="stylesheet" href="/static/wap/css/goods.css">
	<script type="text/javascript" src="/static/wap/js/pxtorem.js"></script>
	<script type="text/javascript" src="/static/wap/js/jquery.min.js"></script>
	<script type="text/javascript" src="/static/wap/js/config.js"></script>
  <!-- 引入样式文件 -->
  <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/vant@2.12/lib/index.css"
  />
  <!-- 引入 Vue 和 Vant 的 JS 文件 -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6/dist/vue.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vant@2.12/lib/vant.min.js"></script>
  <style>
    .van-tab{
        color: #666666!important;
    }
    .van-tab--active{
        color: #FE3251!important;
    }
    .van-tabs__line{
        background-color:#FE3251!important;
    }
    .van-nav-bar{
        height: 46px!important;
    }
    .van-list{
        width:17.25rem;
        margin:auto;
    }

  </style>
</head>
<body>
<div id="app">
  <van-notice-bar
    left-icon="volume-o"
    text="<?php echo config('site.goods_notice'); ?>"
  >
  </van-notice-bar>
  <van-tabs @click="onClick" v-model="active" sticky>
    <van-tab v-for="(item,index) in tabs" :title="item.name" :key="index" :name="item.id">
        <van-list
            v-model="loading"
            :finished="finished"
            finished-text="没有更多了"
            :offset="30"
            @load="onLoad"
        >
            <div class="flex-wrap">
                <div class="item" v-for="item in items" :key="item.id">
                  <a :href="'goods_detail.html?id='+item.id" >
                        <div class="img_box flex-center-center">
                          <img :src="item.goods_image" alt="">
                        </div>
                        <div class="name ellipsis02">{{item.goods_name}}</div>
                        <div class="flex-between">
                          <p class="price">￥{{item.price}}</p>
                          <p class="bi">{{item.jifen}}积分</p>
                        </div>
                  </a>
                </div>
            </div>
        </van-list>
    </van-tab>
  </van-tabs>
  <div style="height: 2.8rem;"></div>
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

</div>
<script>
new Vue({
  el: '#app',
  data () {
        return {
            active:0,
            tabs:[],
            type:0,
            items: [],
            loading: false,
            finished: false,
            page:1,
            limit:15
        }
    },
    mounted() {
      var that=this
      $.post("<?php echo url('index/category'); ?>",{},function(data){
        data.data.unshift({
          name:'全部',
          id:''
        })
        that.tabs=data.data
      })
    },
    methods: {
        onClick(name, title) {
            console.log(name, title)
            this.items=[];
            this.page=1;
            this.loading = true;
            this.finished = false;
            this.onLoad(name);
        },
        onLoad(name) {
            var that=this;
            // 异步更新数据
            setTimeout(() => {
              $.post("<?php echo url('index/goods'); ?>",{page:that.page,limit:that.limit,id:name},function(data){
                if(data.data.data.length!=0){
                  for(var i=0;i<data.data.data.length;i++){
                      that.items.push(data.data.data[i]);
                  } 
                  // 页数递增
                  that.page++ 
                  // 加载状态结束
                  that.loading = false;
                  // 数据全部加载完成
                  if (that.page==data.data.last_page+1) {
                      that.finished = true;
                  }
                }else{
                    // 加载状态结束
                    that.loading = false;
                    that.finished = true;
                }
              })
            }, 300);
        },
    }
});
</script>
</body>
</html>
