var site_url = "http://" + document.domain;
var agent_id = 0;
var wxshare = {};
var query_arg = {};
//图片延时加载
var zooyoo_img_error = function (img) {
    $(img).error(function () {
        $(this).unbind("error").attr("src", "/Public/images/layz_bg.jpg");
    });
}
var zooyoo_load = function (a, d) {
    var b = function (f) {
        if (typeof (f.style) != "undefined" && ((typeof (f.style.display) != "undefined" && f.style.display ==
                "none") || (typeof (f.style.visibility) != "undefined" && f.style.visibility == "hidden"))) {
            return false
        } else {
            if (typeof (f.parentNode) != "undefined" && f.parentNode != null && f.parentNode != f) {
                return b(f.parentNode)
            }
        }
        return true
    };
    var c = $("img[_src]").not(".zz").filter(function (f) {
        return b(this)
    });
    this.checkImg = function (i) {
        var h = $(window).height();
        var g = $(window).scrollTop();
        c.each(function () {
            var k = this;
            var m = k.height;
            var j = $(k).offset().top;
            if (m <= 1) {
                m = d;
                j = j - m
            }
            a = (a <= m) ? m : a;
            var l = parseInt(m / 10);
            if ((((j + a) - i) > 0 && (j - i) < h) || ((j + m >= g) && (g + h >= j + l))) {
                if (k.getAttribute("_src")) {
                    k.src = k.getAttribute("_src");
                    zooyoo_img_error(k);
                    k.removeAttribute("_src");
                    $(k).fadeIn();
                }
            }
        });
        var f = $("img[_src]");
        if (f.length == 0) {
            window.onresize = null;
            window.onscroll = null
        }
    };
    this.init = function () {
        this.checkImg(document.documentElement.scrollTop)
    };
    this.loadDefImg = function (f) {
        for (var g = 0; g < c.length; g++) {
            var h = c[g];
            h.src = f
        }
    }
};
//记录分享日志
var zy_share_log = function (share_type, share_page, share_data) {
    $.ajax({
        type: 'POST',
        url: '/index.php/Ajax/sharelog',
        data: {
            type: share_type,
            action: share_page,
            data: share_data
        }
    });
    return;
}
//获取当前url
var get_now_url = function (skip, puid) {
    url = site_url + window.location.pathname;
    var skips = ",isappinstalled,from," + (skip ? skip + ',' : '');
    var schurl = window.location.search;
    var qeury = "";
    if (puid > 0) qeury = "puid=" + puid;
    if (schurl) {
        schurl = schurl.substr(1).split("&");
        for (var i = 0; i < schurl.length; i++) {
            t = schurl[i].split("=");
            if (skips.indexOf(',' + t[0].toLowerCase() + ',') == -1 && t[1]) {
                qeury += (qeury == "" ? "" : "&") + t[0] + "=" + t[1];
            }
        }
    }
    if (qeury != "") qeury = "?" + qeury;
    return url + qeury;
}
var zyscroll = function (st, time) {
    var sf = parseInt($(window).scrollTop()),
        i = 0,
        runEvery = 5;
    st = parseInt(st);
    time /= runEvery;
    var interval = setInterval(function () {
        i++;
        $(window).scrollTop((st - sf) / time * i + sf);
        if (i >= time) {
            clearInterval(interval);
        }
    }, runEvery);
}

