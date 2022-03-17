//商品详情购买
function skuClose() {
    $("#skuCont").removeClass("attribute_wrap_show").css("z-index", "-1");
}

//更新购物车
function update_car(num, shopNum, that) {
    var ids = new Array();
    $.each($("*[event='cart'].on"), function () {
        ids.push($(this).data("id"));
    })
    var car_id = 0,
        url;
    if (that) {
        car_id = that ? $(that).attr("car_id") : 0;
        url = $(that).attr('uri');
    } else {
        url = "/index.php/Cart/update.html";
    }
    //$.ui.dialog.loading({text:"正在加载中…"})
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            'car_id': car_id,
            'number': num,
            'ids': ids
        },
        dataType: "json",
        success: function (res) {
            $.ui.dialog.close();
            if (res.status == false) {
                if (res.msg) $.ui.dialog.alert({
                    tip: res.msg
                });
            }
            if (res.total) {
                $("#oamount").html('<em>' + res.total.oamount + '</em>');
                $("#discount").html('<em>' + res.total.discount + '</em>');
                if (res.total.intmin > 0) {
                    $("#amount").html('<em>' + res.total.amount + '</em>元 + <em>' + res.total.intmin +
                        "</em>积分");
                } else {
                    $("#amount").html('<em>' + res.total.amount + '</em>元');
                }
                $("#num").html('<em>' + res.total.total + '</em>');
            }
            if (shopNum && res.number) {
                shopNum.val(res.number);
            }
            //$(".shpcart i").show().html(res.total.total);
            if (res.callback) eval(res.callback);
        }
    })
}

$(function () {
    $("#pageload").hide();
    $(".showload").click(function () {
        $.ui.dialog.loading({
            text: "正在加载中…"
        });
        return;
    });
    $(".nav-buttom a").click(function () {
        $.ui.dialog.loading({
            text: "正在加载中…"
        });
        return;
    });
    $(".gohome").click(function () {
        $.ui.dialog.loading({
            text: "正在加载中…"
        });
        location.href = "/";
    })
    $("a.back,a.getback").tap(function () {
        if ($(this).attr("uri")) {
            location.href = $(this).attr("uri");
        } else {
            if (history.length > 1) {
                history.back(-1);
            } else {
                var uri = $(this).data("uri");
                if (uri) {
                    location.href = uri;
                } else {
                    location.href = "/";
                }
            }
        }
    });

    //商品及店铺收藏操作
    $(".collect").click(function () {
        var that = $(this);
        var id = $(this).data("id");
        var type = $(this).data('type');
        $.ajax({
            url: "/index.php/ajax/fav",
            data: {
                id: id,
                type: type ? type : 'goods'
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                if (res.result) {
                    if (res.status == 1) {
                        $(that).addClass("on").find("span,p").html("已收藏");
                        $(".fav_num").html($(".fav_num").html() * 1 + 1);
                    } else {
                        $(that).removeClass("on").find("span,p").html("收藏");
                        $(".fav_num").html($(".fav_num").html() * 1 - 1);
                    }
                } else {
                    $.ui.dialog.tip({
                        text: res.msg,
                        time: 1.5 * 1000
                    });
                }
            },
            error: function () {
                $.ui.dialog.tip({
                    text: "收藏失败,请重试",
                    time: 1.5 * 1000
                });
            }
        })
    })

    //用户删除收藏
    $(document).on("click", ".fav-del", function () {
        var id = $(this).data("id");
        var obj = $(this).parents("li");
        $.ui.dialog.confirm({
            text: '确定要删除吗？',
            cancel: {
                val: '取消',
                handler: function (e) {

                }
            },
            confirm: {
                val: '确定',
                handler: function (e) {
                    $.ui.dialog.loading({
                        text: "正在处理中…"
                    })
                    $.ajax({
                        type: "POST",
                        url: "/index.php/ajax/delfav",
                        data: {
                            id: id
                        },
                        dataType: "json",
                        success: function (data) {
                            $.ui.dialog.close();
                            if (data.result) {
                                //$.ui.dialog.tip({text:data.msg});
                                $(obj).remove();
                                if ($("#fav_list").find("li").size() == 0) {
                                    location.reload();
                                }
                            } else {
                                $.ui.dialog.alert({
                                    tip: data.msg
                                });
                            }
                            return;
                        }
                    })
                }
            }
        })
    })
    //会员中心 收缩JS
    var myList = $(".my-list").find("li");
    myList.each(function () {
        var myLi = $(this).children(".xiangqing");
        if (myLi.length > 0) {
            $(this).find("a").tap(function () {
                if (myLi.is(":hidden")) {
                    myLi.show();
                    $(this).prev("i").css({
                        "-moz-transform": "rotate(90deg)",
                        "-webkit-transform": "rotate(90deg)",
                        "-o-transform": "rotate(90deg)",
                        "transform": "rotate(90deg)",
                        "top": "11px"
                    });
                } else {
                    myLi.hide();
                    $(this).prev("i").css({
                        "-moz-transform": "rotate(0deg)",
                        "-webkit-transform": "rotate(0deg)",
                        "-o-transform": "rotate(0deg)",
                        "transform": "rotate(0deg)",
                        "top": "6px"
                    });
                }
            });
        };
    });
    //地址选择
    var radio = $(".radio");
    var radioA = radio.children(".choose").find("a.types");
    radio.children(".choose").tap(function () {
        var radioA = $(this).find("a.types");
        if (radioA.attr("class") == "types") {
            $(this).find("a.types").addClass("on");
            $(this).siblings(".choose").find("a.types").removeClass("on");
        } else {
            $(this).find("a.types").removeClass("on")
        };
    });
    //添加到购物车
    $(".attr_btns_buy").click(function () {
        var that = $(this);
        var url = $(that).data("uri");
        var toUrl = $(that).data("nuri");
        var goods_id = $(that).data("id");
        var spec_id = $(that).data("spec");
        var number = $(".buy_number:eq(0)").val();
        if (spec_id == '-1' || $(that).hasClass("buy-gray")) {
            //$.ui.dialog.alert({tip:'请选择商品规格！'});
            return false;
        }
        $.ui.dialog.loading({
            text: "正在加载中…"
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                goods_id: goods_id,
                number: number,
                spec_id: spec_id
            },
            dataType: "json",
            success: function (data) {
                $.ui.dialog.close();
                if (data.status) {
                    if (toUrl && $(that).attr("data-type") == 'buy') {
                        $.ui.dialog.flatLoading({
                            text: "数据加载中…"
                        });
                        window.location.href = toUrl;
                    } else {
                        skuClose()
                        $.ui.dialog.confirm({
                            text: data.msg,
                            icon: {
                                url: '/Public/images/i-right.png',
                                width: '35px',
                                height: '36px'
                            },
                            cancel: {
                                val: '关闭'
                            },
                            confirm: {
                                val: '去结算',
                                handler: function (e) {
                                    $.ui.dialog.flatLoading({
                                        text: "数据加载中…"
                                    });
                                    window.location.href = toUrl;
                                }
                            }
                        })
                    }
                } else {
                    $.ui.dialog.alert({
                        tip: data.msg
                    });
                }
                if (data.total && data.total.goods_num > 0) {
                    $(".showload").find("i").show().html(data.total.total);
                    $(".cart-num span").show().html(data.total.total);
                }
                return;
            },
            error: function () {
                $.ui.dialog.alert({
                    tip: '加入失败，请重试！'
                });
            }

        })
    })

    //购物车
    var shopCart = $(".shopcart");
    shopCart.find("li").each(function () {
        $(this).find(".check-icon").tap(function () {
            if ($(this).attr("class") == "check-icon") {
                $(this).addClass("no-check");
                $(".money").hide();
            } else {
                $(this).removeClass("no-check");
                $(".money").show();
            }
        });
        $(this).find(".del").click(function () {
            var id = $(this).attr("id");
            var url = $(this).attr("url");

            $.ui.dialog.confirm({
                text: '确定要删除吗？',
                cancel: {
                    val: '取消',
                    handler: function (e) {

                    }
                },
                confirm: {
                    val: '确定',
                    handler: function (e) {
                        $.ui.dialog.loading({
                            text: "正在处理中…"
                        })
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                id: id
                            },
                            dataType: "json",
                            success: function (data) {
                                $.ui.dialog.close();
                                if (data.status) {
                                    $.ui.dialog.ok({
                                        text: data.msg
                                    });
                                    $(this).parents("li").remove();
                                    location.reload();
                                } else {
                                    $.ui.dialog.alert({
                                        tip: data.msg
                                    });
                                }
                                return;
                            }
                        })
                    }
                }
            })

        });
    });
    //提交订单
    $(".order_submit").click(function () {
        if ($("*[event='cart'].on").size() == 0) {
            $.ui.dialog.alert({
                tip: '亲，请选择要购买的商品'
            });
            return;
        }
        $.ui.dialog.loading({
            text: "正在加载中…"
        })
        window.location.href = $(this).data("uri");
    })
    //页面跳转
    $(".jump_url").tap(function () {
        var obj = $(this);
        url = obj.attr("url");
        $.ui.dialog.loading({
            text: "正在加载中…"
        })
        window.location.href = url;
    })

    $(".shopcart-ment").find(".clear").children("a").tap(function () {
        var shopLi = shopCart.find("li").size();
        if (shopLi > 0) {
            var url = $(".clear").attr("url");
            $.ui.dialog.confirm({
                text: '确定要清空购物车吗？',
                cancel: {
                    val: '取消',
                    handler: function (e) {

                    }
                },
                confirm: {
                    val: '确定',
                    handler: function (e) {
                        $.ui.dialog.loading({
                            text: "正在处理中…"
                        })
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            success: function (data) {
                                $.ui.dialog.close();
                                if (data.status) {
                                    shopCart.find("li").remove();
                                    location.reload();
                                } else {
                                    $.ui.dialog.alert({
                                        tip: data.msg
                                    });
                                }
                                return;
                            }
                        })
                    }
                }
            })
        }
    });
    var shopPn = $(".items-opt");
    shopPn.children(".btn-del").click(function () {
        var that = $(this);
        var shopNum = $(that).next(".fm-txt");
        if (parseInt(shopNum.val()) > 1) {
            var num = parseInt(shopNum.val()) - 1;
            if ($(this).attr("action") == "update_car") {
                update_car(num, shopNum, that);
            } else {
                shopNum.val(num);
                if (goods_weight > 1 && bywt == 1) {
                    wnum = goods_weight * num;
                    wnum = wnum >= 1000 ? (wnum / 1000 + '千') : wnum;
                    $(".goods_weight").html(wnum);
                }
                $(".buy_number").val(num);
            }
            if (num == 1) $(this).addClass("unselect");
        } else {
            $(that).addClass("unselect");
        };
        $(that).parent().find(".btn-add").removeClass("unselect");
    });
    shopPn.children(".btn-add").click(function () {
        var that = $(this);
        var shopNum = $(this).prev(".fm-txt");
        $(that).parent().find(".btn-del").removeClass("unselect");
        var num = parseInt(shopNum.val()) + 1;
        if ($(this).attr("action") == "update_car") {
            update_car(num, shopNum, that);
        } else {
            var stock = $("#goods_stock").text() * 1;
            if (num > stock) {
                $(that).addClass("unselect");
                return false;
            } else {
                shopNum.val(num);
                if (goods_weight > 1 && bywt == 1) {
                    wnum = goods_weight * num;
                    wnum = wnum >= 1000 ? (wnum / 1000 + '千') : wnum;
                    $(".goods_weight").html(wnum);
                }
                $(".buy_number").val(num);
                if (num == stock) $(that).addClass("unselect");
            }
        }
    });

    //确认收货
    $(".confirm").tap(function () {
        var order_id = $(this).attr('order_id');
        var url = $(this).attr('url');
        $.ui.dialog.confirm({
            text: '确定已收货或已消费吗？',
            cancel: {
                val: '取消',
                handler: function (e) {

                }
            },
            confirm: {
                val: '确定',
                handler: function (e) {
                    $.ui.dialog.loading({
                        text: "正在处理中…"
                    });
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            order_id: order_id
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status) {
                                $.ui.dialog.ok({
                                    text: '操作成功'
                                });
                                window.location.reload();
                            } else {
                                $.ui.dialog.alert({
                                    tip: data.msg
                                });
                            }
                            return;
                        },
                        error: function () {
                            $.ui.dialog.alert({
                                title: '确认收货',
                                tip: '操作失败，请重试！'
                            });
                        }
                    })
                }
            }
        })
    })
});