//用户微信获取地址
function wxgetLocation() {
    try {
        wx.getLocation({
            type: 'wgs84',
            success: function (res) {
                var postion = {
                    coords: {
                        latitude: res.latitude,
                        longitude: res.longitude,
                        precision: res.accuracy,
                        speed: res.speed
                    }
                };
                getLocation_ok(postion);
            }
        });
    } catch (e) {
        getLocation();
    }
}
//获取用户地理位置
function getLocation() {
    var options = {
        enableHighAccuracy: true,
        maximumAge: 1000
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getLocation_ok, getLocation_err, options);
    } else {
        console.log("浏览器不支持geolocation");
    }
}
//成功获取操作
function getLocation_ok(position) {
    //更新用户地址
    $.ajax({
        type: "post",
        url: "/index.php/Ajax/location",
        data: position.coords,
        async: false,
        success: function (res) {
            return (res.status == 1);
        },
        error: function () {
            return false;
        }
    });
}
//获取不到位置时
function getLocation_err(error) {

    switch (error.code) {
        case 1:
            msg = "位置服务被拒绝";
            break;
        case 2:
            msg = "暂时获取不到位置信息";
            break;
        case 3:
            msg = "获取信息超时";
            break;
        case 4:
            msg = "未知错误";
            break;
    }
    console.log("getLocation-error:" + msg);
    return msg;
}
$(function () {
    var a = function (e) {
        e.preventDefault();
    };
    var zy_load = new zooyoo_load(200, 200);
    zy_load.init();
    window.onscroll = function () {
        zy_load.init();
    }
    $("img").error(function () {
        $(this).unbind("error").attr("src", "/Public/images/layz_bg.jpg");
    });
    //window.onresize = function(){zy_load.init();}

    //左侧分类展开JS	
    $("#subfield").click(function () {
        if ($("#m-bg").is(":hidden")) {
            $("#m-bg").show();
            $(".nav-list").removeClass("slideOut").addClass("slidIn");
            document.addEventListener('touchmove', a);
        };
    });
    //分类层级展示
    $("#scroller ul li").click(function () {
        if ($(this).find("i").hasClass("up")) {
            $(this).find("i").removeClass("up");
            $(this).find("div.sub").fadeOut();
        } else {
            $(this).find("i").addClass("up");
            $(this).find("div.sub").fadeIn();
        }
    });
    /*搜索按钮*/
    $(".sousuo").click(function () {
        site_search()
    });
    $(".search-st .hd").click(function () {
        $(this).next(".bd").toggle();
    });
    $("#sch_type").children("li").click(function () {
        var type = $(this).data("type");
        $(".search-st").children(".hd").find("span").text($(this).text());
        $(this).parents("form").attr("action", $(this).data("uri"));
        if (type == "shop") {
            $(this).parents(".search-st").next().attr("placeholder", "搜索店铺");
        }
        if (type == "goods") {
            $(this).parents(".search-st").next().attr("placeholder", "搜索宝贝");
        }
        if (type == "all") {
            $(this).parents(".search-st").next().attr("placeholder", "搜索宝贝");
        }
        if (type == "shopgoods") {
            $(this).parents(".search-st").next().attr("placeholder", "搜索宝贝");
        }
        $(".search-st").children(".bd").hide();
    });
    $('#keyword').keydown(function (event) {
        if (event.keyCode == "13") site_search();
    });
    /*全站搜索*/
    function site_search() {
        var that = $("form.sch_form");
        var url = $(that).attr("action");
        var shop_id = $(that).find("input[name='shop_id']").val() ? $(that).find("input[name='shop_id']").val() :
            0;
        var keyword = $.trim($(that).find("input[name='keyword']").val());
        var cat_id = $(that).find("input[name='cat_id']").val();
        if (keyword == "") return;
        url += "?keyword=" + decodeURIComponent(keyword);
        if (!isNaN(shop_id)) url += "&shop_id=" + shop_id;
        location.href = url;
    }

    function navClose() {
        if ($(".nav-list").hasClass("slidIn")) {
            $(".nav-list").removeClass("slidIn").addClass("slideOut");
            $("#m-bg").hide();
            document.removeEventListener('touchmove', a);
        };
    };
    $("#m-bg").on("touchend", function () {
        navClose();
        event.preventDefault();
    });
    $("#navClose").click(function () {
        navClose()
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() > 150) {
            $(".top-btn").show();
        } else {
            $(".top-btn").hide();
        }
    });
    $(".top-btn").find("a").click(function () {
        zyscroll('0', 100);
    });
    //搜索js
    $("#search").children("a").click(function () {
        var sText = $(".search-text");
        if (sText.is(":hidden") == true) {
            sText.show();
            $(this).children("i").css("color", "#fcd605");
        } else {
            sText.hide();
            $(this).children("i").css("color", "#fff")
        };
    });
    //用户中心订阅状态
    $("#cfg_msg").click(function () {
        location.href = $(this).data("uri");
    });
    $(".my_subscribe span").click(function () {
        $(this).toggleClass("on");
        var cfg_val = $(this).hasClass("on") ? 1 : 0;
        var cfg_name = $(this).data("name");
        $.ajax({
            type: 'POST',
            url: '/index.php/Member/subscribe',
            data: {
                cfg_name: cfg_name,
                status: cfg_val
            },
            success: function (res) {}
        })
    });
    //头部固定
    var topHead = $(".layout").offset() ? $(".layout").offset().top : 0;
    $(window).scroll(function () {
        if ($(".top-fix").size() > 0) {
            if ($(window).scrollTop() > topHead) {
                $(".top-fix").addClass("top-fix-on");
                $("body").css("padding-top", "90px");
            } else {
                $(".top-fix").removeClass("top-fix-on");
                $("body").css("padding-top", "0px");
            };
        };
        if ($("body").find(".start-fix").size() > 0) {
            if ($(window).scrollTop() > 45) {

                $(".start-fix").addClass("start-fix-on");
                $("body").css("padding-top", "0px");
            } else {
                $(".start-fix").removeClass("start-fix-on");
                $("body").css("padding-top", "45px");
            };
        };
    });
    if ($("body").find(".start-fix").size() > 0) {
        $("body").css("padding-top", "45px");
    }
    /*列表排列切换*/
    $(".showtype").click(function () {
        if ($(".list-item").hasClass("list-body")) {
            $(".list-item").removeClass("list-body");
        } else {
            $(".list-item").addClass("list-body");
        }
    });
});